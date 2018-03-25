<?php

namespace stekel\Kodi\Methods;

use stekel\Kodi\Kodi;
use stekel\Kodi\KodiAdapter;
use stekel\Kodi\Models\System as SystemModel;
use stekel\Kodi\Models\Volume;

class System {
    
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
    protected $method = 'XBMC';
    
    /**
     * Construct
     */
    public function __construct(Kodi $kodi) {
        
        $this->kodi = $kodi;
    }
    
    /**
     * Get kodi system parameters
     *
     * @return Collection
     */
    public function getInfoLabels() {
        
        try {
            
            $response = $this->kodi->adapter()->call($this->method.'.GetInfoLabels', [
                'labels' => [
                    'System.BuildVersion',
                    'System.BuildDate',
                    'System.FriendlyName',
                    'Network.IPAddress',
                ]
            ]);
        } catch (\Exception $e) {
            
            return new SystemModel((object) [
                'System.BuildVersion' => null,
                'System.BuildDate' => null,
                'System.FriendlyName' => null,
                'Network.IPAddress' => null,
            ]);
        }
        
        return new SystemModel($response);
    }
    
    /**
     * Get volume
     *
     * @return Volume
     */
    public function getVolume() {
    
        $result = $this->kodi->adapter()->call('Application.GetProperties', [
            'properties' => [
                'volume'
            ]
        ]);
        
        return new Volume($result, $this->kodi);
    }
    
    /**
     * Set volume
     *
     * @param  integer $volume
     * @return integer
     */
    public function setVolume($volume) {
    
        return $this->kodi->adapter()->call('Application.SetVolume', [
            'volume' => $volume
        ]);
    }
}