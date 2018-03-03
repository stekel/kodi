<?php

namespace stekel\Kodi\Models;

class Addon extends Model {
    
    /**
     * Type
     *
     * @var string
     */
    protected $type = 'addon';
    
    /**
     * Attribute aliases
     *
     * @var array
     */
    protected $attributeAliases = [
        'id' => 'addonid',
    ];
}