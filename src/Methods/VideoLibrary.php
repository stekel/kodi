<?php

namespace stekel\Kodi\Methods;

use stekel\Kodi\KodiAdapter;

class VideoLibrary {
    
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
    protected $method = 'VideoLibrary';
    
    /**
     * Construct
     */
    public function __construct(KodiAdapter $adapter) {
        
        $this->adapter = $adapter;
    }
    
    /**
     * Get all tv shows
     *
     * @return Collection
     */
    public function getTvShows() {
        
        $response = $this->adapter->call($this->method.'.GetTVShows', []);
        
        if (!isset($response->tvshows)) {
            
            return collect([]);
        }
        
        return collect($response->tvshows);
    }
    
    /**
     * Get all episodes from a tv show id
     *
     * @param  integer    $tvShowId
     * @return Collection
     */
    public function getEpisodes($tvShowId) {
        
        $response = $this->adapter->call($this->method.'.GetEpisodes', [
            'tvshowid' => $tvShowId,
            'properties' => [
                "title",
                "lastplayed",
            ],
        ]);
        
        if (!isset($response->episodes)) {
            
            return collect([]);
        }
        
        return collect($response->episodes);
    }
    
    /**
     * Get latest episodes from a given show
     *
     * @param  string     $showName
     * @param  integer    $limit
     * @return Collection
     */
    public function latestEpisodes($showName='', $limit=1) {
        
        $response = $this->adapter->call($this->method.'.GetRecentlyAddedEpisodes', [
            'limits' => [
                'end' => $limit
            ]
        ]);
        
        return collect($response->episodes);
    }
    
    /**
     * Get recently added episodes
     *
     * @param  integer $limit
     * @return Collection
     */
    public function recentlyAddedEpisodes($limit=3) {
        
        $response = $this->adapter->call($this->method.'.GetRecentlyAddedEpisodes', [
            'limits' => [
                'end' => $limit
            ]
        ]);
        
        return collect($response->episodes);
    }
    
    /**
     * Clean the video library
     *
     * @return [type]
     */
    public function clean() {
        
        $response = $this->adapter->call($this->method.'.Clean', [
            'showdialogs' => false
        ]);
        
        return ($response == 'OK') ? true : false;
    }
}