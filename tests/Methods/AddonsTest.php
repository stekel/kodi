<?php

namespace stekel\Kodi\Tests\Methods;

use stekel\Kodi\Kodi;
use stekel\Kodi\Tests\TestCase;
use stekel\Kodi\Models\Addon;
use stekel\Kodi\Models\TvShow;

class AddonsTest extends TestCase {
    
    /** @test **/
    public function can_get_addons() {
        
        $kodi = $this->fakeKodi->createResponse([
            'addons' => [
                $this->fakeAddon(),
                $this->fakeAddon(),
                $this->fakeAddon()
            ],
            'limits' => (object) [
                'end' => 3,
                'start' => 0,
                'total' => 3
            ]
        ])->bind();
        
        $addons = $kodi->addons()->getAddons();
        
        $this->assertCount(3, $addons);
        $this->assertEquals(Addon::class, get_class($addons->first()));
    }
    
    /** @test **/
    public function can_execute_the_random_episode_addon() {
    
        $kodi = $this->fakeKodi->createResponse("OK")->bind();
        
        $this->assertEquals("OK", $kodi->addons()->playRandom(new TvShow((object) [
            'title' => 'Game of Thrones',
            'file' => 'smb://server/tvshows/Game of Thrones/'
        ])));
    }
    
    /**
     * Create a fake addon
     *
     * @return stdClass
     */
    private function fakeAddon() {
        
        return (object) [
            'addonid' => rand(1, 100),
            'type' => 'kodi.context.item'
        ];
    }
}