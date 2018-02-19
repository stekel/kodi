<?php

namespace stekel\Kodi\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use stekel\Kodi\Kodi;
use stekel\Kodi\KodiAdapter;

class FakeKodi {
    
    /**
     * Responses to send
     *
     * @var array
     */
    protected $responses = [];
    
    /**
     * Create response
     *
     * @param  mixed $result
     * @return FakeKodiAdapter
     */
    public function createResponse($result) {
    
        array_push($this->responses, new Response(200, [], json_encode([
            'id' => 1,
            'jsonrpc' => '2.0',
            'result' => $result
        ])));
        
        return $this;
    }
    
    /**
     * Bind responses to client
     *
     * @return void
     */
    public function bind() {
        
        return new Kodi(
            new KodiAdapter(
                new Client([
                    'handler' => HandlerStack::create(new MockHandler($this->responses))
                ])
            )
        );
    }
}