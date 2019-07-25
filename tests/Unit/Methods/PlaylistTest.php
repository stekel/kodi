<?php

namespace stekel\Kodi\Tests\Unit\Methods;

use stekel\Kodi\Models\Playlist;
use stekel\Kodi\Models\Song;
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
                    'name',
                ]
            ]
        ], $this->fakeKodi->getHistoryRequest(0));
        
        $this->assertCount(2, $playlists);
        $this->assertEquals(Playlist::class, get_class($playlists->first()));
    }

    /** @test **/
    public function can_list_all_items_in_a_playlist() {

        $kodi = $this->fakeKodi->createResponse([
            'items' => [
                new Song((object) ['title' => 'Ice Ice Baby']),
                new Song((object) ['title' => 'Can\'t Touch This']),
            ]
        ])->bind();

        $items = $kodi->playlist()->getItems(new Playlist((object) [
            'playlistid' => 999,
        ]));

        $this->assertEquals(1, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'method' => 'Playlist.GetItems',
            'params' => [
                'playlistid' => 999,
            ]
        ], $this->fakeKodi->getHistoryRequest(0));

        $this->assertCount(2, $items);
        $this->assertEquals(Song::class, get_class($items->first()));
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

    /**
     * Create a fake song
     *
     * @return object
     */
    private function fakeSong($title='') {

        return (object) [
            'songid' => rand(1, 100),
            'title' => ($title != '') ? $title : 'Fake Song '.rand(1,100),
        ];
    }
}