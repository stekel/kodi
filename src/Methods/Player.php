<?php

namespace stekel\Kodi\Methods;

use stekel\Kodi\KodiAdapter;
use stekel\Kodi\Models\Player as PlayerModel;
use stekel\Kodi\Models\Episode;
use stekel\Kodi\Models\Song;

class Player {
    
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
    protected $method = 'Player';
    
    /**
     * Construct
     */
    public function __construct(KodiAdapter $adapter) {
        
        $this->adapter = $adapter;
    }
    
    /**
     * Get active players
     *
     * @return Collection
     */
    public function getActivePlayers() {
    
        return collect($this->adapter->call($this->method.'.GetActivePlayers', []))->transform(function($item) {
            
            return new PlayerModel($item);
        });
    }
    
    /**
     * Open
     *
     * @param  mixed   $model
     * @return boolean
     */
    public function open($model) {
    
        $this->adapter->call($this->method.'.Open', [
            'item' => [
                $model->getParameter('id') => $model->id,
            ],
        ]);
        
        return true;
    }
    
    /**
     * Play/Pause
     *
     * @return boolean
     */
    public function playPause() {
    
        $this->adapter->call($this->method.'.PlayPause', [
            'playerid' => 1
        ]);
        
        return true;
    }
    
    /**
     * Stop
     *
     * @return boolean
     */
    public function stop() {
    
        $this->adapter->call($this->method.'.Stop', [
            'playerid' => 1
        ]);
        
        return true;
    }
    
    /**
     * Get the currently playing item
     *
     * @return array
     */
    public function getItem() {
        
        $activePlayer = $this->getActivePlayers()->first();
        
        if ($activePlayer->type == 'audio') {
            
            $response = $this->adapter->call($this->method.'.GetItem', [
                'playerid' => 1,
                'properties' => [
                    "title",
                    "album",
                    "artist",
                    "duration"
                ]
            ]);
            
            return new Song($response[0]->item);
        }
        
        $response = $this->adapter->call($this->method.'.GetItem', [
            'playerid' => 1,
            'properties' => [
                "title",
                "season",
                "episode",
                "duration",
                "showtitle",
                "tvshowid",
            ]
        ]);
        
        return new Episode($response[0]->item);
    }
}