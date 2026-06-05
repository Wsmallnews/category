# Wsmallnews system classify/category modules

[![Latest Version on Packagist](https://img.shields.io/packagist/v/wsmallnews/category.svg?style=flat-square)](https://packagist.org/packages/wsmallnews/category)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/wsmallnews/category/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/wsmallnews/category/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/wsmallnews/category/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/wsmallnews/category/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/wsmallnews/category.svg?style=flat-square)](https://packagist.org/packages/wsmallnews/category)

`wsmallnews/category` is a Filament category management package built on `wsmallnews/filament-nestedset`. It provides hierarchical categories, category type management, scopeable data, and optional Filament tenancy support.

## Features

- Category tree management powered by `kalnoy/nestedset` through `wsmallnews/filament-nestedset`
- Category type records for separating different category trees
- Scopeable category data via `scope_type` and `scope_id`
- Automatic tenant scoping when the current Filament panel supports tenancy
- Filament plugin registration for category pages and category type resources
- Frontend Livewire category tree component

## AI Guidelines

This package ships Laravel Boost AI Guidelines in `resources/boost/guidelines/core.blade.php`.

Install or enable Laravel Boost in your application, then refresh Boost resources so this package's guidelines are discovered and added to the project overview:

```bash
php artisan boost:update --discover
```

Boost updates the root `boost.json` and `CLAUDE.md` automatically. Check those files after running the command to confirm `wsmallnews/category` is included.

## Installation

You can install the package via composer:

```bash
composer require wsmallnews/category
```

The package provides an install command. By default it also installs its support dependency:

```bash
php artisan sn-category:install
```

To skip dependency installation and interactive prompts:

```bash
php artisan sn-category:install --no-deps --no-interaction
```

You can publish and run only the migrations with:

```bash
php artisan vendor:publish --tag="category-migrations"
php artisan migrate
```

You can publish only the config file with:

```bash
php artisan vendor:publish --tag="category-config"
```

Optionally, you can publish the views using:

```bash
php artisan vendor:publish --tag="category-views"
```

## Configuration

The package configuration lives in `config/sn-category.php`:

```php
return [
    'scopeable' => [
        'scope_type' => 'sn-category',
        'scope_id' => 0,
    ],

    'models' => [
        'category' => Wsmallnews\Category\Models\Category::class,
        'category_type' => Wsmallnews\Category\Models\CategoryType::class,
    ],

    'panel_register' => [
        'pages' => [
            Wsmallnews\Category\Filament\Pages\Category\CategoryPage::class,
        ],
        'resources' => [
            Wsmallnews\Category\Filament\Resources\CategoryTypes\CategoryTypeResource::class,
        ],
    ],
];
```

## Filament plugin

Register the plugin on your Filament panel:

```php
use Wsmallnews\Category\CategoryPlugin;

$panel
    ->plugin(CategoryPlugin::make());
```

`CategoryPlugin` registers the pages and resources configured in `sn-category.panel_register`:

- `Wsmallnews\Category\Filament\Pages\Category\CategoryPage`
- `Wsmallnews\Category\Filament\Resources\CategoryTypes\CategoryTypeResource`

## Usage

### Category page

`CategoryPage` extends `Wsmallnews\Category\Filament\Pages\Category\Base`, which extends `Wsmallnews\FilamentNestedset\Filament\Pages\NestedsetPage`.

The base page automatically resolves or creates a `CategoryType` for the configured scope, applies the type's level to the nestedset tree, and scopes category records by:

- `scope_type`
- `scope_id`
- `type_id`
- `team_id` when tenancy is enabled

### Custom category page

Create your own page by extending the base page:

```php
<?php

namespace App\Filament\Pages;

use Wsmallnews\Category\Filament\Pages\Category\Base;

class ProductCategories extends Base
{
    protected static ?string $slug = 'product-categories';

    protected static ?string $scopeType = 'product';

    protected static int $scopeId = 0;

    protected static ?int $level = 3;
}
```

### Category model

`Wsmallnews\Category\Models\Category` uses `Kalnoy\Nestedset\NodeTrait` and defines scope attributes for nestedset scoping:

```php
public function getScopeAttributes(): array
{
    return ['scope_type', 'scope_id', 'type_id', 'team_id'];
}
```

When tenancy is disabled, `team_id` is not added to the scope attributes.

### Frontend component

The service provider registers the category tree Livewire component as:

```blade
<livewire:sn-category-components-categories />
```

Use the component when you need a frontend category tree display that follows the package conventions.

## Namespace Quick Reference

| Category | Namespace |
| --- | --- |
| Plugin | `Wsmallnews\Category\CategoryPlugin` |
| ServiceProvider | `Wsmallnews\Category\CategoryServiceProvider` |
| Page Base | `Wsmallnews\Category\Filament\Pages\Category\Base` |
| Page Implementation | `Wsmallnews\Category\Filament\Pages\Category\CategoryPage` |
| Page Widget | `Wsmallnews\Category\Filament\Pages\Category\Widgets\Category` |
| Category Type Resource | `Wsmallnews\Category\Filament\Resources\CategoryTypes\CategoryTypeResource` |
| Category Model | `Wsmallnews\Category\Models\Category` |
| Category Type Model | `Wsmallnews\Category\Models\CategoryType` |
| Frontend Component | `Wsmallnews\Category\Livewire\Components\Categories` |
| Install Command | `Wsmallnews\Category\Commands\CategoryInstallCommand` |
| Utils | `Wsmallnews\Category\Support\Utils` |

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [smallnews](https://github.com/Wsmallnews)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
