<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Feature;
use Illuminate\Support\Facades\Gate;

class FeaturesTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $perPage = 10;

    protected $listeners = [
        'refreshTable' => '$refresh',
    ];

    public function render()
    {
        $features = Feature::when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.features-table', [
            'features' => $features,
        ]);
    }

    public function create()
    {
        // Notify browser to open modal
        $this->dispatchBrowserEvent('open-feature-form', [
            'mode' => 'create',
        ]);

        // Notify Livewire component to open form
        $this->emit('openFeatureForm', [
            'mode' => 'create',
        ]);
    }

    public function edit(Feature $feature)
    {
        // Notify browser to open modal
        $this->dispatchBrowserEvent('open-feature-form', [
            'mode' => 'edit',
            'featureId' => $feature->id,
        ]);

        // Notify Livewire component to open form
        $this->emit('openFeatureForm', [
            'mode' => 'edit',
            'featureId' => $feature->id,
        ]);
    }

    public function delete(Feature $feature)
    {
        $this->authorize('delete', $feature);
        $feature->delete();

        session()->flash('message', 'Feature deleted successfully.');

        // Refresh table
        $this->dispatch('refreshTable');
    }
}