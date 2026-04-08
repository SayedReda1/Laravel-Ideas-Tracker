<x-layout>
    <div class="max-w-2xl mx-auto my-10 space-y-5">
        <a href="{{ route('idea.show', $idea) }}" class="flex items-center gap-x-2 text-sm text-muted-foreground">
            <x-icons.arrow-back />
            Back
        </a>

        <h2 class="text-3xl font-bold">Edit Idea</h2>

        <form x-data="{
                    status: @js($idea->status),
                    newLink: '',
                    links: @js($idea->links),
                    newStep: '',
                    steps: @js(collect($idea->steps)->map(fn ($step) => $step['description'])),
                }"
              action="{{ route('idea.update', $idea) }}"
              method="POST"
              enctype="multipart/form-data"
        >
            @csrf
            @method('PATCH')

            <div class="space-y-6">
                <x-form.field
                    label="Title"
                    name="title"
                    type="text"
                    placeholder="Enter your idea title"
                    data-test="idea-title"
                    autofocus
                    value="{{ $idea->title }}"
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
                    value="{{ $idea->description }}"
                />

                @if($idea->image_path)
                    <div
                        class="space-y-2"
                        x-data="{
                            removed: false,
                            preview: null,
                            triggerPicker() { this.$refs.imagePicker.click() },
                            onFileChange(e) {
                                const file = e.target.files[0];
                                if (!file) return;
                                this.removed = false;
                                const reader = new FileReader();
                                reader.onload = (ev) => { this.preview = ev.target.result };
                                reader.readAsDataURL(file);
                            },
                            removeImage() {
                                this.removed = true;
                                this.preview = null;
                                this.$refs.imagePicker.value = '';
                            }
                        }"
                    >
                        <label class="label">Featured Image</label>

                        {{-- Hidden file input (used both when image exists and after removal) --}}
                        <input
                            type="file"
                            name="image"
                            id="image"
                            accept="image/*"
                            class="hidden"
                            x-ref="imagePicker"
                            @change="onFileChange($event)"
                            data-test="idea-image"
                        />

                        {{-- Shown while an image is present (existing or newly picked) --}}
                        <template x-if="!removed">
                            <div class="relative inline-block group">
                                <img
                                    :src="preview ?? '{{ Storage::url($idea->image_path) }}'"
                                    alt="Featured image"
                                    @click="triggerPicker()"
                                    class="h-48 w-full object-cover rounded-lg cursor-pointer ring-2 ring-transparent group-hover:ring-primary transition"
                                    title="Click to change image"
                                />
                                <div
                                    class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition rounded-lg pointer-events-none"
                                >
                                    <span class="text-white text-sm font-medium">Click to change</span>
                                </div>
                                <button
                                    type="button"
                                    @click.stop="removeImage()"
                                    class="absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold px-2 py-1 rounded transition"
                                    data-test="remove-image-button"
                                >
                                    Remove
                                </button>
                            </div>
                        </template>

                        {{-- Shown after removal: plain file picker --}}
                        <template x-if="removed">
                            <div class="space-y-1">
                                <p class="text-sm text-muted-foreground">Image removed. Pick a new one (optional).</p>
                                <button
                                    type="button"
                                    @click="triggerPicker()"
                                    class="btn btn-outlined text-sm"
                                >
                                    Choose Image
                                </button>
                                <template x-if="preview">
                                    <img :src="preview" alt="New image preview" class="mt-2 h-48 w-full object-cover rounded-lg" />
                                </template>
                            </div>
                        </template>

                        {{-- Tell the server to delete the current image when removed=true and no new file chosen --}}
                        <input type="hidden" name="remove_image" :value="removed && !$refs.imagePicker.files.length ? '1' : '0'" />

                        <x-form.error name="image" />
                    </div>
                @else
                    <div class="space-y-2">
                        <label for="image" class="label">Featured Image</label>
                        <input type="file" name="image" id="image" accept="image/*" data-test="idea-image" />
                        <x-form.error name="image" />
                    </div>
                @endif

                <div>
                    <fieldset class="space-y-3">
                        <legend class="label">Steps</legend>
                        <template x-for="(step,index) in steps">
                            <div class="flex gap-x-4 items-center">
                                <input type="text" name="steps[]" x-model="step" class="flex-1 input">
                                <button
                                    type="button"
                                    @click="steps.splice(index,1)"
                                    class="text-3xl rotate-45 form-muted-icon text-red-400"
                                >+</button>
                            </div>
                        </template>
                        <div class="flex gap-x-4 items-center">
                            <input
                                type="text"
                                name="step"
                                id="step"
                                class="input focus:outline-none focus:ring-2 focus:ring-primary flex-1"
                                placeholder="What needs to be done?"
                                x-model="newStep"
                                data-test="step-field"
                            >
                            <button
                                type="button"
                                class="text-3xl form-muted-icon"
                                @click="steps.push(newStep.trim()); newStep = ''"
                                :disabled="newStep.trim().length === 0"
                                data-test="add-new-step-button"
                            >+</button>
                        </div>
                    </fieldset>
                </div>

                <div>
                    <fieldset class="space-y-3">
                        <legend class="label">Links</legend>
                        <template x-for="(link,index) in links">
                            <div class="flex gap-x-4 items-center">
                                <input type="text" name="links[]" x-model="link" class="flex-1 input">
                                <button
                                    type="button"
                                    @click="links.splice(index,1)"
                                    class="text-3xl rotate-45 form-muted-icon text-red-400"
                                >+</button>
                            </div>
                        </template>
                        <div class="flex gap-x-4 items-center">
                            <input
                                type="text"
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
                    </fieldset>
                </div>

                <div class="flex justify-end items-center gap-x-5 mt-4">
                    <button type="button" @click="show=false">Cancel</button>
                    <button type="submit" class="btn">Update</button>
                </div>
            </div>
        </form>
    </div>
</x-layout>
