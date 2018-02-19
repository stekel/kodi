<?php

namespace stekel\Kodi;

use GuzzleHttp\Client as GuzzleClient;

class KodiAdapter {
    
    /**
     * Guzzle Client
     *
     * @var GuzzleClient
     */
    protected $guzzleClient;
    
    /**
     * JSON RPC version
     *
     * @var string
     */
    protected $jsonrpc = "2.0";
    
    /**
     * Construct
     */
    public function __construct(GuzzleClient $guzzleClient) {
        
        $this->guzzleClient = $guzzleClient;
    }
    
    /**
     * Call the api
     *
     * @param  string $method
     * @param  array  $params
     * @return string
     */
    public function call($method, array $params=null) {
        
        $response = $this->guzzleClient->post('jsonrpc', [
            'json' => [
                'jsonrpc' => $this->jsonrpc,
                'method' => $method,
                'id' => 1,
                'params' => $params
            ]
        ]);
        
        $contents = json_decode($response->getBody()->getContents());
        
        if (property_exists($contents, 'result')) {
            
            return $contents->result;
        }
        
        return $contents;
    }
}