<?php
/**
 * Created by PhpStorm.
 * User: njohns
 * Date: 4/14/15
 * Time: 11:54 PM
 */

namespace GuerrillaMail\Connection;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\Mock;

class GuzzleConnectionTest extends \PHPUnit_Framework_TestCase
{
    private $client;

    public function setUp()
    {
        $body = ['sid_token' => 'test'];

        $mock = new Mock([
            new Response(200, [], Stream::factory(json_encode($body)))
        ]);

        $client = new Client();
        $client->getEmitter()->attach($mock);

        $this->client = $client;
    }

    public function testGettSendsRequest()
    {
        $client = new GuzzleConnection($this->client, '');
        $response = $client->get('f', []);

        $this->assertArrayHasKey('sid_token', $response);
    }

    public function testPostSendsRequest()
    {
        $client = new GuzzleConnection($this->client, '');
        $response = $client->post('f', []);

        $this->assertArrayHasKey('sid_token', $response);
    }

    public function testGetWithSidToken()
    {
        $client = new GuzzleConnection($this->client, '');
        $response = $client->get('f', ['sid_token' => 'test']);

        $this->assertArrayHasKey('sid_token', $response);
    }

    public function testGetWithSidTokenAndApiKey()
    {
        $client = new GuzzleConnection($this->client, 'key');
        $response = $client->get('f', ['sid_token' => 'test']);

        $this->assertArrayHasKey('sid_token', $response);
    }

}
