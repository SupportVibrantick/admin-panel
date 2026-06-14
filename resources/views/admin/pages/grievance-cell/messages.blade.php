 
<div class="canvas-scroll" style="flex:1 1 0; overflow-y:auto; padding:16px 20px 8px;">

    <div class="d-flex justify-content-between align-items-start mb-3 pb-3 border-bottom">
        <div>
            <p class="mb-1 small text-muted">
                <strong>Subject:</strong> {{ $ticket->subject }}
            </p>
            <p class="mb-1 small text-muted">
                <strong>User:</strong>
                {{ $ticket->user ? $ticket->user->first_name . ' ' . $ticket->user->last_name : '–' }}
                @if($ticket->user)
                    <span class="text-secondary">({{ $ticket->user->user_name }})</span>
                @endif
            </p>
            <p class="mb-0 small text-muted">
                <strong>Category:</strong> {{ ucfirst($ticket->category) }}
                &nbsp;
               {{--  |&nbsp;
                <strong>Priority:</strong>
                <span class="badge
                    @if($ticket->priority === 'high') bg-danger
                    @elseif($ticket->priority === 'medium') bg-warning text-dark
                    @else bg-secondary
                    @endif">
                    {{ ucfirst($ticket->priority) }}
                </span> --}}
            </p>
        </div>

        <div class="text-end flex-shrink-0 ms-3">
            @php
                $statusMap = [
                    'open'        => ['label' => 'Open',       'class' => 'bg-success'],
                    'in_progress' => ['label' => 'In Progress', 'class' => 'bg-warning text-dark'],
                    'closed'      => ['label' => 'Closed',      'class' => 'bg-danger'],
                ];
                $s = $statusMap[$ticket->status] ?? ['label' => ucfirst($ticket->status), 'class' => 'bg-secondary'];
            @endphp
            <div class="mb-1">
                <span class="badge {{ $s['class'] }}">{{ $s['label'] }}</span>
            </div>
            <div class="dropdown d-inline-block">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                        data-bs-toggle="dropdown">
                    Change Status
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item change-status-btn" href="#"
                           data-ticket="{{ $ticket->id }}" data-status="open">
                            <span class="badge bg-success me-1">Open</span> Open
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item change-status-btn" href="#"
                           data-ticket="{{ $ticket->id }}" data-status="in_progress">
                            <span class="badge bg-warning text-dark me-1">In Progress</span> In Progress
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item change-status-btn" href="#"
                           data-ticket="{{ $ticket->id }}" data-status="closed">
                            <span class="badge bg-danger me-1">Closed</span> Close Ticket
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- ── Chat thread ─────────────────────────────────────── --}}
    <div id="chatThread" class="d-flex flex-column gap-2 pb-2">

        @forelse($messages as $message)
            @php
                $isAdmin = $message->isAdminReply();
                $isUser  = ! $isAdmin && $message->sender_id == $ticket->user_id;
            @endphp

            <div class="d-flex {{ $isUser ? 'justify-content-start' : 'justify-content-end' }}">
                <div style="
                    max-width: 75%;
                    padding: 10px 14px;
                    border-radius: {{ $isUser ? '16px 16px 4px 16px' : '16px 16px 16px 4px' }};
                    background: {{ $isUser ? '#0d6efd' : '#f0f0f0' }};
                    color: {{ $isUser ? '#fff' : '#212529' }};
                ">
                    <div class="d-flex justify-content-between align-items-center gap-3 mb-1">
                        <small class="fw-semibold" style="font-size:.7rem;opacity:.8;">
                            @if($isAdmin)
                                Admin
                            @else
                                {{ $message->sender
                                    ? trim($message->sender->first_name . ' ' . $message->sender->last_name)
                                    : 'User' }}
                            @endif
                        </small>
                        <small style="font-size:.65rem;opacity:.7;white-space:nowrap;">
                            {{ $message->created_at->format('d M, h:i A') }}
                        </small>
                    </div>

                    <p class="mb-1" style="font-size:.875rem;word-break:break-word;">
                        {{ $message->message }}
                    </p>

                    @if($message->attachments->count())
                        <div class="d-flex flex-wrap gap-1 mt-1">
                            @foreach($message->attachments as $att)
                                <a href="{{ asset('storage/' . $att->file_path) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-{{ $isUser ? 'light' : 'primary' }}"
                                   style="font-size:.7rem;padding:2px 8px;">
                                    <i class="fas fa-paperclip me-1"></i>Attachment
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        @empty
            <div class="text-center text-muted py-5">
                <i class="fas fa-comments fa-2x mb-2 d-block opacity-25"></i>
                No messages yet.
            </div>
        @endforelse
    </div>

</div>{{-- /.canvas-scroll --}}


{{-- ═══════════════════════════════════════════════════════════════
     2.  STICKY REPLY FOOTER  (always visible at bottom)
═══════════════════════════════════════════════════════════════ --}}
<div class="canvas-reply border-top bg-white flex-shrink-0"
     style="padding:12px 20px 16px;">

    @if($ticket->status !== 'closed')

        <form id="adminReplyForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

            <textarea name="message" rows="2"
                      class="form-control form-control-sm mb-2"
                      placeholder="Type your reply…" required
                      style="resize:none;"></textarea>

            <div class="d-flex align-items-center gap-2">

                {{-- Attachment picker --}}
                <label class="btn btn-sm btn-outline-secondary mb-0 flex-shrink-0"
                       style="cursor:pointer;" title="Attach file">
                    <i class="fas fa-paperclip"></i>
                    <input type="file" name="attachment" class="d-none"
                           accept=".jpg,.jpeg,.png,.pdf">
                </label>

                {{-- Filename preview --}}
                <span id="attachmentName"
                      class="small text-muted text-truncate flex-grow-1"
                      style="max-width:180px;"></span>

                {{-- Send button --}}
                <button type="submit" class="btn btn-sm btn-primary ms-auto flex-shrink-0"
                        id="replyBtn">
                    <i class="fas fa-paper-plane me-1"></i>Send Reply
                </button>
            </div>
        </form>

    @else

        <p class="text-center text-muted small mb-0 py-1">
            <i class="fas fa-lock me-1"></i>
            This ticket is closed. Reopen it to reply.
        </p>

    @endif
</div>{{-- /.canvas-reply --}}
