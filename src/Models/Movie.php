<?php

namespace stekel\Kodi\Models;

class Movie extends Model {
    
    /**
     * Type
     *
     * @var string
     */
    protected $type = 'movie';
    
    /**
     * Attribute aliases
     *
     * @var array
     */
    protected $attributeAliases = [
        'id' => 'movieid',
    ];
}