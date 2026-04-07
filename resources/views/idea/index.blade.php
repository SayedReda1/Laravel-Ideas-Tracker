<x-layout>
    <div class="mb-10">
        <header class="py-8 md:py-12">
            <h1 class="text-3xl font-bold">Ideas</h1>
            <p class="text-muted-foreground text-sm mt-2">Capture your thoughts. Make a plan.</p>

            <x-card
                x-data
                @click="$dispatch('open-modal', 'new-idea-modal')"
                is="button"
                class="mt-10 cursor-pointer h-32 w-full text-left"
                data-test="create-idea-button"
            >
                <p>What's on your mind?</p>
            </x-card>
        </header>


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
                            <x-idea.status :status="$idea->status" />
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

        {{-- Modal --}}
        <x-modal title="New Idea" name="new-idea-modal" class="shadow-xl max-w-2xl w-full max-h-[80vh] overflow-auto">
            <form x-data="{ status: 'pending', newLink: '', links: [] }" action="{{ route('idea.store') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <x-form.field
                        label="Title"
                        name="title"
                        type="text"
                        placeholder="Enter your idea title"
                        autofocus
                        data-test="idea-title"
                    />

                    <div>
                        <label for="status" class="label">Status</label>
                        <div class="flex gap-x-3 mt-2">
                            @foreach(\App\IdeaStatus::cases() as $status)
                                <button
                                    type="button"
                                    @click="status=@js($status->value)"
                                    class="btn rounded-lg flex-1"
                                    :class="{'btn-outlined': status !== @js($status->value)}"
                                    data-test="idea-status-{{ $status->value }}"
                                >
                                    {{ $status->label() }}
                                </button>
                            @endforeach
                            <x-form.error name="status" />
                        </div>
                        <input type="hidden" name="status" :value="status" />
                    </div>

                    <x-form.field
                        label="Description"
                        name="description"
                        type="textarea"
                        placeholder="Describe your idea..."
                        data-test="idea-description"
                    />

                    <div>
                        <fieldset class="space-y-3">
                            <legend class="label">Links</legend>
                            <div class="flex gap-x-4 items-center">
                                <input
                                    type="url"
                                    name="link"
                                    id="link"
                                    class="input focus:outline-none focus:ring-2 focus:ring-primary flex-1"
                                    placeholder="https://example.com"
                                    x-model="newLink"
                                    data-test="link-field"
                                >
                                <button
                                    type="button"
                                    class="text-3xl form-muted-icon"
                                    @click="links.push(newLink.trim()); newLink = ''"
                                    :disabled="newLink.trim().length === 0"
                                    data-test="add-new-link-button"
                                >+</button>
                            </div>

                            <template x-for="(link,index) in links">
                                <div class="flex gap-x-4 items-center">
                                    <input type="text" name="links[]" x-model="link" class="flex-1 input">
                                    <button
                                        type="button"
                                        @click="links.splice(index,1)"
                                        class="text-3xl rotate-45 form-muted-icon"
                                    >+</button>
                                </div>
                            </template>

                        </fieldset>
                    </div>

                    <div class="flex justify-end items-center gap-x-5 mt-4">
                        <button type="button" @click="show=false">Cancel</button>
                        <button type="submit" class="btn">Create</button>
                    </div>

                </div>
            </form>
        </x-modal>

    </div>
</x-layout>
