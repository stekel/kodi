<?php

namespace stekel\Kodi\Tests;

use stekel\Kodi\Tests\TestCase;

class PlayerTest extends TestCase {
    
    /** @test **/
    public function can_get_active_players() {
        
        $kodi = $this->fakeKodi->createResponse([
            (object) [
                'playerid' => 1,
                'type' => 'video'
            ]
        ])->bind();
        
        $players = $kodi->player()->getActivePlayers();
        
        $this->assertCount(1, $players);
        $this->assertEquals(1, $players->first()->id);
        $this->assertEquals('video', $players->first()->type);
    }
    
    /** @test **/
    public function can_play_or_pause_the_current_active_player() {
    
        $kodi = $this->fakeKodi->createResponse([
            (object) [
                'speed' => 1
            ]
        ])->bind();
    
        $this->assertTrue($kodi->player()->playPause());
    }
    
    /** @test **/
    public function can_stop_the_current_active_player() {
    
        $kodi = $this->fakeKodi->createResponse([
            (object) [
                'speed' => 1
            ]
        ])->bind();
    
        $this->assertTrue($kodi->player()->stop());
    }
    
    /** @test **/
    public function can_get_the_currently_playing_episode_item() {
    
        $kodi = $this->fakeKodi->createResponse([
            (object) [
                'playerid' => 1,
                'type' => 'video'
            ]
        ])->createResponse([
            (object) [
                'item' => [
                    'episode' => 1,
                    'id' => 321,
                    'label' => 'Episode Title',
                    'season' => 2,
                    'showtitle' => 'Show Title',
                    'title' => 'Episode Title',
                    'tvshowid' => 443,
                    'type' => 'episode'
                ]
            ]
        ])->bind();
    
        $episode = $kodi->player()->getItem();
    
        $this->assertEquals('Episode Title', $episode->title);
        $this->assertEquals(2, $episode->season);
        $this->assertEquals('Show Title', $episode->showtitle);
    }
}