<?php

namespace hlyall01\Backpack\CRUD\app\Library\CrudPanel;

/**
 * Adds fluent syntax to Backpack CRUD Fields.
 *
 * In addition to the existing:
 * - CRUD::addField(['name' => 'price', 'type' => 'number']);
 *
 * Developers can also do:
 * - CRUD::field('price')->type('number');
 *
 * And if the developer uses CrudField as Field in their CrudController:
 * - Field::name('price')->type('number');
 *
 * @method self type(string $value)
 * @method self label(string $value)
 * @method self tab(string $value)
 * @method self prefix(string $value)
 * @method self suffix(string $value)
 * @method self default(mixed $value)
 * @method self hint(string $value)
 * @method self attributes(array $value)
 * @method self wrapper(array $value)
 * @method self fake(bool $value)
 * @method self store_in(string $value)
 * @method self validationRules(string $value)
 * @method self validationMessages(array $value)
 * @method self entity(string $value)
 * @method self addMorphOption(string $key, string $label, array $options)
 * @method self morphTypeField(array $value)
 * @method self morphIdField(array $value)
 */
class CrudField
{
    protected $attributes;

    public function __construct($name)
    {
        if (empty($name)) {
            abort(500, 'Field name can\'t be empty.');
        }

        $field = $this->crud()->firstFieldWhere('name', $name);

        // if field exists
        if ((bool) $field) {
            // use all existing attributes
            $this->setAllAttributeValues($field);
        } else {
            // it means we're creating the field now,
            // so at the very least set the name attribute
            $this->setAttributeValue('name', $name);
        }

        $this->save();
    }

    public function crud()
    {
        return app()->make('crud');
    }

    /**
     * Create a CrudField object with the parameter as its name.
     *
     * @param  string  $name  Name of the column in the db, or model attribute.
     * @return CrudField
     */
    public static function name($name)
    {
        return new static($name);
    }

    /**
     * When defining the entity, make sure Backpack guesses the relationship attributes if needed.
     *
     * @param  string|bool  $entity
     * @return self
     */
    public function entity($entity)
    {
        $this->attributes['entity'] = $entity;

        if ($entity !== false) {
            $this->attributes = $this->crud()->makeSureFieldHasRelationshipAttributes($this->attributes);
        }

        return $this->save();
    }

    /**
     * Remove the current field from the current operation.
     *
     * @return void
     */
    public function remove()
    {
        $this->crud()->removeField($this->attributes['name']);
    }

    /**
     * Remove an attribute from the current field definition array.
     *
     * @param  string  $attribute  Name of the attribute being removed.
     * @return CrudField
     */
    public function forget($attribute)
    {
        $this->crud()->removeFieldAttribute($this->attributes['name'], $attribute);

        return $this;
    }

    /**
     * Move the current field after another field.
     *
     * @param  string  $destinationField  Name of the destination field.
     * @return CrudField
     */
    public function after($destinationField)
    {
        $this->crud()->removeField($this->attributes['name']);
        $this->crud()->addField($this->attributes)->afterField($destinationField);

        return $this;
    }

    /**
     * Move the current field before another field.
     *
     * @param  string  $destinationField  Name of the destination field.
     * @return CrudField
     */
    public function before($destinationField)
    {
        $this->crud()->removeField($this->attributes['name']);
        $this->crud()->addField($this->attributes)->beforeField($destinationField);

        return $this;
    }

    /**
     * Make the current field the first one in the fields list.
     *
     * @return CrudField
     */
    public function makeFirst()
    {
        $this->crud()->removeField($this->attributes['name']);
        $this->crud()->addField($this->attributes)->makeFirstField();

        return $this;
    }

    /**
     * Make the current field the last one in the fields list.
     *
     * @return CrudField
     */
    public function makeLast()
    {
        $this->crud()->removeField($this->attributes['name']);
        $this->crud()->addField($this->attributes);

        return $this;
    }

    // -------------------
    // CONVENIENCE METHODS
    // -------------------
    // These methods don't do exactly what advertised by their name.
    // They exist because the original syntax was too long.

    /**
     * Set the wrapper width at this many number of columns.
     * For example, to set a field wrapper to span across 6 columns, you can do both:
     * ->wrapper(['class' => 'form-group col-md-6'])
     * ->size(6).
     *
     * @param  int  $numberOfColumns  How many columns should this field span across (1-12)?
     * @return CrudField
     */
    public function size($numberOfColumns)
    {
        $this->attributes['wrapper']['class'] = 'form-group col-md-'.$numberOfColumns;

        return $this->save();
    }

    /**
     * Set an event to a certain closure. Will overwrite if existing.
     *
     * @param  string  $event  Name of Eloquent Model event
     * @param  \Closure  $closure  The function aka callback aka closure to run.
     * @return CrudField
     */
    public function on(string $event, \Closure $closure)
    {
        $this->attributes['events'][$event] = $closure;

        return $this->save();
    }

    /**
     * When subfields are defined, pass them through the guessing function
     * so that they have label, relationship attributes, etc.
     *
     * @param  array  $subfields  Subfield definition array
     * @return self
     */
    public function subfields($subfields)
    {
        $this->attributes['subfields'] = $subfields;
        $this->attributes = $this->crud()->makeSureFieldHasNecessaryAttributes($this->attributes);

        return $this->save();
    }

    /**
     * Save the validation rules on the CrudPanel per field basis.
     *
     * @param  string  $rules  the field rules: required|min:1|max:5
     * @return self
     */
    public function validationRules(string $rules)
    {
        $this->attributes['validationRules'] = $rules;
        $this->crud()->setValidationFromArray([$this->attributes['name'] => $rules]);

        return $this;
    }

    /**
     * Save the validation messages on the CrudPanel per field basis.
     *
     * @param  array  $messages  the messages for field rules: [required => please input something, min => the minimum allowed is 1]
     * @return self
     */
    public function validationMessages(array $messages)
    {
        $this->attributes['validationMessages'] = $messages;

        // append the field name to the rule name of validationMessages array.
        // eg: ['required => 'This field is required']
        // will be transformed into: ['field_name.required' => 'This field is required]
        $this->crud()->setValidationFromArray([], array_merge(...array_map(function ($rule, $message) {
            return [$this->attributes['name'].'.'.$rule => $message];
        }, array_keys($messages), $messages)));

        return $this;
    }

    /**
     * This function is responsible for setting up the morph fields structure.
     * Developer can define the morph structure as follows:
     *  'morphOptions => [
     *       ['nameOnAMorphMap', 'label', [options]],
     *       ['App\Models\Model'], // display the name of the model
     *       ['App\Models\Model', 'label', ['data_source' => backpack_url('smt')]
     *  ]
     * OR
     * ->addMorphOption('App\Models\Model', 'label', ['data_source' => backpack_url('smt')]).
     *
     * @param  string  $key  - the morph option key, usually a \Model\Class or a string for the morphMap
     * @param  string|null  $label  - the displayed text for this option
     * @param  array  $options  - options for the corresponding morphable_id field (usually ajax options)
     * @return self
     *
     * @throws \Exception
     */
    public function addMorphOption(string $key, $label = null, array $options = [])
    {
        $this->crud()->addMorphOption($this->attributes['name'], $key, $label, $options);

        return $this;
    }

    /**
     * Allow developer to configure the morph type field.
     *
     * @param  array  $configs
     * @return self
     *
     * @throws \Exception
     */
    public function morphTypeField(array $configs)
    {
        $morphField = $this->crud()->fields()[$this->attributes['name']];

        if (empty($morphField) || ($morphField['relation_type'] ?? '') !== 'MorphTo') {
            throw new \Exception('Trying to configure the morphType on a non-morphTo field. Check if field and relation name matches.');
        }
        [$morphTypeField, $morphIdField] = $morphField['subfields'];

        $morphTypeField = array_merge($morphTypeField, $configs);

        $morphField['subfields'] = [$morphTypeField, $morphIdField];

        $this->crud()->modifyField($this->attributes['name'], $morphField);

        return $this;
    }

    /**
     * Allow developer to configure the morph type id selector.
     *
     * @param  array  $configs
     * @return self
     *
     * @throws \Exception
     */
    public function morphIdField(array $configs)
    {
        $morphField = $this->crud()->fields()[$this->attributes['name']];

        if (empty($morphField) || ($morphField['relation_type'] ?? '') !== 'MorphTo') {
            throw new \Exception('Trying to configure the morphType on a non-morphTo field. Check if field and relation name matches.');
        }

        [$morphTypeField, $morphIdField] = $morphField['subfields'];

        $morphIdField = array_merge($morphIdField, $configs);

        $morphField['subfields'] = [$morphTypeField, $morphIdField];

        $this->crud()->modifyField($this->attributes['name'], $morphField);

        return $this;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }
    // ---------------
    // PRIVATE METHODS
    // ---------------

    /**
     * Set the value for a certain attribute on the CrudField object.
     *
     * @param  string  $attribute  Name of the attribute.
     * @param  mixed  $value  Value of that attribute.
     */
    private function setAttributeValue($attribute, $value)
    {
        $this->attributes[$attribute] = $value;
    }

    /**
     * Replace all field attributes on the CrudField object
     * with the given array of attribute-value pairs.
     *
     * @param  array  $array  Array of attributes and their values.
     */
    private function setAllAttributeValues($array)
    {
        $this->attributes = $array;
    }

    /**
     * Update the global CrudPanel object with the current field attributes.
     *
     * @return CrudField
     */
    private function save()
    {
        $key = $this->attributes['name'];

        if ($this->crud()->hasFieldWhere('name', $key)) {
            $this->crud()->modifyField($key, $this->attributes);
        } else {
            $this->crud()->addField($this->attributes);
        }

        return $this;
    }

    // -----------------
    // DEBUGGING METHODS
    // -----------------

    /**
     * Dump the current object to the screen,
     * so that the developer can see its contents.
     *
     * @codeCoverageIgnore
     *
     * @return CrudField
     */
    public function dump()
    {
        dump($this);

        return $this;
    }

    /**
     * Dump and die. Duumps the current object to the screen,
     * so that the developer can see its contents, then stops
     * the execution.
     *
     * @codeCoverageIgnore
     *
     * @return CrudField
     */
    public function dd()
    {
        dd($this);

        return $this;
    }

    // -------------
    // MAGIC METHODS
    // -------------

    /**
     * If a developer calls a method that doesn't exist, assume they want:
     * - the CrudField object to have an attribute with that value;
     * - that field be updated inside the global CrudPanel object;.
     *
     * Eg: type('number') will set the "type" attribute to "number"
     *
     * @param  string  $method  The method being called that doesn't exist.
     * @param  array  $parameters  The arguments when that method was called.
     * @return CrudField
     */
    public function __call($method, $parameters)
    {
        $this->setAttributeValue($method, $parameters[0]);

        return $this->save();
    }
}
