<?php

namespace stekel\Kodi\Models;

use stekel\Kodi\Kodi;

class Volume extends Model {
    
    /**
     * Kodi
     *
     * @var Kodi
     */
    protected $kodi;
    
    /**
     * Construct
     *
     * @param stdClass $parameters
     * @param Kodi     $kodi
     */
    public function __construct(\stdClass $parameters, Kodi $kodi) {
        
        parent::__construct($parameters);
        
        $this->kodi = $kodi;
    }
    
    /**
     * Get current volume
     *
     * @return integer
     */
    public function current() {
    
        return $this->volume;
    }
    
    /**
     * Increase the volume
     *
     * @param  integer $increment
     * @return Volume
     */
    public function increaseBy($increment=0) {
        
        $volume = $this->volume + $increment;
        
        if ($volume > 100) {
            
            $volume = 100;
        }
        
        $this->kodi->system()->setVolume($volume);
        
        $this->parameters['volume'] = $volume;
        
        return $this;
    }
    
    /**
     * Decrease the volume
     *
     * @param  integer $decrement
     * @return Volume
     */
    public function decreaseBy($decrement=0) {
        
        $volume = $this->volume - $decrement;
        
        if ($volume < 0) {
            
            $volume = 0;
        }
        
        $this->kodi->system()->setVolume($volume);
        
        $this->parameters['volume'] = $volume;
        
        return $this;
    }
}