<?php

namespace App\Http\Controllers\MLM;

use \App\Models\MLMTree;
use App\Http\Controllers\Controller;
use App\Mail\MlmActivationMail;
use App\Models\MlmUser;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PayoutBalance;
use App\Models\PayoutConfig;
use App\Models\Product;
use App\Models\SpillingPreference;
use App\Services\PayoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MLMUserController extends Controller
{
    public function index()
    {
        $users = MlmUser::with(['sponsor', 'tree'])
            ->where('is_deleted', false)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // ✅ Load active products for order modal
        $products = Product::where('status', 1)
            ->where('stock', '>', 0)
            ->get(['id', 'name', 'sku', 'price', 'discount_price', 'cc_points', 'stock']);

        return view('admin.pages.mlm.register-users', compact('users', 'products'));
    }
 
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sponsor_username' => 'required|string|exists:mlm_users,user_name',
            'user_name' => 'required|string|max:255|unique:mlm_users,user_name',
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'required|email|max:255|unique:mlm_users,email',
            'phone' => 'required|digits:10|unique:mlm_users,phone',
            'password' => 'required|string|min:8|confirmed',
            'commission_percentage' => 'nullable',
        ]);

        DB::beginTransaction();
        try {
            $sponsor = MlmUser::where('user_name', $validated['sponsor_username'])
                ->where('is_active', true)->where('is_deleted', false)->firstOrFail();

            // 1. Create User
            $mlmUser = MlmUser::create([
                'user_name' => $validated['user_name'],
                'track_id' => 'TRK' . date('Y') . strtoupper(Str::random(6)) . time(),
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'sponsor_id' => $sponsor->id,
                // ✅ FIX: Use 'none' instead of 'pending'
                'position_in_sponsor_leg' => 'none',
                'membership_type' => 'CUSTOMER',
                'is_active' => false,
                'is_verified' => false,
                'is_deleted' => false,
                'verification_token' => Str::random(60),
                'verification_expires' => now()->addHours(24),
                'commission_percentage' => $validated['commission_percentage'],
            ]);

            // 2. Create Tree Node → Holding Tank
            MLMTree::create([   
                'mlm_user_id' => $mlmUser->id,
                'parent_id' => null,
                'position' => 'none',
                'level' => 0,
            ]);

            try {
                $activationUrl = route('mlm.activate', ['token' => $mlmUser->verification_token]);

                // Log before sending
                Log::info("Preparing to send activation email", [
                    'user' => $mlmUser->user_name,
                    'email' => $mlmUser->email,
                    'token' => substr($mlmUser->verification_token, 0, 10) . '...',
                    'url' => $activationUrl,
                ]);

                // Send email (sync, not queued)
                Mail::to($mlmUser->email)->send(new MlmActivationMail($mlmUser, $activationUrl));

                // Log success
                Log::info("✅ Activation email SENT successfully", [
                    'user' => $mlmUser->user_name,
                    'email' => $mlmUser->email,
                ]);
            } catch (\Exception $e) {
                // Log detailed error
                Log::error("❌ Activation email FAILED", [
                    'user' => $mlmUser->user_name,
                    'email' => $mlmUser->email,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                // Don't rollback - user still created, admin can resend later
                // But add a warning to session
                session()->flash('email_warning', "User created but activation email failed. Admin can resend from user list.");
            }

            DB::commit();
            return redirect()->route('mlm-users.index')
                ->with('success', "User registered! Added to Holding Tank.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }


    public function holdingTank()
    {
        // ✅ Get unplaced users (EXCLUDING ROOT USER)
        $holdingUsers = \App\Models\MLMTree::with(['mlmUser.sponsor'])
            ->whereHas('mlmUser', function ($q) {
                $q->where('is_verified', true)
                    ->where('is_active', true);
            })
            ->where(function ($q) {
                $q->whereNull('parent_id')
                    ->where('position', 'none');
            })
            ->whereHas('mlmUser', function ($q) {
                $q->where('user_name', '!=', 'Founder01');
            })
            ->latest()
            ->paginate(15);

        // ✅ Get ALL active users as parents (INCLUDING Founder01)
        $parents = MlmUser::where('is_active', true)
            ->where('is_deleted', false)
            ->orderBy('user_name')
            ->get(['id', 'user_name', 'first_name', 'last_name']);

        return view('admin.pages.mlm.holding-tank', compact('holdingUsers', 'parents'));
    }

    public function scopeInHoldingTank($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('parent_id')
                ->where('position', 'none');
        })
            ->whereHas('mlmUser', function ($q) {
                $q->where('is_verified', true)
                    ->where('is_active', true)
                    ->where('user_name', '!=', 'Founder01'); // Root exclude
            });
    }
    public function placeUser(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:mlm_users,id',
            'parent_id' => 'required|exists:mlm_users,id',
            'position' => 'required|in:left,right',
        ]);

        // Prevent self-placement
        if ($validated['user_id'] == $validated['parent_id']) {
            return back()->withErrors(['error' => 'User ko khud ke neeche place nahi kar sakte.']);
        }

        DB::beginTransaction();
        try {
            $userTree = \App\Models\MLMTree::where('mlm_user_id', $validated['user_id'])
                ->where(function ($q) {
                    $q->whereNull('parent_id')->orWhere('position', 'none');
                })
                ->with('mlmUser') // ✅ Load user relation
                ->firstOrFail();

            $user = $userTree->mlmUser;

            // 🔒 STRICT VALIDATION: Active & Verified hona zaroori hai
            if (!$user->is_verified) {
                throw new \Exception('❌ User verified nahi hai. Pehle verify karein.');
            }
            if (!$user->is_active) {
                throw new \Exception('❌ User active nahi hai. Pehle activate karein.');
            }

            // ✅ Parent Tree Node Fetch
            $parentTree = \App\Models\MLMTree::where('mlm_user_id', $validated['parent_id'])->firstOrFail();

            // ✅ Position Availability Check
            $occupied = \App\Models\MLMTree::where('parent_id', $parentTree->id)
                ->where('position', $validated['position'])
                ->exists();

            if ($occupied) {
                throw new \Exception("❌ Position '{$validated['position']}' already occupied hai is parent ke neeche.");
            }

            // 🌳 Tree Node Update
            $userTree->update([
                'parent_id' => $parentTree->id,
                'position' => $validated['position'],
                'level' => $parentTree->level + 1,
            ]);

            // 🔄 Closure Table Sync
            app(\App\Services\MLMClosureService::class)->syncClosures($userTree, $validated['parent_id']);

            // ✅ Update User Reference
            $user->update(['position_in_sponsor_leg' => $validated['position']]);

            DB::commit();
            return back()->with('success', "✅ User successfully placed in {$validated['position']} leg!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'required|email|max:255|unique:mlm_users,email,' . $id,
            'phone' => 'required|string|max:20|unique:mlm_users,phone,' . $id,
            'is_active' => 'nullable|boolean',
            'is_verified' => 'nullable|boolean',
            'password' => 'nullable|string|min:8|confirmed',
            'commission_percentage' => 'sometimes|nullable|in:10,12,14,16,18,20',
        ]);

        $mlmUser = MlmUser::findOrFail($id);

        $updateData = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'] ?? null,
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'is_active' => $validated['is_active'] ?? $mlmUser->is_active,
            'is_verified' => $validated['is_verified'] ?? $mlmUser->is_verified,
            'commission_percentage' => $validated['commission_percentage'],
        ];

        // if (isset($validated['commission_percentage'])) {
        //     $updateData['commission_percentage'] = $validated['commission_percentage'];
        // }

        if (isset($validated['is_verified']) && $validated['is_verified'] && !$mlmUser->is_verified) {
            $updateData['verification_token'] = null;
            $updateData['verification_expires'] = null;
        }

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $mlmUser->update($updateData);

        return redirect()->route('mlm-users.index')
            ->with('success', "User updated: {$mlmUser->user_name}");
    }

    public function destroy($id)
    {
        $mlmUser = MlmUser::findOrFail($id);
        $mlmUser->update(['is_deleted' => true, 'is_active' => false]);
        return redirect()->route('mlm-users.index')->with('success', "User soft-deleted: {$mlmUser->user_name}");
    }

  
    public function recycleBin()
    {
        $deletedUsers = MlmUser::with(['sponsor'])
            ->where('is_deleted', true)
            ->orderBy('updated_at', 'desc')
            ->paginate(15);
        return view('admin.pages.mlm.recycle-bin', compact('deletedUsers'));
    }

    public function restore($id)
    {
        try {
            $mlmUser = MlmUser::findOrFail($id);
            $mlmUser->update(['is_deleted' => false, 'is_active' => $mlmUser->is_active]);
            $spillingPreference = SpillingPreference::where('mlm_user_id', $id)->first();
            if ($spillingPreference && ($spillingPreference->is_deleted ?? false)) {
                $spillingPreference->update(['is_deleted' => false]);
            }
            return redirect()->back()->with('success', "✅ User restored: {$mlmUser->user_name}");
        } catch (\Exception $e) {
            Log::error('Restore user failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', "❌ Failed to restore user: {$e->getMessage()}");
        }
    }

    public function permanentDelete($id)
    {
        try {
            $mlmUser = MlmUser::findOrFail($id);
            $userName = $mlmUser->user_name;
            SpillingPreference::where('mlm_user_id', $id)->delete();
            $mlmUser->delete();
            return redirect()->back()->with('success', "🗑️ User permanently deleted: {$userName}");
        } catch (\Exception $e) {
            Log::error('Permanent delete failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', "❌ Failed to permanently delete: {$e->getMessage()}");
        }
    }

    public function bulkRestore(Request $request)
    {
        $validated = $request->validate(['user_ids' => 'required|array', 'user_ids.*' => 'exists:mlm_users,id']);
        $count = 0;
        foreach ($validated['user_ids'] as $id) {
            $user = MlmUser::find($id);
            if ($user && $user->is_deleted) {
                $user->update(['is_deleted' => false]);
                $count++;
            }
        }
        return redirect()->back()->with('success', "✅ {$count} user(s) restored successfully");
    }

    public function bulkPermanentDelete(Request $request)
    {
        $validated = $request->validate(['user_ids' => 'required|array', 'user_ids.*' => 'exists:mlm_users,id']);
        $count = 0;
        foreach ($validated['user_ids'] as $id) {
            $user = MlmUser::find($id);
            if ($user) {
                SpillingPreference::where('mlm_user_id', $id)->delete();
                $user->delete();
                $count++;
            }
        }
        return redirect()->back()->with('success', "🗑️ {$count} user(s) permanently deleted");
    }

  public function dashboard()
{
    // 1. Holding Tank (Pending Approvals)
    $pendingApprovals = \App\Models\MLMTree::whereNull('parent_id')
        ->where('position', 'none')
        ->whereHas('mlmUser', function ($q) {
            $q->where('is_verified', true)
              ->where('is_active', true)
              ->where('user_name', '!=', 'Founder01');
        })->count();

    // 2. Revenue & Orders (सेफ वर्जन - अगर Order मॉडल न हो तो 0)
    $totalRevenue = 0;
    $totalOrders = 0;
    $completedOrders = 0;
    
    if (class_exists(\App\Models\Order::class) && Schema::hasTable('orders')) {
        $totalOrders = \App\Models\Order::count();
        
        // Status कॉलम चेक करें
        if (Schema::hasColumn('orders', 'status')) {
            if (Schema::hasColumn('orders', 'total_amount')) {
                $totalRevenue = \App\Models\Order::where('status', 'completed')->sum('total_amount');
            } elseif (Schema::hasColumn('orders', 'grand_total')) {
                $totalRevenue = \App\Models\Order::where('status', 'completed')->sum('grand_total');
            }
            $completedOrders = \App\Models\Order::where('status', 'completed')->count();
        }
    }

    // 3. Users Stats
    $activeUsers = \App\Models\MlmUser::where('is_active', true)->where('is_deleted', false)->count();
    $newUsersToday = \App\Models\MlmUser::whereDate('created_at', today())->count();
    
    $customerCount = 0;
    $directSellers = 0;
    $inactiveCount = \App\Models\MlmUser::where('is_active', false)->where('is_deleted', false)->count();
    
    if (Schema::hasColumn('mlm_users', 'membership_type')) {
        $customerCount = \App\Models\MlmUser::where('membership_type', 'CUSTOMER')->where('is_deleted', false)->count();
        $directSellers = \App\Models\MlmUser::where('membership_type', 'DIRECT_SELLER')->where('is_active', true)->where('is_deleted', false)->count();
    }

    // 4. Payouts (सेफ वर्जन)
    $totalPayout = 0;
    if (Schema::hasTable('payout_balances')) {
        if (Schema::hasColumn('payout_balances', 'total_payout')) {
            $totalPayout = \App\Models\PayoutBalance::sum('total_payout');
        } elseif (Schema::hasColumn('payout_balances', 'amount')) {
            $totalPayout = \App\Models\PayoutBalance::sum('amount');
        } elseif (Schema::hasColumn('payout_balances', 'net_amount')) {
            $totalPayout = \App\Models\PayoutBalance::sum('net_amount');
        } elseif (Schema::hasColumn('payout_balances', 'total_amount')) {
            $totalPayout = \App\Models\PayoutBalance::sum('total_amount');
        }
    }

    // 5. Network Tree Nodes
    $leftNodes = \App\Models\MLMTree::where('position', 'left')->count();
    $rightNodes = \App\Models\MLMTree::where('position', 'right')->count();
    $totalNodes = $leftNodes + $rightNodes;

    // 6. Inventory Status
    $totalProducts = 0;
    $outOfStock = 0;
    $lowStock = 0;
    $inventoryPercentage = 0;
    
    if (Schema::hasTable('products')) {
        $totalProducts = \App\Models\Product::count();
        $outOfStock = \App\Models\Product::where('stock', '<=', 0)->count();
        $lowStock = \App\Models\Product::where('stock', '>', 0)->where('stock', '<=', 10)->count();
        $inStock = $totalProducts - $outOfStock;
        $inventoryPercentage = $totalProducts > 0 ? round(($inStock / $totalProducts) * 100) : 0;
    }

    // 7. Bonus Value
    $leftTeamVolume = 0; 
    $rightTeamVolume = 0;
    $leftCarryVolume = 0;
    $rightCarryVolume = 0;
    $totalBV = $leftTeamVolume + $rightTeamVolume;

    $eligibleCount = $pendingApprovals;

    return view('admin.pages.dashboard', compact(
        'pendingApprovals', 'totalRevenue', 'totalOrders', 'completedOrders',
        'activeUsers', 'newUsersToday', 'totalPayout', 'directSellers',
        'eligibleCount', 'leftNodes', 'rightNodes', 'totalNodes',
        'customerCount', 'inactiveCount', 'totalProducts', 'lowStock',
        'outOfStock', 'inventoryPercentage', 'leftTeamVolume', 'rightTeamVolume',
        'leftCarryVolume', 'rightCarryVolume', 'totalBV'
    ));
}
}
