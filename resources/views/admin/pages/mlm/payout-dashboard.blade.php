@extends('admin.layout.admin-master')
@section('title', 'Payout Dashboard')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Payout Dashboard</li>
            </ol>
        </div>

        <!-- Config Banner - FIXED: Using controller-passed $config with null-safe access -->
        <div class="alert alert-info mb-4">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Payout Rules:</strong> 
            {{ $config?->products_for_payout ?? 40 }} products = 
            {{ $config?->getThresholdCC() ?? 800 }} CC | 
            1 CC = ₹{{ number_format($config?->cc_to_currency_rate ?? 60, 2) }}
        </div>

        <!-- Users Table -->
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="fas fa-users me-2"></i>Users with Payout Activity</h5>
                <span class="badge bg-primary">{{ $usersWithPayouts->total() ?? 0 }} Users</span>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-hover datatable">
                        <thead class="bg-light">
                            <tr>
                                <th>User</th>
                                <th>Sponsor</th>
                                <th>CC Balance</th>
                                <th>Available</th>
                                <th>Total Earned</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usersWithPayouts as $user)
                                @php
                                    $balance = $user->payoutBalance;
                                    // Safe config values with fallbacks
                                    $ccRate = $config?->cc_to_currency_rate ?? 60;
                                    $ccBalance = $balance?->cc_balance ?? 0;
                                    $ccValue = $ccBalance * $ccRate;
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }}</strong><br>
                                        <small class="text-muted">@{{ $user->user_name ?? 'N/A' }}</small>
                                    </td>
                                    <td>{{ $user->sponsor?->user_name ?? 'ROOT' }}</td>
                                    <td>
                                        {{ number_format($ccBalance) }} CC<br>
                                        <small>≈ ₹{{ number_format($ccValue, 2) }}</small>
                                    </td>
                                    <td class="text-success">
                                        ₹{{ number_format($balance?->available_balance ?? 0, 2) }}
                                    </td>
                                    <td>
                                        ₹{{ number_format($balance?->total_earned ?? 0, 2) }}
                                    </td>
                                    <td>
                                        @if($balance?->is_payout_eligible)
                                            <span class="badge bg-success">Eligible</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary view-payout" data-id="{{ $user->id }}">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                    </td>
                                </tr> 
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3">{{ $usersWithPayouts?->links() ?? '' }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="payoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Payout Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="payoutBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success d-none" id="withdrawBtn" data-bs-toggle="modal" data-bs-target="#withdrawModal">Withdraw</button>
            </div>
        </div>
    </div>
</div>

<!-- Withdraw Modal -->
<div class="modal fade" id="withdrawModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('mlm-users.withdraw') }}" method="POST">
            @csrf
            <input type="hidden" name="user_id" id="wUserId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Withdraw</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        Available: ₹<span id="wAvailable">0.00</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Method</label>
                        <select name="method" class="form-select" required>
                            <option value="bank">Bank Transfer</option>
                            <option value="upi">UPI</option>
                            <option value="wallet">Wallet</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Submit Request</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Safe config injection from Blade with complete fallbacks
    const cfg = {{ $config ? json_encode([
        'cc_to_currency_rate' => $config->cc_to_currency_rate ?? 60,
        'products_for_payout' => $config->products_for_payout ?? 40,
        'threshold_cc' => $config->getThresholdCC() ?? 800
    ]) : json_encode([
        'cc_to_currency_rate' => 60,
        'products_for_payout' => 40,
        'threshold_cc' => 800
    ]) }};

    // Initialize Bootstrap modal instance safely
    const payoutModalEl = document.getElementById('payoutModal');
    const payoutModal = payoutModalEl ? (bootstrap.Modal?.getOrCreateInstance?.(payoutModalEl) || new bootstrap.Modal(payoutModalEl)) : null;
    
    // Format currency helper
    const fmt = (n) => {
        const num = parseFloat(n) || 0;
        return num.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    };

    // View button click handler
    document.querySelectorAll('.view-payout').forEach(btn => {
        btn.onclick = async (e) => {
            e.preventDefault();
            const id = btn.dataset.id;
            const body = document.getElementById('payoutBody');
            const wBtn = document.getElementById('withdrawBtn');
            
            // Show loading state
            body.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading...</p></div>';
            if (wBtn) wBtn.classList.add('d-none');
            
            try {
                const res = await fetch(`/mlm-users/payout/${id}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!res.ok) {
                    const errText = await res.text().catch(() => 'Unknown error');
                    throw new Error(`Server error: ${res.status} - ${errText.substring(0, 150)}`);
                }
                
                const data = await res.json();
                
                if (!data?.summary) throw new Error('No payout data found');
                
                const s = data.summary;
                
                // Build modal content with safe null checks
                body.innerHTML = `
                    <div class="row g-3">
                        <div class="col-4 text-center">
                            <div class="card bg-primary text-white h-100">
                                <div class="card-body">
                                    <i class="fas fa-coins fa-2x mb-2"></i>
                                    <h6 class="mb-1">CC Balance</h6>
                                    <h4 class="mb-0">${s.personal_cc || 0} CC</h4>
                                    <small>≈ ₹${fmt((s.personal_cc || 0) * cfg.cc_to_currency_rate)}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body">
                                    <i class="fas fa-wallet fa-2x mb-2"></i>
                                    <h6 class="mb-1">Available</h6>
                                    <h4 class="mb-0">₹${fmt(s.available_balance)}</h4>
                                    <small>Ready to withdraw</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="card bg-info text-white h-100">
                                <div class="card-body">
                                    <i class="fas fa-trophy fa-2x mb-2"></i>
                                    <h6 class="mb-1">Total Earned</h6>
                                    <h4 class="mb-0">₹${fmt((s.available_balance || 0) + (s.locked_balance || 0))}</h4>
                                    <small>Lifetime earnings</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="fw-bold">Eligibility Progress</label>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar ${s.is_eligible ? 'bg-success' : 'bg-warning'}" 
                                 style="width: ${Math.min(100, Math.max(0, s.progress_percent || 0))}%"></div>
                        </div>
                        <small class="text-muted mt-1 d-block">
                            ${s.products_needed || 0} more products to unlock payouts
                            ${s.is_eligible ? '<span class="text-success fw-bold ms-1">✓ ELIGIBLE</span>' : ''}
                        </small>
                    </div>
                    
                    <h6 class="mt-4 mb-2"><i class="fas fa-history me-1"></i>Recent Transactions</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="bg-light">
                                <tr><th>Date</th><th>Type</th><th>CC</th><th>Amount</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                ${(data.transactions || []).length > 0 
                                    ? data.transactions.map(t => `
                                        <tr>
                                            <td><small>${t.created_at ? new Date(t.created_at).toLocaleDateString() : 'N/A'}</small></td>
                                            <td><span class="badge bg-${t.type === 'direct_income' ? 'success' : 'info'}">${(t.type || '').replace('_', ' ')}</span></td>
                                            <td>${t.cc_amount || 0}</td>
                                            <td>₹${fmt(t.currency_amount)}</td>
                                            <td><span class="badge bg-${t.status === 'credited' ? 'success' : 'secondary'}">${t.status || 'pending'}</span></td>
                                        </tr>
                                    `).join('')
                                    : '<tr><td colspan="5" class="text-center text-muted py-3">No transactions yet</td></tr>'
                                }
                            </tbody>
                        </table>
                    </div>
                `;
                
                // Show withdraw button if eligible and has balance
                if (wBtn && s.is_eligible && (s.available_balance || 0) > 0) {
                    wBtn.classList.remove('d-none');
                    const wUserId = document.getElementById('wUserId');
                    const wAvailable = document.getElementById('wAvailable');
                    if (wUserId && data?.user?.id) wUserId.value = data.user.id;
                    if (wAvailable) wAvailable.textContent = fmt(s.available_balance);
                }
                
                // Show the modal
                if (payoutModal) payoutModal.show();
                
            } catch (e) {
                console.error('❌ Modal error:', e);
                body.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Error:</strong> ${e.message || 'Something went wrong'}
                        <button class="btn btn-sm btn-primary mt-2" onclick="location.reload()">
                            <i class="fas fa-redo me-1"></i>Retry
                        </button>
                    </div>
                `;
                if (payoutModal) payoutModal.show();
            }
        };
    });
    
    // Reset modal on close
    if (payoutModalEl) {
        payoutModalEl.addEventListener('hidden.bs.modal', () => {
            const body = document.getElementById('payoutBody');
            const wBtn = document.getElementById('withdrawBtn');
            if (body) body.innerHTML = '';
            if (wBtn) wBtn.classList.add('d-none');
        });
    }
});
</script>
@endpush