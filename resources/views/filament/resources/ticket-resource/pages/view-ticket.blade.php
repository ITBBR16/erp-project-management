<x-filament::page>
    <div class="space-y-6">
        <div>
            <h2 class="text-xl font-bold">Komentar</h2>

            @foreach ($record->comments as $comment)
                <div class="border p-4 rounded shadow my-2">
                    <div class="text-sm text-gray-500">
                        <strong>{{ $comment->user->name }}</strong> 
                        • {{ $comment->created_at->diffForHumans() }}
                    </div>
                    <div class="mt-1">{{ $comment->content }}</div>
                </div>
            @endforeach
        </div>

        <form action="{{ route('tickets.comments.store', $record->id) }}" method="POST">
            @csrf
            <textarea name="content" class="w-full border rounded p-2" rows="3" placeholder="Tulis komentar..."></textarea>
            <x-filament::button type="submit" class="mt-2">Kirim</x-filament::button>
        </form>
    </div>
</x-filament::page>