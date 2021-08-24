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
    public $editable = true;

    public function disableEdition()
    {
        $this->editable = false;
        return $this->withMeta(['editable' => $this->editable]);
    }

    public function onlyOutline()
    {
        return $this->withMeta(['only_outline' => true]);
    }

    public function jsonSerialize()
    {
        $this->withMeta(['editable' => $this->editable]);
        return parent::jsonSerialize();
    }
}
