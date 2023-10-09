# Wsmallnews system classify/category modules

[![Latest Version on Packagist](https://img.shields.io/packagist/v/wsmallnews/category.svg?style=flat-square)](https://packagist.org/packages/wsmallnews/category)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/wsmallnews/category/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/wsmallnews/category/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/wsmallnews/category/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/wsmallnews/category/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/wsmallnews/category.svg?style=flat-square)](https://packagist.org/packages/wsmallnews/category)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require wsmallnews/category
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="category-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="category-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="category-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$category = new Wsmallnews\Category();
echo $category->echoPhrase('Hello, Wsmallnews!');
```

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
