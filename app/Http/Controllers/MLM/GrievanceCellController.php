<?php

namespace App\Http\Controllers\MLM;

use App\Http\Controllers\Controller;
use App\Models\GrievanceAttachment;
use App\Models\GrievanceMassage;
use App\Models\Grivance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class GrievanceCellController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $grievance = Grivance::select(
                'grivances.*',
                'mlm_users.first_name',
                'mlm_users.last_name',
                'mlm_users.user_name'
            )
            ->leftJoin('mlm_users', 'grivances.user_id', '=', 'mlm_users.id');

            return DataTables::of($grievance)
                ->addIndexColumn()

                ->addColumn('name', fn($row) => $row->first_name . ' ' . $row->last_name)

                ->addColumn('username', fn($row) => $row->user_name)
                ->addColumn('subject', function ($row) {
                    return Str::limit($row->subject, 50);
                })

                ->addColumn('status', function ($row) {
                    $map = [
                        'open'        => ['label' => 'Open',        'class' => 'bg-success'],
                        'in_progress' => ['label' => 'In Progress',  'class' => 'bg-warning text-dark'],
                        'closed'      => ['label' => 'Closed',       'class' => 'bg-danger'],
                    ];
                    $item = $map[$row->status] ?? ['label' => ucfirst($row->status), 'class' => 'bg-secondary'];
                    return '<span class="badge ' . $item['class'] . '">' . $item['label'] . '</span>';
                })

                ->filterColumn('status', fn($query, $keyword) =>
                    $query->where('status', 'like', "%{$keyword}%")
                )

                ->addColumn('created_at', fn($row) => $row->created_at->format('d-m-Y'))

                ->addColumn('actions', fn($row) =>
                    '<button class="btn btn-sm btn-primary view-ticket-button"
                        data-id="' . $row->id . '"
                        data-ticket="' . $row->ticket_no . '">
                        <i class="fas fa-eye"></i> View
                    </button>'
                )

                ->rawColumns(['status', 'actions'])
                ->make(true);
        }

        return view('admin.pages.grievance-cell.index');
    }

    
    public function messages($id)
    {
        $ticket = Grivance::with('user')->findOrFail($id);

        $messages = GrievanceMassage::with(['sender', 'attachments'])
            ->where('grivance_id', $id)
            ->oldest()
            ->get();

        $html = view(
            'admin.pages.grievance-cell.messages',
            compact('messages', 'ticket')
        )->render();

        return response()->json([
            'success' => true,
            'html'    => $html,
        ]);
    }

    
    public function reply(Request $request)
    {
        $request->validate([
            'ticket_id'  => 'required|exists:grivances,id',
            'message'    => 'required|string|max:5000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $ticket = Grivance::findOrFail($request->ticket_id);

        if ($ticket->status === 'closed') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot reply to a closed ticket.',
            ], 422);
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')
                ->store('grievances', 'public');
        }

        // Admin sender_id: use Auth user id OR a special sentinel.
        // We store 0 to indicate "admin" since admins are in the users table, not mlm_users.
        // Alternatively, pass sender_id explicitly. We'll use a nullable sender with admin flag.
        $msg = GrievanceMassage::create([
            'grivance_id'  => $ticket->id,
            'sender_id'    => null,           // null = admin reply
            'message'      => $request->message,
            'attachment'   => $attachmentPath,
        ]);

        if ($attachmentPath) {
            GrievanceAttachment::create([
                'message_id' => $msg->id,
                'file_path'  => $attachmentPath,
            ]);
        }

        // Auto-move to in_progress on first admin reply
        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Reply sent.',
        ]);
    }

    /**
     * Change ticket status from admin panel.
     */
    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,closed',
        ]);

        $ticket = Grivance::findOrFail($id);
        $ticket->status = $request->status;

        if ($request->status === 'closed') {
            $ticket->closed_at = now();
        } else {
            $ticket->closed_at = null;
        }

        $ticket->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated to ' . $request->status . '.',
            'status'  => $ticket->status,
        ]);
    }
}
