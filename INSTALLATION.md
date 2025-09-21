# Installation Guide for Filament Flexible Content

## Publishing to Packagist (Recommended)

1. Create an account on [Packagist.org](https://packagist.org)
2. Submit your package: https://packagist.org/packages/submit
3. Enter your GitHub repository URL: `https://github.com/iamgerwin/filament-flexible-content`
4. Once approved, users can install with:
   ```bash
   composer require iamgerwin/filament-flexible-content
   ```

## Installing from GitHub (Before Packagist Publication)

In your Laravel project (not this package directory), add the repository to your `composer.json`:

```bash
# In your Laravel project directory
composer config repositories.filament-flexible-content vcs https://github.com/iamgerwin/filament-flexible-content

# Then install the package
composer require iamgerwin/filament-flexible-content:dev-main
```

## Local Development Testing

To test this package locally in a Laravel project:

1. In your Laravel project's `composer.json`, add:
   ```json
   "repositories": [
       {
           "type": "path",
           "url": "../path/to/filament-flexible-content-skeleton/filament-flexible-content"
       }
   ],
   ```

2. Then require the package:
   ```bash
   composer require iamgerwin/filament-flexible-content:@dev
   ```

## Requirements

- PHP ^8.2
- Laravel ^11.0 or ^12.0
- Filament ^4.0

## After Installation

1. Publish the config:
   ```bash
   php artisan vendor:publish --tag="filament-flexible-content-config"
   ```

2. Publish migrations (if needed):
   ```bash
   php artisan vendor:publish --tag="filament-flexible-content-migrations"
   php artisan migrate
   ```