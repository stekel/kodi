<?php

namespace stekel\Kodi\Models;

class Artist extends Model {
    
    /**
     * Type
     *
     * @var string
     */
    protected $type = 'artist';
    
    /**
     * Attribute aliases
     *
     * @var array
     */
    protected $attributeAliases = [
        'id' => 'artistid',
    ];
}