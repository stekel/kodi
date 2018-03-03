<?php

namespace stekel\Kodi\Tests\Methods;

use stekel\Kodi\Kodi;
use stekel\Kodi\Models\Addon;
use stekel\Kodi\Models\TvShow;
use stekel\Kodi\Tests\Helpers\Request;
use stekel\Kodi\Tests\TestCase;

class GuiTest extends TestCase {
    
    use Request;
    
    /** @test **/
    public function can_show_a_gui_notification() {
        
        $kodi = $this->fakeKodi->createResponse("OK")->bind();
        
        $kodi->gui()->showNotification(
            'Notification Title',
            'This is a notification.'
        );
                
        $this->assertEquals(1, $this->fakeKodi->requestCount());
        $this->assertRequestBodyMatches([
            'method' => 'GUI.ShowNotification',
            'params' => [
                'title' => 'Notification Title',
                'message' => 'This is a notification.',
            ]
        ], $this->fakeKodi->getHistoryRequest(0));
    }
}