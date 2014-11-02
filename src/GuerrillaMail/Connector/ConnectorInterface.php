<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 11/1/14
 * Time: 9:40 PM
 */

namespace GuerrillaMail\Connector;

interface ConnectorInterface
{
    /**
     * Issue a POST request to service.
     *
     * @param $action
     * @param array $body
     * @return mixed
     */
    public function transmit($action, array $body);

    /**
     * Issue a GET request to service
     * @param $action
     * @param array $body
     * @return mixed
     */
    public function retrieve($action, array $body);
}