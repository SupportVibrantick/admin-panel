<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GrievanceAttachment;
use App\Models\GrievanceMassage;
use App\Models\Grivance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GrievanceController extends Controller
{ 
    public function raiseTicket(Request $request)
    {
        $validated = $request->validate([
            'user_id'    => 'required|exists:mlm_users,id',
            'subject'    => 'required|string|max:255',
            'category'   => 'required|in:wallet,withdrawal,commission,kyc,account,other',
            'priority'   => 'nullable|in:low,medium,high',
            'message'    => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        DB::beginTransaction();

        try {
            $ticket = Grivance::create([
                'user_id'   => $request->user_id,
                'ticket_no' => Grivance::generateTicketNo(),
                'subject'   => $validated['subject'],
                'category'  => $validated['category'],
                'priority'  => $validated['priority'] ?? 'medium',
                'status'    => 'open',
            ]);

            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')
                    ->store('grievances', 'public');
            }

            $grievanceMessage = GrievanceMassage::create([
                'grivance_id' => $ticket->id,
                'sender_id'   => $request->user_id,
                'message'     => $validated['message'],
                'attachment'  => $attachmentPath,
            ]);

            if ($attachmentPath) {
                GrievanceAttachment::create([
                    'message_id' => $grievanceMessage->id,
                    'file_path'  => $attachmentPath,
                ]);
            }

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Ticket raised successfully.',
                'data'    => [
                    'ticket_id' => $ticket->id,
                    'ticket_no' => $ticket->ticket_no,
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Failed to raise ticket. ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/ticket-messages/{id}
     * Fetch all messages for a ticket (used by mobile app).
     */
    public function getMessages(Request $request, $id)
    {
        $ticket = Grivance::with('user')->find($id);

        if (! $ticket) {
            return response()->json(['status' => false, 'message' => 'Ticket not found.'], 404);
        }

        $messages = GrievanceMassage::with(['sender', 'attachments'])
            ->where('grivance_id', $id)
            ->oldest()
            ->get()
            ->map(function ($msg) use ($ticket) {
                return [
                    'id'          => $msg->id,
                    'message'     => $msg->message,
                    'sender_id'   => $msg->sender_id,
                    'sender_name' => $msg->sender
                        ? trim($msg->sender->first_name . ' ' . $msg->sender->last_name)
                        : 'Unknown',
                    'is_user'     => $msg->sender_id === $ticket->user_id,
                    'attachments' => $msg->attachments->map(fn($a) => [
                        'url' => asset('storage/' . $a->file_path),
                    ]),
                    'created_at'  => $msg->created_at->format('d M Y h:i A'),
                ];
            });

        return response()->json([
            'status'  => true,
            'ticket'  => [
                'id'        => $ticket->id,
                'ticket_no' => $ticket->ticket_no,
                'subject'   => $ticket->subject,
                'category'  => $ticket->category,
                'priority'  => $ticket->priority,
                'status'    => $ticket->status,
            ],
            'messages' => $messages,
        ]);
    }

    /**
     * POST /api/reply-ticket
     * Reply to an existing ticket (user or admin).
     */
    public function replyTicket(Request $request)
    {
        $validated = $request->validate([
            'ticket_id'  => 'required|exists:grivances,id',
            'sender_id'  => 'required|exists:mlm_users,id',
            'message'    => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $ticket = Grivance::findOrFail($validated['ticket_id']);

        if ($ticket->status === 'closed') {
            return response()->json([
                'status'  => false,
                'message' => 'Cannot reply to a closed ticket.',
            ], 422);
        }

        DB::beginTransaction();

        try {
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')
                    ->store('grievances', 'public');
            }

            $msg = GrievanceMassage::create([
                'grivance_id' => $ticket->id,
                'sender_id'   => $validated['sender_id'],
                'message'     => $validated['message'],
                'attachment'  => $attachmentPath,
            ]);

            if ($attachmentPath) {
                GrievanceAttachment::create([
                    'message_id' => $msg->id,
                    'file_path'  => $attachmentPath,
                ]);
            }

            // Auto-move status to in_progress when admin first replies
            if ($ticket->status === 'open' && $ticket->user_id !== $validated['sender_id']) {
                $ticket->update(['status' => 'in_progress']);
            }

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Reply sent successfully.',
                'data'    => [
                    'message_id' => $msg->id,
                    'ticket_no'  => $ticket->ticket_no,
                    'status'     => $ticket->fresh()->status,
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Failed to send reply. ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * PUT /api/ticket-status/{id}
     * Change the status of a ticket (open / in_progress / closed).
     */
    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,closed',
        ]);

        $ticket = Grivance::find($id);

        if (! $ticket) {
            return response()->json(['status' => false, 'message' => 'Ticket not found.'], 404);
        }

        $ticket->status = $request->status;

        if ($request->status === 'closed') {
            $ticket->closed_at = now();
        }

        $ticket->save();

        return response()->json([
            'status'  => true,
            'message' => 'Ticket status updated to ' . $request->status . '.',
            'data'    => [
                'ticket_id' => $ticket->id,
                'ticket_no' => $ticket->ticket_no,
                'status'    => $ticket->status,
            ],
        ]);
    }

    /**
     * GET /api/my-tickets?user_id=
     * List all tickets for a user.
     */
    public function myTickets(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:mlm_users,id',
        ]);

        $tickets = Grivance::where('user_id', $request->user_id)
            ->latest()
            ->get()
            ->map(fn($t) => [
                'id'         => $t->id,
                'ticket_no'  => $t->ticket_no,
                'subject'    => $t->subject,
                'category'   => $t->category,
                'priority'   => $t->priority,
                'status'     => $t->status,
                'created_at' => $t->created_at->format('d M Y'),
            ]);

        return response()->json([
            'status'  => true,
            'tickets' => $tickets,
        ]);
    }
}
