<?php

namespace Comicrelief\GuerrillaMail\Model;

/**
 * Class EmailAddressModel
 *
 * @package Comicrelief\GuerrillaMail
 */
class EmailAddressModel {

  /**
   * @var string
   */
  private $emailAddress;

  /**
   * @var int
   */
  private $created;

  /**
   * @var string
   */
  private $alias;

  /**
   * @var string
   */
  private $sid;

  /**
   * EmailAddressModel constructor.
   *
   * @param $array
   */
  public function __construct($array) {
    $this->emailAddress = $array['email_addr'];
    $this->created = $array['email_timestamp'];
    $this->alias = $array['alias'];
    $this->sid = $array['sid_token'];
  }

  /**
   * @return mixed
   */
  public function getEmailAddress() {
    return $this->emailAddress;
  }

  /**
   * @return mixed
   */
  public function getCreated() {
    return $this->created;
  }

  /**
   * @return mixed
   */
  public function getAlias() {
    return $this->alias;
  }

  /**
   * @return mixed
   */
  public function getSid() {
    return $this->sid;
  }

}
