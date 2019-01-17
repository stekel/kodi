<?php

namespace stekel\Kodi;

use GuzzleHttp\Client as GuzzleClient;
use stekel\Kodi\Exceptions\KodiConnectionFailed;

class KodiAdapter
{
    
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
    public function __construct(GuzzleClient $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }
    
    /**
     * Call the api
     *
     * @param  string $method
     * @param  array  $params
     * @return string
     */
    public function call($method, array $params=null)
    {
        try {
            $response = $this->guzzleClient->post('jsonrpc', [
                'json' => [
                    'jsonrpc' => $this->jsonrpc,
                    'method' => $method,
                    'id' => 1,
                    'params' => $params
                ]
            ]);
        } catch (\Exception $e) {
            throw new KodiConnectionFailed();
        }
        
        $contents = json_decode($response->getBody()->getContents());
        
        return (property_exists($contents, 'result')) ? $contents->result : $contents;
    }
}
