<?php

namespace stekel\Kodi\Methods;

use stekel\Kodi\KodiAdapter;
use stekel\Kodi\Models\Episode;
use stekel\Kodi\Models\TvShow;

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
        
        if (!isset($response->tvshows) || empty($response->tvshows)) {
            
            return collect([]);
        }
        
        return collect($response->tvshows)->transform(function($tvshow) {
            
            return new TvShow($tvshow);
        });
    }
    
    /**
     * Get tv show details
     *
     * @param  TvShow $tvShow
     * @return TvShow
     */
    public function getTVShowDetails(TvShow $tvShow) {
        
        $response = $this->adapter->call($this->method.'.GetTVShowDetails', [
            'tvshowid' => $tvShow->id,
            'properties' => [
                "dateadded",
                "episode",
                "lastplayed",
                "playcount",
                "season",
                "title",
                "watchedepisodes",
                "year",
            ]
        ]);
        
        return new TvShow($response->tvshowdetails);
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
        
        if (!isset($response->episodes) || empty($response->episodes)) {
            
            return collect([]);
        }
        
        return collect($response->episodes)->transform(function($episode) {
            
            return new Episode($episode);
        });
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
        
        return collect($response->episodes)->transform(function($episode) {
            
            return new Episode($episode);
        });
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
        
        return ($response == "OK") ? true : false;
    }
}