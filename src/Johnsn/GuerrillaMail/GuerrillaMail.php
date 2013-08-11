<?php

namespace Johnsn\GuerrillaMail;

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
     * Fetch new email address or 
     * resume previous state if $this->sid_token != NULL
     *
     * @param string $lang
     * @return mixed
     */
    public function get_email_address($lang = 'en')
    {
        $action = "get_email_address";
        $options = array(
            'lang' => $lang,
            'sid_token' => $this->sid_token,
        );

        return $this->_retrieve($action, $options);
    }

    /**
     * Fetch up to 20 new emails starting from the oldest email.
     * If $seq is set, return up to 20 new emails starting from $seq
     *
     * @param int $seq mail_id sequence number starting point
     * @return mixed
     */
    public function check_email($seq = 0)
    {
        $action = "check_email";
        $options = array(
            'seq' => $seq,
            'sid_token' => $this->sid_token
        );

        return $this->_retrieve($action, $options);
    }

    /**
     * Fetch up to 20 new emails starting from the oldest email.
     * If $offset is set, skip to the offset value (0 - 19)
     * If $seq is set, return up to 20 new emails starting from $seq
     *
     * @param int $offset number of items to skip (0 - 19)
     * @param int $seq mail_id sequence number starting point
     * @return mixed
     */
    public function get_email_list($offset = 0, $seq = 0)
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
     * Return email based on $email_id
     *
     * @param $email_id mail_id of the requested email
     * @return bool
     */
    public function fetch_email($email_id)
    {
        $action = "fetch_email";
        $options = array(
            'email_id' => $email_id,
            'sid_token' => $this->sid_token
        );

        return $this->_retrieve($action, $options);
    }

    /**
     * Change users email address
     *
     * @param $email_user
     * @param string $lang
     * @return bool
     */
    public function set_email_address($email_user, $lang = 'en')
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
     * Forget users email and sid_token
     *
     * @param $email_address
     * @return bool
     */
    public function forget_me($email_address)
    {
        $action = "forget_me";
        $options = array(
            'email_addr' => $email_address,
            'sid_token' => $this->sid_token
        );

        return $this->_transmit($action, $options);
    }

    /**
     * Delete the emails matching the array of mail_id's in $email_ids
     * @param $email_ids list of mail_ids to delete from the server.
     * @return bool
     */
    public function del_email($email_ids)
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
