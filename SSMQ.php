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
	private /** @noinspection PhpUnusedPrivateFieldInspection */
        $sQueueType = 'mysql';
	
	/**
	 * @var array
	 */
	private $aQueues = array();
	
	/**
	 * @var SSMQ
	 */
	private static $instance;
	
	/**
	 * Private constructor, factory is Singleton
	 */
	private function __construct() {
		
	}
	
	public static function getInstance() {
		
		if (empty(self::$instance)) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	/**
	 * Method returns queue object
	 * @param string $sQueue queue name
	 * @param string $sQueueType queue type, if not specified, default will be used
	 * @return Base
	 * 
	 * TODO: choose type of queue engine
	 */
	public function create($sQueue, /** @noinspection PhpUnusedParameterInspection */
                           $sQueueType = null) {
		
		if (empty($this->aQueues[$sQueue])) {
			$this->aQueues[$sQueue] = new MySqlQueue($sQueue);
		}
		
		return $this->aQueues[$sQueue];
	}
}