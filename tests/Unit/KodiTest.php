<?php

namespace stekel\Kodi\Tests;

use stekel\Kodi\Kodi;
use stekel\Kodi\Tests\TestCase;

class KodiTest extends TestCase {
    
    /** @test **/
    public function can_create_an_instance_from_the_static_create_method() {
        
        $this->assertEquals(Kodi::class, get_class(Kodi::connect()));
    }
}