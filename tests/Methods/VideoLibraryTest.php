<?php

use stekel\Kodi\Models\Episode;
use stekel\Kodi\Models\TvShow;
use stekel\Kodi\Tests\TestCase;

class VideoLibraryTest extends TestCase {
    
    /** @test **/
    public function can_clean_video_library() {
        
        $kodi = $this->fakeKodi->createResponse('OK')->bind();
        
        $this->assertTrue($kodi->videoLibrary()->clean());
    }
    
    /** @test **/
    public function can_get_all_tvshows() {
        
        $kodi = $this->fakeKodi->createResponse([
            'tvshows' => [
                $this->fakeTvShow(),
                $this->fakeTvShow(),
                $this->fakeTvShow()
            ],
            'limits' => (object) [
                'end' => 3,
                'start' => 0,
                'total' => 3
            ]
        ])->bind();
        
        $tvshows = $kodi->videoLibrary()->getTvShows();
        
        $this->assertCount(3, $tvshows);
        $this->assertEquals(TvShow::class, get_class($tvshows->first()));
    }
    
    /** @test **/
    public function can_get_all_tvshow_episodes() {
        
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
        
        $episodes = $kodi->videoLibrary()->getEpisodes(1);
        
        $this->assertCount(3, $episodes);
        $this->assertEquals(Episode::class, get_class($episodes->first()));
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
        
        $episodes = $kodi->videoLibrary()->recentlyAddedEpisodes(3);
        
        $this->assertCount(3, $episodes);
        $this->assertEquals(Episode::class, get_class($episodes->first()));
    }
    
    /**
     * Create a fake episode
     *
     * @return stdClass
     */
    private function fakeEpisode() {
        
        return (object) [
            'episodeid' => rand(1, 100),
            'label' => rand(1,4).'x0'.rand(1,9).'. Fake Episode'
        ];
    }
    
    /**
     * Create a fake tv show
     *
     * @return stdClass
     */
    private function fakeTvShow() {
        
        return (object) [
            'tvshowid' => rand(1, 100),
            'title' => 'Fake Tv Show '.rand(1,100),
        ];
    }
}