<div class="p-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Features</h2>

        @can('create', App\Models\Feature::class)
        <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                wire:click="create">
            Create Feature
        </button>
        @endcan
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-2 border-b text-left text-sm font-medium text-gray-700">Name</th>
                    <th class="px-4 py-2 border-b text-left text-sm font-medium text-gray-700">Description</th>
                    <th class="px-4 py-2 border-b text-left text-sm font-medium text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($features as $feature)
                    <tr>
                        <td class="border-b px-4 py-2">{{ $feature->name }}</td>
                        <td class="border-b px-4 py-2">{{ $feature->description }}</td>
                        <td class="border-b px-4 py-2">
                            @can('update', $feature)
                            <button class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 mr-2"
                                    wire:click="edit({{ $feature->id }})">
                                Edit
                            </button>
                            @endcan

                            @can('delete', $feature)
                            <button class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600"
                                    x-on:click="if (!confirm('Are you sure you want to delete this feature?')) return; $wire.delete({{ $feature->id }})">
                                Delete
                            </button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-gray-500">No features found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $features->links() }}
    </div>
</div>