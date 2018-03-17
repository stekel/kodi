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
| JSON-RPC Function | Execution |
| -------- | --------- |
| Addons.GetAddons | `$kodi->addons()->getAddons();` |
| Addons.ExecuteAddon : script.playrandomvideos | `$kodi->addons()->playRandom($model)`<br>$model can be a `TvShow` or `Song` |

### Gui
| JSON-RPC Function | Execution |
| -------- | --------- |
| GUI.ShowNotification | `$kodi->gui()->showNotification($title, $message);` |

### Player
| JSON-RPC Function | Execution |
| -------- | --------- |
| Player.GetActivePlayers | `$kodi->player()->getActivePlayers();` |
| Player.Open | `$kodi->player()->open($model);`<br>$model can be an `Episode` or `Song` |
| Player.GoTo | `$kodi->player()->goto($player, 'next');`<br>`$kodi->player()->goto($player, 'previous');`<br>$player is a `Player` model |
| Player.PlayPause | `$kodi->player()->playPause();` |
| Player.Stop | `$kodi->player()->stop();` |
| Player.GetItem | `$kodi->player()->getItem();`<br>Returns either an `Episode` or `Song` |

### Video Library
| JSON-RPC Function | Execution |
| -------- | --------- |
| VideoLibrary.Clean | `$kodi->videoLibrary()->clean();` |
| VideoLibrary.GetEpisodes | `$kodi->videoLibrary()->getEpisodes($tvShowId);` |
| VideoLibrary.GetRecentlyAddedEpisodes | `$kodi->videoLibrary()->recentlyAddedEpisodes($limit=3);` |
| VideoLibrary.GetTVShowDetails | `$kodi->videoLibrary()->getTVShowDetails(TvShow $tvshow);` |
| VideoLibrary.GetTVShows | `$kodi->videoLibrary()->getTvShows();` |
| VideoLibrary.GetMovies | `$kodi->videoLibrary()->getMovies(array $filter);` |

### System
| JSON-RPC Function | Execution |
| -------- | --------- |
| XBMC.GetInfoLabels | `$kodi->system()->getInfoLabels();` |
| Application.GetProperties | `$kodi->system()->getVolume();` |
| Application.SetVolume | `$kodi->system()->setVolume($volume);` |