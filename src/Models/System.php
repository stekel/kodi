<?php

namespace stekel\Kodi\Models;

class System extends Model {
    
    /**
     * Type
     *
     * @var string
     */
    protected $type = 'system';
    
    /**
     * Attribute aliases
     *
     * @var array
     */
    protected $attributeAliases = [
        'buildDate' => 'System.BuildDate',
        'buildVersion' => 'System.BuildVersion',
        'friendlyName' => 'System.FriendlyName',
    ];
}