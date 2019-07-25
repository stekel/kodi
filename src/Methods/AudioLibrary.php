<?php

namespace stekel\Kodi\Methods;

use stekel\Kodi\KodiAdapter;
use stekel\Kodi\Models\Album;
use stekel\Kodi\Models\Artist;

class AudioLibrary {
    
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
    protected $method = 'AudioLibrary';
    
    /**
     * Construct
     */
    public function __construct(KodiAdapter $adapter) {
        
        $this->adapter = $adapter;
    }
    
    /**
     * Get Artists
     *
     * @param  array $filter
     * @return Collection
     */
    public function getArtists($filter=[]) {
        
        if (isset($filter['title'])) {
            
            $filter = [
                'field' => 'title',
                'operator' => 'is',
                'value' => $filter['title'],
            ];
        }
        
        $response = $this->adapter->call($this->method.'.GetArtists', [
            'filter' => $filter,
            'properties' => [
                "title",
                "lastplayed",
            ],
        ]);
        
        if (!isset($response->artists) || empty($response->artists)) {
            
            return collect([]);
        }
        
        return collect($response->artists)->transform(function($artist) {
            
            return new Artist($artist);
        });
    }

    /**
     * Get all albums on an artist
     *
     * @param Artist $artist
     * @return Collection
     * @throws \stekel\Kodi\Exceptions\KodiConnectionFailed
     */
    public function getAlbumsByArtist(Artist $artist) {

        $response = $this->adapter->call($this->method.'.GetAlbums', [
            'filter' => [
                'field' => 'artistid',
                'operator' => 'is',
                'value' => $artist->id,
            ],
        ]);

        if (!isset($response->albums) || empty($response->albums)) {

            return collect([]);
        }

        return collect($response->albums)->transform(function($album) {

            return new Album($album);
        });
    }
}