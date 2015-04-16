<?php
/**
 * Created by PhpStorm.
 * User: njohns
 * Date: 4/14/15
 * Time: 10:11 PM
 */

namespace GuerrillaMail\Connection;

interface ConnectionInterface
{
    public function get($action, array $options);
    public function post($action, array $options);
}
