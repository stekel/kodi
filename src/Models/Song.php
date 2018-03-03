<?php

namespace stekel\Kodi\Models;

class Song extends Model {
    
    /**
     * Type
     *
     * @var string
     */
    protected $type = 'song';
    
    /**
     * Attribute aliases
     *
     * @var array
     */
    protected $attributeAliases = [
        'id' => 'songid',
    ];
}