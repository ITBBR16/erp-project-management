<x-filament::page>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-4 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold">{{ $record->name }}</h2>
        <x-filament::button
            color="gray"
            icon="heroicon-m-arrow-path"
            wire:click="$refresh"
            wire:loading.attr="disabled"
            wire:target="$refresh"
        >
            <span wire:loading.remove wire:target="$refresh">
                Refresh Board
            </span>

            <span wire:loading wire:target="$refresh" class="flex items-center gap-1 animate-spin">
                <x-heroicon-m-arrow-path class="h-5 w-5" />
                Refreshing...
            </span>
        </x-filament::button>
    </div>

    <div class="text-sm text-gray-400">
        Last loaded at: {{ now() }}
    </div>

    {{-- Kanban Board --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    @foreach ($ticketStatuses->filter(fn($status) => $status->name !== 'Done') as $status)
        <div class="bg-white/30 dark:bg-gray-900/30 rounded-xl shadow p-0 overflow-hidden">
            {{-- Header --}}
            <div class="px-4 py-3" style="background-color: {{ $status->color }}">
                <div class="flex justify-between items-center">
                    <h3 class="text-sm font-bold text-white">
                        {{ $status->name }}
                    </h3>
                    <span class="text-sm text-white">
                        {{ $status->tickets->count() }}
                    </span>
                </div>
            </div>

            {{-- Ticket List --}}
            <div class="p-3 space-y-4 min-h-[100px] sortable" data-status-id="{{ $status->id }}" id="status-{{ $status->id }}">
                @foreach ($status->tickets as $ticket)
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-md shadow-sm cursor-move" data-ticket-id="{{ $ticket->id }}">
                        <div class="text-xs mb-2 font-mono text-gray-500">{{ $ticket->identifier }}</div>
                        <div class="font-semibold mb-2">{{ $ticket->title }}</div>
                        <div class="text-sm mb-2 text-gray-600 dark:text-gray-400">{{ Str::limit($ticket->description, 60) }}</div>
                        <div class="flex justify-between items-center mt-4">
                            <div class="text-xs flex items-center gap-1 text-gray-600">
                                <x-heroicon-m-user class="h-4 w-4" />
                                {{ $ticket->assignee?->name ?? '-' }}
                            </div>
                            <button
                                wire:click="$emit('showCommentModal', {{ $ticket->id }})"
                                class="text-primary-600 hover:text-primary-700"
                                title="Comment"
                            >
                                <x-heroicon-m-chat-bubble-left-right class="h-5 w-5" />
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@livewire('ticket-comment-modal')
</x-filament::page>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        function initSortable() {
            const columns = document.querySelectorAll('.sortable');

            columns.forEach(column => {
                new Sortable(column, {
                    group: 'kanban',
                    animation: 150,
                    onEnd: function (evt) {
                        const ticketId = evt.item.dataset.ticketId;
                        const newStatusId = evt.to.dataset.statusId;

                        fetch("{{ route('ticket.update-status') }}", {
                            method: "POST",
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                ticket_id: ticketId,
                                ticket_status_id: newStatusId
                            })
                        }).then(response => {
                            if (!response.ok) {
                                alert('Gagal memindahkan tiket.');
                            }
                        });
                    }
                });
            });
        }

        document.addEventListener("DOMContentLoaded", initSortable);

        document.addEventListener("livewire:load", () => {
            initSortable();

            Livewire.hook('message.processed', () => {
                initSortable();
            });
        });
    </script>
@endpush
