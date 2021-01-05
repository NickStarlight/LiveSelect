<?php

namespace LiveSelect\Traits;

use Error;

/**
 * The LiveSelect trait.
 *
 * @package    Livewire-LiveSelect
 * @author     Nickolas Gomes Moraes <contato@nickgomes.dev>
 * @copyright  2020 Nickolas Gomes Moraes
 * @license    https://opensource.org/licenses/MIT  MIT License
 * @version    Release: 1.0.0
 * @link       https://github.com/NickStarlight/LiveSelect
 */
trait WithLiveSelect 
{
    /**
     * Initializes the trait, creating the event listeners
     * required for LiveSelect.
     * 
     * This function is a magic method provided by Laravel
     * and works in a similar fashion of __construct()
     * 
     * @return void
     */
    protected function initializeWithLiveSelect() : void
    {
        $this->listeners['live-select-updated'] = 'assignValueToModel';
    }

    /**
     * Emits the error bag to the LiveSelect components.
     * This function is part of the Livewire lifecycle.
     * 
     * We use this because the MessageBag exposed by Laravel
     * does not propagate to children components of Livewire 
     * and since we want LiveSelect to be a high-level, easy
     * and no-headache plugin, propagating it manually makes
     * the user life easier by just making the validations
     * in one single place(in this case, the parent/sibling
     * components)
     * 
     * @see https://github.com/livewire/livewire/pull/710s
     * 
     * @return void
     */
    public function renderingWithLiveSelect() : void
    {
        $errors = $this->getErrorBag();

        if (sizeof($errors) > 0) {
            $this->emit('live-select-errors', $errors);
        }
    }

    /**
     * Assign the selected values to the model.
     * 
     * This function will attempt to bind the values selected
     * on the LiveSelect dropdown to the user-provided model.
     * If the model provided cannot be found as a public variable
     * this function will throw an Error.
     * 
     * @param array $event['model'] The model name
     * @param array $event['data'] The data to be bound to the model
     * 
     * @return void
     */
    public function assignValueToModel(array $event) : void
    {
        $model = $event['model'];
        $data = $event['data'];
        $componentName = get_class($this);
        
        if (!property_exists($this, $model)) {
            throw new Error("Unable to find a public variable named '$model', is it set on the '$componentName' component?");
        }

        $this->$model = $data;
        $this->syncInput($model, $data);
    }
}