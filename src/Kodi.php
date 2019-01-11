<?php

namespace stekel\Kodi;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Collection;
use stekel\Exceptions\KodiFunctionNotFound;
use stekel\Kodi\KodiAdapter;
use stekel\Kodi\Methods\Addons;
use stekel\Kodi\Methods\Application;
use stekel\Kodi\Methods\Gui;
use stekel\Kodi\Methods\Player;
use stekel\Kodi\Methods\System;
use stekel\Kodi\Methods\AudioLibrary;
use stekel\Kodi\Methods\VideoLibrary;
use stekel\Kodi\Tests\Helpers\FakeKodi;

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
    
    /**
     * Return the adapter
     *
     * @return KodiAdapter
     */
    public function adapter() {
    
        return $this->adapter;
    }
    
    /**
     * Return an instance of FakeKodi
     *
     * @return FakeKodi
     */
    public static function fake() {
    
        return new FakeKodi();
    }
    
    /**
     * Add-on functions
     *
     * @return Addons
     */
    public function addons() {
    
        return new Addons($this->adapter);
    }
    
    /**
     * Player functions
     *
     * @return Player
     */
    public function player() {
        
        return new Player($this);
    }
    
    /**
     * Audio library functions
     *
     * @return AudioLibrary
     */
    public function audioLibrary() {
    
        return new AudioLibrary($this->adapter);
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
    
    /**
     * System functions
     *
     * @return System
     */
    public function system() {
    
        return new System($this);
    }
}