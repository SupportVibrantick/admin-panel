@extends('admin.layout.admin-master')

@section('title', 'Wallet Management')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Wallet Management</li>
            </ol>
        </div>

        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Wallets</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createWalletModal">
                    <i class="fas fa-plus me-1"></i> Create New Wallet
                </button>
            </div>
            {{-- @dump($wallets->toArray()) --}}
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="form-check-input"></th>
                                <th>Wallet Name</th>
                                <th>Code</th>
                                <th>Eligibility</th>
                                <th>Currency</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($wallets as $wallet)
                                <tr>
                                    <td><input type="checkbox" class="form-check-input"></td>
                                   <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <!-- ✅ Safe access with null coalescing -->
                                                <strong>{{ $wallet->name ?? 'N/A' }}</strong>
                                                <small class="d-block text-muted">
                                                    Created {{ $wallet->created_at?->format('d M Y H:i') ?? 'N/A' }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-dark">{{ $wallet->code }}</span></td>
                                    <td>{{ str_replace('_', ' ', $wallet->eligibility) }}</td>
                                    <td><strong>{{ $wallet->currency_code }}</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $wallet->is_active ? 'success' : 'secondary' }}">
                                            {{ $wallet->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                                                       <td>
                                        <div class="d-flex gap-1">
                                            <!-- Edit Button -->
                                            <button class="btn btn-sm btn-light" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editWalletModal{{ $wallet->id }}"
                                                    title="Edit Wallet">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                            
                                            <!-- Settings Button -->
                                            <button class="btn btn-sm btn-light" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#payoutConfigModal{{ $wallet->id }}"
                                                    title="Payout Settings">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                            
                                            <!-- More Actions Dropdown -->
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light" 
                                                        data-bs-toggle="dropdown" 
                                                        title="More Actions">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="#" 
                                                           onclick="syncWallet({{ $wallet->id }})">
                                                            <i class="fas fa-sync me-2"></i> Sync Wallet
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#" 
                                                           data-bs-toggle="modal" 
                                                           data-bs-target="#assignUserModal{{ $wallet->id }}">
                                                            <i class="fas fa-user-plus me-2"></i> Assign User
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#removeUserModal{{ $wallet->id }}">
                                                            <i class="fas fa-user-times me-2"></i> Remove User
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#" 
                                                           data-bs-toggle="modal" 
                                                           data-bs-target="#walletChargesModal{{ $wallet->id }}">
                                                            <i class="fas fa-money-bill-wave me-2"></i> Wallet Charges
                                                        </a>
                                                    </li>
                                    
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="#" 
                                                           onclick="deleteWallet({{ $wallet->id }})">
                                                            <i class="fas fa-trash me-2"></i> Delete
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Edit Wallet Modal -->
                                <div class="modal fade" id="editWalletModal{{ $wallet->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <form action="{{ route('wallets.update', $wallet) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Update Master Wallet</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Wallet Display Name *</label>
                                                        <input type="text" name="name" class="form-control" 
                                                               value="{{ $wallet->name }}" required>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Internal Code *</label>
                                                            <select name="code" class="form-select" required>
                                                                <option value="COMMISSION" {{ $wallet->code == 'COMMISSION' ? 'selected' : '' }}>Commission</option>
                                                                <option value="PURCHASE" {{ $wallet->code == 'PURCHASE' ? 'selected' : '' }}>Purchase</option>
                                                                <option value="REWARD" {{ $wallet->code == 'REWARD' ? 'selected' : '' }}>Reward</option>
                                                                <option value="BONUS" {{ $wallet->code == 'BONUS' ? 'selected' : '' }}>Bonus</option>
                                                                <option value="REFERRAL" {{ $wallet->code == 'REFERRAL' ? 'selected' : '' }}>Referral</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Eligibility Rule *</label>
                                                            <select name="eligibility" class="form-select" required>
                                                                <option value="ALL" {{ $wallet->eligibility == 'ALL' ? 'selected' : '' }}>All Users</option>
                                                                <option value="SPONSORED_ONLY" {{ $wallet->eligibility == 'SPONSORED_ONLY' ? 'selected' : '' }}>Sponsored Users Only</option>
                                                                <option value="ACTIVE_MEMBERS" {{ $wallet->eligibility == 'ACTIVE_MEMBERS' ? 'selected' : '' }}>Active Members Only</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Currency Code *</label>
                                                        <input type="text" name="currency_code" class="form-control" 
                                                               value="{{ $wallet->currency_code }}" maxlength="3" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="form-check form-switch">
                                                            <input type="hidden" name="is_active" value="0">
                                                            <input type="checkbox" name="is_active" class="form-check-input" 
                                                                   value="1" {{ $wallet->is_active ? 'checked' : '' }}>
                                                            <label class="form-check-label">
                                                                Status: {{ $wallet->is_active ? 'Active' : 'Inactive' }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                
                                <!-- Payout Configuration Modal -->
                                <div class="modal fade" id="payoutConfigModal{{ $wallet->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <form action="{{ route('wallets.update-payout-config', $wallet) }}" method="POST">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-cog me-2"></i>Wallet Payout Configuration
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label">Payout Schedule *</label>
                                                            <select name="payout_schedule" class="form-select" required>
                                                                <option value="DAILY" {{ ($wallet->configuration->payout_schedule ?? '') == 'DAILY' ? 'selected' : '' }}>Daily</option>
                                                                <option value="WEEKLY" {{ ($wallet->configuration->payout_schedule ?? '') == 'WEEKLY' ? 'selected' : '' }}>Weekly</option>
                                                                <option value="MONTHLY" {{ ($wallet->configuration->payout_schedule ?? '') == 'MONTHLY' ? 'selected' : '' }}>Monthly</option>
                                                                <option value="INSTANT" {{ ($wallet->configuration->payout_schedule ?? '') == 'INSTANT' ? 'selected' : '' }}>Instant</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label">Payout Execution Day</label>
                                                            <select name="payout_execution_day" class="form-select">
                                                                <option value="">Select Day</option>
                                                                <option value="Monday" {{ ($wallet->configuration->payout_execution_day ?? '') == 'Monday' ? 'selected' : '' }}>Monday</option>
                                                                <option value="Tuesday" {{ ($wallet->configuration->payout_execution_day ?? '') == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                                                                <option value="Wednesday" {{ ($wallet->configuration->payout_execution_day ?? '') == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                                                                <option value="Thursday" {{ ($wallet->configuration->payout_execution_day ?? '') == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                                                                <option value="Friday" {{ ($wallet->configuration->payout_execution_day ?? '') == 'Friday' ? 'selected' : '' }}>Friday</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label">Refund Window (Days) *</label>
                                                            <input type="number" name="refund_window_days" class="form-control" 
                                                                   value="{{ $wallet->configuration->refund_window_days ?? 30 }}" required>
                                                            <small class="text-muted">Days to hold funds</small>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label">Min Withdraw Amount *</label>
                                                            <input type="number" name="min_withdraw_amount" class="form-control" 
                                                                   value="{{ $wallet->configuration->min_withdraw_amount ?? 500 }}" 
                                                                   step="0.01" required>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label">Max Payouts Per Batch *</label>
                                                            <input type="number" name="max_payouts_per_batch" class="form-control" 
                                                                   value="{{ $wallet->configuration->max_payouts_per_batch ?? 500 }}" required>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label">Withdraw Cool-down (Days) *</label>
                                                            <input type="number" name="withdraw_cooldown_days" class="form-control" 
                                                                   value="{{ $wallet->configuration->withdraw_cooldown_days ?? 7 }}" required>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label">Start Window</label>
                                                            <input type="time" name="start_window" class="form-control" 
                                                                   value="{{ $wallet->configuration->start_window ?? '' }}">
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label">End Window</label>
                                                            <input type="time" name="end_window" class="form-control" 
                                                                   value="{{ $wallet->configuration->end_window ?? '' }}">
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <div class="form-check form-switch mt-4">
                                                                <input type="hidden" name="auto_payout" value="0">
                                                                <input type="checkbox" name="auto_payout" class="form-check-input" 
                                                                       value="1" {{ ($wallet->configuration->auto_payout ?? false) ? 'checked' : '' }}>
                                                                <label class="form-check-label">Automated Payout</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Processing Fee (%)</label>
                                                            <input type="number" name="processing_fee_percent" class="form-control" 
                                                                   value="{{ $wallet->configuration->processing_fee_percent ?? 0 }}" 
                                                                   step="0.01" min="0" max="100">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Processing Fee (Fixed)</label>
                                                            <input type="number" name="processing_fee_fixed" class="form-control" 
                                                                   value="{{ $wallet->configuration->processing_fee_fixed ?? 0 }}" 
                                                                   step="0.01" min="0">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Discard Changes</button>
                                                    <button type="submit" class="btn btn-primary">Save Configuration</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                
                                <!-- Wallet Charges Modal -->
                                <div class="modal fade" id="walletChargesModal{{ $wallet->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <form action="{{ route('wallets.update-charges', $wallet) }}" method="POST">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-money-bill-wave me-2"></i>Wallet Charges Configuration
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div id="chargesContainer{{ $wallet->id }}">
                                                        @php $chargeIndex = 0; @endphp
                                                        @forelse($wallet->charges as $charge)
                                                            <div class="charge-item border rounded p-3 mb-3">
                                                                <div class="row">
                                                                    <div class="col-md-3 mb-3">
                                                                        <label class="form-label">Charge Type</label>
                                                                        <input type="text" name="charges[{{ $chargeIndex }}][charge_type]" 
                                                                               class="form-control" value="{{ $charge->charge_type }}" required>
                                                                    </div>
                                                                    <div class="col-md-3 mb-3">
                                                                        <label class="form-label">Charge Mode</label>
                                                                        <select name="charges[{{ $chargeIndex }}][charge_mode]" class="form-select" required>
                                                                            <option value="FIXED" {{ $charge->charge_mode == 'FIXED' ? 'selected' : '' }}>Fixed Amount</option>
                                                                            <option value="PERCENTAGE" {{ $charge->charge_mode == 'PERCENTAGE' ? 'selected' : '' }}>Percentage (%)</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-2 mb-3">
                                                                        <label class="form-label">Charge Value *</label>
                                                                        <input type="number" name="charges[{{ $chargeIndex }}][charge_value]" 
                                                                               class="form-control" value="{{ $charge->charge_value }}" 
                                                                               step="0.01" required>
                                                                    </div>
                                                                    <div class="col-md-2 mb-3">
                                                                        <label class="form-label">Min Charge</label>
                                                                        <input type="number" name="charges[{{ $chargeIndex }}][min_charge]" 
                                                                               class="form-control" value="{{ $charge->min_charge }}" 
                                                                               step="0.01">
                                                                    </div>
                                                                    <div class="col-md-2 mb-3">
                                                                        <label class="form-label">Max Charge</label>
                                                                        <input type="number" name="charges[{{ $chargeIndex }}][max_charge]" 
                                                                               class="form-control" value="{{ $charge->max_charge }}" 
                                                                               step="0.01">
                                                                    </div>
                                                                </div>
                                                                <button type="button" class="btn btn-sm btn-danger" 
                                                                        onclick="this.closest('.charge-item').remove()">
                                                                    <i class="fas fa-trash"></i> Remove
                                                                </button>
                                                            </div>
                                                            @php $chargeIndex++; @endphp
                                                        @empty
                                                            <p class="text-muted text-center">No charges configured yet.</p>
                                                        @endforelse
                                                        
                                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                                onclick="addCharge{{ $wallet->id }}()">
                                                            <i class="fas fa-plus me-1"></i> Add Another Charge
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Save Charges</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                
                               <!-- Assign User Modal -->
                                <div class="modal fade" id="assignUserModal{{ $wallet->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <form action="{{ route('wallets.assign-user', $wallet) }}" method="POST">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Assign User to Wallet</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Select User *</label>
                                                        <select name="user_id" class="form-select" required>
                                                            <option value="">Choose a user...</option>
                                                            @foreach(\App\Models\MlmUser::where('is_deleted', false)->orderBy('user_name')->get() as $user)
                                                                @php
                                                                    $isAssigned = $wallet->balances()
                                                                        ->where('user_id', $user->id)
                                                                        ->exists();
                                                                @endphp
                                                                <option value="{{ $user->id }}" 
                                                                        {{ $isAssigned ? 'disabled' : '' }}>
                                                                    {{ $user->user_name }} - {{ $user->first_name }} {{ $user->last_name }}
                                                                    {{ $isAssigned ? '(Already Assigned)' : '' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <small class="text-muted">
                                                            Disabled users are already assigned to this wallet.
                                                        </small>
                                                    </div>
                                                    
                                                    <!-- Show Already Assigned Users -->
                                                    @if($wallet->balances()->count() > 0)
                                                        <div class="alert alert-info mt-3">
                                                            <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i>Already Assigned Users:</h6>
                                                            <ul class="mb-0">
                                                                @foreach($wallet->balances()->with('user')->get() as $balance)
                                                                    @if($balance->user)
                                                                        <li>
                                                                            <strong>{{ $balance->user->user_name }}</strong> 
                                                                            - {{ $balance->user->first_name }} {{ $balance->user->last_name }}
                                                                            <span class="badge bg-success ms-2">Balance: ₹{{ number_format($balance->balance, 2) }}</span>
                                                                        </li>
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                    
                                                    <div class="alert alert-info">
                                                        <i class="fas fa-info-circle me-2"></i>
                                                        This will create a wallet balance entry for the selected user.
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Assign User</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                                                <!-- Remove User Modal -->
                                <div class="modal fade" id="removeUserModal{{ $wallet->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <form action="{{ route('wallets.remove-user', $wallet) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-user-times me-2"></i>Remove User from Wallet
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @if($wallet->balances()->count() > 0)
                                                        <div class="alert alert-warning">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                                            <strong>Warning:</strong> Removing a user will delete their wallet balance entry. Make sure the balance is zero before removing!
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label class="form-label">Select User to Remove *</label>
                                                            <select name="user_id" class="form-select" required>
                                                                <option value="">Choose a user...</option>
                                                                @foreach($wallet->balances()->with('user')->get() as $balance)
                                                                    @if($balance->user)
                                                                        <option value="{{ $balance->user->id }}">
                                                                            {{ $balance->user->user_name }} - {{ $balance->user->first_name }} {{ $balance->user->last_name }}
                                                                            (Balance: ₹{{ number_format($balance->balance, 2) }})
                                                                        </option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="alert alert-info">
                                                            <h6 class="mb-2">Current Wallet Balances:</h6>
                                                            <ul class="mb-0">
                                                                @foreach($wallet->balances()->with('user')->get() as $balance)
                                                                    @if($balance->user)
                                                                        <li>
                                                                            <strong>{{ $balance->user->user_name }}</strong> 
                                                                            - Balance: <span class="{{ $balance->balance > 0 ? 'text-danger' : 'text-success' }}">
                                                                                ₹{{ number_format($balance->balance, 2) }}
                                                                            </span>
                                                                        </li>
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @else
                                                        <div class="alert alert-info text-center">
                                                            <i class="fas fa-info-circle me-2"></i>
                                                            No users assigned to this wallet yet.
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger" {{ $wallet->balances()->count() == 0 ? 'disabled' : '' }}>
                                                        <i class="fas fa-user-times me-1"></i> Remove User
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        No wallets found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $wallets->links() }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Create Wallet Modal -->
<div class="modal fade" id="createWalletModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('wallets.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Wallet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Wallet Display Name *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Internal Code *</label>
                            <select name="code" class="form-select" required>
                                <option value="">Select Code</option>
                                <option value="COMMISSION">Commission</option>
                                <option value="PURCHASE">Purchase</option>
                                <option value="REWARD">Reward</option>
                                <option value="BONUS">Bonus</option>
                                <option value="REFERRAL">Referral</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Eligibility Rule *</label>
                            <select name="eligibility" class="form-select" required>
                                <option value="ALL">All Users</option>
                                <option value="SPONSORED_ONLY">Sponsored Users Only</option>
                                <option value="ACTIVE_MEMBERS">Active Members Only</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Currency Code *</label>
                        <input type="text" name="currency_code" class="form-control" value="INR" maxlength="3" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Wallet Type *</label>
                            <select name="type" class="form-select" required>
                                <option value="BOTH">Credit & Debit</option>
                                <option value="CREDIT">Credit Only</option>
                                <option value="DEBIT">Debit Only</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Min Balance</label>
                            <input type="number" name="min_balance" class="form-control" value="0" step="0.01">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Wallet</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ✅ Pass errors from Blade to JavaScript
const validationErrors = @json($errors->all());

document.addEventListener('DOMContentLoaded', function() {
    
    // Success Message
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            timer: 4000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    @endif
    
    // Error Message
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            timer: 5000,
            timerProgressBar: true,
            showConfirmButton: true,
            toast: false,
            position: 'center'
        });
    @endif
    
    // ✅ Validation Errors - Fixed
    @if($errors->any())
        let errorHtml = '<ul class="text-start mb-0">';
        @foreach($errors->all() as $error)
            errorHtml += '<li>{{ $error }}</li>';
        @endforeach
        errorHtml += '</ul>';
        
        Swal.fire({
            icon: 'error',
            title: 'Validation Error!',
            html: errorHtml,
            timer: 6000,
            timerProgressBar: true,
            showConfirmButton: true
        });
    @endif
    
});

// Delete Wallet with SweetAlert Confirmation
function deleteWallet(id) {
    Swal.fire({
        title: 'Delete Wallet?',
        text: "Are you sure you want to delete this wallet? This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Delete!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/wallets/${id}`;
            form.innerHTML = `@csrf @method('DELETE')`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Sync Wallet
function syncWallet(id) {
    Swal.fire({
        title: 'Sync Wallet?',
        text: "This will sync wallet balances with recent transactions.",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#0d6efd',
        confirmButtonText: 'Yes, Sync!',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return new Promise((resolve) => {
                setTimeout(() => {
                    resolve();
                }, 1000);
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Synced!', 'Wallet has been synced successfully.', 'success');
        }
    });
}

// Add charge functionality
@foreach($wallets as $wallet)
window.addCharge{{ $wallet->id }} = function() {
    const container = document.getElementById('chargesContainer{{ $wallet->id }}');
    const chargeIndex = {{ $wallet->charges->count() }} + Math.floor(Math.random() * 1000);
    const html = `
        <div class="charge-item border rounded p-3 mb-3">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Charge Type</label>
                    <input type="text" name="charges[${chargeIndex}][charge_type]" 
                           class="form-control" placeholder="e.g., Withdrawal Fee" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Charge Mode</label>
                    <select name="charges[${chargeIndex}][charge_mode]" class="form-select" required>
                        <option value="FIXED">Fixed Amount</option>
                        <option value="PERCENTAGE">Percentage (%)</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Charge Value *</label>
                    <input type="number" name="charges[${chargeIndex}][charge_value]" 
                           class="form-control" step="0.01" required>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Min Charge</label>
                    <input type="number" name="charges[${chargeIndex}][min_charge]" 
                           class="form-control" step="0.01">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Max Charge</label>
                    <input type="number" name="charges[${chargeIndex}][max_charge]" 
                           class="form-control" step="0.01">
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('.charge-item').remove()">
                <i class="fas fa-trash"></i> Remove
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: 'Charge field added',
        showConfirmButton: false,
        timer: 2000
    });
};
@endforeach
</script>
@endpush