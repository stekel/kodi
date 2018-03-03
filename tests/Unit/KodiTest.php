<?php

namespace stekel\Kodi\Tests\Unit;

use stekel\Kodi\Kodi;
use stekel\Kodi\Tests\Helpers\FakeKodi;
use stekel\Kodi\Tests\TestCase;

class KodiTest extends TestCase {
    
    /** @test **/
    public function can_create_an_instance_from_the_static_create_method() {
        
        $this->assertEquals(Kodi::class, get_class(Kodi::connect()));
    }
    
    /** @test **/
    public function can_return_an_instance_of_fakekodi_for_testing() {
        
        $this->assertEquals(FakeKodi::class, get_class(Kodi::fake()));
    }
}