@extends('admin.layout.admin-master')
@section('title', 'KYC Request')

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
                    <h5 class="card-title mb-0"><i class="fas fa-users me-2"></i>KYC Request</h5>
                </div>
                <div class="card-body p- 0">
                    <div class="table- responsive">
                        <table class="table table-hover" id="kycTable">
                            <thead class="bg-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Pan Number</th>
                                    <th>Aadhaar Number</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>
                    </div> 
                </div>
            </div>
        </div>


        <div class="modal fade" id="kycmodal" tabindex="-1" aria-labelledby="kycmodalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="kycmodalLabel">Payout Details</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="update-kyc-request" id="updateKycRequestForm" method="POST"> 
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="userName" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="userName" value="" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" value="" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="panNumber" class="form-label">Pan Number</label>
                                    <input type="text" class="form-control" id="panNumber" value="" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="aadhaarNumber" class="form-label">Aadhaar Number</label>
                                    <input type="text" class="form-control" id="aadhaarNumber" value="" readonly>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="remarks" class="form-label">Pan Card Image</label>
                                    <img src="" class="w-100 img-thumbnail" alt="" id="pan_image" style="height:360px;">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="remarks" class="form-label">Aadhaar Front Image</label>
                                    <img src="" class="w-100 img-thumbnail" alt="" id="aadhaar_front_image" style="height:360px;">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="remarks" class="form-label">Aadhaar Back Image</label>
                                    <img src="" class="w-100 img-thumbnail" alt="" id="aadhaar_back_image" style="height:360px;">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="remarks" class="form-label">Bank Document</label>
                                    <img src="" class="w-100 img-thumbnail" alt="" id="bank_document" style="height:360px;">
                                </div> 
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Status</label>

                                    <div class="form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="status_pending" value="pending" checked>
                                        <label class="form-check-label" for="status_pending">
                                            Pending
                                        </label>
                                    </div>

                                    <div class="form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="status_approved" value="approved">
                                        <label class="form-check-label" for="status_approved">
                                            Approved
                                        </label>
                                    </div>

                                    <div class="form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="status_rejected" value="rejected">
                                        <label class="form-check-label" for="status_rejected">
                                            Rejected
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="remarks" class="form-label">Remarks</label>
                                    <textarea class="form-control mt-2" id="reject_reason" name="reject_reason" rows="3" placeholder="Enter remarks here..."></textarea>
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
                showKyc: "{{ route('kyc-documents.show', ':id') }}",
                updateKyc: "{{ route('kyc-documents.update', ':id') }}"
            };

            let table = $('#kycTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('kyc-documents.index') }}",
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
                        data: 'pan_number',
                        name: 'pan_number'
                    },
                    {
                        data: 'aadhaar_number',
                        name: 'aadhaar_number'
                    }, 
                    {
                        data: 'status',
                        name: 'status'
                    },                 
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },                 
                    {
                        data: 'actions',
                        name: 'actions'
                    },                 
                    
                ]
            });



            $('#kycTable').on('click', '.view-kyc-btn', function () {
                var requestId = $(this).data('id');
                        
                $.ajax({
                    url: window.routes.showKyc.replace(':id', requestId),
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
                        $('#name').val(response.user.first_name + ' ' + response.user.last_name);
                        $('#panNumber').val(response.pan_number);
                        $('#reject_reason').val(response.reject_reason);
                        $('#aadhaarNumber').val(response.aadhaar_number);
                        $('input[name="status"][value="' + response.status + '"]').prop('checked', true);
                        $('#pan_image').attr('src', response.pan_image ?? 'https://placehold.net/400x400.png');
                        $('#aadhaar_front_image').attr('src', response.aadhaar_front_image ?? 'https://placehold.net/400x400.png');
                        $('#aadhaar_back_image').attr('src', response.aadhaar_back_image ?? 'https://placehold.net/400x400.png');
                        $('#bank_document').attr('src', response.bank_document_image ?? 'https://placehold.net/400x400.png');

                        $('.update-kyc-request').attr('data-id', requestId);
                        $('#kycmodal').modal('show');
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            $('#updateKycRequestForm').submit(function (e) {
                e.preventDefault();
                var requestId = $('.update-kyc-request').data('id');
                var status = $('input[name="status"]:checked').val();
                var reject_reason = $('#reject_reason').val();

                $.ajax({
                    url: window.routes.updateKyc.replace(':id', requestId),
                    method: 'PUT',
                    data: {
                        _token: window.csrfToken,
                        status: status,
                        reject_reason: reject_reason
                    },
                    beforeSend: function(xhr) {
                        swal.fire({
                            title: 'Updating...',
                            text: 'Updating KYC request status.',
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
                            $('#kycmodal').modal('hide');
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