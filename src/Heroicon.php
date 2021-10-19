<?php

namespace AlexAzartsev\Heroicon;

use Laravel\Nova\Fields\Field;

class Heroicon extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'heroicon';
    public $editor = true;

    public function disableEditior()
    {
        $this->editor = false;
        return $this->withMeta(['editor' => $this->editor]);
    }
}
