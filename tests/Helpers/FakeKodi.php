<?php

namespace stekel\Kodi\Tests\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use stekel\Kodi\Kodi;
use stekel\Kodi\KodiAdapter;

class FakeKodi
{
    
    /**
     * Responses to send
     *
     * @var array
     */
    protected $responses = [];
    
    /**
     * Request history
     *
     * @var array
     */
    public $history = [];
    
    /**
     * Create response
     *
     * @param  mixed $result
     * @return FakeKodiAdapter
     */
    public function createResponse($result)
    {
        array_push($this->responses, new Response(200, [], json_encode([
            'id' => 1,
            'jsonrpc' => '2.0',
            'result' => $result
        ])));
        
        return $this;
    }
    
    /**
     * Create exception
     *
     * @param  string $message
     * @return FakeKodiAdapter
     */
    public function createException($message)
    {
        array_push($this->responses, new RequestException($message, new Request('GET', 'test')));
        
        return $this;
    }
    
    /**
     * Bind responses to client
     *
     * @return void
     */
    public function bind()
    {
        $stack = HandlerStack::create(new MockHandler($this->responses));
        $stack->push(Middleware::history($this->history));
        
        return new Kodi(
            new KodiAdapter(
                new Client([
                    'handler' => $stack
                ])
            )
        );
    }
    
    /**
     * Count of requests in the history
     *
     * @return integer
     */
    public function requestCount()
    {
        return count($this->history);
    }
    
    /**
     * Get history request by key
     *
     * @param  integer $key
     * @return Request
     */
    public function getHistoryRequest($key)
    {
        return $this->history[$key]['request'] ?? $this->history[$key]['request'];
    }
}
