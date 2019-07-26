<?php

namespace stekel\Kodi\Methods;

use stekel\Kodi\KodiFacade as Kodi;
use stekel\Kodi\Exceptions\KodiConnectionFailed;
use stekel\Kodi\KodiAdapter;
use stekel\Kodi\Models\System as SystemModel;
use stekel\Kodi\Models\Volume;

class System {
    
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
    protected $method = 'XBMC';

    /**
     * Construct
     *
     * @param KodiAdapter $adapter
     */
    public function __construct(KodiAdapter $adapter) {
        
        $this->adapter = $adapter;
    }

    /**
     * Get kodi system parameters
     *
     * @return SystemModel
     * @throws KodiConnectionFailed
     */
    public function getInfoLabels() {
        
        $response = $this->adapter->call($this->method.'.GetInfoLabels', [
            'labels' => [
                'System.BuildVersion',
                'System.BuildDate',
                'System.FriendlyName',
                'Network.IPAddress',
            ]
        ]);
        
        return new SystemModel($response);
    }

    /**
     * Get volume
     *
     * @return Volume
     * @throws KodiConnectionFailed
     */
    public function getVolume() {
    
        $result = $this->adapter->call('Application.GetProperties', [
            'properties' => [
                'volume'
            ]
        ]);
        
        return new Volume($result, Kodi::getFacadeRoot());
    }

    /**
     * Set volume
     *
     * @param integer $volume
     * @return integer
     * @throws KodiConnectionFailed
     */
    public function setVolume($volume) {
    
        return $this->adapter->call('Application.SetVolume', [
            'volume' => $volume
        ]);
    }
}