<?php

namespace Comicrelief\GuerrillaMail;

/**
 * Class GuerrillaMail
 *
 * @package Comicrelief\GuerrillaMail
 */
class GuerrillaMail
{
    /**
     * Connection Object
     * @var null
     */
    private $connection = null;

    /**
     * sid_token
     * @var null
     */
    private $sid_token = null;

    /**
     * Available domains
     * @var array
     */
    private $domains = array(
        'guerrillamailblock.com',
    );

    /**
     * @param $connection
     * @param null $sid_token
     */
    public function __construct($connection, $sid_token = null)
    {
        $this->connection = $connection;

        $this->sid_token = $sid_token;
    }

    public function __get($key)
    {
        return $this->$key;
    }

    /**
     * Fetch new email address.
     *
     * @param string $lang
     * @return mixed
     */
    public function getEmailAddress($lang = 'en')
    {
        $action = "get_email_address";
        $options = array(
            'lang' => $lang
        );

        $response = $this->_retrieve($action, $options);

        return new EmailAddressModel($response);
    }

    /**
     * Fetch new emails
     * @return mixed
     */
    public function checkEmail()
    {
        $action = "check_email";
        $options = array(
            'seq' => 0,
            'sid_token' => $this->sid_token
        );

        return $this->_retrieve($action, $options);
    }

    /**
     * @param int $offset
     * @param int $seq
     * @return mixed
     */
    public function getEmailList($offset = 0, $seq = 0)
    {
        $action = "get_email_list";
        $options = array(
            'offset' => $offset,
            'sid_token' => $this->sid_token
        );

        if(!empty($seq))
        {
            $options['seq'] = $seq;
        }

        return $this->_retrieve($action, $options);
    }

    /**
     * @param $email_id
     * @return bool
     */
    public function fetchEmail($email_id)
    {
        $action = "fetch_email";
        $options = array(
            'email_id' => $email_id,
            'sid_token' => $this->sid_token
        );

        return $this->_retrieve($action, $options);
    }

    /**
     * @param $email_user
     * @param string $lang
     * @return bool
     */
    public function setEmailAddress($email_user, $lang = 'en')
    {
        $action = "set_email_user";
        $options = array(
            'email_user' => $email_user,
            'lang' => $lang,
            'sid_token' => $this->sid_token
        );

        return $this->_transmit($action, $options);
    }

    /**
     * @param $email_address
     * @return bool
     */
    public function forgetMe($email_address)
    {
        $action = "forget_me";
        $options = array(
            'email_addr' => $email_address,
            'sid_token' => $this->sid_token
        );

        return $this->_transmit($action, $options);
    }

    /**
     * @param $email_ids
     * @return bool
     */
    public function deleteEmail($email_ids)
    {
        $action = "del_email";
        $options = array(
            'email_ids' => $email_ids,
            'sid_token' => $this->sid_token
        );

        return $this->_transmit($action, $options);
    }

    /**
     * @param $action
     * @param $options
     * @return bool
     */
    private function _retrieve($action, $options)
    {
        $response = $this->connection->retrieve($action, $options);

        if($response['status'] == 'error')
        {
            return false;
        }

        if(isset($response['data']['sid_token']))
        {
            $this->sid_token = $response['data']['sid_token'];
        }

        return $response['data'];
    }

    /**
     * @param $action
     * @param $options
     * @return bool
     */
    private function _transmit($action, $options)
    {
        $response = $this->connection->transmit($action, $options);

        if($response['status'] == 'error')
        {
            return false;
        }

        if(isset($response['data']['sid_token']))
        {
            $this->sid_token = $response['data']['sid_token'];
        }

        return $response['data'];
    }


}
