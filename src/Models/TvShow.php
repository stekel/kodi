<?php

namespace stekel\Kodi\Models;

class TvShow extends Model {
    
    /**
     * Type
     *
     * @var string
     */
    protected $type = 'tvshow';
    
    /**
     * Attribute aliases
     *
     * @var array
     */
    protected $attributeAliases = [
        'id' => 'tvshowid',
    ];
}