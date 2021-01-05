<?php

namespace LiveSelect;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\MessageBag;
use Illuminate\View\View;
use Livewire\Component;

/**
 * The LiveSelect component.
 *
 * @package    Livewire-LiveSelect
 * @author     Nickolas Gomes Moraes <contato@nickgomes.dev>
 * @copyright  2020 Nickolas Gomes Moraes
 * @license    https://opensource.org/licenses/MIT  MIT License
 * @version    Release: 1.0.0
 * @link       https://github.com/NickStarlight/LiveSelect
 */
class LiveSelect extends Component
{
    /**
     * Determines if LiveSelect will behave
     * like a multi-selection dropdown.
     * 
     * @var bool Optional - Defaults to false
     */
    public bool $multiMode;

    /**
     * Determines if LiveSelect allow
     * searching and filtering the options.
     * 
     * @var bool Optional - Defaults to false
     */
    public bool $searchMode;

    /**
     * The current applied text-filtering
     * on the options list.
     * 
     * @var string Optional - Defaults to an empty string
     */
    public string $searchText;

    /**
     * The text to be shown on top of the
     * input for identification purposes
     * 
     * @var string Optional - Defaults to null
     */
    public string $description;

    /**
     * An array containing the options
     * to be rendered on the dropdown
     * 
     * @var array Required
     */
    public iterable $options;

    /**
     * An array containing the sorted options
     * to be rendered on the dropdown
     * 
     * @var array Required
     */
    public iterable $sortedOptions;

    /**
     * The parent-component variable name
     * where LiveSelect will set an array
     * containing the selected options
     * using the LiveSelect trait.
     * 
     * Think of this parameter as the wire:model
     * data binding behavior.
     * 
     * @var string Required
     */
    public string $model;

    /**
     * The array-key containing the option
     * value.
     * 
     * @var string Required
     */
    public string $value;

    /**
     * The array-key containing the option
     * label.
     * 
     * @var string Required
     */
    public string $label;

    /**
     * The list containing the selected values
     * from the dropdown.
     * 
     * @var array Optional
     */
    public array $selected = [];

    /**
     * The default selected values on the dropdown.
     * 
     * @var array
     */
    public $default;

    /**
     * The default LiveSelect event listeners.
     * live-select-errors => Used for replicating
     * the parent-validation errors to nested LiveSelect
     * components.
     * 
     * @var array Required
     */
    protected $listeners = [ 'live-select-errors' => 'replicateErrorBag' ];

    public function mount(string $description = '', bool $search = false, bool $multi = false, string $label = null, string $value = null, array $options, string $model, array $default = null) : void
    {
        $this->multiMode = $multi;
        $this->searchMode = $search;
        $this->description = $description;
        $this->model = $model;
        $this->label = $label !== null ? $label : 'label';
        $this->value = $value !== null ? $value : 'value';
        $this->searchText = '';
        $this->default = $default;

        /** We only check if the default is selected once */
        $this->checkDefaultSelected();

        /**
         * The user can submit an Eloquent Collection straight away, if that's
         * the case, we call the toArray() method in order to convert the results
         * to a multidimensional array that is required by LiveSelect
         */
        if ($options instanceof Collection) {
            $this->options = $options->toArray();
        } else {
            $this->options = $options;
        }
        
    }

    /**
     * Render the Livewire component.
     * 
     * @return Illuminate\View\View
     */
    public function render() : View
    {
        $this->sortBySearch();

        return view('live-select::live-select');
    }

    /**
     * Checks if a default option has been provided.
     * 
     * This is useful for pre-filled select dropdowns
     * like 'user-state => active/inactive'
     * 
     * @return void
     */
    public function checkDefaultSelected() : void
    {
        if ($this->default !== null) {
            foreach ($this->default as $value) {
                $this->updateSelectedOptions($value);
            }
        }
    }

    /**
     * Updates the array containing the selected options.
     * 
     * This will overwrite the liveSelectSelected array if 
     * LiveSelect is on single selection mode, otherwise,
     * this will append or remove the option from the array,
     * depending wether the option is already in it based
     * on the liveSelectLabel(if set) and liveSelectValue(if set).
     * 
     * @param mixed $value The value to be pushed to the selected options
     * 
     * @return void
     */
    public function updateSelectedOptions($value) : void
    {
        $exists = in_array($value, array_column($this->selected, $this->value));
        $optionData = array_search($value, array_column($this->options, $this->value));

        /** 
         * If LiveSelect is not on multimode, we just
         * set an empty array if the value is already
         * selected, otherwise, we push the data.
         */
        if (!$this->multiMode) {
            $this->selected = $exists ? [] : [$this->options[$optionData]];
        } else {
            /**
             * On multimode, if the value already exists
             * we remove it by getting it's index on the
             * liveSelectSelected array and splicing it.
             */
            if($exists) {
                $selectedKey = array_search($value, array_column($this->selected, $this->value));
                array_splice($this->selected, $selectedKey, 1);
            } else {
                /** If it's on multimode and it doesn't exists, just push it. */
                array_push($this->selected, $this->options[$optionData]);
            }
        }

        /** Emit the selection event to the parent */
        $this->emitSelectedEvent();
    }

    /**
     * Sorts the option list by the user search
     * parameters using the similar_text PHP function.
     * 
     * @return void
     */
    private function sortBySearch() : void
    {
        if ($this->searchText !== '') {
            $label = $this->label;
            $searchText = $this->searchText;
            $this->sortedOptions = $this->options;
    
            usort($this->sortedOptions, function($a, $b) use ($searchText, $label) {
                return similar_text($searchText, $b[$label]) <=> similar_text($searchText, $a[$label]);
            });
        } else {
            $this->sortedOptions = $this->options;
        }
    }

    /**
     * Emits an event containing the user-provided model name 
     * and selected data from the dropdown.
     * 
     * This event will be captured by the LiveSelect trait that
     * is bound to a Livewire component and expects an existing
     * public variable named after the model parameter.
     * If the public variable can't be found, the LiveSelect trait
     * will throw an Error.
     * 
     * @return void
     */
    protected function emitSelectedEvent() : void
    {
        $this->emit('live-select-updated', [
            'model' => $this->model,
            'data' => $this->selected
        ]);
    }

    /**
     * Replicate the parent emitted errors to LiveSelect.
     * 
     * This function used the internal Livewire $errorBag
     * public variable.
     * 
     * @see App\Traits\LiveSelect\renderingLiveSelect()
     * 
     * @param array $errorBag Illuminate\Support\MessageBag
     * @return void
     */
    public function replicateErrorBag(array $errorBag) : void
    {
        $messageBag = new MessageBag();
        foreach ($errorBag as $field => $error) {
            $messageBag->add($field, $error[0]);
        }

        $this->errorBag = $messageBag;
    }
}
