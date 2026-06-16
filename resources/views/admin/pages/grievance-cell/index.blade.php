@extends('admin.layout.admin-master')
@section('title', 'Grievance Cell')

@section('content')
<div class="content-body">
    <div class="container-fluid">

        {{-- ── Breadcrumb ──────────────────────────────────────────── --}}
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Grievance Cell</li>
            </ol>
        </div>

        {{-- ── Ticket list card ─────────────────────────────────────── --}}
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-ticket-alt me-2"></i>Ticket List
                </h5>
            </div>
            <div class="card-body ">
                <div class="table-responsive">
                    <table class="table table-hover" id="kycTable">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Ticket No</th>
                                <th style="max-width: 150px;">Subject</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

    </div><!-- /container-fluid -->
</div><!-- /content-body -->

{{-- ── Offcanvas – ticket detail + chat ─────────────────────── --}}
<div class="offcanvas offcanvas-end"
     data-bs-scroll="true"
     tabindex="-1"
     id="grievanceCanvas"
     aria-labelledby="grievanceCanvasLabel"
     style="width: 700px; display: flex; flex-direction: column;">

    <div class="offcanvas-header border-bottom flex-shrink-0">
        <h5 class="offcanvas-title" id="grievanceCanvasLabel">
            <i class="fas fa-ticket-alt me-2 text-primary"></i>
            <span id="canvasTicketTitle">Ticket Detail</span>
        </h5>
        <button type="button" class="btn-close"
                data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    {{-- Wrapper fills all remaining height and uses flex-column --}}
    <div id="offcanvasBody"
         style="flex: 1 1 0; overflow: hidden; display: flex; flex-direction: column; padding: 0;">

        {{-- Initial loader shown before a ticket is opened --}}
        <div class="text-center py-5 text-muted" id="canvasInitialLoader">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 mb-0">Loading…</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
window.csrfToken = '{{ csrf_token() }}';

window.routes = {
    messages:     '{{ route('grievance.messages',  ':id') }}',
    reply:        '{{ route('grievance.reply') }}',
    changeStatus: '{{ route('grievance.status',    ':id') }}',
    grievanceIndex: '{{ route('grievance.index') }}',
};

// ── Helpers ──────────────────────────────────────────────────
function showBodyLoader() {
    document.getElementById('offcanvasBody').innerHTML = `
        <div class="text-center py-5 text-muted" style="flex:1;">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 mb-0">Loading messages…</p>
        </div>`;
}

function scrollChatToBottom() {
    // Scroll the canvas-scroll container (not chatThread) to bottom
    const scroller = document.querySelector('#offcanvasBody .canvas-scroll');
    if (scroller) scroller.scrollTop = scroller.scrollHeight;
}

// ── Open ticket offcanvas ────────────────────────────────────
function openMessage(id, ticketNo) {
    document.getElementById('canvasTicketTitle').textContent = 'Ticket #' + ticketNo;

    const offcanvas = new bootstrap.Offcanvas(
        document.getElementById('grievanceCanvas')
    );
    offcanvas.show();

    loadMessages(id);
}

function loadMessages(id) {
    const body = document.getElementById('offcanvasBody');
    showBodyLoader();

    $.ajax({
        url: window.routes.messages.replace(':id', id),
        method: 'GET',
        success: function (response) {
            body.innerHTML = response.html;
            scrollChatToBottom();
            bindReplyForm();
            bindStatusButtons();
        },
        error: function () {
            body.innerHTML = `
                <div class="alert alert-danger m-3">
                    Failed to load ticket. Please try again.
                </div>`;
        }
    });
}

// ── Reply form ───────────────────────────────────────────────
function bindReplyForm() {
    const form = document.getElementById('adminReplyForm');
    if (!form) return;

    // Show selected filename
    const fileInput = form.querySelector('input[type="file"]');
    if (fileInput) {
        fileInput.addEventListener('change', function () {
            document.getElementById('attachmentName').textContent =
                this.files[0] ? this.files[0].name : '';
        });
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const btn      = document.getElementById('replyBtn');
        const ticketId = form.querySelector('[name="ticket_id"]').value;
        const message  = form.querySelector('[name="message"]').value.trim();

        if (!message) return;

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

        const data = new FormData(form);
        data.append('_token', window.csrfToken);

        $.ajax({
            url: window.routes.reply,
            method: 'POST',
            data: data,
            processData: false,
            contentType: false,
            success: function () {
                // Reload the messages pane
                loadMessages(ticketId);
            },
            error: function (xhr) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Send Reply';
                const msg = xhr.responseJSON?.message ?? 'Failed to send reply.';
                Swal.fire({ icon: 'error', title: 'Error', text: msg, timer: 3000 });
            }
        });
    });
}

// ── Status change buttons ────────────────────────────────────
function bindStatusButtons() {
    document.querySelectorAll('.change-status-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const ticketId = this.dataset.ticket;
            const newStatus = this.dataset.status;

            $.ajax({
                url: window.routes.changeStatus.replace(':id', ticketId),
                method: 'POST',
                data: {
                    _token: window.csrfToken,
                    _method: 'POST',
                    status: newStatus,
                },
                success: function (response) {
                    if (response.success) {
                        // Reload messages pane to reflect new status
                        loadMessages(ticketId);
                        // Refresh the DataTable row
                        $('#kycTable').DataTable().ajax.reload(null, false);
                    }
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message ?? 'Failed to update status.',
                        timer: 3000
                    });
                }
            });
        });
    });
}

// ── DataTable ────────────────────────────────────────────────
$(document).ready(function () {

    $('#kycTable').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        ajax: window.routes.grievanceIndex,
        columns: [
            { data: 'DT_RowIndex',  name: 'DT_RowIndex', searchable: false, orderable: false },
            { data: 'name',         name: 'mlm_users.first_name' },
            { data: 'username',     name: 'mlm_users.user_name' },
            { data: 'ticket_no',    name: 'ticket_no' },
            { data: 'subject',      name: 'subject' },
            { data: 'category',     name: 'category' },
            { data: 'status',       name: 'status' },
            { data: 'created_at',   name: 'created_at' },
            { data: 'actions',      name: 'actions', orderable: false, searchable: false },
        ]
    });

    // View ticket button
    $('#kycTable').on('click', '.view-ticket-button', function () {
        openMessage($(this).data('id'), $(this).data('ticket'));
    });
});
</script>
@endpush
