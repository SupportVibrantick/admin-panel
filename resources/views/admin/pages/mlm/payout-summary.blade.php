@extends('admin.layout.admin-master')
@section('title', 'Payout Summary')

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Payout Summary</li>
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
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Particulars</th>
                                    <th>Credit</th>
                                    <th>Debit</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($summary as $item)
                                    <tr>
                                        <td>{{ $loop->iteration  }}</td>
                                        <td>{{ $item->user?->first_name }} {{ $item->user?->last_name }}</td>
                                        <td>{{ $item->user?->user_name }}</td>
                                        <td>{{ $item->transaction_date->format('d M Y, h:i A') }}</td>
                                        <td>{{ $item->type }}</td>
                                        <td>{{ $item->particular }}</td>
                                        <td>₹{{ number_format($item->credit, 2) }}</td>
                                        <td>₹{{ number_format($item->debit, 2) }}</td>
                                        <td>{{ $item->remark }}</td>                                         
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            No payout summaries found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">
                        {{ $summary->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection