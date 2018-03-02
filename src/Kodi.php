<?php

namespace stekel\Kodi;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Collection;
use stekel\Exceptions\KodiFunctionNotFound;
use stekel\Kodi\KodiAdapter;
use stekel\Kodi\Methods\Addons;
use stekel\Kodi\Methods\Gui;
use stekel\Kodi\Methods\Player;
use stekel\Kodi\Methods\VideoLibrary;

class Kodi {
    
    /**
     * Kodi adapter
     *
     * @var KodiAdapter
     */
    protected $adapter;
    
    /**
     * Construct
     */
    public function __construct(KodiAdapter $adapter) {
        
        $this->adapter = $adapter;
    }
    
    /**
     * Connect
     *
     * @param  string $host
     * @param  string $port
     * @param  string $username
     * @param  string $password
     * @return Kodi
     */
    public static function connect($host='127.0.0.1', $port='8080', $username='xbmc', $password='xbmc') {
    
        return new Kodi(
            new KodiAdapter(
                new GuzzleClient([
                    'base_uri' => 'http://'.$host.':'.$port.'/',
                    'timeout'  => 2.0,
                ])
            )
        );
    }
    
    public function addons() {
    
        return new Addons($this->adapter);
    }
    
    /**
     * Player functions
     *
     * @return Player
     */
    public function player() {
        
        return new Player($this->adapter);
    }
    
    /**
     * Video library functions
     *
     * @return VideoLibrary
     */
    public function videoLibrary() {
    
        return new VideoLibrary($this->adapter);
    }
    
    /**
     * Gui functions
     *
     * @return Gui
     */
    public function gui() {
    
        return new Gui($this->adapter);
    }
}