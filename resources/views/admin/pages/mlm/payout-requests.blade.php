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
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="payoutRequestsTable">
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
                                
                            </tbody>
                        </table>
                    </div>
                     
                </div>
            </div>
        </div>

        <div class="modal fade" id="payoutDetailsModal" tabindex="-1" aria-labelledby="payoutDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="payoutDetailsModalLabel">Payout Details</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="update-payout-request" id="updatePayoutRequestForm" method="POST"> 
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="userName" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="userName" value="" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="fullName" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="fullName" value="" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" value="" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="amount" class="form-label">Requested Amount</label>
                                    <input type="text" class="form-control" id="amount" value="" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="paymentMode" class="form-label">Payment Mode</label>
                                    <input type="text" class="form-control" id="paymentMode" value="" readonly>
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
                                <div class="col-md-12 mb-3">
                                    <label for="remarks" class="form-label">Remarks</label>
                                    <textarea class="form-control mt-2" id="remarks" name="remarks" rows="3" placeholder="Enter remarks here..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            window.csrfToken = '{{ csrf_token() }}';
            window.routes = {
                showPayoutRequest: "{{ route('mlm-users.show-payout-request', ':id') }}",
                updatePayoutRequest: "{{ route('mlm-users.update-payout-request', ':id') }}"
            };

            let table = $('#payoutRequestsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('mlm-users.payout-request') }}",
                
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'username',
                        name: 'username'
                    },
                    {
                        data: 'requested_amount',
                        name: 'amount'
                    },
                    {
                        data: 'payment_mode',
                        name: 'mode_of_payment'
                    },
                    {
                        data: 'request_date',
                        name: 'created_at'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ]
            });


            $('#payoutRequestsTable').on('click', '.view-details-btn', function () {
                var requestId = $(this).data('id');
                        
                $.ajax({
                    url: window.routes.showPayoutRequest.replace(':id', requestId),
                    method: 'GET',
                    beforeSend: function(xhr) {
                        swal.fire({
                            title: 'Loading...',
                            text: 'Fetching payout request details.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function (response) {
                        Swal.close();
                        $('#userName').val(response.user.user_name);
                        $('#fullName').val(response.user.first_name + ' ' + response.user.last_name);
                        $('#email').val(response.user.email);
                        $('#amount').val(response.amount);
                        $('#paymentMode').val(response.mode_of_payment);
                        $('input[name="status"][value="' + response.status + '"]').prop('checked', true);
                        $('#remarks').val(response.remark);
                        $('.update-payout-request').attr('data-id', requestId);
                        $('#payoutDetailsModal').modal('show');
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            $('#updatePayoutRequestForm').submit(function (e) {
                e.preventDefault();
                var requestId = $('.update-payout-request').data('id');
                var status = $('input[name="status"]:checked').val();
                var remarks = $('#remarks').val();

                $.ajax({
                    url: window.routes.updatePayoutRequest.replace(':id', requestId),
                    method: 'PUT',
                    data: {
                        _token: window.csrfToken,
                        status: status,
                        remarks: remarks
                    },
                    beforeSend: function(xhr) {
                        swal.fire({
                            title: 'Updating...',
                            text: 'Updating payout request status.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function (response) {
                        Swal.close();
                        if (response.success) {
                            swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                timer: 2000,
                                timerProgressBar: true,
                                
                            });
                            $('#payoutDetailsModal').modal('hide');
                            table.ajax.reload(null, false);
                        }
                        
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>    
@endpush
 
 