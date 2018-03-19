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
| --------- | --------- |
| Addons.GetAddons | `$kodi->addons()->getAddons();` |
| Addons.ExecuteAddon : script.playrandomvideos | `$kodi->addons()->playRandom($model)`<br>$model can be a `TvShow` or `Song` |

### Gui
| JSON-RPC Function | Execution |
| --------- | --------- |
| GUI.ShowNotification | `$kodi->gui()->showNotification($title, $message);` |

### Player
| JSON-RPC Function | Execution |
| --------- | --------- |
| Player.GetActivePlayers | `$kodi->player()->getActivePlayers();`<br>Returns a collection of `Player` models |
| Player.Open | `$kodi->player()->open($model);`<br>$model can be an `Episode` or `Song` |
| Player.GoTo | `$kodi->player()->goto(Player $player, 'next');`<br>`$kodi->player()->goto(Player $player, 'previous');` |
| Player.PlayPause | `$kodi->player()->playPause(Player $player);` |
| Player.Stop | `$kodi->player()->stop(Player $player);` |
| Player.GetItem | `$kodi->player()->getItem(Player $player);`<br>Returns either an `Episode` or `Song` |

#### Player Model

For convenience, you can interact directly with the given `Player` model with the following commands.

| Function | Description |
| --------- | --------- |
| `$player->open(Model $model);` | Play the given media model (Movie, TvShow, etc.) |
| `$player->playPause();` | Play or pause the given player |
| `$player->stop();` | Stop the currently playing media |
| `$player->nowPlaying();` | Return the model of the currently playing media |
| `$player->next();` | Play the next item in the current playlist |
| `$player->previous();` | Play the previous item in the current playlist |


### Video Library
| JSON-RPC Function | Execution |
| --------- | --------- |
| VideoLibrary.Clean | `$kodi->videoLibrary()->clean();` |
| VideoLibrary.GetEpisodes | `$kodi->videoLibrary()->getEpisodes($tvShowId);` |
| VideoLibrary.GetRecentlyAddedEpisodes | `$kodi->videoLibrary()->recentlyAddedEpisodes($limit=3);` |
| VideoLibrary.GetTVShowDetails | `$kodi->videoLibrary()->getTVShowDetails(TvShow $tvshow);` |
| VideoLibrary.GetTVShows | `$kodi->videoLibrary()->getTvShows();` |
| VideoLibrary.GetMovies | `$kodi->videoLibrary()->getMovies(array $filter);` |

### System
| JSON-RPC Function | Execution |
| --------- | --------- |
| XBMC.GetInfoLabels | `$kodi->system()->getInfoLabels();` |
| Application.GetProperties | `$kodi->system()->getVolume();` |
| Application.SetVolume | `$kodi->system()->setVolume($volume);` |