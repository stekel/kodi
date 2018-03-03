<?php

namespace stekel\Kodi\Models;

use stdClass;

class Model {
    
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
     */
    public function __construct(stdClass $parameters) {
        
        $this->parameters = array_merge($this->parameters, (array) $parameters);
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