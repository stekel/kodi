<?php

namespace stekel\Kodi\Models;

class Episode extends Model {
    
    /**
     * Type
     *
     * @var string
     */
    protected $type = 'episode';
    
    /**
     * Attribute aliases
     *
     * @var array
     */
    protected $attributeAliases = [
        'id' => 'episodeid',
    ];
}