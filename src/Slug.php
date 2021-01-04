<?php

namespace Drobee\NovaSluggable;

use Laravel\Nova\Fields\Field;
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

    public function slugModel(string $model): self
    {
        return $this->withMeta(['model' => $model]);
    }
    
    public function slugUnique(): self
    {
        return $this->setOption('generateUniqueSlugs', true);
    }

    public function slugMaxLength(int $length): self
    {
        return $this->setOption('maximumLength', $length);
    }

    public function slugSeparator(string $separator): self
    {
        return $this->setOption('slugSeparator', $separator);
    }

    public function slugLanguage(string $language): self
    {
        return $this->setOption('slugLanguage', $language);
    }

    public function event(string $eventType): self
    {
        if (in_array($eventType, ['keyup', 'blur'])) {
            return $this->setOption('event', $eventType);
        }

        return $this->setOption('event', 'keyup');
    }

    protected function setOption(string $name, string $value): self
    {
        $this->options[$name] = $value;
        return $this->withMeta(['options' => $this->options]);
    }
}
