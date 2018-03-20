<?php

namespace stekel\Kodi\Models;

class Player extends Model {
    
    /**
     * Attribute aliases
     *
     * @var array
     */
    protected $attributeAliases = [
        'id' => 'playerid',
    ];
    
    /**
     * Open the given media on this player
     *
     * @param  Model $media
     * @return void
     */
    public function open(Model $media) {
    
        $this->kodi->player()->open($media);
    }
    
    /**
     * Play/Pause
     *
     * @return void
     */
    public function playPause() {
    
        $this->kodi->player()->playPause($this);
    }
    
    /**
     * Stop
     *
     * @return void
     */
    public function stop() {
    
        $this->kodi->player()->stop($this);
    }
    
    /**
     * Return the currently playing item
     *
     * @return Model
     */
    public function nowPlaying() {
    
        return $this->kodi->player()->getItem($this);
    }
    
    /**
     * Next playlist item
     *
     * @return void
     */
    public function next() {
    
        $this->kodi->player()->goTo($this, 'next');
    }
    
    /**
     * Previous playlist item
     *
     * @return void
     */
    public function previous() {
    
        $this->kodi->player()->goTo($this, 'previous');
    }
}