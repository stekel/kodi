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