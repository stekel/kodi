<?php

namespace stekel\Kodi\Methods;

use stekel\Kodi\KodiAdapter;
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
}