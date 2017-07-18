<?php

namespace Comicrelief\GuerrillaMail\Behat;

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;
use Comicrelief\GuerrillaMail\GuerrillaConnect\CurlConnection;
use Comicrelief\GuerrillaMail\GuerrillaMail;

class GuerrillaMailContext extends RawMinkContext implements Context {

  /**
   * @var \Comicrelief\GuerrillaMail\GuerrillaMail
   */
  private $mailer;

  /**
   * @var \Comicrelief\GuerrillaMail\Model\EmailAddressModel
   */
  private $emailAddressModel;

  /**
   * EmailContext constructor.
   */
  public function __construct() {
    $connection = new CurlConnection("127.0.0.1", "GuerrillaMail_Library");

    //The second parameter is the client's sid (optional)
    $this->mailer = new GuerrillaMail($connection);

    $this->emailAddressModel = $this->mailer->getEmailAddress();
  }

  /**
   * Fills in form field with specified id|name|label|value
   * Example: When I fill in the "email" email field with a test email address
   *
   * @When /^(?:|I )fill in the "(?P<field>(?:[^"]|\\")*)" email field with a test email address
   */
  public function fillFieldWithTestEmailAddress($field)
  {
    $field = str_replace('\\"', '"', $field);
    $value = $this->emailAddressModel->getEmailAddress();
    $this->getSession()->getPage()->fillField($field, $value);
  }

}
