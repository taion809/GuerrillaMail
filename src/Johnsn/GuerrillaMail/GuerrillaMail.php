<?php

namespace Johnsn\GuerrillaMail;

class GuerrillaMail
{
    private $connection = null;

    private $sid = null;

    private $domains = array(
        'guerrillamailblock.com',
    );

    public function __construct($connection, $sid = null)
    {
        $this->connection = $connection;

        $this->sid = $sid;
    }

    public function __get($key)
    {
        return $this->$key;
    }

    public function get_email_address($lang = 'en')
    {
        $action = "get_email_address";
        $options = array(
            'lang' => $lang
        );

        $response = $this->_retrieve($action, $options);

        if(isset($response['sid_token']))
        {
            $this->sid = $response['sid_token'];
        }

        return $response;
    }

    public function check_email()
    {
        $action = "check_email";
        $options = array(
            'seq' => 0,
            'sid' => $this->sid
        );

        return $this->_retrieve($action, $options);
    }

    public function get_email_list($offset = 0, $seq = null)
    {
        $action = "get_email_list";
        $options = array(
            'offset' => $offset,
            'sid' => $this->sid
        );

        if(!empty($seq))
        {
            $options['seq'] = $seq;
        }

        return $this->_retrieve($action, $options);
    }

    public function fetch_email($email_id)
    {
        $action = "fetch_email";
        $options = array(
            'email_id' => $email_id,
            'sid' => $this->sid
        );

        return $this->_retrieve($action, $options);
    }

    public function set_email_address($email_user, $lang = 'en')
    {
        $action = "set_email_user";
        $options = array(
            'email_user' => $email_user,
            'lang' => $lang,
            'sid' => $this->sid
        );

        return $this->_transmit($action, $options);
    }

    public function forget_me($email_address)
    {
        $action = "forget_me";
        $options = array(
            'email_addr' => $email_address,
            'sid' => $this->sid
        );

        return $this->_transmit($action, $options);
    }

    public function del_email(array $email_ids)
    {
        $action = "forget_me";
        $options = array(
            'email_ids' => $email_ids,
            'sid' => $this->sid
        );

        return $this->_transmit($action, $options);
    }

    private function _retrieve($action, $options)
    {
        $response = $this->connection->retrieve($action, $options);

        if($response['status'] == 'error')
        {
            return false;
        }

        return $response['data'];
    }

    private function _transmit($action, $options)
    {
        $response = $this->connection->transmit($action, $options);

        if($response['status'] == 'error')
        {
            return false;
        }

        return $response['data'];
    }


}
