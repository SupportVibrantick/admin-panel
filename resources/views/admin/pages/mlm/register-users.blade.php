@extends('admin.layout.admin-master')
@section('title', 'MLM Users | Continuity Care')


@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <!-- Breadcrumb -->
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">MLM Users</li>
                </ol>
            </div>

            <!-- Header -->
            <div class="form-head d-flex mb-3 align-items-start">
                <div class="me-auto">
                    <button class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        + Add Customer
                    </button>
                </div>
            </div>

          <!-- ✅ Unified Alerts Section - ALL messages show here -->
            @if (session('success') || session('error') || session('email_warning') || $errors->any())
                <div class="alert-container mb-3">
                    
                    {{-- Success Message --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Email Warning (Special Case) --}}
                    @if (session('email_warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('email_warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- General Error --}}
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Validation Errors Summary --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="mb-2"><i class="bi bi-exclamation-octagon-fill me-2"></i>Validation Errors:</h6>
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                </div>
            @endif
            <!-- Table -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="table-responsive">
                        <table class="table table-bordered shadow-sm ">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Sponsor</th>
                                    <th>Status</th>
                                    <th>Commission</th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $users->firstItem() + $loop->index }}</td>
                                        <td>
                                            <strong>{{ $user->user_name }}</strong><br>
                                            <small class="text-muted">{{ $user->track_id }}</small>
                                        </td>
                                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                        <td>{{ $user->email }}<br><small class="text-muted">{{ $user->phone }}</small>
                                        </td>
                                        <td>
                                            @if ($user->sponsor)
                                                <span class="badge bg-info">{{ $user->sponsor->user_name }}</span>
                                            @else
                                                <span class="badge bg-primary">ROOT</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->is_deleted)
                                                <span class="badge bg-danger">Deleted</span>
                                            @elseif(!$user->is_active)
                                                <span class="badge bg-warning">Inactive</span>
                                            @elseif(!$user->is_verified)
                                                <span class="badge bg-info">Pending</span>
                                            @else
                                                <span class="badge bg-success">Active</span>
                                            @endif
                                        </td>

                                        <!-- ✅ FIXED: Use direct commission_percentage field -->
                                        <td>
                                            @php
                                                $commission = $user->commission_percentage;
                                                $badgeColor = $commission == 20 ? 'success' : 'primary';
                                                $amount = $commission == 20 ? 200 : 100;
                                            @endphp
                                            <span class="badge bg-{{ $badgeColor }}">
                                                {{ $commission ? $commission . '%' : 'N/A' }} 
                                            </span>
                                            <br><small>₹{{ $amount }}/bottle</small>
                                        </td>

                                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false" title="Actions"
                                                    style="border: none; background: transparent;">
                                                    <i class="fas fa-ellipsis-v text-muted"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                                                    style="min-width: 200px;">
                                                    <!-- Edit -->
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center gap-2"
                                                            href="#" data-bs-toggle="modal"
                                                            data-bs-target="#editUserModal{{ $user->id }}">
                                                            <i class="fas fa-edit text-warning"></i>
                                                            <span>Edit</span>
                                                        </a>
                                                    </li>

                                                    <!-- Create Order -->
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center gap-2"
                                                            href="#"
                                                            onclick="createOrder({{ $user->id }}, '{{ $user->user_name }}'); return false;">
                                                            <i class="fas fa-shopping-cart text-success"></i>
                                                            <span>Create Order</span>
                                                        </a>
                                                    </li>

                                                    <!-- Delete -->
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center gap-2 text-danger"
                                                            href="#"
                                                            onclick="confirmDelete({{ $user->id }}, '{{ $user->user_name }}'); return false;">
                                                            <i class="fas fa-trash"></i>
                                                            <span>Delete</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit User Modal -->
                                    <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Customer</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>

                                                <form action="{{ route('mlm-users.update', $user->id) }}" method="POST"
                                                    id="editForm{{ $user->id }}">
                                                    @csrf @method('PUT')
                                                    <div class="modal-body">
                                                        @if ($errors->any())
                                                            <div class="alert alert-danger">
                                                                <ul class="mb-0">
                                                                    @foreach ($errors->all() as $error)
                                                                        <li>{{ $error }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif

                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-bold">User Name *</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $user->user_name }}" readonly>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-bold">Sponsor Username *</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $user->sponsor ? $user->sponsor->user_name : 'ROOT' }}"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-bold">First Name *</label>
                                                                <input type="text" name="first_name" class="form-control"
                                                                    value="{{ $user->first_name }}" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-bold">Last Name</label>
                                                                <input type="text" name="last_name" class="form-control"
                                                                    value="{{ $user->last_name }}">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-bold">Email *</label>
                                                                <input type="email" name="email" class="form-control"
                                                                    value="{{ $user->email }}" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-bold">Phone *</label>
                                                                <input type="text" name="phone" class="form-control"
                                                                    value="{{ $user->phone }}" required>
                                                            </div>

                                                            <!-- ✅ FIXED: Commission Dropdown with correct class and value binding -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-bold">Commission % <span
                                                                        class="text-danger">*</span></label>
                                                                <select name="commission_percentage" class="select @error('commission_percentage') is-invalid @enderror">
                                                                    <option value="">Select Commission</option>
                                                                    @foreach ([10, 12, 14, 16, 18, 20] as $percent)
                                                                        @php
                                                                            $label =
                                                                                $percent == 20
                                                                                    ? '20% (Premium - ₹200/bottle)'
                                                                                    : "{$percent}% (₹100/bottle)";
                                                                        @endphp
                                                                        <option value="{{ $percent }}"
                                                                            {{ ($user->commission_percentage ?? 10) == $percent ? 'selected' : '' }}>
                                                                            {{ $label }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('commission_percentage')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                                <small class="text-muted">10-18% = ₹100 per bottle | 20% =
                                                                    ₹200 per bottle</small>
                                                            </div>

                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-bold">Password</label>
                                                                <div class="input-group">
                                                                    <input type="password" name="password"
                                                                        class="form-control"
                                                                        placeholder="Leave blank to keep current"
                                                                        id="edit_password{{ $user->id }}">
                                                                    <button class="btn btn-outline-secondary"
                                                                        type="button"
                                                                        onclick="toggleEditPassword({{ $user->id }})">
                                                                        <i class="fa fa-eye"></i>
                                                                    </button>
                                                                </div>
                                                                <small class="text-muted">Leave blank to keep current
                                                                    password</small>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-bold">Confirm Password</label>
                                                                <div class="input-group">
                                                                    <input type="password" name="password_confirmation"
                                                                        class="form-control"
                                                                        id="edit_password_confirmation{{ $user->id }}">
                                                                    <button class="btn btn-outline-secondary"
                                                                        type="button"
                                                                        onclick="toggleEditPasswordConfirm({{ $user->id }})">
                                                                        <i class="fa fa-eye"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 mb-3">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label class="form-label fw-bold">Active</label>
                                                                        <div class="form-check form-switch">
                                                                            <input type="hidden" name="is_active"
                                                                                value="0">
                                                                            <input type="checkbox" name="is_active"
                                                                                class="form-check-input"
                                                                                id="is_active{{ $user->id }}"
                                                                                value="1"
                                                                                {{ $user->is_active ? 'checked' : '' }}
                                                                                role="switch">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label fw-bold">Verified</label>
                                                                        <div class="form-check form-switch">
                                                                            <input type="hidden" name="is_verified"
                                                                                value="0">
                                                                            <input type="checkbox" name="is_verified"
                                                                                class="form-check-input"
                                                                                id="is_verified{{ $user->id }}"
                                                                                value="1"
                                                                                {{ $user->is_verified ? 'checked' : '' }}
                                                                                role="switch">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">CANCEL</button>
                                                        <button type="submit" class="btn btn-primary">UPDATE</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="">
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New MLM User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  

                    <form action="{{ route('mlm-users.store') }}" method="POST" id="mlmRegisterForm" novalidate>
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Sponsor Username <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="sponsor_username"
                                    class="form-control @error('sponsor_username') is-invalid @enderror"
                                    value="{{ old('sponsor_username') }}" placeholder="Enter sponsor username" required
                                    autocomplete="off" id="sponsor_username">
                                @error('sponsor_username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">User Name <span class="text-danger">*</span></label>
                                <input type="text" name="user_name"
                                    class="form-control @error('user_name') is-invalid @enderror"
                                    value="{{ old('user_name') }}" placeholder="Unique username" required>
                                @error('user_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name"
                                    class="form-control @error('first_name') is-invalid @enderror"
                                    value="{{ old('first_name') }}" placeholder="First name" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Last Name</label>
                                <input type="text" name="last_name"
                                    class="form-control @error('last_name') is-invalid @enderror"
                                    value="{{ old('last_name') }}" placeholder="Last name">
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                    placeholder="user@example.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Phone <span class="text-danger">*</span></label>
                                <input type="tel" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}"
                                    placeholder="9999999999" pattern="[0-9]{10}" maxlength="10" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Commission Percentage -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Commission % <span class="text-danger">*</span></label>
                                <select name="commission_percentage" class="select @error('commission_percentage') is-invalid @enderror" required>
                                    <option value="">Select Commission</option>
                                    @foreach ([10, 12, 14, 16, 18, 20] as $percent)
                                        @php
                                            $label =
                                                $percent == 20
                                                    ? '20% (Premium - ₹200/bottle)'
                                                    : "{$percent}% (₹100/bottle)";
                                        @endphp
                                        <option value="{{ $percent }}"
                                            {{ old('commission_percentage') == $percent ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('commission_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">10-18% = ₹100 per bottle | 20% = ₹200 per bottle</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Min 8 characters" required id="password">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="togglePwd('password')"><i class="fa fa-eye"></i></button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Confirm Password <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        placeholder="Re-enter password" required id="password_confirmation">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="togglePwd('password_confirmation')"><i class="fa fa-eye"></i></button>
                                </div>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="reset" class="btn btn-warning px-4">Reset</button>
                            <button type="submit" class="btn btn-primary px-5" id="submitBtn"><i
                                    class="bi bi-check-circle me-2"></i>Register User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ Simple Create Order Modal -->
    <div class="modal fade" id="createOrderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('mlm-users.store-order') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" id="orderUserId">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="fas fa-shopping-cart me-2"></i>Create Order</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @if (isset($products) && $products->count() > 0)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Select Product</label>
                                <select name="items[0][product_id]" id="productSelect" class="select" required>
                                    <option value="">Choose a product...</option>
                                    @foreach ($products as $p)
                                        <option value="{{ $p->id }}">
                                            {{ $p->name }} ({{ $p->sku }}) -
                                            ₹{{ number_format($p->discount_price ?? $p->price, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Quantity</label>
                                <input type="number" name="items[0][quantity]" class="form-control" min="1"
                                    max="99" value="1" required>
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-bold">Payment Mode</label>
                                <select name="payment_mode" class="select" required>
                                    <option value="cash">Cash</option>
                                    <option value="online">Online Payment</option>
                                    <option value="upi">UPI</option>
                                </select>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>No products available. Please add products
                                first.
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        @if (isset($products) && $products->count() > 0)
                            <button type="submit" class="btn btn-success"><i class="fas fa-check me-1"></i>Create
                                Order</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts') 
@php
    $hasRegisterErrors = $errors->has('sponsor_username') || $errors->has('user_name') || $errors->has('email') || $errors->has('phone') || $errors->has('password');
    $hasEditErrors = false; // Edit form errors usually don't redirect back with old input
@endphp

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // ✅ Open Add User Modal if registration errors exist
        @if ($hasRegisterErrors || old('sponsor_username') || old('user_name'))
            const addModal = new bootstrap.Modal(document.getElementById('addUserModal'));
            addModal.show();
            
            // Optional: Scroll to top to see alerts
            window.scrollTo({ top: 0, behavior: 'smooth' });
        @endif

        // ✅ Optional: Auto-hide alerts after 8 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert-dismissible').forEach(alert => {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            });
        }, 8000);
    });
</script>
    <script>
        // Toggle Password
        function togglePwd(fieldId) {
            const input = document.getElementById(fieldId);
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        // Toggle Edit Password
        function toggleEditPassword(userId) {
            const input = document.getElementById('edit_password' + userId);
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        // Toggle Edit Password Confirm
        function toggleEditPasswordConfirm(userId) {
            const input = document.getElementById('edit_password_confirmation' + userId);
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        // Validate Sponsor in Real-time
        document.getElementById('sponsor_username').addEventListener('blur', async function() {
            const username = this.value.trim();
            const statusEl = document.getElementById('sponsor-status');
            if (username.length >= 3) {
                try {
                    const response = await fetch(
                        `/api/mlm/check-sponsor?username=${encodeURIComponent(username)}`);
                    const data = await response.json();
                    if (data.valid) {
                        statusEl.textContent = `✓ Valid sponsor: ${data.sponsor_name}`;
                        statusEl.className = 'text-success';
                    } else {
                        statusEl.textContent = '✗ Sponsor not found';
                        statusEl.className = 'text-danger';
                    }
                } catch (error) {
                    console.log('Sponsor check failed:', error);
                }
            }
        });

        // Form Submit Handler
        document.getElementById('mlmRegisterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            if (password !== confirm) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Mismatch',
                    text: 'Passwords do not match!',
                    timer: 3000
                });
                return false;
            }
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Registering...';
            submitBtn.disabled = true;
            this.submit();
        });

        // Delete Confirmation
        function confirmDelete(userId, userName) {
            Swal.fire({
                title: 'Delete User?',
                text: `Are you sure you want to delete "${userName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/mlm-users/${userId}`;
                    form.innerHTML = `@csrf @method('DELETE')`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Create Order Modal
        function createOrder(userId, userName) {
            $('#orderUserId').val(userId);
            $('#productSelect').val('');
            $('#createOrderModal').modal('show');
        }

        // On page load - show modal if errors
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                const modal = new bootstrap.Modal(document.getElementById('addUserModal'));
                modal.show();
                Swal.fire({
                    icon: 'error',
                    title: 'Registration Failed',
                    text: 'Please check the errors below and try again.',
                    timer: 4000
                });
            });
        @endif
    </script>
@endpush
