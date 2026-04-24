<div x-data="{ open: false }" @openFeatureForm.window="open = true" @closeModal.window="open = false">
    <!-- Modal backdrop -->
    <div class="fixed inset-0 flex items-center justify-center z-50" x-show="open" style="display: none;">
        <div class="fixed inset-0 bg-black opacity-50"></div>
        <!-- Modal panel -->
        <div class="bg-white rounded-lg shadow-lg w-96 relative z-10">
            <div class="flex justify-between items-center px-4 py-2 border-b">
                <h3 class="text-lg font-semibold">{{ $featureId ? 'Edit Feature' : 'Create Feature' }}</h3>
                <button type="button" class="text-gray-500 hover:text-gray-700" @click="open = false" wire:click="$emit('closeModal')">
                    &times;
                </button>
            </div>
            <form wire:submit.prevent="submit" class="p-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1" for="name">Name</label>
                    <input type="text" id="name" wire:model.defer="name" class="w-full border rounded px-3 py-2">
                    @error('name') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1" for="description">Description</label>
                    <textarea id="description" wire:model.defer="description" class="w-full border rounded px-3 py-2"></textarea>
                    @error('description') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end">
                    <button type="button" class="mr-2 px-4 py-2 bg-gray-200 rounded" @click="open = false" wire:click="$emit('closeModal')">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>