<?php

namespace stekel\Kodi\Methods;

use Illuminate\Support\Collection;
use stekel\Kodi\Exceptions\KodiConnectionFailed;
use stekel\Kodi\KodiAdapter;
use stekel\Kodi\KodiFacade;
use stekel\Kodi\Models\Player as PlayerModel;
use stekel\Kodi\Models\Episode;
use stekel\Kodi\Models\Song;

class Player {
    
    /**
     * KodiAdapter
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
     *
     * @param KodiAdapter $adapter
     */
    public function __construct(KodiAdapter $adapter) {
        
        $this->adapter = $adapter;
    }

    /**
     * Get active players
     *
     * @return Collection
     * @throws KodiConnectionFailed
     */
    public function getActivePlayers() {
    
        return collect($this->adapter->call($this->method.'.GetActivePlayers', []))->transform(function($item) {
            
            return new PlayerModel((object) $item, KodiFacade::getFacadeRoot());
        });
    }

    /**
     * Get players
     *
     * @return Collection
     * @throws KodiConnectionFailed
     */
    public function getPlayers() {
    
        return collect($this->adapter->call($this->method.'.GetPlayers', []))->transform(function($item) {
            
            return new PlayerModel((object) $item, KodiFacade::getFacadeRoot());
        });
    }

    /**
     * Open
     *
     * @param mixed $model
     * @return boolean
     * @throws KodiConnectionFailed
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
     * Go to (previous/next)
     *
     * @param PlayerModel $player
     * @param string $direction
     * @return boolean
     * @throws KodiConnectionFailed
     */
    public function goTo(PlayerModel $player, $direction) {
        
        if (!in_array($direction, ['previous', 'next'])) {
            
            return false;
        }
        
        $this->adapter->call($this->method.'.GoTo', [
            'playerid' => $player->id,
            'to' => $direction,
        ]);
        
        return true;
    }

    /**
     * Play/Pause
     *
     * @param PlayerModel $player
     * @return boolean
     * @throws KodiConnectionFailed
     */
    public function playPause(PlayerModel $player=null) {
    
        $this->adapter->call($this->method.'.PlayPause', [
            'playerid' => is_null($player) ? 1 : $player->id,
        ]);
        
        return true;
    }

    /**
     * Stop
     *
     * @param PlayerModel $player
     * @return boolean
     * @throws KodiConnectionFailed
     */
    public function stop(PlayerModel $player=null) {
        
        $this->adapter->call($this->method.'.Stop', [
            'playerid' => is_null($player) ? 1 : $player->id,
        ]);
        
        return true;
    }

    /**
     * Get the currently playing item
     *
     * @param PlayerModel $player
     * @return Episode|Song
     * @throws KodiConnectionFailed
     */
    public function getItem(PlayerModel $player=null) {
        
        $player = is_null($player) ? $this->getActivePlayers()->first() : $player;
        
        if ($player->type == 'audio') {
            
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