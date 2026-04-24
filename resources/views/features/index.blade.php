<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Features</h2>
        <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded" wire:click="$emit('openFeatureForm')">
            Create Feature
        </button>
    </div>

    <div>
        <table class="min-w-full table-auto border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Description</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($features as $feature)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $feature->name }}</td>
                        <td class="px-4 py-2">{{ $feature->description }}</td>
                        <td class="px-4 py-2">
                            <button type="button" class="text-blue-600" wire:click="$emit('openFeatureForm', {{ $feature->id }})">
                                Edit
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <livewire:feature-form />
</div>