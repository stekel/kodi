<?php

namespace stekel\Kodi\Models;

class Album extends Model {
    
    /**
     * Type
     *
     * @var string
     */
    protected $type = 'album';
    
    /**
     * Attribute aliases
     *
     * @var array
     */
    protected $attributeAliases = [
        'id' => 'albumid',
    ];
}