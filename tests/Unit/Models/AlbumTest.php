<?php

namespace stekel\Kodi\Tests\Unit\Methods;

use Facades\stekel\Kodi;
use stekel\Kodi\Models\Album;
use stekel\Kodi\Models\Song;
use stekel\Kodi\Tests\TestCase;

class AlbumTest extends TestCase {

//    /** @test **/
    public function can_get_all_songs() {

        $album = new Album((object) ['title' => 'Sample']);
        $songA = new Song((object) ['title' => 'SongA']);
        $songB = new Song((object) ['title' => 'SongB']);
        $songC = new Song((object) ['title' => 'SongC']);

        Kodi::shouldReceive('getSongsByAlbum')
            ->once()
            ->with($album)
            ->andReturn(collect([$songA, $songB, $songC]));

        $songs = $album->songs();

        $this->assertCount(3, $songs);
        $this->assertEquals(Song::class, get_class($songs->first()));
    }
}