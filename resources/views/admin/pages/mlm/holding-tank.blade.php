{{-- resources/views/admin/pages/mlm/holding-tank.blade.php --}}
@extends('admin.layout.admin-master')

@section('title', 'Holding Tank | MLM')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Holding Tank</li>
            </ol>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Holding Tank List</h5>
                <div class="input-group" style="width: 250px;">
                    <input type="text" class="form-control" placeholder="Search...">
                    <button class="btn btn-outline-secondary"><i class="fas fa-filter"></i> FILTER</button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Full Name</th>
                                <th>Username</th>
                                <th>Sponsor</th>
                                <th>Status</th>
                                <th>Registered At</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($holdingUsers as $item)
                            <tr>
                                <td>
                                    {{ $item->mlmUser->first_name ?? '' }} {{ $item->mlmUser->last_name ?? '' }}
                                </td>
                                <td>
                                    <code>{{ $item->mlmUser->user_name ?? '' }}</code>
                                </td>
                                <td>
                                    {{ $item->mlmUser->sponsor->user_name ?? 'ROOT' }}
                                </td>
                                <td>
                                    <span class="badge bg-warning text-dark">PENDING</span>
                                </td>
                                <td>
                                    {{ $item->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#placementModal{{ $item->mlmUser->id }}">
                                        <i class="fas fa-sitemap"></i> Place
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center py-4 text-muted">No users in holding tank.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $holdingUsers->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Placement Modals --}}
@foreach($holdingUsers as $item)
<div class="modal fade" id="placementModal{{ $item->mlmUser->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('holding-tank.place') }}" method="POST">
            @csrf
            <input type="hidden" name="user_id" value="{{ $item->mlmUser->id }}">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-white border-0 pb-0">
                    <h5 class="modal-title">Placement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-2">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Placement Parent</label>
                        <select name="parent_id" class="select" required>
                            <option value="">Select Parent</option>
                            @foreach($parents as $p)
                                @if($p->id != $item->mlmUser->id)
                                <option value="{{ $p->id }}">
                                    {{ $p->user_name }} ({{ $p->first_name }} {{ $p->last_name }})
                                </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Position</label>
                        <select name="position" class="select" required>
                            <option value="left">LEFT</option>
                            <option value="right">RIGHT</option>
                        </select>
                    </div>
                    
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="fw-bold">Place Now</span>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" name="place_now" checked>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-link text-secondary" data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" class="btn btn-primary px-4">PLACE</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach
@endsection