<?php
namespace SSMQ;

class Message {
	public $message;
	public $attributes;
	public $recipient;
}

abstract class Base {
	
	/**
	 * @var string
	 */
	protected $sQueueName;
	
	/**
	 * Method pushes message into queue
	 * @param string $sMessage
	 * @param string $sRecipient
	 * @param array $aAttributes
	 */
	public function push($sMessage, $sRecipient = null, $aAttributes = null) {
	
		return $this;
	}
	
	/**
	 * Memthod returns first message from queue mathing recipient
	 * @param string $sRecipient
	 * @return Message
	 */
	public function pop($sRecipient = null) {
		
	}
	
	public function __construct($sName) {
		$this->sQueueName = $sName;	
	}
}

class MySqlQueue extends Base {
	
}