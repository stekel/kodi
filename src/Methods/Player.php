<?php

namespace stekel\Kodi\Methods;

use stekel\Kodi\Kodi;
use stekel\Kodi\Models\Player as PlayerModel;
use stekel\Kodi\Models\Episode;
use stekel\Kodi\Models\Song;

class Player {
    
    /**
     * Kodi
     *
     * @var Kodi
     */
    protected $kodi;
    
    /**
     * Method
     *
     * @var string
     */
    protected $method = 'Player';
    
    /**
     * Construct
     */
    public function __construct(Kodi $kodi) {
        
        $this->kodi = $kodi;
    }
    
    /**
     * Get active players
     *
     * @return Collection
     */
    public function getActivePlayers() {
    
        return collect($this->kodi->adapter()->call($this->method.'.GetActivePlayers', []))->transform(function($item) {
            
            return new PlayerModel((object) $item, $this->kodi);
        });
    }
    
    /**
     * Get players
     *
     * @return Collection
     */
    public function getPlayers() {
    
        return collect($this->kodi->adapter()->call($this->method.'.GetPlayers', []))->transform(function($item) {
            
            return new PlayerModel((object) $item, $this->kodi);
        });
    }
    
    /**
     * Open
     *
     * @param  mixed   $model
     * @return boolean
     */
    public function open($model) {
    
        $this->kodi->adapter()->call($this->method.'.Open', [
            'item' => [
                $model->getParameter('id') => $model->id,
            ],
        ]);
        
        return true;
    }
    
    /**
     * Go to (previous/next)
     *
     * @param  PlayerModel  $player
     * @param  string  $direction
     * @return boolean
     */
    public function goTo(PlayerModel $player, $direction) {
        
        if (!in_array($direction, ['previous', 'next'])) {
            
            return false;
        }
        
        $this->kodi->adapter()->call($this->method.'.GoTo', [
            'playerid' => $player->id,
            'to' => $direction,
        ]);
        
        return true;
    }
    
    /**
     * Play/Pause
     *
     * @param  PlayerModel $player
     * @return boolean
     */
    public function playPause(PlayerModel $player=null) {
    
        $this->kodi->adapter()->call($this->method.'.PlayPause', [
            'playerid' => is_null($player) ? 1 : $player->id,
        ]);
        
        return true;
    }
    
    /**
     * Stop
     *
     * @param  PlayerModel $player
     * @return boolean
     */
    public function stop(PlayerModel $player=null) {
        
        $this->kodi->adapter()->call($this->method.'.Stop', [
            'playerid' => is_null($player) ? 1 : $player->id,
        ]);
        
        return true;
    }
    
    /**
     * Get the currently playing item
     *
     * @param  PlayerModel $player
     * @return array
     */
    public function getItem(PlayerModel $player=null) {
        
        $player = is_null($player) ? $this->getActivePlayers()->first() : $player;
        
        if ($player->type == 'audio') {
            
            $response = $this->kodi->adapter()->call($this->method.'.GetItem', [
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
        
        $response = $this->kodi->adapter()->call($this->method.'.GetItem', [
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