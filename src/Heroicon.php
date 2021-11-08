<?php

namespace AlexAzartsev\Heroicon;

use Laravel\Nova\Fields\Field;

class Heroicon extends Field
{
    public $component = 'heroicon';
    public array $icons = [];

    protected static array $supportedSets = [
        ['value' => 'solid', 'label' => 'Heroicons Solid', 'path' => '../resources/icons/heroicons/solid'],
        ['value' => 'outline', 'label' => 'Heroicons Outline', 'path' => '../resources/icons/heroicons/outline'],
        ['value' => 'fa-brands', 'label' => 'Fontawesome brands', 'path' => '../resources/icons/fa/free/brands'],
        ['value' => 'fa-regular', 'label' => 'Fontawesome regular', 'path' => '../resources/icons/fa/free/regular'],
        ['value' => 'fa-solid', 'label' => 'Fontawesome solid', 'path' => '../resources/icons/fa/free/solid'],
    ];

    protected static array $defaultIcons = [
    ];

    protected static array $defaultIconSets = ['solid', 'outline'];
    protected static bool $defaultEditorEnabled = true;

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);
        $this->registerDefaultIcons();
        $this->icons = self::$defaultIcons;
        $this->withMeta(['editor' => self::$defaultEditorEnabled]);
        $this->only(self::$defaultIconSets);

    }

    public function registerDefaultIcons()
    {
        foreach (self::$defaultIconSets as $defaultIconSet) {
            $alreadyRegistered = array_search($defaultIconSet, array_column(self::$defaultIcons, 'value'));
            $key = array_search($defaultIconSet, array_column(self::$supportedSets, 'value'));
            if ($alreadyRegistered === false && $key !== false) {
                $set = self::$supportedSets[$key];
                self::registerGlobalIconSet($set['value'], $set['label'],
                    __DIR__ . '/' . $set['path']);
            }

        }
    }

    public function disableEditor()
    {
        return $this->withMeta(['editor' => false]);
    }

    public function enableEditor()
    {
        return $this->withMeta(['editor' => true]);
    }

    public function onlySolid()
    {
        return $this->only(['solid']);

    }

    public function onlyOutline()
    {
        return $this->only(['outline']);
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

    public static function registerGlobalIconSet(string $key, string $label, string $path): void
    {
        self::$defaultIcons[] = [
            'value' => $key,
            'label' => $label,
            'icons' => self::prepareIcons($key, $path)
        ];
    }

    public static function defaultIconSets(array $sets): void
    {
        self::$defaultIconSets = $sets;
    }

    public static function defaultEditorEnable(bool $enabled): void
    {
        self::$defaultEditorEnabled = $enabled;
    }


    protected static function prepareIcons($key, $path): array
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

    public function only(array $sets)
    {
        $icons = $this->icons;
        $filteredIcons = [];
        foreach ($sets as $set) {
            foreach ($icons as $icon) {
                if ($icon['value'] === $set) {
                    $filteredIcons[] = $icon;
                }
            }
        }

        return $this->withMeta([
            'icons' => $filteredIcons
        ]);
    }
}
