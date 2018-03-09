<?php

namespace stekel\Kodi\Tests\Unit\Methods;

use stekel\Kodi\Models\System as SystemModel;
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
        ])->bind();
        
        $system = $kodi->system()->getInfoLabels();
        
        $this->assertEquals(1, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'params' => [
                'labels' => [
                    'System.BuildVersion',
                    'System.BuildDate',
                    'System.FriendlyName',
                ]
            ]
        ], $this->fakeKodi->getHistoryRequest(0));
        
        $this->assertEquals(SystemModel::class, get_class($system));
        $this->assertEquals('Nov 19 2017', $system->buildDate);
        $this->assertEquals('17.6 Git:2017-11-18-123456789', $system->buildVersion);
        $this->assertEquals('Kodi Living Room', $system->friendlyName);
    }
}