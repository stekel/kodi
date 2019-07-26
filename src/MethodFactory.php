<?php

namespace stekel\Kodi;

use stekel\Kodi\Exceptions\KodiMethodClassNotFound;

class MethodFactory {

    /**
     * @var KodiAdapter
     */
    private $adapter;

    /**
     * Constructor
     *
     * @param KodiAdapter $adapter
     */
    public function __construct(KodiAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Return method class from slug
     *
     * @param $slug
     * @return mixed
     * @throws KodiMethodClassNotFound
     */
    public function fromMethodSlug($slug)
    {
        $methodClassName = 'stekel\Kodi\Methods\\'.ucfirst($slug);

        if (! class_exists($methodClassName)) {
            throw new KodiMethodClassNotFound();
        }

        return new $methodClassName($this->adapter);
    }
}