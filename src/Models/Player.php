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
    
        $this->kodi->player()->playPause();
    }
    
    /**
     * Next playlist item
     *
     * @return void
     */
    public function next() {
    
        $this->kodi->player()->goTo('next');
    }
    
    /**
     * Previous playlist item
     *
     * @return void
     */
    public function previous() {
    
        $this->kodi->player()->goTo('previous');
    }
}