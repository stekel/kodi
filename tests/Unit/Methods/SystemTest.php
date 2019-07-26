<?php

namespace stekel\Kodi\Tests\Unit\Methods;

use stekel\Kodi\Models\System as SystemModel;
use stekel\Kodi\Models\Volume;
use stekel\Kodi\Tests\Helpers\Request;
use stekel\Kodi\Tests\TestCase;

class SystemTest extends TestCase {
    
    use Request;
    
    /** @test **/
    public function can_get_xbmc_properties() {
        
        $kodi = $this->fakeKodi->createResponse([
            'System.BuildDate' => 'Nov 19 2017',
            'System.BuildVersion' => '17.6 Git:2017-11-18-123456789',
            'System.FriendlyName' => 'Kodi Living Room',
            'Network.IPAddress' => '192.168.1.100',
        ])->bind();

        $system = $kodi->system()->getInfoLabels();
        
        $this->assertEquals(1, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'params' => [
                'labels' => [
                    'System.BuildVersion',
                    'System.BuildDate',
                    'System.FriendlyName',
                    'Network.IPAddress'
                ]
            ]
        ], $this->fakeKodi->getHistoryRequest(0));
        
        $this->assertEquals(SystemModel::class, get_class($system));
        $this->assertEquals('Nov 19 2017', $system->buildDate);
        $this->assertEquals('17.6 Git:2017-11-18-123456789', $system->buildVersion);
        $this->assertEquals('Kodi Living Room', $system->friendlyName);
        $this->assertEquals('192.168.1.100', $system->ipAddress);
    }
    
    /** @test **/
    public function can_get_the_volume_and_increase_or_decrease_by_a_percentage() {
        
        $kodi = $this->fakeKodi->createResponse([
            'volume' => '50',
        ])->createResponse([
            '60',
        ])->createResponse([
            '50',
        ])->bind();
        
        $volume = $kodi->system()->getVolume();
        
        $this->assertEquals(1, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'params' => [
                'properties' => [
                    'volume',
                ]
            ]
        ], $this->fakeKodi->getHistoryRequest(0));
        
        $volume->increaseBy(10);
        
        $this->assertEquals(2, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'params' => [
                'volume' => 60
            ]
        ], $this->fakeKodi->getHistoryRequest(1));
        
        $this->assertEquals('60', $volume->current());
        
        $volume->decreaseBy(10);
        
        $this->assertEquals(3, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'params' => [
                'volume' => 50
            ]
        ], $this->fakeKodi->getHistoryRequest(2));
        
        $this->assertEquals('50', $volume->current());
    }
}