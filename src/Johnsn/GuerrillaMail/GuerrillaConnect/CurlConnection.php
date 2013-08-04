<?php

namespace Johnsn\GuerrillaMail\GuerrillaConnect;

class CurlConnection implements GuerrillaConnectInterface
{
    protected $url = 'http://api.guerrillamail.com/ajax.php';

    public function __construct($url = '')
    {
        if(!empty($url))
        {
            $this->url = $url;
        }
    }

    public function retrieve($query)
    {
        $url = $this->url . '?'.http_build_query($query);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
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

    public function transmit($query)
    {
        $url = $this->url;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);

        var_dump(http_build_query($query));

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
