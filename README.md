# Envbar

Envbar is a simple package that will add a colored bar to the top of your pages to indicate the current environment and
the current branch or release.

## Installation

To install this package is very simple, you just need to install it with [composer](https://getcomposer.org/):

```bash
composer require --dev envbar/envbar
```

After installing, you need to publish the assets and the configuration file:

```bash
php artisan vendor:publish --provider="DeeRig\EnvBar\EnvBarServiceProvider" --tag="assets"
php artisan vendor:publish --provider="DeeRig\EnvBar\EnvBarServiceProvider" --tag="config"
```

## Configuration

The configuration file is located in `config/envbar.php`. All the necessary instructions are in the file.

