<?php

namespace stekel\Kodi;

class KodiFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'stekel\Kodi\Kodi';
    }
}
