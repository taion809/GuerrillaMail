<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Nick
 * Date: 8/8/13
 * Time: 5:36 PM
 * To change this template use File | Settings | File Templates.
 */

namespace GuerrillaMail\GuerrillaConnect;

/**
 * Class Connection
 * @package GuerrillaMail\GuerrillaConnect
 */
abstract class Connection
{
    /**
     * GuerrillaMail api endpoint.
     * @var string
     */
    protected $url = 'https://api.guerrillamail.com/ajax.php';

    /**
     * Client IP Address
     * @var string
     */
    protected $ip = "127.0.0.1";

    /**
     * Client Agent
     * @var string
     */
    protected $agent = "GuerrillaMail_Library";

    /**
     * Site domain name. If this is a custom site, use the site domain or site id if domain hasn't been upgraded
     * @var string
     */
    protected $domain = "guerrillamail.com";

    /**
     * API Key. Only needed to access if site was set to 'private' under the 'Edit'->Site settings
     *
     * @var string
     */
    protected $api_key = null;

    /**
     * API Token. Used for Authorization to private domains. Derived from API Key, per session
     *
     * @var string
     */
    protected $api_token = null;

    /**
     * Format query string for GuerrillaMail API consumption.
     *
     * @param $action
     * @param  array  $options
     * @return string
     */
    public function build_query($action, array $options)
    {
        if (!empty($this->domain)) {
            $options['domain'] = $this->domain;
        }
        $query = "f={$action}";
        foreach ($options as $key => $value) {
            if (!is_array($value)) {
                $query .= "&{$key}=" . urlencode($value);
                continue;
            }

            foreach ($value as $a_key => $a_value) {
                $query .= "&{$key}%5B%5D=" . urlencode($a_value);
            }
        }

        return $query;
    }

    /**
     * HTTP GET
     *
     * @param  string $action
     * @param  array  $query
     * @return mixed
     */
    abstract public function retrieve($action, array $query);

    /**
     * HTTP POST
     *
     * @param  string $action
     * @param  array  $query
     * @return mixed
     */
    abstract public function transmit($action, array $query);
}
