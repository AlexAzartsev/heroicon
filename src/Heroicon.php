<?php

namespace AlexAzartsev\Heroicon;

use Laravel\Nova\Fields\Field;

class Heroicon extends Field
{
    public $component = 'heroicon';

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);
        $this->withMeta([
            'editor' => true
        ]);
    }

    public function disableEditor()
    {
        return $this->withMeta(['editor' => false]);
    }
}
