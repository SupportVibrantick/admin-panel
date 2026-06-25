<?php
namespace App\Http\Controllers\MLM;

use App\Http\Controllers\Controller;
use App\Models\MlmUser;
use App\Models\MLMTree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeamGenealogyController extends Controller
{
    private function buildTreeStructure($treeNode, $isViewRoot = true)
    {
        if (!$treeNode) {
            return null;
        }
        
        $user = $treeNode->mlmUser;
        
        $leftChild = $treeNode->leftChild ? 
            MLMTree::where('id', $treeNode->leftChild->id)
                ->with(['leftChild.mlmUser', 'rightChild.mlmUser', 
                        'leftChild.leftChild.mlmUser', 'leftChild.rightChild.mlmUser',
                        'rightChild.leftChild.mlmUser', 'rightChild.rightChild.mlmUser'])
                ->first() : null;
                
        $rightChild = $treeNode->rightChild ?
            MLMTree::where('id', $treeNode->rightChild->id)
                ->with(['leftChild.mlmUser', 'rightChild.mlmUser',
                        'leftChild.leftChild.mlmUser', 'leftChild.rightChild.mlmUser',
                        'rightChild.leftChild.mlmUser', 'rightChild.rightChild.mlmUser'])
                ->first() : null;
        
        return [
            'id' => $treeNode->id,
            'user_id' => $user->id,
            'user_name' => $user->user_name,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'position' => $treeNode->position,
            'level' => $treeNode->level,
            'is_active' => $user->is_active,
            'is_root' => $isViewRoot,
            'cc_balance' => $user->payoutBalance?->cc_balance ?? 0,
            'left' => $leftChild ? $this->buildTreeStructure($leftChild, false) : null,
            'right' => $rightChild ? $this->buildTreeStructure($rightChild, false) : null,
        ];
    }
   /**
     * 🌳 Team Genealogy - Visual Binary Tree View
     */
    public function genealogyView()
    {
        $currentUser = Auth::user();
        
        $rootUser = MlmUser::find(1);
        
        $rootTree = MLMTree::where('mlm_user_id', $rootUser->id)
            ->with(['leftChild.mlmUser', 'rightChild.mlmUser'])
            ->first();
        
        $treeData = $this->buildTreeStructure($rootTree);
        
        return view('admin.pages.mlm.team-genealogy', compact('treeData', 'rootUser'));
    }
    
    /**
     * 📋 Team Downline - Table View
     */
    public function downlineView(Request $request)
    {
        $currentUser = Auth::user();
        
        // Get all downline users
        $query = MLMTree::with(['mlmUser.sponsor', 'mlmUser.payoutBalance', 'parent'])
            ->whereHas('mlmUser', function($q) use ($currentUser) {
                $q->where('is_deleted', false);
                if ($currentUser->user_name !== 'Founder01') {
                    $q->whereIn('id', $this->getAllDownlineIds($currentUser->id));
                }
            })
            ->orderBy('level', 'asc')
            ->orderBy('created_at', 'asc');
        
        // Search & Filters (same as before)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('mlmUser', function($q) use ($search) {
                $q->where('user_name', 'LIKE', "%{$search}%")
                  ->orWhere('first_name', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }
        
        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }
        
        $teamMembers = $query->paginate(15);
        
        // Stats
        $downlineIds = $this->getAllDownlineIds($currentUser->id);
        $stats = [
            'total' => MLMTree::whereIn('mlm_user_id', $downlineIds)->count(),
            'level_1' => MLMTree::whereIn('mlm_user_id', $downlineIds)->where('level', 1)->count(),
            'level_2' => MLMTree::whereIn('mlm_user_id', $downlineIds)->where('level', 2)->count(),
            'left_leg' => MLMTree::whereIn('mlm_user_id', $downlineIds)->where('position', 'left')->count(),
            'right_leg' => MLMTree::whereIn('mlm_user_id', $downlineIds)->where('position', 'right')->count(),
        ];
        
        return view('admin.pages.mlm.team-downline', compact('teamMembers', 'stats', 'currentUser'));
    }

    /**
     * Team Downline - Full Binary Tree Table
     */
   public function index(Request $request)
{
    $currentUser = Auth::user();
    
    // ✅ Fixed query
    $query = MLMTree::with(['mlmUser.sponsor', 'mlmUser.payoutBalance', 'parent'])
        ->whereHas('mlmUser', function($q) use ($currentUser) {
            $q->where('is_deleted', false);
            if ($currentUser->user_name !== 'Founder01') {
                $q->whereIn('id', $this->getAllDownlineIds($currentUser->id));
            }
        })
        ->orderBy('level', 'asc')
        ->orderBy('created_at', 'asc'); // ✅ mlm_trees.created_at
    
    // 🔍 Search
    if ($request->filled('search')) {
        $search = $request->search;
        $query->whereHas('mlmUser', function($q) use ($search) {
            $q->where('user_name', 'LIKE', "%{$search}%")
              ->orWhere('first_name', 'LIKE', "%{$search}%")
              ->orWhere('last_name', 'LIKE', "%{$search}%");
        });
    }
    
    // 📊 Filter by Level
    if ($request->filled('level')) {
        $query->where('level', $request->level);
    }
    
    // Filter by Position
    if ($request->filled('position')) {
        $query->where('position', $request->position);
    }
    
    // Filter by Status
    if ($request->filled('status')) {
        $query->whereHas('mlmUser', function($q) use ($request) {
            $q->where('is_active', $request->status === 'active');
        });
    }
    
    $teamMembers = $query->paginate(50);
    
    // 📊 Stats
    $downlineIds = $this->getAllDownlineIds($currentUser->id);
    $stats = [
        'total' => MLMTree::whereIn('mlm_user_id', $downlineIds)->count(),
        'level_1' => MLMTree::whereIn('mlm_user_id', $downlineIds)->where('level', 1)->count(),
        'level_2' => MLMTree::whereIn('mlm_user_id', $downlineIds)->where('level', 2)->count(),
        'left_leg' => MLMTree::whereIn('mlm_user_id', $downlineIds)->where('position', 'left')->count(),
        'right_leg' => MLMTree::whereIn('mlm_user_id', $downlineIds)->where('position', 'right')->count(),
    ];
    
    return view('admin.pages.mlm.team-downline', compact('teamMembers', 'stats', 'currentUser'));
}
    /**
     * View Full Genealogy Tree (Binary)
     */
    public function viewGenealogy($userId)
    {
        $user = MlmUser::findOrFail($userId);
        $tree = MLMTree::where('mlm_user_id', $userId)->first();
        
        // Build tree from this user
        $treeData = $this->buildBinaryTree($userId);
        
        return view('admin.pages.mlm.partials.binary-tree', compact('treeData', 'user', 'tree'));
    }
    
    /**
     * View Referral Tree (Direct sponsors only)
     */
    public function viewReferralTree($userId)
    {
        $user = MlmUser::findOrFail($userId);
        $referrals = MlmUser::where('sponsor_id', $userId)
            ->where('is_deleted', false)
            ->with('payoutBalance')
            ->get();
        
        return view('admin.pages.mlm.partials.referral-tree', compact('referrals', 'user'));
    }
    
    /**
     * Build binary tree structure recursively
     */
    private function buildBinaryTree($userId)
    {
        $user = MlmUser::with(['tree', 'payoutBalance'])->find($userId);
        if (!$user) return null;
        $tree = $user->tree;
        if (!$tree) return null;
        
        $leftChild = MLMTree::where('parent_id', $tree->id)
            ->where('position', 'left')
            ->with('mlmUser')
            ->first();
        
        $rightChild = MLMTree::where('parent_id', $tree->id)
            ->where('position', 'right')
            ->with('mlmUser')
            ->first();
        
        return [
            'user' => $user,
            'tree' => $tree,
            'level' => $tree->level,
            'position' => $tree->position,
            'cc_balance' => $user->payoutBalance?->cc_balance ?? 0,
            'left' => $leftChild ? $this->buildBinaryTree($leftChild->mlm_user_id) : null,
            'right' => $rightChild ? $this->buildBinaryTree($rightChild->mlm_user_id) : null,
        ];
    }
    
    /**
     * Get all downline user IDs (for queries)
     */
    private function getAllDownlineIds($userId, $maxLevel = 100)
    {
        $ids = [$userId];
        $this->collectDownlineIds($userId, $ids, 0, $maxLevel);
        return array_unique($ids);
    }
    
    private function collectDownlineIds($userId, &$ids, $level, $maxLevel)
    {
        if ($level >= $maxLevel) return;
        
        $tree = MLMTree::where('mlm_user_id', $userId)->first();
        if (!$tree) return;
        
        $children = MLMTree::where('parent_id', $tree->id)
            ->with('mlmUser')
            ->get();
        
        foreach ($children as $child) {
            $ids[] = $child->mlm_user_id;
            $this->collectDownlineIds($child->mlm_user_id, $ids, $level + 1, $maxLevel);
        }
    }
    /**
 * 🔍 API: Get user profile data for modal (AJAX)
 * Returns JSON for the genealogy profile modal
 */
public function userProfile($userId)
{
    try {
        $user = MlmUser::with(['sponsor', 'payoutBalance', 'tree'])->findOrFail($userId);
        $tree = $user->tree;
        
        // Calculate team stats
        $leftTeamBv = 0;
        $rightTeamBv = 0;
        $activeLeftTeam = 0;
        $activeRightTeam = 0;
        $totalLeftTeam = 0;
        $totalRightTeam = 0;
        
        if ($tree) {
            // Get direct left child
            $leftChild = MLMTree::where('parent_id', $tree->id)
                ->where('position', 'left')
                ->with('mlmUser.payoutBalance')
                ->first();
            
            // Get direct right child  
            $rightChild = MLMTree::where('parent_id', $tree->id)
                ->where('position', 'right')
                ->with('mlmUser.payoutBalance')
                ->first();
            
            $leftTeamBv = $leftChild?->mlmUser?->payoutBalance?->cc_balance ?? 0;
            $rightTeamBv = $rightChild?->mlmUser?->payoutBalance?->cc_balance ?? 0;
            
            // Count teams (you may need to adjust based on your actual logic)
            $activeLeftTeam = $leftChild && $leftChild->mlmUser->is_active ? 1 : 0;
            $activeRightTeam = $rightChild && $rightChild->mlmUser->is_active ? 1 : 0;
            
            // Get total team counts
            $totalLeftTeam = $this->countDownline($leftChild?->mlm_user_id);
            $totalRightTeam = $this->countDownline($rightChild?->mlm_user_id);
        }
        
        return response()->json([
            'user' => [
                'id' => $user->id,
                'user_id' => $user->user_name, // or whatever field stores User ID
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'user_name' => $user->user_name,
                'email' => $user->email,
                'is_active' => $user->is_active,
            ],
            'stats' => [
                'sponsor_id' => $user->sponsor?->user_name ?? 'Direct Seller',
                'joined_date' => $user->created_at?->format('d-m-Y') ?? 'N/A',
                'level' => $tree?->level ?? 0,
                'current_right_cc' => $rightTeamBv,
                'current_left_cc' => $leftTeamBv,
                'active_right_team' => $activeRightTeam,
                'active_left_team' => $activeLeftTeam,
                'total_right_team' => $totalRightTeam,
                'total_left_team' => $totalLeftTeam,
                'personal_bv' => $user->payoutBalance?->cc_balance ?? 0,
                'package' => $user->package_name ?? '--', // Adjust field name
                'sponsor' => $user->sponsor 
                    ? "{$user->sponsor->first_name} {$user->sponsor->last_name}" 
                    : 'Direct Seller',
            ]
        ]);
        
    } catch (\Exception $e) {
        Log::error('Genealogy Profile Error: ' . $e->getMessage());
        return response()->json(['error' => 'Server error'], 500);
    }
}

/**
 * Count total downline for a user
 */
private function countDownline($userId, $maxLevel = 100)
{
    if (!$userId) return 0;
    
    $count = 0;
    $this->collectDownlineCount($userId, $count, 0, $maxLevel);
    return $count;
}

private function collectDownlineCount($userId, &$count, $level, $maxLevel)
{
    if ($level >= $maxLevel) return;
    
    $tree = MLMTree::where('mlm_user_id', $userId)->first();
    if (!$tree) return;
    
    $children = MLMTree::where('parent_id', $tree->id)->get();
    
    foreach ($children as $child) {
        $count++;
        $this->collectDownlineCount($child->mlm_user_id, $count, $level + 1, $maxLevel);
    }
}
/**
 * Show downline tree for a specific user
 */
/**
 * Show downline tree for a specific user
 */
public function showUserDownline($userId)
{
    $currentUser = Auth::user();
    
    $rootUser = MlmUser::find(1);
    
    $selectedUser = MlmUser::findOrFail($userId);
    
    $rootTree = MLMTree::where('mlm_user_id', $userId)
        ->with(['leftChild.mlmUser', 'rightChild.mlmUser'])
        ->first();
    
    $treeData = $this->buildTreeStructure($rootTree);
    
    return view('admin.pages.mlm.team-genealogy', compact('treeData', 'rootUser', 'selectedUser'));
}
/**
 * Show profile modal content (Server-side rendered)
 */
/**
 * Show profile modal content (Server-side rendered)
 */
public function showProfileModal($userId)
{
    $user = MlmUser::with(['sponsor', 'payoutBalance', 'tree'])->findOrFail($userId);
    $tree = $user->tree;
    
    // Calculate stats
    $stats = $this->calculateUserStats($user, $tree);
    
    return view('admin.pages.mlm.partials.profile-modal-content', compact('user', 'stats'));
}

/**
 * Calculate user statistics
 */
private function calculateUserStats($user, $tree)
{
    $leftTeamBv = 0;
    $rightTeamBv = 0;
    $activeLeftTeam = 0;
    $activeRightTeam = 0;
    $totalLeftTeam = 0;
    $totalRightTeam = 0;
    
    if ($tree) {
        $leftChild = MLMTree::where('parent_id', $tree->id)
            ->where('position', 'left')
            ->with('mlmUser.payoutBalance')
            ->first();
        
        $rightChild = MLMTree::where('parent_id', $tree->id)
            ->where('position', 'right')
            ->with('mlmUser.payoutBalance')
            ->first();
        
        $leftTeamBv = $leftChild?->mlmUser?->payoutBalance?->cc_balance ?? 0;
        $rightTeamBv = $rightChild?->mlmUser?->payoutBalance?->cc_balance ?? 0;
        $activeLeftTeam = $leftChild && $leftChild->mlmUser->is_active ? 1 : 0;
        $activeRightTeam = $rightChild && $rightChild->mlmUser->is_active ? 1 : 0;
        $totalLeftTeam = $this->countDownline($leftChild?->mlm_user_id);
        $totalRightTeam = $this->countDownline($rightChild?->mlm_user_id);
    }
    
    return [
        'sponsor_id' => $user->sponsor?->user_name ?? 'Direct Seller',
        'joined_date' => $user->created_at?->format('d-m-Y') ?? 'N/A',
        'level' => $tree?->level ?? 0,
        'current_right_cc' => $rightTeamBv,
        'current_left_cc' => $leftTeamBv,
        'active_right_team' => $activeRightTeam,
        'active_left_team' => $activeLeftTeam,
        'total_right_team' => $totalRightTeam,
        'total_left_team' => $totalLeftTeam,
        'personal_bv' => $user->payoutBalance?->cc_balance ?? 0,
        'package' => $user->package_name ?? '--',
    ];
}


}