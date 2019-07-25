<?php

namespace stekel\Kodi\Tests\Unit\Methods;

use stekel\Kodi\Models\Playlist;
use stekel\Kodi\Tests\TestCase;
use stekel\Kodi\Tests\Helpers\Request;

class PlaylistTest extends TestCase {
    
    use Request;
    
    /** @test **/
    public function can_list_all_playlists() {
        
        $playlistA = $this->fakePlaylist('Awesome 80s');
        $playlistB = $this->fakePlaylist('Awesome 90s');

        $kodi = $this->fakeKodi->createResponse([
            'playlists' => [
                $playlistA,
                $playlistB,
            ],
            'limits' => (object) [
                'end' => 2,
                'start' => 0,
                'total' => 2
            ]
        ])->bind();
        
        $playlists = $kodi->playlist()->all();
        
        $this->assertEquals(1, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'method' => 'Playlist.GetPlaylists',
            'params' => [
                'properties' => [
                    'title',
                ]
            ]
        ], $this->fakeKodi->getHistoryRequest(0));
        
        $this->assertCount(2, $playlists);
        $this->assertEquals(Playlist::class, get_class($playlists->first()));
    }

    /**
     * Create a fake playlist
     *
     * @return object
     */
    private function fakePlaylist($title='') {

        return (object) [
            'playlistid' => rand(1, 100),
            'title' => ($title != '') ? $title : 'Fake Playlist '.rand(1,100),
        ];
    }
}