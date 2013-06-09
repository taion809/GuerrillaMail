<?php

namespace Johnsn\GuerrillaMail;

class GuerrillaMail
{
    protected $connection = null;

    protected $query = array();

    protected $sid = null;

    protected $domains = array(
        'guerrillamailblock.com',
    );

    //Should be an subclass of GuerrillaConnect
    public function __construct($connection, $ip, $agent, $sid = null)
    {
        $this->connection = $connection;

        $this->query['ip'] = $ip;
        $this->query['agent'] = $agent;

        $this->sid = $sid;
    }

    public function set_sid($sid)
    {
        $this->sid = $sid;
    }

    public function get_email_address($lang = 'en')
    {
        $query = $this->_build_query(array('f' => 'get_email_address', 'lang' => $lang));
        return $this->_receive($query);
    }

    public function check_email($sid)
    {
        $query = $this->_build_query(array('f' => 'check_email', 'seq' => 0), $sid);
        return $this->_receive($query);
    }

    public function get_email_list($sid, $offset = 0, $seq = null)
    {
        $query = $this->_build_query(array('f' => 'get_email_address', 'offset' => $offset), $sid);

        if(!empty($seq))
        {
            $query['seq'] = $seq;
        }

        return $this->_receive($query);
    }

    public function fetch_email($sid, $email_id)
    {
        $query = $this->_build_query(array('f' => 'fetch_email', 'email_id' => $email_id), $sid);
        $response = $this->_receive($query);

        return $response;
    }

    public function set_email_address($sid, $email, $lang = 'en')
    {
        $query = $this->_build_query(array('f' => 'set_email_user', 'lang' => $lang, 'email_user' => $email), $sid);
        return $this->_transmit($query);
    }

    public function forget_me($sid, $email)
    {
        $query = $this->_build_query(array('f' => 'forget_me', 'email_addr' => $email), $sid);
        return $this->_transmit($query);
    }

    public function del_email($sid, array $email_ids)
    {
        $query = $this->_build_query(array('f' => 'del_email', 'email_ids[]' => $email_ids), $sid);
        return $this->_transmit($query);
    }

    private function _build_query($param, $sid = null)
    {
        $query = array_merge($this->query, $param);

        if(!empty($sid))
        {
            $query['sid_token'] = $sid;
        }
        elseif(!empty($this->sid))
        {
            $query['sid_token'] = $this->sid;
        }

        return $query;
    }

    private function _receive($query)
    {
        $response = $this->connection->retrieve($query);

        if($response['status'] == 'error')
        {
            return false;
        }

        return $response['data'];
    }

    private function _transmit($query)
    {
        $response = $this->connection->transmit($query);

        if($response['status'] == 'error')
        {
            return false;
        }

        return $response['data'];
    }


}
