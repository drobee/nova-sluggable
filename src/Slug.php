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
    
    protected $options = [
        'event' => 'keyup',
    ];

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        $this->withMeta(['options' => $this->options]);
        parent::__construct($name, $attribute, $resolveCallback);
    }

    public function slugModel(string $model): Element
    {
        return $this->withMeta(['model' => $model]);
    }
    
    public function slugUnique(): Element
    {
        return $this->setOption('generateUniqueSlugs', true);
    }

    public function slugMaxLength(int $length): Element
    {
        return $this->setOption('maximumLength', $length);
    }

    public function slugSeparator(string $separator): Element
    {
        return $this->setOption('slugSeparator', $separator);
    }

    public function slugLanguage(string $language): Element
    {
        return $this->setOption('slugLanguage', $language);
    }

    public function event(string $eventType): Element
    {
        if (in_array($eventType, ['keyup', 'blur'])) {
            return $this->setOption('event', $eventType);
        }

        return $this->setOption('event', 'keyup');
    }

    protected function setOption(string $name, string $value): Element
    {
        $this->options[$name] = $value;
        return $this->withMeta(['options' => $this->options]);
    }
}
