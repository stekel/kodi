<?php

namespace stekel\Kodi\Tests\Unit\Methods;

use stekel\Kodi\Models\Episode;
use stekel\Kodi\Models\Player;
use stekel\Kodi\Models\Song;
use stekel\Kodi\Tests\Helpers\Request;
use stekel\Kodi\Tests\TestCase;

class PlayerTest extends TestCase {
    
    use Request;
    
    /** @test **/
    public function can_get_active_players() {
        
        $kodi = $this->fakeKodi->createResponse([
            (object) [
                'playerid' => 1,
                'type' => 'video'
            ]
        ])->bind();
        
        $players = $kodi->player()->getActivePlayers();
        
        $this->assertEquals(1, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'method' => 'Player.GetActivePlayers',
            'params' => []
        ], $this->fakeKodi->getHistoryRequest(0));
        
        $this->assertCount(1, $players);
        $this->assertEquals(1, $players->first()->id);
        $this->assertEquals('video', $players->first()->type);
    }
    
    /** @test **/
    public function can_open_and_play_the_given_episode() {
        
        $kodi = $this->fakeKodi->createResponse([
            (object) [
                'return' => 'string',
            ]
        ])->bind();
        
        $result = $kodi->player()->open(new Episode((object) [
            'episodeid' => 999,
        ]));
        
        $this->assertEquals(1, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'method' => 'Player.Open',
            'params' => [
                'item' => [
                    'episodeid' => 999,
                ],
            ]
        ], $this->fakeKodi->getHistoryRequest(0));
        
        $this->assertTrue($result);
    }
    
    /** @test **/
    public function can_skip_to_the_next_playlist_item() {
        
        $kodi = $this->fakeKodi->createResponse([
            (object) [
                'return' => 'string',
            ]
        ])->bind();
        
        $result = $kodi->player()->goTo(new Player((object) [
            'playerid' => 1
        ]), 'next');
        
        $this->assertEquals(1, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'method' => 'Player.GoTo',
            'params' => [
                'playerid' => 1,
                'to' => 'next',
            ]
        ], $this->fakeKodi->getHistoryRequest(0));
        
        $this->assertTrue($result);
    }
    
    /** @test **/
    public function can_play_or_pause_the_current_active_player_using_the_default_player() {
    
        $kodi = $this->fakeKodi->createResponse([
            (object) [
                'speed' => 1
            ]
        ])->bind();
        
        $this->assertTrue($kodi->player()->playPause());
        
        $this->assertEquals(1, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'method' => 'Player.PlayPause',
            'params' => [
                'playerid' => 1,
            ]
        ], $this->fakeKodi->getHistoryRequest(0));
    }
    
    /** @test **/
    public function can_play_or_pause_the_current_active_player_using_a_given_player() {
    
        $kodi = $this->fakeKodi->createResponse([
            (object) [
                'speed' => 1
            ]
        ])->bind();
        
        $player = new Player((object) [
            'playerid' => 2
        ]);
        
        $this->assertTrue($kodi->player()->playPause($player));
        
        $this->assertEquals(1, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'method' => 'Player.PlayPause',
            'params' => [
                'playerid' => 2,
            ]
        ], $this->fakeKodi->getHistoryRequest(0));
    }
    
    /** @test **/
    public function can_stop_the_current_active_player_with_the_default_player() {
    
        $kodi = $this->fakeKodi->createResponse([
            (object) [
                'speed' => 1
            ]
        ])->bind();
    
        $this->assertTrue($kodi->player()->stop());
        
        $this->assertEquals(1, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'method' => 'Player.Stop',
            'params' => [
                'playerid' => 1,
            ]
        ], $this->fakeKodi->getHistoryRequest(0));
    }
    
    /** @test **/
    public function can_stop_the_current_active_player_with_a_given_player() {
    
        $kodi = $this->fakeKodi->createResponse([
            (object) [
                'speed' => 1
            ]
        ])->bind();
        
        $player = new Player((object) [
            'playerid' => 2
        ]);
        
        $this->assertTrue($kodi->player()->stop($player));
        
        $this->assertEquals(1, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'method' => 'Player.Stop',
            'params' => [
                'playerid' => 2,
            ]
        ], $this->fakeKodi->getHistoryRequest(0));
    }
    
    /** @test **/
    public function can_get_the_currently_playing_tv_episode() {
    
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
        
        $this->assertEquals(2, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'method' => 'Player.GetItem',
            'params' => [
                'playerid' => 1,
                'properties' => [
                    "title",
                    "season",
                    "episode",
                    "duration",
                    "showtitle",
                    "tvshowid",
                ]
            ]
        ], $this->fakeKodi->getHistoryRequest(1));
        
        $this->assertEquals(Episode::class, get_class($episode));
        $this->assertEquals('Episode Title', $episode->title);
        $this->assertEquals(2, $episode->season);
        $this->assertEquals('Show Title', $episode->showtitle);
    }
    
    /** @test **/
    public function can_get_the_currently_playing_song() {
    
        $kodi = $this->fakeKodi->createResponse([
            (object) [
                'playerid' => 1,
                'type' => 'audio'
            ]
        ])->createResponse([
            (object) [
                'item' => [
                    'title' => 'Song Name',
                    'id' => 321,
                    'album' => 'The Album',
                    'artist' => 'The Artist',
                    'duration' => '01:24',
                ]
            ]
        ])->bind();
    
        $song = $kodi->player()->getItem();
        
        $this->assertEquals(2, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'method' => 'Player.GetItem',
            'params' => [
                'playerid' => 1,
                'properties' => [
                    "title",
                    "album",
                    "artist",
                    "duration"
                ]
            ]
        ], $this->fakeKodi->getHistoryRequest(1));
        
        $this->assertEquals(Song::class, get_class($song));
        $this->assertEquals('Song Name', $song->title);
        $this->assertEquals('The Album', $song->album);
        $this->assertEquals('The Artist', $song->artist);
    }
}