@extends("admin.layout.admin-master")
@section("title", "Bank Account Settings | Continuity Care")

@section("content")
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Settings</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Bank Account Settings</a></li>
                </ol>
            </div>

            <!-- Success Alert -->
            @if(session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '{{ session('success') }}',
                        timer: 4000,
                        timerProgressBar: true,
                    });
                </script>
            @endif

            <div class="row">
                @include("admin.components.setting-sidebar")
                <div class="col-lg-9 ps-0">
                    <div class="card">
                        <div class="card-header bg-theme-light">
                            <h4 class="card-title">Bank Account Settings</h4>
                        </div>
                        <div class="card-body">                           
                            <form action="{{ route('bank-account-settings.update', $bankDetails->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-lg-6 mb-4">
                                        <label for="bank_name" class="form-label fw-bold">Bank Name</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control @error('bank_name') is-invalid @enderror" name="bank_name" id="bank_name"  value="{{ old('bank_name', $bankDetails->bank_name) }}" required>
                                        </div>
                                        @error('bank_name')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror                                        
                                    </div>
                                    <div class="col-lg-6 mb-4">
                                        <label for="account_no" class="form-label fw-bold">Account Number</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control @error('account_no') is-invalid @enderror" name="account_no" id="account_no"  value="{{ old('account_no', $bankDetails->account_no) }}" required>
                                        </div>
                                        @error('account_no')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror                                        
                                    </div>
                                    <div class="col-lg-6 mb-4">
                                        <label for="ifsc_code" class="form-label fw-bold">IFSC Code</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control @error('ifsc_code') is-invalid @enderror" name="ifsc_code" id="ifsc_code"  value="{{ old('ifsc_code', $bankDetails->ifsc_code) }}" required>
                                        </div>
                                        @error('ifsc_code')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror                                        
                                    </div>
                                    <div class="col-lg-6 mb-4">
                                        <label for="address" class="form-label fw-bold">Address</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control @error('address') is-invalid @enderror" name="address"  value="{{ old('address', $bankDetails->address) }}" required>
                                        </div>
                                        @error('address')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror                                        
                                    </div>
                                    <div class="col-lg-6 mb-4">
                                        <label for="mode_name" class="form-label fw-bold">Mode Name</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control @error('mode_name') is-invalid @enderror" name="mode_name" id="mode_name"  value="{{ old('mode_name', $bankDetails->mode_name) }}" required>
                                        </div>
                                        @error('mode_name')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror                                        
                                    </div>

                                    <div class="col-lg-6 mb-4">
                                        <label for="cc_is_active" class="form-label fw-bold">Status</label>
                                        <select name="is_active" id="cc_is_active" class="form-control @error('is_active') is-invalid @enderror">
                                            <option value="1" {{ old('is_active', $bankDetails->is_active) == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('is_active', $bankDetails->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        @error('is_active')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div> 

                                <div class="d-flex justify-content-between align-items-center">
                                  
                                    <button type="submit" class="btn btn-primary btn-lg px-5">
                                        <i class="fa fa-save me-1"></i> Save Changes
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection