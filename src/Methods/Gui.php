<?php

namespace stekel\Kodi\Methods;

use stekel\Kodi\KodiAdapter;

class Gui {
    
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
    protected $method = 'GUI';
    
    /**
     * Construct
     */
    public function __construct(KodiAdapter $adapter) {
        
        $this->adapter = $adapter;
    }
    
    /**
     * Show notification
     *
     * @param  string     $title
     * @param  string     $message
     * @return void
     */
    public function showNotification($title, $message) {
    
        return $this->adapter->call($this->method.'.ShowNotification', [
            'title' => $title,
            'message' => $message,
        ]);
    }
}