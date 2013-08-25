<?php

namespace Johnsn\GuerrillaMail\GuerrillaConnect;

/**
 * Class CurlConnection
 * @package Johnsn\GuerrillaMail\GuerrillaConnect
 */
class CurlConnection extends Connection
{
    /**
     * @param string $ip Client IP
     * @param string $agent Client Agent
     * @param string $url API Endpoint
     */
    public function __construct($ip, $agent = '', $url = '')
    {
        $this->ip = $ip;

        if(!empty($agent))
        {
            $this->agent = $agent;
        }

        if(!empty($url))
        {
            $this->url = $url;
        }
    }

    /**
     * HTTP GET using cURL
     *
     * @param string $action
     * @param array $query
     * @return array|mixed
     */
    public function retrieve($action, array $query)
    {
        $url = $this->url . '?'. $this->build_query($action, $query);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if(isset($query['sid_token']))
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cookie: PHPSESSID=' . $query['sid_token']));
        }

        $output = curl_exec($ch);

        $response = json_decode($output, true);

        $data = array();
        switch(curl_getinfo($ch, CURLINFO_HTTP_CODE))
        {
            case 200:
                $data['status'] = 'success';
                $data['data'] = $response;
                break;
            default:
                $data['status'] = 'error';
                $data['message'] = $response;
                break;
        }

        curl_close($ch);
        return $data;
    }

    /**
     * HTTP POST using cURL
     *
     * @param string $action
     * @param array $query
     * @return array|mixed
     */
    public function transmit($action, array $query)
    {
        $url = $this->url;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->build_query($action, $query));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);

        $response = json_decode($output, true);

        $data = array();
        switch(curl_getinfo($ch, CURLINFO_HTTP_CODE))
        {
            case 200:
                $data['status'] = 'success';
                $data['data'] = $response;
                break;
            default:
                $data['status'] = 'error';
                $data['message'] = $response;
                break;
        }

        curl_close($ch);
        return $data;
    }
}
