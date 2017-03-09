<?php

namespace ZendLogPushover\Message;

use Pushy;

/**
 * Date: 09/03/2017
 * @author Paolo Agostinetto <paul.ago@gmail.com>
 */
class DefaultMessageFactory implements MessageFactoryInterface {

	/**
	 * @param string $line
	 * @return Pushy\Message
	 */
	public function factory($line) {

		$message = new Pushy\Message($line);
		$message->setPriority(new Pushy\Priority\HighPriority());
		$message->setTitle("Log from your application");
		$message->setSound(new Pushy\Sound\SirenSound());

		return $message;
	}

}