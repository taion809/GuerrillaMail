<?php
/**
 * Created by PhpStorm.
 * User: njohns
 * Date: 4/14/15
 * Time: 10:05 PM
 */

namespace GuerrillaMail\Connection;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;

/**
 * Class GuzzleConnection
 * @package GuerrillaMail\Connection
 */
class GuzzleConnection implements ConnectionInterface
{
    const USER_AGENT = "GuerrillaMail_Library";

    /**
     * @var ClientInterface
     */
    protected $connection;

    /**
     * @var string
     */
    protected $api_url = 'https://api.guerrillamail.com/ajax.php';

    /**
     * @var string
     */
    protected $api_key = '';

    /**
     * @var string
     */
    protected $api_token = '';

    /**
     * @param ClientInterface $connection
     * @param $api_key
     */
    public function __construct(ClientInterface $connection, $api_key) {
        $this->connection = $connection;
        $this->api_key = $api_key;
    }

    public static function make($api_key = '')
    {
        $defaults = [
            'headers' => [
                'User-Agent' => self::USER_AGENT,
                'Accepts' => 'application/json',
            ]
        ];

        $client = new Client(['defaults' => $defaults]);

        return new self($client, $api_key);
    }

    /**
     * @param $uri
     * @param array $options
     * @return mixed
     * @throws \Exception rethrows RequestException
     */
    public function get($uri, array $options = [])
    {
        return $this->buildRequest('GET', $uri, $options);
    }

    /**
     * @param $uri
     * @param array $options
     * @return mixed
     * @throws \Exception
     */
    public function post($uri, array $options = [])
    {
        return $this->buildRequest('POST', $uri, $options);
    }

    private function buildRequest($method, $uri, array $options)
    {
        $query = array_merge_recursive([
            'f' => $uri,
            'ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1',
            'agent' => self::USER_AGENT
        ], $options);

        try {
            $request = $this->makeRequest($method, $query);
            $response = $this->connection->send($request);
        } catch(RequestException $e) {
            throw new \Exception($e->getMessage(), $e->getCode(), $e);
        }

        return $response->json();
    }

    /**
     * @param $method
     * @param array $options
     * @return \GuzzleHttp\Message\RequestInterface
     */
    private function makeRequest($method, array $options) {
        $cookies = [];
        $headers = [];

        if(isset($options['sid_token'])) {
            $cookies['PHPSESSID'] = $options['sid_token'];
            if($apiToken = $this->getApiToken($options['sid_token'])) {
                $headers['Authorization: ApiToken '] = $apiToken;
            }

            unset($options['sid_token']);
        }

        $request = $this->connection->createRequest($method, $this->api_url, [
            'cookies' => $cookies,
            'headers' => $headers
        ]);

        $request->setQuery($options);

        return $request;
    }

    /**
     * @param $sid
     * @return bool|string
     */
    private function getApiToken($sid)
    {
        if(! $this->api_key) {
            return false;
        }

        $token = hash_hmac('sha256', $sid, $this->api_key);
        return $token;
    }
}
