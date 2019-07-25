<?php

namespace stekel\Kodi\Methods;

use stekel\Kodi\KodiAdapter;
use stekel\Kodi\Models\Playlist as PlaylistModel;

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
                "title",
            ],
        ]);
        
        if (!isset($response->playlists) || empty($response->playlists)) {
            
            return collect([]);
        }
        
        return collect($response->playlists)->transform(function($playlist) {
            
            return new PlaylistModel($playlist);
        });
    }
}