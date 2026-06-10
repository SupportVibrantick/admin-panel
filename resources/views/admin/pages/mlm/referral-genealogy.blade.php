@extends('admin.layout.admin-master')
@section('title', 'Referral Genealogy')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Referral Genealogy</li>
            </ol>
        </div>

        <!-- 📊 Stats Row -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center py-3">
                        <i class="fas fa-users fa-2x mb-2"></i>
                        <h3 class="mb-0">{{ $stats['total'] }}</h3>
                        <small>Total Referrals</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body text-center py-3">
                        <i class="fas fa-check-circle fa-2x mb-2"></i>
                        <h3 class="mb-0">{{ $stats['active'] }}</h3>
                        <small>Active Referrals</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body text-center py-3">
                        <i class="fas fa-coins fa-2x mb-2"></i>
                        <h3 class="mb-0">{{ number_format($stats['total_cc']) }}</h3>
                        <small>Total CC Generated</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- 👥 Referral Cards -->
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-friends me-2"></i>My Direct Referrals
                </h5>
                <span class="badge bg-secondary">{{ $referrals->total() }} Found</span>
            </div>
            <div class="card-body">
                @if($referrals->count() > 0)
                    <div class="row g-3">
                        @foreach($referrals as $ref)
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="card referral-card h-100 border-0 shadow-sm" 
                                     onclick="openProfile({{ $ref->id }})" 
                                     style="cursor:pointer;transition:transform .2s">
                                    <div class="card-body text-center py-4">
                                        <!-- Avatar -->
                                        <div class="referral-avatar mx-auto mb-3">
                                            {{ strtoupper(substr($ref->first_name,0,1).substr($ref->last_name,0,1)) }}
                                        </div>
                                        
                                        <!-- Name -->
                                        <h6 class="card-title mb-1 fw-bold">
                                            {{ $ref->first_name }} {{ $ref->last_name }}
                                        </h6>
                                        <small class="text-muted d-block mb-3">{{ $ref->user_name ?? 'N/A' }}</small>
                                        
                                        <!-- Mini Stats -->
                                        <div class="d-flex justify-content-center gap-2 mb-3">
                                            <span class="badge bg-light text-dark">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $ref->created_at->format('d M') }}
                                            </span>
                                            <span class="badge bg-{{ $ref->is_active ? 'success' : 'secondary' }}">
                                                {{ $ref->is_active ? '✓ Active' : '✗ Inactive' }}
                                            </span>
                                        </div>
                                        
                                        <!-- CC Badge -->
                                        <div class="mb-3">
                                            <small class="text-muted">CC Balance</small><br>
                                            <strong class="text-primary">{{ $ref->payoutBalance?->cc_balance ?? 0 }} CC</strong>
                                        </div>
                                        
                                        <!-- View Button -->
                                        <button class="btn btn-sm btn-outline-primary w-100">
                                            <i class="fas fa-eye me-1"></i> View Details
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                       {{ $referrals->links('pagination::bootstrap-5') }}
                    </div>
                    
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No referrals yet</h5>
                        <p class="text-muted mb-3">Share your referral link to start building your team!</p>
                        <button class="btn btn-primary" onclick="copyRefLink()">
                            <i class="fas fa-copy me-1"></i> Copy Referral Link
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- 👤 Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-0 text-center" id="profileBody">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2 text-muted">Loading...</p>
            </div>
        </div>
    </div>
</div>

<!-- ✅ CSS -->
@push('styles')
<style>
.referral-avatar {
    width: 70px; height: 70px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: white; font-weight: 700; font-size: 20px;
}
.referral-card:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,.12)!important; }
</style>
@endpush

<!-- ✅ JS -->
@push('scripts')
<script>
function openProfile(userId) {
    const modal = document.getElementById('profileModal');
    const body = document.getElementById('profileBody');
    const bsModal = bootstrap.Modal.getOrCreateInstance(modal);
    
    body.innerHTML = '<div class="spinner-border text-primary"></div><p class="mt-2 text-muted">Loading...</p>';
    bsModal.show();
    
    fetch(`/referral-genealogy/user/${userId}`)
        .then(res => res.json())
        .then(data => {
            const u = data.user, s = data.stats;
            const initials = (u.first_name?.[0]||'U') + (u.last_name?.[0]||'S');
            
            body.innerHTML = `
                <div class="referral-avatar mx-auto mb-3" style="width:80px;height:80px;font-size:24px">
                    ${initials.toUpperCase()}
                </div>
                <h5 class="mb-1 fw-bold">${u.first_name} ${u.last_name}</h5>
                <small class="text-muted d-block mb-3">@${u.user_name}</small>
                
                <div class="row g-2 text-start px-3">
                    <div class="col-6"><small class="text-muted">Joined</small><br><strong>${s.joined}</strong></div>
                    <div class="col-6"><small class="text-muted">Status</small><br><strong class="text-${s.status==='Active'?'success':'secondary'}">${s.status}</strong></div>
                    <div class="col-6"><small class="text-muted">CC Balance</small><br><strong>${s.cc_balance} CC</strong></div>
                    <div class="col-6"><small class="text-muted">Available</small><br><strong class="text-success">₹${(s.available||0).toLocaleString('en-IN')}</strong></div>
                    <div class="col-12"><small class="text-muted">Sponsor</small><br><strong>${s.sponsor}</strong></div>
                </div>
                
                <button class="btn btn-secondary mt-3" data-bs-dismiss="modal">Close</button>
            `;
        })
        .catch(() => {
            body.innerHTML = '<div class="alert alert-danger">Failed to load profile</div>';
        });
}

function copyRefLink() {
    const link = `${window.location.origin}/register?ref={{ Auth::user()->user_name ?? '' }}`;
    navigator.clipboard.writeText(link).then(() => alert('✅ Referral link copied!'));
}
</script>
@endpush
@endsection