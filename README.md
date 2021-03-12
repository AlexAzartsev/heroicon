# Heroicon Nova Field

A Laravel Nova Field for managing icons. [Heroicons](https://heroicons.com/) icons used by default. There is a
possability to use custom icon in svg format. Icon saved as svg html tag into db.

## Installation:

Use composer for installation. Laravel with nova required.

```bash
composer require alexazartsev/heroicon
```

## Usage:

Use it as regular nova field:

```php
use AlexAzartsev\Heroicon\Heroicon;

Heroicon::make('Icon');
```

To use or customize icon click on `Edit` button and just edit svg code if selected icon:

<img src="screenshots/custom_icon.gif" width="200">

## Support:

alex.azarsecond@gmail.com

## License:

The MIT License (MIT). Please see [License File](LICENSE) for more information.
