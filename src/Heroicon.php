<?php

namespace AlexAzartsev\Heroicon;

use Laravel\Nova\Fields\Field;

class Heroicon extends Field
{
    public $component = 'heroicon';
    public array $icons = [
        ['value' => 'solid', 'label' => 'Solid'],
        ['value' => 'outline', 'label' => 'Outline'],
    ];

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);
        $this->withMeta([
            'editor' => true,
            'icons'  => $this->icons
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

    public function registerIconSet(string $key, string $label, string $path)
    {
        $this->icons[] = [
            'value' => $key,
            'label' => $label,
            'icons' => $this->prepareIcons($key, $path)
        ];

        return $this->withMeta([
            'icons' => $this->icons
        ]);
    }


    protected function prepareIcons($key, $path)
    {
        $icons = [];
        $files = scandir($path);
        foreach ($files as $file) {
            if (preg_match("/.*\.svg/i", $file)) {
                $icons[] = [
                    'type'    => $key,
                    'name'    => strtolower(str_replace('.svg', '', $file)),
                    'content' => file_get_contents("$path/$file"),
                ];
            }
        }

        return $icons;

    }
}
