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
                    <h5 class="card-title mb-0"><i class="fas fa-users me-2"></i>Payout Summary</h5>
                </div>
                <div class="card-body p- 0">
                    <div class="table- responsive">
                        <table class="table table-hover" id="summaryTable">
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
                               
                            </tbody>
                        </table>
                    </div> 
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#summaryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('mlm-users.payout-summary') }}",
                columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'name',
                    name: 'mlm_users.first_name'
                },
                {
                    data: 'username',
                    name: 'mlm_users.user_name'
                },
                {
                    data: 'transaction_date',
                    name: 'fund_summaries.transaction_date'
                },
                {
                    data: 'type',
                    name: 'fund_summaries.type'
                },
                {
                    data: 'particular',
                    name: 'fund_summaries.particular'
                },
                {
                    data: 'credit',
                    name: 'fund_summaries.credit'
                },
                {
                    data: 'debit',
                    name: 'fund_summaries.debit'
                },
                {
                    data: 'remark',
                    name: 'fund_summaries.remark'
                }
            ]
            });
        });
    </script>
@endpush