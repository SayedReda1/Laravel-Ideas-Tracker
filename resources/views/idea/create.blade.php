<x-layout>
    <x-form.layout title="New Idea" subtitle="Take a minute to think">
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
                            >
                            <button
                                type="button"
                                class="text-3xl form-muted-icon"
                                @click="links.push(newLink.trim()); newLink = ''"
                                :disabled="newLink.trim().length === 0"
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
    </x-form.layout>
</x-layout>
