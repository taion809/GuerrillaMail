<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 11/1/14
 * Time: 9:39 PM
 */

namespace GuerrillaMail\Connector;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;

class GuzzleConnector implements ConnectorInterface
{
    protected $client;

    protected $ip = '127.0.0.1';

    protected $agent = "GuerrillaMail_Library";

    protected $domain = "guerrillamail.com";

    protected $apiurl = 'https://api.guerrillamail.com/ajax.php';

    public function __construct(ClientInterface $client, $ip = '', $agent = '', $domain = '', $apiurl = '')
    {
        $this->client = $client;

        if ($ip) {
            $this->ip = $ip;
        }

        if ($agent) {
            $this->agent = $agent;
        }

        if ($domain) {
            $this->domain = $domain;
        }

        if ($apiurl) {
            $this->apiurl = $apiurl;
        }
    }

    public function transmit($action, array $body)
    {
        $request = $this->buildRequest('post', $action, $body);
        return $this->send($request);
    }

    public function retrieve($action, array $body)
    {
        $request = $this->buildRequest('get', $action, $body);
        return $this->send($request);
    }

    protected function send(RequestInterface $request)
    {
        $response = $this->client->send($request);

        return $this->parseResponse($response);
    }

    protected function buildRequest($method, $action, array $body = [])
    {
        $request = $this->client->createRequest($method, $this->apiurl);
        $request->setHeader('User-Agent', $this->agent);

        if ($body['domain']) {
            $request->getQuery()->add('domain', $body['domain']);
        } else {
            $request->getQuery()->add('domain', $this->domain);
        }

        $request->getQuery()->add('f', $action);

        foreach($body as $key => $value) {
            $request->getQuery()->add($key, $value);
        }

        return $request;
    }

    protected function parseResponse(ResponseInterface $response)
    {
        $status = $response->getStatusCode();

        $jsonResponse = $response->json();

        if($status != 200) {
            throw new \Exception("Service returned non 200 status code.");
        }

        if(! $jsonResponse) {
            throw new \Exception("Service returned empty response.");
        }

        return $jsonResponse;
    }
}