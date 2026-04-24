<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Feature;

class FeatureForm extends Component
{
    public $mode = 'create'; // 'create' or 'edit'
    public $featureId;
    public $name;
    public $description;

    protected $rules = [
        'name'        => 'required|max:255',
        'description' => 'nullable|string',
    ];

    protected $listeners = [
        'openFeatureForm' => 'open',
    ];

    public function open(array $payload)
    {
        $this->mode = $payload['mode'] ?? 'create';
        $this->reset(['name', 'description', 'featureId']);

        if ($this->mode === 'edit' && isset($payload['featureId'])) {
            $this->featureId = $payload['featureId'];
            $feature = Feature::findOrFail($this->featureId);
            $this->name = $feature->name;
            $this->description = $feature->description;
        }

        // Signal Alpine to open modal
        $this->dispatchBrowserEvent('show-feature-modal');
    }

    public function submit()
    {
        $this->validate();

        if ($this->mode === 'create') {
            $this->authorize('create', Feature::class);
            Feature::create([
                'name'        => $this->name,
                'description' => $this->description,
            ]);
            session()->flash('message', 'Feature created successfully.');
        } else {
            $feature = Feature::findOrFail($this->featureId);
            $this->authorize('update', $feature);
            $feature->update([
                'name'        => $this->name,
                'description' => $this->description,
            ]);
            session()->flash('message', 'Feature updated successfully.');
        }

        // Close modal
        $this->dispatchBrowserEvent('close-feature-modal');

        // Tell table to refresh
        $this->dispatch('refreshTable');

        // Reset form
        $this->reset(['name', 'description', 'featureId']);
    }

    public function render()
    {
        return view('livewire.feature-form');
    }
}