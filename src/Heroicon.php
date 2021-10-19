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
            'editor' => true,
            'icons'  => [
                ['value' => 'solid', 'label' => 'Solid'],
                ['value' => 'outline', 'label' => 'Outline'],
            ]
        ]);
    }

    public function disableEditor()
    {
        return $this->withMeta(['editor' => false]);
    }

    public function onlySolid()
    {
        return $this->withMeta([
            'icons' => [
                ['value' => 'solid', 'label' => 'Solid'],
            ]
        ]);
    }

    public function onlyOutline()
    {
        return $this->withMeta([
            'icons' => [
                ['value' => 'outline', 'label' => 'Outline'],
            ]
        ]);
    }
}
