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
use Kodi;

Kodi::player()->playPause(); // Play/Pause the currently playing media
```

### Manual

``` php
use stekel\Kodi\Kodi

$kodi = Kodi::connect('192.168.1.100', '8080', 'xbmc', 'xbmc');

$kodi->player()->playPause(); // Play/Pause the currently playing media
```

## Supported Functions

### Add-ons
| Function | Execution |
| -------- | --------- |
| Addons.GetAddons | `$kodi->addons()->getAddons();` |
| Addons.ExecuteAddon : script.playrandomvideos | `$kodi->addons()->playRandom($model)`<br>$model can be a `TvShow` or `Song` |

### Gui
| Function | Execution |
| -------- | --------- |
| GUI.ShowNotification | `$kodi->gui()->showNotification($title, $message);` |

### Player
| Function | Execution |
| -------- | --------- |
| Player.GetActivePlayers | `$kodi->player()->getActivePlayers();` |
| Player.Open | `$kodi->player()->open($model);`<br>$model can be an `Episode` or `Song` |
| Player.PlayPause | `$kodi->player()->playPause();` |
| Player.Stop | `$kodi->player()->stop();` |
| Player.GetItem | `$kodi->player()->getItem();`<br>Returns either an `Episode` or `Song` |

### Video Library
| Function | Execution |
| -------- | --------- |
| VideoLibrary.GetTVShows | `$kodi->videoLibrary()->getTvShows();` |
| VideoLibrary.GetTVShowDetails | `$kodi->videoLibrary()->getTVShowDetails(TvShow $tvshow);` |
| VideoLibrary.GetEpisodes | `$kodi->videoLibrary()->getEpisodes($tvShowId);` |
| VideoLibrary.GetRecentlyAddedEpisodes | `$kodi->videoLibrary()->recentlyAddedEpisodes($limit=3);` |
| VideoLibrary.Clean | `$kodi->videoLibrary()->clean();` |