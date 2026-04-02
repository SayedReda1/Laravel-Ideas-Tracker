<x-layout>
    <div>
        <header class="py-8 md:py-12">
            <h1 class="text-3xl font-bold">Ideas</h1>
            <p class="text-muted-foreground text-sm mt-2">Capture your thoughts. Make a plan.</p>
        </header>

        <!--Tabs for applying filters-->
        <div class="flex items-center justify-left gap-x-2">
            <x-filter-button
                href="/ideas"
                class="{{ request()->has('status') ? 'btn-outlined' : '' }}"
                count="{{ $counts->get('all') }}"
            >
                All
            </x-filter-button>

            @foreach(\App\IdeaStatus::cases() as $status)
                <x-filter-button
                    href="/ideas?status={{ $status }}"
                    class="{{ request()->status === $status->value ? '' : 'btn-outlined' }}"
                    count="{{ $counts->get($status->value) }}"
                >
                    {{ $status->label() }}
                </x-filter-button>
            @endforeach
        </div>

        <div class="mt-10 text-muted-foreground">
            <div class="grid md:grid-cols-2 gap-6 ">
                @forelse($ideas as $idea)
                    <x-card href="{{ route('idea.show', $idea) }}">
                        <h3 class="text-foreground text-lg">{{ $idea->title }}</h3>
                        <div class="mt-2">
                            <x-idea.status status="{{ $idea->status }}">
                                {{ $idea->status->label() }}
                            </x-idea.status>
                        </div>
                        <div class="mt-5 line-clamp-3">{{ $idea->description }}</div>
                        <div class="mt-5">{{ $idea->created_at->diffForHumans() }}</div>
                    </x-card>
                @empty
                    <x-card>
                        <p>No ideas at this time</p>
                    </x-card>
                @endforelse
            </div>
        </div>
    </div>
</x-layout>
