<?php

namespace GuerrillaMail;

use GuerrillaMail\Connection\ConnectionInterface;
use GuerrillaMail\Connection\GuzzleConnection;
use GuzzleHttp\Client;

/**
 * Class GuerrillaMail
 * @package GuerrillaMail
 */
class GuerrillaMail
{
    /**
     * @var ConnectionInterface|null
     */
    private $client = null;

    /**
     * sid_token
     * @var null
     */
    private $sid_token = null;

    /**
     * @param Connection\ConnectionInterface $client
     * @param null $sid_token
     */
    public function __construct(ConnectionInterface $client, $sid_token = null)
    {
        $this->client = $client;
        $this->sid_token = $sid_token;
    }

    /**
     * @param null $sid_token
     * @return GuerrillaMail
     */
    public static function make($sid_token = null)
    {
        return new self(GuzzleConnection::make(), $sid_token);
    }

    /**
     * Fetch new email address or 
     * resume previous state if $this->sid_token != NULL
     *
     * @param string $lang
     * @return mixed
     */
    public function getEmailAddress($lang = 'en')
    {
        $action = "get_email_address";
        $options = array(
            'lang' => $lang,
            'sid_token' => $this->sid_token,
        );

        return $this->client->get($action, $options);
    }

    /**
     * Fetch up to 20 new emails starting from the oldest email.
     * If $seq is set, return up to 20 new emails starting from $seq
     *
     * @param int $seq mail_id sequence number starting point
     * @return mixed
     */
    public function checkEmail($seq = 0)
    {
        $action = "check_email";
        $options = array(
            'seq' => $seq,
            'sid_token' => $this->sid_token
        );

        return $this->client->get($action, $options);
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

        return $this->client->get($action, $options);
    }

    /**
     * Return email based on $email_id
     *
     * @param $email_id mail_id of the requested email
     * @return bool
     */
    public function fetchEmail($email_id)
    {
        $action = "fetch_email";
        $options = array(
            'email_id' => $email_id,
            'sid_token' => $this->sid_token
        );

        return $this->client->get($action, $options);
    }

    /**
     * Change users email address
     *
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

        return $this->client->post($action, $options);
    }

    /**
     * Forget users email and sid_token
     *
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

        return $this->client->post($action, $options);
    }

    /**
     * Delete the emails matching the array of mail_id's in $email_ids
     * @param $email_ids list of mail_ids to delete from the server.
     * @return bool
     */
    public function delEmail($email_ids)
    {
        $action = "del_email";
        $options = array(
            'email_ids' => $email_ids,
            'sid_token' => $this->sid_token
        );

        return $this->client->post($action, $options);
    }
}
