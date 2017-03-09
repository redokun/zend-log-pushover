<?php

namespace ZendLogPushover\Message;

use Pushy;

/**
 * Date: 09/03/2017
 * @author Paolo Agostinetto <paul.ago@gmail.com>
 */
interface MessageFactoryInterface {

	/**
	 * @param string $line
	 * @return Pushy\Message
	 */
	public function factory($line);
}