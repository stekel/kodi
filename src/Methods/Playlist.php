<?php

namespace stekel\Kodi\Methods;

use Illuminate\Support\Collection;
use stekel\Kodi\KodiAdapter;
use stekel\Kodi\Models\Playlist as PlaylistModel;
use stekel\Kodi\Models\Song;

class Playlist {
    
    /**
     * Kodi adapter
     *
     * @var KodiAdapter
     */
    protected $adapter;
    
    /**
     * Method
     *
     * @var string
     */
    protected $method = 'Playlist';
    
    /**
     * Construct
     */
    public function __construct(KodiAdapter $adapter) {
        
        $this->adapter = $adapter;
    }

    /**
     * Get all playlists
     *
     * @return Collection
     * @throws \stekel\Kodi\Exceptions\KodiConnectionFailed
     */
    public function all() {
        
        $response = $this->adapter->call($this->method.'.GetPlaylists', [
            'properties' => [
                "name",
            ],
        ]);
        
        if (!isset($response->playlists) || empty($response->playlists)) {
            
            return collect([]);
        }
        
        return collect($response->playlists)->transform(function($playlist) {
            
            return new PlaylistModel($playlist);
        });
    }

    /**
     * Get playlist items
     *
     * @param PlaylistModel $playlist
     * @return Collection
     * @throws \stekel\Kodi\Exceptions\KodiConnectionFailed
     */
    public function getItems(PlaylistModel $playlist) {

        $response = $this->adapter->call($this->method.'.GetItems', [
            'playlistid' => $playlist->id,
        ]);

        if (!isset($response->items) || empty($response->items)) {

            return collect([]);
        }

        return collect($response->items)->transform(function($song) {

            return new Song($song);
        });
    }
}