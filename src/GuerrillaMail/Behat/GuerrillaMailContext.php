<?php

namespace Comicrelief\GuerrillaMail\Behat;

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;
use Comicrelief\GuerrillaMail\GuerrillaConnect\CurlConnection;
use Comicrelief\GuerrillaMail\GuerrillaMail;

/**
 * Class GuerrillaMailContext
 *
 * @package Comicrelief\GuerrillaMail\Behat
 */
class GuerrillaMailContext extends RawMinkContext implements Context {

  CONST FIELD_MAIL_SUBJECT = 'mail_subject';
  CONST FIELD_MAIL_BODY = 'mail_excerpt';
  CONST FIELD_MAIL_ID = 'mail_id';

  /**
   * @var \Comicrelief\GuerrillaMail\GuerrillaMail
   */
  private $mailer;

  /**
   * @var \Comicrelief\GuerrillaMail\Model\EmailAddressModel
   */
  private $emailAddressModel;

  /**
   * @var array
   */
  private $cachedEmails = [];

  /**
   * EmailContext constructor.
   */
  public function __construct() {
    $this->generateNewEmailAccount();
  }

  /**
   * @Then I generate a new test email address
   */
  public function iGenerateANewTestEmailAddress()
  {
    $this->generateNewEmailAccount();
  }

  /**
   * Fills in form field with specified id|name|label|value.
   * Example: When I fill in the "email" field with a test email address
   *
   * @Then I fill in the :arg1 field with a test email address
   */
  public function iFillInTheFieldWithATestEmailAddress($field)
  {
    $field = str_replace('\\"', '"', $field);
    $value = $this->emailAddressModel->getEmailAddress();
    $this->getSession()->getPage()->fillField($field, $value);
  }

  /**
   * Checks to see if an email exists with a value in the body.
   * Example: Then I should receive an email with "test" in the body
   *
   * @Then I should receive an email with :arg1 in the body
   */
  public function iShouldReceiveAnEmailWithInTheBody($arg1)
  {
    $this->checkEmailFieldForContents($this::FIELD_MAIL_BODY, $arg1);
  }

  /**
   * Checks to see if an email exists with a value in the subject.
   * Example: Then I should receive an email with "test" in the subject
   *
   * @Then I should receive an email with :arg1 in the subject
   */
  public function iShouldReceiveAnEmailWithInTheSubject($arg1)
  {
    $this->checkEmailFieldForContents($this::FIELD_MAIL_SUBJECT, $arg1);
  }

  /**
   * @Then I should receive an email with :arg1 in the body and :arg2 in the subject
   */
  public function iShouldReceiveAnEmailWithInTheBodyAndInTheSubject($arg1, $arg2)
  {
    $emailId = $this->checkEmailFieldForContents($this::FIELD_MAIL_BODY, $arg1);
    $this->checkEmailFieldForContents($this::FIELD_MAIL_SUBJECT, $arg2, $emailId);
  }

  /**
   * Check created email account to see if an email exists with a value in a field.
   * @param $field
   * @param $contents
   * @param null $emailId
   *
   * @return mixed
   * @throws \Exception
   */
  private function checkEmailFieldForContents($field, $contents, $emailId = null)
  {
    $loweredContents = strtolower($contents);

    for ($attempts = 0; $attempts <= 60; $attempts++) {
      // Fetch and cache the emails.
      $this->cacheEmails();

      if (count($this->cachedEmails) >= 1) {
        foreach ($this->cachedEmails as $email) {
          // Test to see if the contents of the field matches the defined field.
          $contentsTest = strpos(strtolower($email[$field]), $loweredContents) >= 0;

          // If the email is set then add extra check to make sure the email contains the id.
          if ($emailId && $contentsTest && $emailId == $email[$this::FIELD_MAIL_ID]) {
            return $email[$this::FIELD_MAIL_ID];
          } else if (!$emailId && $contentsTest) {
            return $email[$this::FIELD_MAIL_ID];
          }
        }
      }

      sleep(1.5);
    }

    throw new \Exception('Email does not exist');
  }

  /**
   * Checks and Caches emails for future testing against.
   */
  private function cacheEmails()
  {
    // Get the emails from guerilla mail.
    $inbox = $this->mailer->checkEmail();

    // Test to see if there are any emails in the inbox.
    if ($inbox['count'] >= 1) {
      // Loop through the emails and cache them/
      foreach ($inbox['list'] as $email) {
        $this->cachedEmails[$email[$this::FIELD_MAIL_ID]] = $email;
      }
    }
  }

  /**
   * Generate a new email account.
   */
  private function generateNewEmailAccount()
  {
    $connection = new CurlConnection("127.0.0.1", "GuerrillaMail_Library");

    //The second parameter is the client's sid (optional)
    $this->mailer = new GuerrillaMail($connection);

    $this->emailAddressModel = $this->mailer->getEmailAddress();
    $this->cachedEmails = [];
  }

  /**
   * Fills in form field with specified CSS locator
   *
   * @Then I fill in the css :locator field with a test email address
   */
  public function iFillInTheLocatorFieldWithATestEmailAddress($locator)
  {
    $field = str_replace('\\"', '"', $locator);
    $value = $this->emailAddressModel->getEmailAddress();
    $this->getSession()->getPage()->find('css',$locator)->setValue($value);
  }

}
