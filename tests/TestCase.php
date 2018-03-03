<?php

namespace stekel\Kodi\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use stekel\Kodi\Tests\Helpers\FakeKodi;

class TestCase extends BaseTestCase {
    
    /**
     * Setup
     */
    public function setUp() {
    
        parent::setUp();
        
        $this->fakeKodi = new FakeKodi();
    }
}