<?php

namespace ZendLogPushover\Writer;

use Pushy;
use Zend\Log\Formatter\FormatterInterface;
use ZendLogPushover\Message\DefaultMessageFactory;
use ZendLogPushover\Message\MessageFactoryInterface;
use ZendLogPushover\PushoverLoggerException;

/**
 * Date: 08/03/2017
 * @author Paolo Agostinetto <paul.ago@gmail.com>
 */
class Pushover extends \Zend\Log\Writer\AbstractWriter {

	/**
	 * @var Pushy\Client
	 */
	protected $client;

	/**
	 * @var Pushy\User
	 */
	protected $user;

	/**
	 * @var FormatterInterface
	 */
	protected $formatter;

	/**
	 * @var MessageFactoryInterface
	 */
	protected $messageFactory;

	/**
	 * Constructor
	 *
	 * Set options for a writer. Accepted options are:
	 * - filters: array of filters to add to this filter
	 * - formatter: formatter for this writer
	 *
	 * @param  array|\Traversable $options
	 */
	public function __construct(array $options = []) {
		parent::__construct($options);

		if ($this->formatter === null) {
			$this->formatter = new \Zend\Log\Formatter\Simple([
				'dateTimeFormat' => 'Y-m-d H:i:s',
				'format' => "Priority: %priorityName% (%priority%)\nDate: %timestamp%\n%message% %extra%",
			]);
		}

		$this->messageFactory = new DefaultMessageFactory();
	}

	/**
	 * @return Pushy\Client
	 */
	public function getClient() {
		return $this->client;
	}

	/**
	 * @param Pushy\Client $client
	 */
	public function setClient($client) {
		$this->client = $client;
	}

	/**
	 * @return Pushy\User
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @param Pushy\User $user
	 */
	public function setUser($user) {
		$this->user = $user;
	}

	/**
	 * @return MessageFactoryInterface
	 */
	public function getMessageFactory() {
		return $this->messageFactory;
	}

	/**
	 * @param MessageFactoryInterface $messageFactory
	 */
	public function setMessageFactory(MessageFactoryInterface $messageFactory) {
		$this->messageFactory = $messageFactory;
	}

	/**
	 * @param array $event
	 * @throws PushoverLoggerException
	 */
	protected function doWrite(array $event) {
		if(!$this->user){
			throw new PushoverLoggerException("Pushover user is not set");
		}

		if(!$this->client){
			throw new PushoverLoggerException("Pushover client is not set");
		}

		$line = $this->formatter->format($event);

		// Message
		$message = $this->messageFactory->factory($line);
		$message->setUser($this->user);

		// Send
		$this->client->sendMessage($message);
	}

}