<x-filament::modal :visible="$showModal" slide-over width="2xl" wire:model.defer="showModal">
    <x-slot name="header">
        <h2 class="text-lg font-bold">Comments for {{ $ticket->title ?? '' }}</h2>
    </x-slot>

    <x-slot name="content">
        @if ($ticket)
            <div class="space-y-3 max-h-72 overflow-y-auto pr-2">
                @forelse ($ticket->comments as $comment)
                    <div class="border p-2 rounded">
                        <div class="text-xs text-gray-500">
                            {{ $comment->user->name }} • {{ $comment->created_at->diffForHumans() }}
                        </div>
                        <div class="text-sm">{{ $comment->content }}</div>
                    </div>
                @empty
                    <div class="text-sm text-gray-500">No comments yet.</div>
                @endforelse
            </div>

            <div class="mt-4 space-y-2">
                <textarea
                    wire:model.defer="commentContent"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-primary-500 dark:bg-gray-900 dark:text-white"
                    rows="3"
                    placeholder="Write a comment..."
                ></textarea>

                <x-filament::button wire:click="submitComment">Submit</x-filament::button>
            </div>
        @endif
    </x-slot>
</x-filament::modal>
