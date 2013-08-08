<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Nick
 * Date: 8/8/13
 * Time: 5:36 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Johnsn\GuerrillaMail\GuerrillaConnect;

abstract class Connection
{
    protected $url = 'http://api.guerrillamail.com/ajax.php';

    protected $ip = "127.0.0.1";

    protected $agent = "GuerrillaMail_Library";

    public function build_query($action, array $options)
    {
        $query = "f={$action}";
        foreach($options as $key => $value)
        {
            if(!is_array($value))
            {
                $query .= "&{$key}=" . urlencode($value);
                continue;
            }

            foreach($value as $a_key => $a_value)
            {
                $query .= "&{$a_key}%5B%5D=" . urlencode($a_value);
            }
        }

        return $query;
    }

    abstract function retrieve($action, $query);
    abstract function transmit($action, $query);
}
