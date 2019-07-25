<?php

namespace stekel\Kodi\Tests\Unit\Methods;

use stekel\Kodi\Models\Album;
use stekel\Kodi\Models\Artist;
use stekel\Kodi\Models\Song;
use stekel\Kodi\Tests\TestCase;
use stekel\Kodi\Tests\Helpers\Request;

class AudioLibraryTest extends TestCase {
    
    use Request;
    
    /** @test **/
    public function can_get_an_artist_by_the_title() {
        
        $artist = $this->fakeArtist('Disturbed');
        
        $kodi = $this->fakeKodi->createResponse([
            'artists' => [
                $artist
            ],
            'limits' => (object) [
                'end' => 1,
                'start' => 0,
                'total' => 1
            ]
        ])->bind();
        
        $artists = $kodi->audioLibrary()->getArtists(['title' => $artist->title]);
        
        $this->assertEquals(1, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'method' => 'AudioLibrary.GetArtists',
            'params' => [
                'filter' => [
                    'field' => 'title',
                    'operator' => 'is',
                    'value' => $artist->title
                ],
                'properties' => [
                    'title',
                    'lastplayed',
                ]
            ]
        ], $this->fakeKodi->getHistoryRequest(0));
        
        $this->assertCount(1, $artists);
        $this->assertEquals(Artist::class, get_class($artists->first()));
    }

    /** @test **/
    public function can_get_all_albums_by_an_artist() {

        $kodi = $this->fakeKodi->createResponse([
            'albums' => [
                new Album((object) ['title' => 'AlbumA']),
                new Album((object) ['title' => 'AlbumB']),
            ],
            'limits' => (object) [
                'end' => 2,
                'start' => 0,
                'total' => 2
            ]
        ])->bind();

        $albums = $kodi->audioLibrary()->getAlbumsByArtist(new Artist((object) [
            'artistid' => 999
        ]));

        $this->assertEquals(1, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'method' => 'AudioLibrary.GetAlbums',
            'params' => [
                'filter' => [
                    'field' => 'artistid',
                    'operator' => 'is',
                    'value' => 999
                ],
            ]
        ], $this->fakeKodi->getHistoryRequest(0));

        $this->assertCount(2, $albums);
        $this->assertEquals(Album::class, get_class($albums->first()));
    }

    /** @test **/
    public function can_get_all_songs_on_an_album() {

        $kodi = $this->fakeKodi->createResponse([
            'songs' => [
                new Song((object) ['title' => 'SongA']),
                new Song((object) ['title' => 'SongB']),
            ],
            'limits' => (object) [
                'end' => 2,
                'start' => 0,
                'total' => 2
            ]
        ])->bind();

        $songs = $kodi->audioLibrary()->getSongsByAlbum(new Album((object) [
            'albumid' => 999
        ]));

        $this->assertEquals(1, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'method' => 'Audio.Details.Song',
            'params' => [
                'filter' => [
                    'field' => 'songid',
                    'operator' => 'is',
                    'value' => 999
                ],
            ]
        ], $this->fakeKodi->getHistoryRequest(0));

        $this->assertCount(2, $songs);
        $this->assertEquals(Song::class, get_class($songs->first()));
    }

    /**
     * Create a fake artist
     *
     * @return object
     */
    private function fakeArtist($title='') {
        
        return (object) [
            'artistid' => rand(1, 100),
            'title' => ($title != '') ? $title : 'Fake Artist '.rand(1,100),
        ];
    }
}