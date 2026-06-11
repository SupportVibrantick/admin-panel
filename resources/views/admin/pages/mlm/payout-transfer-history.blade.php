@extends('admin.layout.admin-master')
@section('title', 'Payout Transfer History')

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Payout Transfer History</li>
                </ol>
            </div>  

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
                                    <th>Sender</th>
                                    <th>Receiver</th>
                                    <th>Sender Username</th>
                                    <th>Receiver Username</th>
                                    <th>Amount</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transfers as $item)
                                    <tr>
                                        <td>{{ $loop->iteration  }}</td>
                                        <td>{{ $item->sender?->first_name }} {{ $item->sender?->last_name }}</td>
                                        <td>{{ $item->receiver?->first_name }} {{ $item->receiver?->last_name }}</td>
                                        <td>{{ $item->sender?->user_name }}</td>
                                        <td>{{ $item->receiver?->user_name }}</td>
                                        <td>₹{{ number_format($item->amount, 2) }}</td>
                                        <td>{{ $item->remark }}</td>              
                                        <td>{{ ucfirst($item->status) }}</td>              
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            No payout transfers found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">
                        {{ $transfers->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection