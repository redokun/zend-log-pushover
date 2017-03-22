<?php

namespace ZendLogPushoverTest\Writer;

use Pushy\Message;
use Pushy\User;
use Zend\Log\Logger;
use ZendLogPushover\Message\DefaultMessageFactory;
use ZendLogPushover\Message\MessageFactoryInterface;
use ZendLogPushover\Writer\Pushover;

class PushoverTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @expectedException \ZendLogPushover\PushoverLoggerException
	 */
	public function testShouldFailBecauseMissingUser() {

		/** @var \Pushy\Client $client */
		$client = $this->getMockBuilder(\Pushy\Client::class)
			->disableOriginalConstructor()
			->getMock();

		$writer = new Pushover();
		$writer->setClient($client);

		$this->invokeMethod($writer, "doWrite", [$this->getEventData()]);
	}

	/**
	 * @expectedException \ZendLogPushover\PushoverLoggerException
	 */
	public function testShouldFailBecauseMissingClient() {

		$user = $this->getUserMock();

		$writer = new Pushover();
		$writer->setUser($user);

		$this->invokeMethod($writer, "doWrite", [$this->getEventData()]);
	}

	public function testShouldWork() {

		$client = $this->getClientMock();
		$user = $this->getUserMock();

		$writer = new Pushover();
		$writer->setUser($user);
		$writer->setClient($client);

		$this->assertInstanceOf(MessageFactoryInterface::class, $writer->getMessageFactory());

		// Test message factory

		/** @var \PHPUnit_Framework_MockObject_MockObject|DefaultMessageFactory $messageFactory */
		$messageFactory = $this->getMockBuilder(DefaultMessageFactory::class)
			->disableOriginalConstructor()
			->getMock();

		$messageFactory->expects($this->once())
			->method("factory")
			->willReturnCallback(function(){
				return new Message("Test message");
			});

		$writer->setMessageFactory($messageFactory);

		// Call
		$this->invokeMethod($writer, "doWrite", [$this->getEventData()]);
	}

	public function testFlushQueue() {

		$client = $this->getClientMock();
		$user = $this->getUserMock();

		$client->expects($this->once())
			->method('sendMessage')
			->with($this->isInstanceOf(Message::class));

		$writer = new Pushover();
		$writer->setUser($user);
		$writer->setClient($client);

		// Call doWrite
		$this->invokeMethod($writer, "doWrite", [$this->getEventData()]);

		$this->assertCount(1, $writer->getMessages());

		$writer->flushMessages();

		$this->assertCount(0, $writer->getMessages());
	}

	/**
	 * @return array
	 */
	public function getEventData() {
		return [
			'timestamp' => new \DateTime(),
			'priority' => Logger::DEBUG,
			'message' => "Test entry",
		];
	}

	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject|\Pushy\User
	 */
	public function getUserMock() {
		$user = $this->getMockBuilder(\Pushy\User::class)
			->disableOriginalConstructor()
			->getMock();

		return $user;
	}

	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject|\Pushy\Client
	 */
	public function getClientMock() {
		$client = $this->getMockBuilder(\Pushy\Client::class)
			->disableOriginalConstructor()
			->getMock();

		return $client;
	}

	/**
	 * Call protected/private method of a class.
	 *
	 * @param object &$object Instantiated object that we will run method on.
	 * @param string $methodName Method name to call
	 * @param array $parameters Array of parameters to pass into method.
	 *
	 * @return mixed Method return.
	 */
	public function invokeMethod(&$object, $methodName, array $parameters = array()) {
		$reflection = new \ReflectionClass(get_class($object));
		$method = $reflection->getMethod($methodName);
		$method->setAccessible(true);

		return $method->invokeArgs($object, $parameters);
	}
}
