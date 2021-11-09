<?php

namespace AlexAzartsev\Heroicon;

use Laravel\Nova\Fields\Field;

class Heroicon extends Field
{
    public $component = 'heroicon';
    public array $icons = [];

    protected static array $supportedSets = [
        ['value' => 'solid', 'label' => 'Heroicons solid', 'path' => __DIR__ . '/../resources/icons/heroicons/solid'],
        [
            'value' => 'outline',
            'label' => 'Heroicons outline',
            'path'  => __DIR__ . '/../resources/icons/heroicons/outline'
        ],
        [
            'value' => 'fa-brands',
            'label' => 'Font Awesome brands',
            'path'  => __DIR__ . '/../resources/icons/fa/free/brands'
        ],
        [
            'value' => 'fa-regular',
            'label' => 'Font Awesome regular',
            'path'  => __DIR__ . '/../resources/icons/fa/free/regular'
        ],
        [
            'value' => 'fa-solid',
            'label' => 'Font Awesome solid',
            'path'  => __DIR__ . '/../resources/icons/fa/free/solid'
        ],
    ];

    protected static array $defaultIcons = [];

    protected static array $defaultIconSets = ['solid', 'outline', 'fa-brands', 'fa-regular', 'fa-solid'];
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
                self::registerGlobalIconSet(
                    $set['value'],
                    $set['label'],
                    $set['path']
                );
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

    public function onlyFaBrands()
    {
        return $this->only(['fa-brands']);
    }

    public function onlyFaSolid()
    {
        return $this->only(['fa-solid']);
    }

    public function onlyFaRegular()
    {
        return $this->only(['fa-regular']);
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
                $content = file_get_contents("$path/$file");
                $content = preg_replace('/<!--(.*?)-->/m', '', $content);
                $icons[] = [
                    'type'    => $key,
                    'name'    => strtolower(str_replace('.svg', '', $file)),
                    'content' => $content,
                ];
            }
        }

        return $icons;
    }

    public function only(array $sets)
    {
        $filteredIcons = [];
        foreach ($sets as $set) {
            $suportedSetKey = array_search($set, array_column(self::$supportedSets, 'value'));
            if (array_search($set, array_column($this->icons, 'value')) === false && $suportedSetKey !== false) {
                $supportedSet = self::$supportedSets[$suportedSetKey];
                $this->registerIconSet(
                    $supportedSet['value'],
                    $supportedSet['label'],
                    $supportedSet['path']
                );
            }
            foreach ($this->icons as $icon) {
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
