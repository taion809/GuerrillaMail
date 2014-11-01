<?php

namespace GuerrillaMail\GuerrillaConnect;

/**
 * Class CurlConnection
 * @package GuerrillaMail\GuerrillaConnect
 */
class CurlConnection extends Connection
{
    /**
     * @param string $ip      Client IP
     * @param string $agent   Client Agent
     * @param string $url     API Endpoint
     * @param string $domain  Site's master domain
     * @param string $api_key API Key
     */
    public function __construct($ip, $agent = '', $url = '', $domain='', $api_key='')
    {
        $this->ip = $ip;

        if (!empty($agent)) {
            $this->agent = $agent;
        }

        if (!empty($url)) {
            $this->url = $url;
        }

        if (!empty($domain)) {
            $this->domain = $domain;
        }

        if (!empty($api_key)) {
            $this->api_key = $api_key;
        }
    }

    /**
     * @param $sid_token
     *
     * @return null|string
     */
    protected function get_api_token($sid_token)
    {
        $ret = null;
        if (!empty($this->api_token)) {
            $ret = $this->api_token;
        } elseif (!empty($this->api_key)) {
            $this->api_token = hash_hmac('sha256', $sid_token, $this->api_key);
            $ret = $this->api_token;
        }

        return $ret;
    }

    /**
     * HTTP GET using cURL
     *
     * @param  string      $action
     * @param  array       $query
     * @return array|mixed
     */
    public function retrieve($action, array $query)
    {
        $headers  = array();
        $url = $this->url . '?'. $this->build_query($action, $query);

        $ch = curl_init();
        $curl_options = array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL            => $url,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_CAINFO         => dirname(__FILE__) . '/cacert.pem',
        );

        curl_setopt_array($ch, $curl_options);

        if (isset($query['sid_token'])) {
            $headers[] = 'Cookie: PHPSESSID=' . $query['sid_token'];
            if ($api_token = $this->get_api_token($query['sid_token'])) {
                $headers[] = 'Authorization: ApiToken ' . $api_token;
            }
        }

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $output = curl_exec($ch);

        $response = json_decode($output, true);

        $data = array();
        switch (curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
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
     * @param  string      $action
     * @param  array       $query
     * @return array|mixed
     */
    public function transmit($action, array $query)
    {
        $headers  = array();
        $url = $this->url;
        if (isset($query['sid_token'])) {
            if ($api_token = $this->get_api_token($query['sid_token'])) {
                $headers[] = 'Authorization: ApiToken ' . $api_token;
            }
        }

        $ch = curl_init();
        $curl_options = array(
            CURLOPT_POST           => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL            => $url,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_CAINFO         => dirname(__FILE__) . '/cacert.pem',
            CURLOPT_POSTFIELDS     => $this->build_query($action, $query)
        );

        curl_setopt_array($ch, $curl_options);

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $output = curl_exec($ch);

        $response = json_decode($output, true);

        $data = array();
        switch (curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
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
