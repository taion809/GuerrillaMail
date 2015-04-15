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
     * @param $action
     * @param array $options
     * @return mixed
     * @throws \Exception
     */
    public function get($action, array $options)
    {
        $requestOptions = $this->buildRequestOptions($action, $options);

        try {
            $response = $this->connection->get($this->api_url, [
                'headers' => $requestOptions['headers'],
                'cookies' => $requestOptions['cookies'],
                'query' => $requestOptions['query'],
            ]);
        } catch(RequestException $e) {
            throw new \Exception($e->getMessage(), $e->getCode(), $e);
        }

        return $response->json();
    }

    /**
     * @param $action
     * @param array $options
     * @return mixed
     * @throws \Exception
     */
    public function post($action, array $options)
    {
        $requestOptions = $this->buildRequestOptions($action, $options);

        try {
            $response = $this->connection->post($this->api_url, [
                'headers' => $requestOptions['headers'],
                'cookies' => $requestOptions['cookies'],
                'query' => $requestOptions['query'],
            ]);
        } catch(RequestException $e) {
            throw new \Exception($e->getMessage(), $e->getCode(), $e);
        }

        return $response->json();
    }

    private function buildRequestOptions($action, array $options)
    {
        $options = array_merge_recursive([
            'f' => $action,
            'ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1',
            'agent' => self::USER_AGENT
        ], $options);

        return $this->parseOptions($options);
    }

    private function parseOptions(array $options)
    {
        $cookies = [];
        $headers = [];

        if(isset($options['sid_token'])) {
            $cookies['PHPSESSID'] = $options['sid_token'];
            if($apiToken = $this->getApiToken($options['sid_token'])) {
                $headers['Authorization: ApiToken '] = $apiToken;
            }

            unset($options['sid_token']);
        }

        return [
            'cookies' => $cookies,
            'headers' => $headers,
            'query' => $options
        ];
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
