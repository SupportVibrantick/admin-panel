@extends('admin.layout.admin-master')
@section('title', 'Manage Referral Downline')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Manage Referral Downline</li>
            </ol>
        </div>

        <!-- 📊 Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-0">{{ $stats['total'] }}</h3>
                                <small>Total Downline</small>
                            </div>
                            <i class="fas fa-users fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-0">{{ $stats['active'] }}</h3>
                                <small>Active</small>
                            </div>
                            <i class="fas fa-check-circle fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-0">{{ $stats['inactive'] }}</h3>
                                <small>Inactive</small>
                            </div>
                            <i class="fas fa-times-circle fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-0">₹{{ number_format($stats['total_earned'], 0) }}</h3>
                                <small>Total Earned</small>
                            </div>
                            <i class="fas fa-coins fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 📋 Downline Table -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Referral Downline</h5>
            </div>
            <div class="card-body">
                <!-- Search & Filter -->
                <form method="GET" action="{{ route('referral-downline.index') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search by username, name, email..." 
                                       value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
                                <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th>User ID</th>
                                <th>Sponsor</th>
                                <th>Sale</th>
                                <th>Earned</th>
                                <th>Rank</th>
                                <th>Registered on</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($downlines as $downline)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-2" 
                                                 style="width:35px;height:35px;background:linear-gradient(135deg,#667eea,#764ba2);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:600;font-size:12px;">
                                                {{ strtoupper(substr($downline->first_name,0,1).substr($downline->last_name,0,1)) }}
                                            </div>
                                            <div>
                                                <strong>{{ $downline->user_name }}</strong><br>
                                                <small class="text-muted">{{ $downline->first_name }} {{ $downline->last_name }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($downline->sponsor)
                                            <span class="badge bg-info">{{ $downline->sponsor->user_name }}</span>
                                        @else
                                            <span class="badge bg-secondary">Direct</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>0</strong> {{-- Update with actual sales count --}}
                                    </td>
                                    <td>
                                        <strong class="text-success">₹{{ number_format($downline->payoutBalance?->total_earned ?? 0, 0) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">1</span> {{-- Update with actual rank --}}
                                    </td>
                                    <td>
                                        {{ $downline->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light" type="button" 
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="#" 
                                                       onclick="viewTree({{ $downline->id }}, '{{ $downline->user_name }}', 'genealogy'); return false;">
                                                        <i class="fas fa-sitemap me-2"></i> Genealogy Tree
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" 
                                                       onclick="viewTree({{ $downline->id }}, '{{ $downline->user_name }}', 'referral'); return false;">
                                                        <i class="fas fa-project-diagram me-2"></i> Referral Tree
                                                    </a>
                                                </li>
                                    
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('referral-genealogy.index') }}?user={{ $downline->id }}">
                                                        <i class="fas fa-user me-2"></i> View Profile
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        No referrals found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $downlines->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 🌳 Tree View Modal -->
<div class="modal fade" id="treeModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="treeModalTitle">Tree View</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="treeModalBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">Loading tree...</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function viewTree(userId, userName, type) {
    const modal = document.getElementById('treeModal');
    const body = document.getElementById('treeModalBody');
    const title = document.getElementById('treeModalTitle');
    const bsModal = bootstrap.Modal.getOrCreateInstance(modal);
    
    title.textContent = `${userName} - ${type === 'genealogy' ? 'Genealogy Tree' : 'Referral Tree'}`;
    body.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2 text-muted">Loading tree...</p></div>';
    bsModal.show();
    
    const url = type === 'genealogy' 
        ? `/referral-downline/${userId}/genealogy`
        : `/referral-downline/${userId}/referral-tree`;
    
    fetch(url)
        .then(res => res.text())
        .then(html => {
            body.innerHTML = html;
        })
        .catch(() => {
            body.innerHTML = '<div class="alert alert-danger">Failed to load tree</div>';
        });
}
</script>
@endpush
@endsection