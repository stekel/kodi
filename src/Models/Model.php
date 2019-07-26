<?php

namespace stekel\Kodi\Models;

use stdClass;
use stekel\Kodi\Kodi;

class Model {
    
    /**
     * Kodi
     *
     * @var Kodi
     */
    protected $kodi;
    
    /**
     * Type
     *
     * @var string
     */
    protected $type = '';
    
    /**
     * Parameters
     *
     * @var array
     */
    protected $parameters = [];
    
    /**
     * Attribute aliases
     *
     * @var array
     */
    protected $attributeAliases = [];

    /**
     * Construct
     *
     * @param stdClass $parameters
     * @param Kodi     $kodi
     */
    public function __construct(\stdClass $parameters, Kodi $kodi=null) {
        
        $this->parameters = array_merge($this->parameters, (array) $parameters);

        $this->kodi = $kodi;
    }

    /**
     * Get the given parameter
     *
     * @param  string $param
     * @return string
     */
    public function __get($param) {
        
        if ($param == 'type' && $this->type != '') {
            
            return $this->type;
        }
        
        if ($this->hasAlias($param)) {
            
            return $this->parameters[$this->attributeAliases[$param]];
        }
        
        return $this->parameters[$param] ?? null;
    }
    
    /**
     * Return the parameter from the alias
     *
     * @param  string $alias
     * @return string
     */
    public function getParameter($alias='') {
    
        return ($this->hasAlias($alias)) ? $this->attributeAliases[$alias] : null;
    }
    
    /**
     * Attribute has an alias?
     *
     * @param  string  $param
     * @return boolean
     */
    private function hasAlias($param) {
        
        return isset($this->attributeAliases[$param]);
    }
}