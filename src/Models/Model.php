<?php

namespace stekel\Kodi\Models;

use stdClass;

class Model {
    
    /**
     * Parameters
     *
     * @var stdClass
     */
    protected $parameters;
    
    /**
     * Attributes
     *
     * @var array
     */
    protected $attributes = [];
    
    /**
     * Construct
     *
     * @param stdClass $parameters
     */
    public function __construct(stdClass $parameters) {
        
        $this->parameters = $parameters;
    }
    
    /**
     * Get the given parameter
     *
     * @param  string $param
     * @return string
     */
    public function __get($param) {
        
        if (isset($this->attributes[$param])) {
            
            $parameter = $this->attributes[$param];
            
            return $this->parameters->$parameter;
        }
        
        return $this->parameters->$param ?? null;
    }
}