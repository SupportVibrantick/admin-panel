@extends('admin.layout.admin-master')
@section('title', 'Payout Requests')

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Payout Dashboard</li>
                </ol>
            </div>      
            
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

            
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="fas fa-users me-2"></i>Payout Requests</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Requested Amount</th>
                                    <th>Pyment Mode</th>
                                    <th>Request Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($payoutRequests as $request)
                                    <tr>
                                        <td>{{ $loop->iteration  }}</td>
                                        <td>{{ $request->user?->first_name }} {{ $request->user?->last_name }}</td>
                                        <td>{{ $request->user?->user_name }}</td>
                                        <td>₹{{ number_format($request->amount, 2) }}</td>
                                        <td>{{ ucfirst($request->mode_of_payment) }}</td>
                                        <td>{{ $request->created_at->format('d M Y, h:i A') }}</td>
                                        <td>
                                            @if ($request->status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif ($request->status === 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif ($request->status === 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($request->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary view-details-btn" data-bs-toggle="modal" data-bs-target="#payoutDetailsModal-{{ $request->id }}">
                                                <i class="fas fa-eye"></i> View Details
                                            </button>

                                            <div class="modal fade" id="payoutDetailsModal-{{ $request->id }}" tabindex="-1" aria-labelledby="payoutDetailsModal-{{ $request->id }}Label" aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="payoutDetailsModal-{{ $request->id }}Label">Payout Details</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form class="update-payout-request" data-="{{ $request->id }}" action="{{ route('mlm-users.update-payout-request', $request->id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')

                                                                <div class="row">
                                                                    <div class="col-md-6 mb-3">
                                                                        <label for="userName" class="form-label">Username</label>
                                                                        <input type="text" class="form-control" id="userName" value="{{ $request->user?->user_name }}" readonly>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label for="fullName" class="form-label">Full Name</label>
                                                                        <input type="text" class="form-control" id="fullName" value="{{ $request->user?->first_name }} {{ $request->user?->last_name }}" readonly>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label for="email" class="form-label">Email</label>
                                                                        <input type="email" class="form-control" id="email" value="{{ $request->user?->email }}" readonly>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label for="amount" class="form-label">Requested Amount</label>
                                                                        <input type="text" class="form-control" id="amount" value="₹{{ number_format($request->amount, 2) }}" readonly>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label for="paymentMode" class="form-label">Payment Mode</label>
                                                                        <input type="text" class="form-control" id="paymentMode" value="{{ ucfirst($request->mode_of_payment) }}" readonly>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label for="status" class="form-label">Status</label>

                                                                        <div class="mt-3">
                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input" type="radio" name="status" id="pending" value="pending" checked>
                                                                                <label class="form-check-label" for="pending">Pending</label>
                                                                            </div>

                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input" type="radio" name="status" id="approved" value="approved">
                                                                                <label class="form-check-label" for="approved">Approved</label>
                                                                            </div>

                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input" type="radio" name="status" id="rejected" value="rejected">
                                                                                <label class="form-check-label" for="rejected">Rejected</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                         
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            No payout requests found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">
                        {{ $payoutRequests->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
 