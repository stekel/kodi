<?php

namespace stekel\Kodi\Models;

class Playlist extends Model {
    
    /**
     * Type
     *
     * @var string
     */
    protected $type = 'playlist';
    
    /**
     * Attribute aliases
     *
     * @var array
     */
    protected $attributeAliases = [
        'id' => 'playlistid',
    ];
}