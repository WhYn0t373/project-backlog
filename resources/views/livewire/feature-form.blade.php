<div x-data="{ open: false }"
     @show-feature-modal.window="open = true"
     @close-feature-modal.window="open = false">
    <div x-show="open" class="fixed inset-0 flex items-center justify-center z-50">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-black opacity-50" @click="open = false"></div>

        <!-- Modal -->
        <div class="bg-white rounded-lg shadow-xl p-6 relative z-10 w-full max-w-md mx-4">
            <h3 class="text-lg font-semibold mb-4">
                {{ $mode === 'create' ? 'Create Feature' : 'Edit Feature' }}
            </h3>

            <form wire:submit.prevent="submit">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Name</label>
                    <input type="text"
                           wire:model.defer="name"
                           class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Feature name" />
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Description</label>
                    <textarea wire:model.defer="description"
                              class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Optional description"></textarea>
                    @error('description')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button"
                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
                            @click="open = false">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        {{ $mode === 'create' ? 'Create' : 'Save' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>