<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Feature;

class FeatureList extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = '';
    public $perPage = 10;
    public $toastMessage;

    /** @var \Illuminate\Pagination\LengthAwarePaginator|null */
    public $features = null;

    protected $paginationTheme = 'bootstrap';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilter()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $feature = Feature::findOrFail($id);
        $feature->delete();

        $this->toastMessage = 'Feature deleted successfully.';
    }

    public function render()
    {
        $query = Feature::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        if ($this->filter) {
            $query->where('status', $this->filter);
        }

        $this->features = $query->orderBy('id', 'desc')
                                 ->paginate($this->perPage);

        return view('livewire.feature-list', [
            'features' => $this->features,
        ]);
    }
}