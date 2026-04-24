<?php

namespace App\Http\Livewire;

use App\Models\Feature;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Throwable;

/**
 * Livewire component responsible for creating and editing Feature records.
 *
 * The component exposes public properties that are bound to form fields in the Blade view.
 * It supports both creation and editing of features, handling validation, persistence,
 * and event emission to notify parent components of changes.
 *
 * @package App\Http\Livewire
 */
class FeatureForm extends Component
{
    /**
     * The unique identifier of the Feature being edited.
     * If null, the component will create a new Feature.
     *
     * @var int|null
     */
    public ?int $featureId = null;

    /**
     * Feature name input.
     *
     * @var string
     */
    public string $name = '';

    /**
     * Feature description input.
     *
     * @var string|null
     */
    public ?string $description = null;

    /**
     * Livewire event listeners.
     *
     * @var array<string, string>
     */
    protected $listeners = [
        'openFeatureForm' => 'openForm',
    ];

    /**
     * Mounts the component with optional feature ID.
     *
     * If an ID is provided, the corresponding Feature record is loaded
     * and its data is populated into the component properties.
     *
     * @param  int|null  $id
     * @return void
     */
    public function mount(?int $id = null): void
    {
        $this->featureId = $id;

        if ($this->featureId) {
            try {
                $feature = Feature::findOrFail($this->featureId);

                $this->name = $feature->name;
                $this->description = $feature->description;
            } catch (Throwable $e) {
                Log::error('FeatureForm mount error: {message}', [
                    'message' => $e->getMessage(),
                    'id' => $this->featureId,
                ]);

                // If the feature cannot be loaded, reset to create mode
                $this->reset();
                $this->featureId = null;
            }
        }
    }

    /**
     * Open the form modal, optionally loading a specific Feature.
     *
     * @param  int|null  $id
     * @return void
     */
    public function openForm(?int $id = null): void
    {
        $this->featureId = $id;

        if ($this->featureId) {
            try {
                $feature = Feature::findOrFail($this->featureId);

                $this->name = $feature->name;
                $this->description = $feature->description;
            } catch (Throwable $e) {
                Log::error('FeatureForm openForm error: {message}', [
                    'message' => $e->getMessage(),
                    'id' => $this->featureId,
                ]);

                $this->reset(['name', 'description']);
                $this->featureId = null;
            }
        } else {
            $this->reset(['name', 'description']);
        }

        // Trigger the Alpine listener to open the modal
        $this->dispatchBrowserEvent('openFeatureForm');
    }

    /**
     * Validation rules for the form fields.
     *
     * @return array<string, string|array>
     */
    protected function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }

    /**
     * Handles form submission.
     *
     * Validates the input and either creates a new Feature or updates an existing one.
     * Emits events on success and logs any unexpected errors.
     *
     * @return void
     *
     * @throws ValidationException
     */
    public function submit(): void
    {
        $validatedData = $this->validate();

        try {
            if ($this->featureId) {
                $feature = Feature::findOrFail($this->featureId);
                $feature->update($validatedData);
            } else {
                Feature::create($validatedData);
            }

            // Notify listeners that a feature was created/updated
            $this->emit('featureUpdated');

            // Close the modal
            $this->emit('closeModal');

            // Reset form fields for next use
            $this->reset(['name', 'description']);
        } catch (Throwable $e) {
            Log::error('FeatureForm submit error: {message}', [
                'message' => $e->getMessage(),
                'feature_id' => $this->featureId,
            ]);

            // Re-throw to allow Livewire to display a generic error
            throw $e;
        }
    }

    /**
     * Render the Livewire component.
     *
     * The Blade view resides at resources/views/livewire/feature-form.blade.php.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.feature-form');
    }
}