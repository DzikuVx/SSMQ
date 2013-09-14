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
	/**
	 * @var string
	 */
	static private $dbHost;
	/**
	 * @var string
	 */
	static private $dbLogin;
	/**
	 * @var string
	 */
	static private $dbPassword;
	/**
	 * @var string
	 */
	static private $dbName;
	
	/**
	 * @var \mysqli
	 */
	static private $dbConnection;
	
	/**
	 * @var int
	 */
	private $queueId;
	
	/**
	 * Set database credentials
	 * @param string $dbHost
	 * @param string $dbLogin
	 * @param string $dbPassword
	 * @param string $dbName
	 */
	static public function setCredentials($dbHost = 'localhost', $dbLogin = 'ssmq', $dbPassword = 'ssmq', $dbName = 'ssmq') {
		self::$dbHost = $dbHost;
		self::$dbLogin = $dbLogin;
		self::$dbPassword = $dbPassword;
		self::$dbName = $dbName;
	}

	/**
	 * Method gets id of current queue
	 */
	private function getQueueId() {
		$oQuery = self::$dbConnection->query("SELECT * FROM `queues` WHERE `name`='{$this->sQueueName}'");
		
		while ($tResult = $oQuery->fetch_object()) {
			$this->queueId = $tResult->idqueues;
		}
		
		if (empty($this->queueId)) {
			self::$dbConnection->query("INSERT INTO `queues`(name) VALUES('{$this->sQueueName}')");
			$this->queueId = self::$dbConnection->insert_id;
		}
		
	}
	
	public function __construct($sName) {

		/*
		 * If db connection not created, create new one
		 */
		if (empty(static::$dbConnection)) {
			self::$dbConnection = new \mysqli(self::$dbHost, self::$dbLogin, self::$dbPassword, self::$dbName);
		}
		
		$this->sQueueName = $sName;

		$this->getQueueId();
	}

	/**
	 * (non-PHPdoc)
	 * @see SSMQ.Base::push()
	 */
	public function push($sMessage, $sRecipient = null, $aAttributes = null) {
		
		if (!empty($sRecipient)) {
			$sRecipient = "'" . $sRecipient . "'";
		} else {
			$sRecipient = "null";
		}
		
		if (!empty($aAttributes)) {
			$aAttributes = "'" . json_encode($aAttributes) . "'";
		} else {
			$aAttributes = "null";
		}
		
		self::$dbConnection->query("INSERT INTO `messages`(
				idqueues,
				recipient,
				message,
				attributes				
			) VALUES(
				'{$this->queueId}',
				{$sRecipient},
				'{$sMessage}',
				{$aAttributes}
			)");
		
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see SSMQ.Base::pop()
	 */
	public function pop($sRecipient = null) {
		
		$retVal 	= null;
		$iMessageId = null;
		
		if (!empty($sRecipient)) {
			$sRecipient = " = '" . $sRecipient . "'";
		} else {
			$sRecipient = " IS NULL";
		}
		
		$oQuery = self::$dbConnection->query("SELECT 
				* 
			FROM 
				`messages` 
			WHERE 
				idqueues = {$this->queueId} AND
				recipient {$sRecipient}
			ORDER BY
				idmessages
			LIMIT 
				1
			");
		
		while ($tResult = $oQuery->fetch_object()) {
			
			$retVal 			= new Message();
			$retVal->recipient 	= $tResult->recipient;
			$retVal->message 	= $tResult->message;
			$retVal->attributes = json_decode($tResult->attributes);
			$iMessageId 		= $tResult->idmessages;
		
		}

		/*
		 * Drop returned message
		 */
		if (!empty($iMessageId)) {
			self::$dbConnection->query("DELETE FROM `messages` WHERE idmessages={$iMessageId}");
		}
		
		return $retVal;
	}
	
}