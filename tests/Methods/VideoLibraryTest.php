<?php

use stekel\Kodi\Tests\TestCase;

class VideoLibraryTest extends TestCase {
    
    /** @test **/
    public function can_clean_video_library() {
        
        $kodi = $this->fakeKodi->createResponse('OK')->bind();
        
        $this->assertTrue($kodi->videoLibrary()->clean());
    }
    
    /** @test **/
    public function can_get_recently_added_episodes() {
        
        $kodi = $this->fakeKodi->createResponse([
            'episodes' => [
                $this->fakeEpisode(),
                $this->fakeEpisode(),
                $this->fakeEpisode()
            ],
            'limits' => (object) [
                'end' => 3,
                'start' => 0,
                'total' => 3
            ]
        ])->bind();
        
        $this->assertCount(3, $kodi->videoLibrary()->recentlyAddedEpisodes());
    }
    
    private function fakeEpisode() {
        
        return (object) [
            'episodeid' => rand(1, 100),
            'label' => rand(1,4).'x0'.rand(1,9).'. Fake Episode'
        ];
    }
}