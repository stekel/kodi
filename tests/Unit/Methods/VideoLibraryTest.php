<?php

namespace stekel\Kodi\Tests\Unit\Methods;

use stekel\Kodi\Models\Episode;
use stekel\Kodi\Models\Movie;
use stekel\Kodi\Models\TvShow;
use stekel\Kodi\Tests\TestCase;
use stekel\Kodi\Tests\Helpers\Request;

class VideoLibraryTest extends TestCase {
    
    use Request;
    
    /** @test **/
    public function can_get_all_tvshows_and_return_a_collection() {
        
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
        
        $this->assertEquals(1, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'method' => 'VideoLibrary.GetTVShows',
            'params' => []
        ], $this->fakeKodi->getHistoryRequest(0));
        
        $this->assertCount(3, $tvshows);
        $this->assertEquals(TvShow::class, get_class($tvshows->first()));
    }
    
    /** @test **/
    public function can_get_an_empty_collection_when_no_tvshows_exist() {
        
        $kodi = $this->fakeKodi->createResponse([
            'tvshows' => [],
            'limits' => (object) [
                'end' => 0,
                'start' => 0,
                'total' => 0
            ]
        ])->bind();
        
        $tvshows = $kodi->videoLibrary()->getTvShows();
        
        $this->assertEquals(1, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'method' => 'VideoLibrary.GetTVShows',
            'params' => []
        ], $this->fakeKodi->getHistoryRequest(0));
        
        $this->assertCount(0, $tvshows);
    }
    
    /** @test **/
    public function can_get_tvshow_details() {
        
        $kodi = $this->fakeKodi->createResponse([
            'tvshowdetails' => [
                'dateadded' => 'dateadded',
                'episode' => 'episode',
                'lastplayed' => 'lastplayed',
                'playcount' => 'playcount',
                'season' => 'season',
                'title' => 'title',
                'watchedepisodes' => 'watchedepisodes',
                'year' => 'year',
            ]
        ])->bind();
        
        $tvshow = $kodi->videoLibrary()->getTVShowDetails(new TvShow((object) [
            'tvshowid' => 999,
        ]));
        
        $this->assertEquals(1, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'method' => 'VideoLibrary.GetTVShowDetails',
            'params' => [
                'tvshowid' => 999,
                'properties' => [
                    "dateadded",
                    "episode",
                    "lastplayed",
                    "playcount",
                    "season",
                    "title",
                    "watchedepisodes",
                    "year",
                ]
            ]
        ], $this->fakeKodi->getHistoryRequest(0));
        
        $this->assertEquals(TvShow::class, get_class($tvshow));
        $this->assertEquals('dateadded', $tvshow->dateadded);
        $this->assertEquals('episode', $tvshow->episode);
        $this->assertEquals('lastplayed', $tvshow->lastplayed);
        $this->assertEquals('playcount', $tvshow->playcount);
        $this->assertEquals('season', $tvshow->season);
        $this->assertEquals('title', $tvshow->title);
        $this->assertEquals('watchedepisodes', $tvshow->watchedepisodes);
        $this->assertEquals('year', $tvshow->year);
    }
    
    /** @test **/
    public function can_get_all_tvshow_episodes_and_return_a_collection() {
        
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
        
        $this->assertEquals(1, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'method' => 'VideoLibrary.GetEpisodes',
            'params' => [
                'tvshowid' => 1,
                'properties' => [
                    'title',
                    'lastplayed'
                ]
            ]
        ], $this->fakeKodi->getHistoryRequest(0));
        
        $this->assertCount(3, $episodes);
        $this->assertEquals(Episode::class, get_class($episodes->first()));
    }
    
    /** @test **/
    public function can_get_an_empty_collection_when_no_episodes_exist() {
        
        $kodi = $this->fakeKodi->createResponse([
            'episodes' => [],
            'limits' => (object) [
                'end' => 0,
                'start' => 0,
                'total' => 0
            ]
        ])->bind();
        
        $episodes = $kodi->videoLibrary()->getEpisodes(1);
        
        $this->assertEquals(1, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'method' => 'VideoLibrary.GetEpisodes',
            'params' => [
                'tvshowid' => 1,
                'properties' => [
                    'title',
                    'lastplayed'
                ]
            ]
        ], $this->fakeKodi->getHistoryRequest(0));
        
        $this->assertCount(0, $episodes);
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
        
        $this->assertEquals(1, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'method' => 'VideoLibrary.GetRecentlyAddedEpisodes',
            'params' => [
                'limits' => [
                    'end' => 3,
                ],
            ]
        ], $this->fakeKodi->getHistoryRequest(0));
        
        $this->assertCount(3, $episodes);
        $this->assertEquals(Episode::class, get_class($episodes->first()));
    }
    
    /** @test **/
    public function can_get_a_movie_by_the_title() {
        
        $movie = $this->fakeMovie('Cars');
        
        $kodi = $this->fakeKodi->createResponse([
            'movies' => [
                $movie
            ],
            'limits' => (object) [
                'end' => 1,
                'start' => 0,
                'total' => 1
            ]
        ])->bind();
        
        $movies = $kodi->videoLibrary()->getMovies(['title' => $movie->title]);
        
        $this->assertEquals(1, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'method' => 'VideoLibrary.GetMovies',
            'params' => [
                'filter' => [
                    'field' => 'title',
                    'operator' => 'is',
                    'value' => $movie->title
                ],
                'properties' => [
                    'title',
                    'lastplayed',
                ]
            ]
        ], $this->fakeKodi->getHistoryRequest(0));
        
        $this->assertCount(1, $movies);
        $this->assertEquals(Movie::class, get_class($movies->first()));
    }
    
    /** @test **/
    public function can_clean_video_library() {
        
        $kodi = $this->fakeKodi->createResponse('OK')->bind();
        
        $this->assertTrue($kodi->videoLibrary()->clean());
        
        $this->assertEquals(1, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'method' => 'VideoLibrary.Clean',
            'params' => [
                'showdialogs' => false
            ]
        ], $this->fakeKodi->getHistoryRequest(0));
    }
    
    /** @test **/
    public function can_update_video_library() {
        
        $kodi = $this->fakeKodi->createResponse('OK')->bind();
        
        $this->assertTrue($kodi->videoLibrary()->scan());
        
        $this->assertEquals(1, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'method' => 'VideoLibrary.Scan',
            'params' => [
                'showdialogs' => false
            ]
        ], $this->fakeKodi->getHistoryRequest(0));
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
    
    /**
     * Create a fake movie
     *
     * @return stdClass
     */
    private function fakeMovie($title='') {
        
        return (object) [
            'movieid' => rand(1, 100),
            'title' => ($title != '') ? $title : 'Fake Movie '.rand(1,100),
        ];
    }
}