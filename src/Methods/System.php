<?php

namespace stekel\Kodi\Methods;

use stekel\Kodi\KodiAdapter;
use stekel\Kodi\Models\System as SystemModel;

class System {
    
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
    protected $method = 'XBMC';
    
    /**
     * Construct
     */
    public function __construct(KodiAdapter $adapter) {
        
        $this->adapter = $adapter;
    }
    
    /**
     * Get kodi system parameters
     *
     * @return Collection
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
}