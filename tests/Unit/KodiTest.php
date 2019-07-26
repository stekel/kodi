<?php

namespace stekel\Kodi\Tests\Unit;

use stekel\Kodi\Exceptions\KodiMethodClassNotFound;
use stekel\Kodi\Kodi;
use stekel\Kodi\Methods\Playlist;
use stekel\Kodi\Tests\Helpers\FakeKodi;
use stekel\Kodi\Tests\TestCase;
use stekel\Kodi\Exceptions\KodiConnectionFailed;

class KodiTest extends TestCase
{
    /** @test **/
    public function can_create_an_instance_from_the_static_create_method()
    {
        $this->assertEquals(Kodi::class, get_class(Kodi::connect()));
    }
    
    /** @test **/
    public function can_return_an_instance_of_fakekodi_for_testing()
    {
        $this->assertEquals(FakeKodi::class, get_class(Kodi::fake()));
    }
    
    /** @test **/
    public function can_throw_an_exception_if_unable_to_connect_to_a_kodi_instance()
    {
        $fakeKodi = Kodi::fake();
        
        $fakeKodi->createException('Request failed.');
        
        $kodi = $fakeKodi->bind();
        
        $this->expectException(KodiConnectionFailed::class);
        
        $kodi->player()->playPause();
    }

    /** @test * */
    public function can_return_a_valid_method_class()
    {
        $this->assertInstanceOf(Playlist::class, Kodi::fake()->bind()->playlist());
    }

    /** @test * */
    public function can_throw_an_exception_if_the_method_class_does_not_exist()
    {
        $this->expectException(KodiMethodClassNotFound::class);

        Kodi::fake()->bind()->unknownMethodCall();
    }
}
