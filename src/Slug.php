<?php

namespace Drobee\NovaSluggable;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Element;
use Laravel\Nova\Http\Requests\NovaRequest;

class Slug extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-sluggable-slug-field';

    public function slugModel(string $model): Element
    {
        return $this->withMeta(['model' => $model]);
    }

    public function slugOptions(array $options): Element
    {
        return $this->withMeta(['options' => $options]);
    }
}
