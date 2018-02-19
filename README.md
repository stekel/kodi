# Kodi

This package provides a simple api for interacting with a running Kodi instance using the Kodi jsonrpc api.

## Install

Via Composer

``` bash
$ composer require stekel/kodi
```

## Usage

### Laravel

``` php
use Facades\stekel\Kodi\Kodi;

Kodi::player()->playPause(); // Play/Pause the currently playing media
```

### Manual

``` php
use stekel\Kodi\Kodi

$kodi = Kodi::connect('192.168.1.100', '8080', 'xbmc', 'xbmc');

$kodi->player()->playPause(); // Play/Pause the currently playing media
```

