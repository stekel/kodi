<?php

namespace stekel\Kodi\Tests\Methods;

use stekel\Kodi\Kodi;
use stekel\Kodi\Models\Addon;
use stekel\Kodi\Models\TvShow;
use stekel\Kodi\Tests\Helpers\Request;
use stekel\Kodi\Tests\TestCase;

class AddonsTest extends TestCase {
    
    use Request;
    
    /** @test **/
    public function can_get_addons_with_a_collection() {
        
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
                
        $this->assertEquals(1, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'method' => 'Addons.GetAddons',
            'params' => []
        ], $this->fakeKodi->getHistoryRequest(0));
        
        $this->assertCount(3, $addons);
        $this->assertEquals(Addon::class, get_class($addons->first()));
    }
    
    /** @test **/
    public function can_get_an_empty_collection_when_no_addons_exist() {
        
        $kodi = $this->fakeKodi->createResponse([
            'addons' => [],
            'limits' => (object) [
                'end' => 0,
                'start' => 0,
                'total' => 0
            ]
        ])->bind();
        
        $addons = $kodi->addons()->getAddons();
                
        $this->assertEquals(1, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'method' => 'Addons.GetAddons',
            'params' => []
        ], $this->fakeKodi->getHistoryRequest(0));
        
        $this->assertCount(0, $addons);
    }
    
    /** @test **/
    public function can_execute_the_random_episode_addon() {
    
        $kodi = $this->fakeKodi->createResponse("OK")->bind();
        
        $this->assertTrue($kodi->addons()->playRandom(new TvShow((object) [
            'tvshowid' => 999,
            'title' => 'Game of Thrones',
        ])));
        
        $this->assertEquals(1, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'method' => 'Addons.ExecuteAddon',
            'params' => [
                'addonid' => 'script.playrandomvideos',
                'params' => [
                    'videodb://tvshows/titles/999',
                ]
            ]
        ], $this->fakeKodi->getHistoryRequest(0));
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