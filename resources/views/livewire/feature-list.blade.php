<div>
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4">
        <div class="mb-2 md:mb-0">
            <input type="text"
                   placeholder="Search..."
                   class="border rounded px-3 py-2 w-full md:w-64"
                   wire:model.debounce.500ms="search" />
        </div>

        <div>
            <select wire:model="filter"
                    class="border rounded px-3 py-2">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="pending">Pending</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="px-4 py-2 border-b">ID</th>
                    <th class="px-4 py-2 border-b">Name</th>
                    <th class="px-4 py-2 border-b">Description</th>
                    <th class="px-4 py-2 border-b">Status</th>
                    <th class="px-4 py-2 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($features as $feature)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border-b">{{ $feature->id }}</td>
                        <td class="px-4 py-2 border-b">{{ $feature->name }}</td>
                        <td class="px-4 py-2 border-b">{{ Str::limit($feature->description, 50) }}</td>
                        <td class="px-4 py-2 border-b">{{ ucfirst($feature->status) }}</td>
                        <td class="px-4 py-2 border-b">
                            <button wire:click="delete({{ $feature->id }})"
                                    class="text-red-600 hover:text-red-800"
                                    onclick="return confirm('Are you sure you want to delete this feature?');">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-center">No features found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $features->links() }}
    </div>

    @if ($toastMessage)
        <div x-data="{ show: true }"
             x-init="setTimeout(() => { show = false; $wire.set('toastMessage', null); }, 3000)"
             x-show="show"
             x-transition.opacity.duration.200ms
             class="fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded shadow-lg z-50">
            {{ $toastMessage }}
        </div>
    @endif
</div>