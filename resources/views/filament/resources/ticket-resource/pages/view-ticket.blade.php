<x-filament::page>
    <div class="space-y-4">

        {{-- Informasi Tiket --}}
        <x-filament::section>
            <x-filament::section.header>
                <h2 class="text-xl font-bold">{{ $record->title }}</h2>
                <p class="text-sm text-gray-500">{{ $record->identifier }} - {{ $record->project->name }}</p>
            </x-filament::section.header>

            <p>{{ $record->description }}</p>
        </x-filament::section>

        {{-- Komentar --}}
        <x-filament::section>
            <x-filament::section.header>
                <h3 class="text-lg font-semibold">Comments</h3>
            </x-filament::section.header>

            @forelse ($record->comments as $comment)
                <div class="border-t pt-2 mt-2">
                    <div class="text-sm text-gray-600">
                        {{ $comment->user->name ?? 'Unknown' }} • {{ $comment->created_at->diffForHumans() }}
                    </div>
                    <div class="mt-1">{{ $comment->content }}</div>
                </div>
            @empty
                <p class="text-sm text-gray-500">No comments yet.</p>
            @endforelse

            {{-- Tambah Komentar --}}
            <form wire:submit.prevent="addComment" class="mt-4 space-y-2">
                <x-filament::textarea
                    wire:model.defer="commentContent"
                    label="Add Comment"
                    required
                    rows="3"
                />
                <x-filament::button type="submit">
                    Submit
                </x-filament::button>
            </form>
        </x-filament::section>
    </div>
</x-filament::page>
