<x-filament::page>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach ($statuses as $status)
            <div class="bg-white dark:bg-gray-800 rounded shadow p-4">
                <h2 class="font-bold text-lg mb-3">{{ $status->name }}</h2>

                @foreach ($tickets->where('ticket_status_id', $status->id) as $ticket)
                    <div class="p-3 mb-2 bg-gray-100 dark:bg-gray-700 rounded">
                        <strong>{{ $ticket->title }}</strong>
                        <div class="text-xs text-gray-500">#{{ $ticket->ticket_number }}</div>
                        <div class="text-sm">{{ $ticket->assignee->name ?? 'Tidak ada assignee' }}</div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</x-filament::page>
