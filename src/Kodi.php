<?php

namespace stekel\Kodi;

use GuzzleHttp\Client as GuzzleClient;
use stekel\Kodi\Methods\Addons;
use stekel\Kodi\Methods\AudioLibrary;
use stekel\Kodi\Methods\Gui;
use stekel\Kodi\Methods\Player;
use stekel\Kodi\Methods\Playlist;
use stekel\Kodi\Methods\System;
use stekel\Kodi\Methods\VideoLibrary;
use stekel\Kodi\Tests\Helpers\FakeKodi;

class Kodi
{
    /**
     * Kodi adapter
     *
     * @var KodiAdapter
     */
    protected $adapter;

    /**
     * Method factory
     *
     * @var MethodFactory
     */
    protected $methodFactory;

    /**
     * Construct
     */
    public function __construct(KodiAdapter $adapter, MethodFactory $methodFactory)
    {
        $this->adapter = $adapter;
        $this->methodFactory = $methodFactory;
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
    public static function connect($host='127.0.0.1', $port='8080', $username='xbmc', $password='xbmc')
    {
        $kodiAdapter = new KodiAdapter(
            new GuzzleClient([
                'base_uri' => 'http://'.$host.':'.$port.'/',
                'timeout'  => 2.0,
            ])
        );

        return new Kodi($kodiAdapter, new MethodFactory($kodiAdapter));
    }
    
    /**
     * Return the adapter
     *
     * @return KodiAdapter
     */
    public function adapter()
    {
        return $this->adapter;
    }
    
    /**
     * Return an instance of FakeKodi
     *
     * @return FakeKodi
     */
    public static function fake()
    {
        return new FakeKodi();
    }

    /**
     * Handle missing function calls
     * 
     * @param $method
     * @param $parameters
     * @return mixed
     * @throws Exceptions\KodiMethodClassNotFound
     */
    public function __call($method, $parameters) {

        return $this->methodFactory->fromMethodSlug($method);
    }
}
