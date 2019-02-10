<?php

namespace Drobee\NovaSluggable;

use Laravel\Nova\Element;
use Laravel\Nova\Fields\Text;

class SluggableText extends Text
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-sluggable-sluggabletext-field';

    /**
     * @param string $slugField
     * @return Element
     */
    public function slug($slugField = 'Slug'): Element
    {
        return $this->withMeta([__FUNCTION__ => $slugField]);
    }
}
