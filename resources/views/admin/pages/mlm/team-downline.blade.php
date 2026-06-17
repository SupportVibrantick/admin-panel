@extends('admin.layout.admin-master')
@section('title', 'Team Downline')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Team Downline</li>
            </ol>
        </div>

        <!-- 📊 Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-2">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center py-3">
                        <i class="fas fa-users fa-2x mb-2"></i>
                        <h3 class="mb-0">{{ $stats['total'] }}</h3>
                        <small>Total Team</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-info text-white">
                    <div class="card-body text-center py-3">
                        <i class="fas fa-layer-group fa-2x mb-2"></i>
                        <h3 class="mb-0">{{ $stats['level_1'] }}</h3>
                        <small>Level 1</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-info text-white">
                    <div class="card-body text-center py-3">
                        <i class="fas fa-layer-group fa-2x mb-2"></i>
                        <h3 class="mb-0">{{ $stats['level_2'] }}</h3>
                        <small>Level 2</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center py-3">
                        <i class="fas fa-arrow-left fa-2x mb-2"></i>
                        <h3 class="mb-0">{{ $stats['left_leg'] }}</h3>
                        <small>Left Leg</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center py-3">
                        <i class="fas fa-arrow-right fa-2x mb-2"></i>
                        <h3 class="mb-0">{{ $stats['right_leg'] }}</h3>
                        <small>Right Leg</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- 📋 Team Downline Table -->
        <div class="card">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <h5 class="card-title mb-0">Team Downline</h5>
                    <button class="btn btn-sm btn-primary" onclick="exportTable()">
                        <i class="fas fa-download me-1"></i> Export
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Search & Filter -->
                <form method="GET" action="{{ route('team-downline.index') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search by username or name..." 
                                       value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select name="level" class="form-select">
                                <option value="">All Levels</option>
                                @for($i = 0; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ request('level')==$i?'selected':'' }}>Level {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="position" class="form-select">
                                <option value="">All Positions</option>
                                <option value="left" {{ request('position')=='left'?'selected':'' }}>LEFT</option>
                                <option value="right" {{ request('position')=='right'?'selected':'' }}>RIGHT</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
                                <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover" id="teamTable">
                        <thead class="bg-light">
                            <tr>
                                <th>User ID</th>
                                <th>Parent</th>
                                <th>Level</th>
                                <th>Sale</th>
                                <th>Position</th>
                                <th>Registered on</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teamMembers as $member)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-2" 
                                                 style="width:35px;height:35px;background:linear-gradient(135deg,#667eea,#764ba2);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:600;font-size:12px;">
                                                {{ strtoupper(substr($member->mlmUser->first_name,0,1).substr($member->mlmUser->last_name,0,1)) }}
                                            </div>
                                            <div>
                                                <strong>{{ $member->mlmUser->user_name }}</strong><br>
                                                <small class="text-muted">{{ $member->mlmUser->first_name }} {{ $member->mlmUser->last_name }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($member->mlmUser->sponsor)
                                            <span class="badge bg-info">{{ $member->mlmUser->sponsor->user_name }}</span>
                                        @else
                                            <span class="text-muted">no parent</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $member->level }}</span>
                                    </td>
                                    <td>
                                        <strong>0</strong> {{-- Update with actual sales --}}
                                    </td>
                                    <td>
                                        @if($member->position === 'left')
                                            <span class="badge bg-success">LEFT</span>
                                        @elseif($member->position === 'right')
                                            <span class="badge bg-warning text-dark">RIGHT</span>
                                        @else
                                            <span class="badge bg-secondary">{{ strtoupper($member->position) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $member->mlmUser->created_at->format('d M Y H:i') }}
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
                                                       onclick="viewTree({{ $member->mlm_user_id }}, '{{ $member->mlmUser->user_name }}', 'genealogy'); return false;">
                                                        <i class="fas fa-sitemap me-2"></i> Genealogy Tree
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" 
                                                       onclick="viewTree({{ $member->mlm_user_id }}, '{{ $member->mlmUser->user_name }}', 'referral'); return false;">
                                                        <i class="fas fa-project-diagram me-2"></i> Referral Tree
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('referral-genealogy.profile', ['userId' => $member->mlm_user_id]) }}">
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
                                        No team members found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $teamMembers->links('pagination::bootstrap-5') }}
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
        ? `/team-downline/${userId}/genealogy`
        : `/team-downline/${userId}/referral-tree`;
    
    fetch(url)
        .then(res => res.text())
        .then(html => {
            body.innerHTML = html;
        })
        .catch(() => {
            body.innerHTML = '<div class="alert alert-danger">Failed to load tree</div>';
        });
}

function exportTable() {
    // Simple CSV export
    const table = document.getElementById('teamTable');
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    for (let row of rows) {
        const cols = row.querySelectorAll('td, th');
        let csvRow = [];
        for (let col of cols) {
            csvRow.push('"' + col.textContent.trim() + '"');
        }
        csv.push(csvRow.join(','));
    }
    
    const blob = new Blob([csv.join('\n')], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'team-downline.csv';
    a.click();
}
</script>
@endpush
@endsection