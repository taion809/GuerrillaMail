<?php

namespace GuerrillaMail;

class GuerrillaMailTest extends \PHPUnit_Framework_TestCase
{
    public function testGetEmailAddressNotEmpty()
    {
    	$response = array(
            'email_addr' => 'a@guerrillamailblock.com',
            'email_timestamp' => time(),
            'alias' => 'test_alias',
            'sid_token' => '2cvob6bud4l6iqb61tvnklc7i7',
		);

    	$connection = $this->getMockBuilder("GuerrillaMail\\Connection\\ConnectionInterface")
            ->setConstructorArgs([])
    		->getMock();

		$connection->expects($this->once())
			->method('get')
			->will($this->returnValue($response));

    	$gm = new GuerrillaMail($connection);

    	$email = $gm->get_email_address();

    	$this->assertNotEmpty($email);
    }

    public function testGetEmailAddressReturnsEmailAddress()
    {
        $response = array(
            'email_addr' => 'a@guerrillamailblock.com',
            'email_timestamp' => time(),
            'alias' => 'test_alias',
            'sid_token' => '2cvob6bud4l6iqb61tvnklc7i7',
        );

        $connection = $this->getMockBuilder("GuerrillaMail\\Connection\\ConnectionInterface")
            ->setConstructorArgs([])
            ->getMock();

        $connection->expects($this->once())
            ->method('get')
            ->will($this->returnValue($response));

        $gm = new GuerrillaMail($connection);

    	$email = $gm->get_email_address();

    	$this->assertArrayHasKey('email_addr', $email);
    }

    public function testCheckEmailReturnsEmails()
    {
        $response = array(
            'list' => [],
            'email_addr' => 'a@guerrillamailblock.com',
            'email_timestamp' => time(),
            'alias' => 'test_alias',
            'sid_token' => '2cvob6bud4l6iqb61tvnklc7i7',
        );

    	$sid = '2cvob6bud4l6iqb61tvnklc7i7';

        $connection = $this->getMockBuilder("GuerrillaMail\\Connection\\ConnectionInterface")
            ->setConstructorArgs([])
            ->getMock();

        $connection->expects($this->once())
            ->method('get')
            ->will($this->returnValue($response));

        $gm = new GuerrillaMail($connection, $sid);

    	$email = $gm->check_email();

    	$this->assertArrayHasKey('list', $email);
    }

    public function testGetEmailListReturnsListOfEmailsFromNoOffset()
    {
        $response = array(
            'list' => [],
            'email_addr' => 'a@guerrillamailblock.com',
            'email_timestamp' => time(),
            'alias' => 'test_alias',
            'sid_token' => '2cvob6bud4l6iqb61tvnklc7i7',
        );

    	$sid = '2cvob6bud4l6iqb61tvnklc7i7';

        $connection = $this->getMockBuilder("GuerrillaMail\\Connection\\ConnectionInterface")
            ->setConstructorArgs([])
            ->getMock();

        $connection->expects($this->once())
            ->method('get')
            ->will($this->returnValue($response));

        $gm = new GuerrillaMail($connection, $sid);

    	$email = $gm->check_email();

    	$this->assertArrayHasKey('list', $email);
    }

    public function testFetchEmailReturnsEmailBasedOnID()
    {
    	$response = array(
            'mail_id' => 1
		);

    	$sid = '2cvob6bud4l6iqb61tvnklc7i7';
    	$mail_id = 1;

        $connection = $this->getMockBuilder("GuerrillaMail\\Connection\\ConnectionInterface")
            ->setConstructorArgs([])
            ->getMock();

        $connection->expects($this->once())
            ->method('get')
            ->will($this->returnValue($response));

        $gm = new GuerrillaMail($connection, $sid);

    	$email = $gm->fetch_email($mail_id);

    	$this->assertEquals($email['mail_id'], 1);
    }

    public function testSetEmailAddressReturnsNewEmail()
    {
    	$response = array(
            'email_addr' => 'new_email@guerrillamailblock.com',
            'email_timestamp' => time(),
            'sid_token' => '2cvob6bud4l6iqb61tvnklc7i7',
		);

		$sid = '2cvob6bud4l6iqb61tvnklc7i7';
		$expectedEmail = 'new_email@guerrillamailblock.com';

        $connection = $this->getMockBuilder("GuerrillaMail\\Connection\\ConnectionInterface")
            ->setConstructorArgs([])
            ->getMock();

        $connection->expects($this->once())
            ->method('post')
            ->will($this->returnValue($response));

        $gm = new GuerrillaMail($connection, $sid);

    	$email = $gm->set_email_address('new_email@guerrillamailblock.com');

    	$this->assertEquals($email['email_addr'], $expectedEmail);
    }

    public function testForgetMeReturnsTrue()
    {
    	$response = true;

		$sid = '2cvob6bud4l6iqb61tvnklc7i7';
		$forgetableEmail = 'new_email@guerrillamailblock.com';

        $connection = $this->getMockBuilder("GuerrillaMail\\Connection\\ConnectionInterface")
            ->setConstructorArgs([])
            ->getMock();

        $connection->expects($this->once())
            ->method('post')
            ->will($this->returnValue($response));

        $gm = new GuerrillaMail($connection, $sid);

    	$email = $gm->forget_me($forgetableEmail);

    	$this->assertTrue($email);
    }

    public function testDelEmailFromArrayOfIDs()
    {
    	$response = array(
            'deleted_ids' => array(1, 2, 3)
		);

		$sid = '2cvob6bud4l6iqb61tvnklc7i7';
		$deletableEmailIds = array(1, 2, 3);

        $connection = $this->getMockBuilder("GuerrillaMail\\Connection\\ConnectionInterface")
            ->setConstructorArgs([])
            ->getMock();

        $connection->expects($this->once())
            ->method('post')
            ->will($this->returnValue($response));

        $gm = new GuerrillaMail($connection, $sid);

    	$email = $gm->del_email($deletableEmailIds);

    	$this->assertEquals($email['deleted_ids'], $deletableEmailIds);
    }

}
