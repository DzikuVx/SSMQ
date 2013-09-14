<?php

namespace SSMQ;

require_once dirname(__FILE__) . '/src/Queue.php';

/**
 * SSMQ queues factory
 * @author pawel
 *
 */
class SSMQ {
	
	/**
	 * @var string
	 */
	private $sQueueType = 'mysql';
	
	/**
	 * @var array
	 */
	private $aQueues = array();
	
	/**
	 * @var SSMQ
	 */
	private $instance;
	
	/**
	 * Private constructor, factory is Singleton
	 */
	private function __construct() {
		
	}
	
	public static function getInstance() {
		
	}
	
	/**
	 * Method returns queue object
	 * @param string $sQueue queue name
	 * @param string $sQueueType queue type, if not specified, default will be used
	 * @return Base
	 */
	public function create($sQueue, $sQueueType = null) {
		
	}
}