<?php

namespace stekel\Kodi\Methods;

use stekel\Kodi\Models\Addon;
use stekel\Kodi\KodiAdapter;

class Addons {
    
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
    protected $method = 'Addons';
    
    /**
     * Construct
     */
    public function __construct(KodiAdapter $adapter) {
        
        $this->adapter = $adapter;
    }
    
    /**
     * Get addons
     *
     * @return Collection
     */
    public function getAddons() {
    
        $response = $this->adapter->call($this->method.'.GetAddons', []);
        
        if (!isset($response->addons)) {
            
            return collect([]);
        }
        
        return collect($response->addons)->transform(function($addon) {
            
            return new Addon($addon);
        });
    }
    
    /**
     * Play a random show|song
     *
     * @param  mixed $model
     * @return boolean
     */
    public function playRandom($model) {
    
        return $this->adapter->call($this->method.'.ExecuteAddon', [
            'addonid' => 'script.playrandomvideos',
            'params' => [
                'videodb://tvshows/titles/'.$model->title,
            ]
        ]);
    }
}