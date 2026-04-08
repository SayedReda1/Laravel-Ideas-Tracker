<x-layout>
    <div class="py-8 max-w-4xl mx-auto">
        <div class="flex justify-between items-center pb-8">
            <a href="{{ route('idea.index') }}" class="flex items-center gap-x-2 text-sm text-muted-foreground">
                <x-icons.arrow-back />
                Back to Ideas
            </a>

            <div class="flex items-center gap-x-3">
                <a href="{{ route('idea.edit', $idea) }}" class="btn btn-outlined">
                    <x-icons.external />
                    Edit idea
                </a>
                <form action="{{ route('idea.destroy', $idea) }}" method="post">
                    @csrf
                    @method('delete')
                    <button class="btn btn-outlined text-red-500">
                        <x-icons.trash />
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="space-y-3">
            @if($idea->image_path)
                <div class="rounded-lg overflow-hidden">
                    <img src="{{ asset('storage/' . $idea->image_path) }}" alt="{{ $idea->title }}" class="w-full h-auto object-cover">
                </div>
            @endif

            <h1 class="font-bold text-4xl">{{ $idea->title }}</h1>

            <div class="mt-3 flex gap-x-3 items-center">
                <x-idea.status :status="$idea->status" />

                <div class="text-muted-foreground text-sm">{{ $idea->created_at->diffForHumans() }}</div>
            </div>

            <x-card class="mt-6">
                <div class="text-foreground max-w-none cursor-pointer">{{ $idea->description }}</div>
            </x-card>

            @if($idea->steps->count())
                <div>
                    <h3 class="font-bold text-xl mb-3">Steps</h3>
                    <div class="mt-3 space-y-2">
                        @foreach ($idea->steps as $step)
                            <x-card>
                                <form action="{{ route('step.update', $step) }}" method="post">
                                    @csrf
                                    @method('patch') 
                                    <div class="flex items-center gap-x-3">
                                        <button type="submit" class="size-5 flex items-center justify-center rounded-lg font-bold text-primary-foreground {{ $step->completed ? 'bg-primary' : 'border border-primary' }}">
                                            &check;
                                        </button>
                                        <span class="{{ $step->completed ? 'line-through text-muted-foreground' : '' }}">{{ $step->description }}</span>
                                    </div>
                                </form>
                            </x-card>
                        @endforeach
                    </div>
                </div>
            @endif
            
            @if($idea->links->count())
                <div>
                    <h3 class="font-bold text-xl mb-3">Links</h3>
                    <div class="mt-3 space-y-2">
                        @foreach ($idea->links as $link)
                            <x-card :href="$link" class="text-primary font-medium flex items-center gap-x-3">
                                <x-icons.external />
                                {{ $link }}
                            </x-card>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

    </div>
</x-layout>