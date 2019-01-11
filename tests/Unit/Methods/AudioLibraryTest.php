<?php

namespace stekel\Kodi\Tests\Unit\Methods;

use stekel\Kodi\Models\Artist;
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
    
    /**
     * Create a fake artist
     *
     * @return stdClass
     */
    private function fakeArtist($title='') {
        
        return (object) [
            'artistid' => rand(1, 100),
            'title' => ($title != '') ? $title : 'Fake Artist '.rand(1,100),
        ];
    }
}