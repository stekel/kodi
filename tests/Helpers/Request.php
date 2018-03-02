<?php

namespace stekel\Kodi\Tests\Helpers;

use GuzzleHttp\Psr7\Request as GuzzleRequest;

trait Request {
    
    public function assertRequestBodyMatches(array $actual, GuzzleRequest $request) {
        
        $requestBody = json_decode($request->getBody()->read(1024), true);
        
        foreach ($actual as $key => $value) {
            
            $this->assertEquals($value, $requestBody[$key]);
        }
    }
}