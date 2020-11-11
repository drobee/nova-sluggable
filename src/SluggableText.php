<?php

namespace Drobee\NovaSluggable;

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
     * @return $this
     */
    public function slug($slugField = 'Slug'): self
    {
        return $this->withMeta([__FUNCTION__ => $slugField]);
    }
}
