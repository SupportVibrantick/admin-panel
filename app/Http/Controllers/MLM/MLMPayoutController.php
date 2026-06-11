<?php
namespace App\Http\Controllers\MLM;

use App\Http\Controllers\Controller;
use App\Models\FundRequest;
use App\Models\FundSummary;
use App\Models\FundTransfer;
use App\Models\MlmUser;
use App\Models\PayoutBalance;
use App\Models\PayoutTransaction;
use App\Services\PayoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MLMPayoutController extends Controller
{
public function dashboard(Request $request)
{
    $config = \App\Models\PayoutConfig::first();
    
    // ✅ Fallback if no config exists in DB
    if (!$config) {
        $config = new \App\Models\PayoutConfig([
            'products_for_payout' => 40,
            'threshold_cc' => 800,
            'cc_to_currency_rate' => 60,
        ]);
    }
    
    $usersWithPayouts = MlmUser::with(['payoutBalance', 'sponsor'])
        ->where('is_deleted', false)
        ->whereHas('payoutBalance', fn($qb) => 
            $qb->where('total_earned', '>', 0)
              ->orWhere('available_balance', '>', 0)
              ->orWhere('cc_balance', '>', 0)
        )
        ->orderBy('created_at', 'desc')
        ->paginate(20);
    
    return view('admin.pages.mlm.payout-dashboard', compact('config', 'usersWithPayouts'));
}

    public function payoutRequest(Request $request)
    {
        $payoutRequests = FundRequest::with('user', 'bankDetail')
            // ->where('type', 'withdrawal')
            // ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            // dd($payoutRequests->toArray());
        
        return view('admin.pages.mlm.payout-requests', compact('payoutRequests'));
    }

    public function details($userId)
    {
        $user = MlmUser::with('payoutBalance')->findOrFail($userId);
        $summary = (new PayoutService())->getUserPayoutSummary($user->id);
        $txns = PayoutTransaction::where('mlm_user_id', $user->id)
            ->orderBy('created_at', 'desc')->take(10)->get();
        
        return response()->json(['user' => $user, 'summary' => $summary, 'transactions' => $txns]);
    }

    public function withdraw(Request $request)
    {
        $v = $request->validate([
            'user_id' => 'required|exists:mlm_users,id',
            'amount' => 'required|numeric|min:1',
            'method' => 'required|in:bank,upi,wallet',
        ]);

        DB::beginTransaction();
        try {
            $user = MlmUser::findOrFail($v['user_id']);
            $balance = PayoutBalance::where('mlm_user_id', $user->id)->firstOrFail();
            
            if (!$balance->is_payout_eligible) throw new \Exception('Not eligible. Complete 40 products first.');
            if ($balance->available_balance < $v['amount']) throw new \Exception('Insufficient balance.');

            PayoutTransaction::create([
                'mlm_user_id' => $user->id,
                'type' => 'withdrawal',
                'currency_amount' => $v['amount'],
                'status' => 'pending',
                'description' => "Withdrawal via {$v['method']}",
                'meta' => ['method' => $v['method']],
            ]);

            $balance->decrement('available_balance', $v['amount']);
            $balance->increment('total_withdrawn', $v['amount']);

            DB::commit();
            return back()->with('success', '✅ Withdrawal request submitted!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function approveWithdrawal($id, $action)
    {
        $txn = PayoutTransaction::findOrFail($id);
        if ($action === 'approve') {
            $txn->update(['status' => 'withdrawn']);
            $msg = "✅ Approved for {$txn->user->user_name}";
        } else {
            $txn->update(['status' => 'rejected']);
            $txn->user->payoutBalance->increment('available_balance', $txn->currency_amount);
            $msg = "❌ Rejected";
        }
        return back()->with('success', $msg);
    }

    public function payoutSummary(Request $request)
    {
        $summary = FundSummary::with('user')
            ->orderBy('transaction_date', 'desc')
            ->paginate(20);
        return view('admin.pages.mlm.payout-summary', compact('summary'));   
    }
    public function payoutTransferHistory(Request $request)
    {
        $transfers = FundTransfer::with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('admin.pages.mlm.payout-transfer-history', compact('transfers'));   
    }

    public function updatePayoutRequest(Request $request, $id)
    {
        // dd($request->all(), $id);
        $v = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $fundRequest = FundRequest::findOrFail($id);
        $fundRequest->update(['status' => $v['status']]);

        if ($v['status'] === 'approved') {
             
        }

        return back()->with('success', 'Payout request updated successfully!');
    }
    
}