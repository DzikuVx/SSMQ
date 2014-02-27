<?php

namespace SSMQ\tests;

use SSMQ\MySqlQueue;
use SSMQ\SSMQ;

/** @noinspection PhpIncludeInspection */
require_once 'SSMQ.php';

MySqlQueue::setCredentials('localhost', 'ssmq', 'ssmq', 'ssmq');

class SSMQTest extends \PHPUnit_Framework_TestCase {

    public function testMysqlConnector() {

        $oFactory = SSMQ::getInstance();
        $this->assertInstanceOf('\SSMQ\SSMQ', $oFactory);

        $oQueue = $oFactory->create('queueA');
        $this->assertInstanceOf('\SSMQ\MySqlQueue', $oQueue);

        $oQueue->truncate();

        $oPop = $oQueue->pop();
        $this->assertNull($oPop);

        //String message without recipient and attributes
        $sMessage = 'This is test message';
        $oQueue->push($sMessage);
        $oPop = $oQueue->pop();
        $this->assertInstanceOf('\SSMQ\Message', $oPop);
        $this->assertEquals($sMessage, $oPop->message);
        $this->assertNull($oPop->recipient);
        $this->assertNull($oPop->attributes);

        $oPop = $oQueue->pop();
        $this->assertNull($oPop);

        //Numerical message without recipient and attributes
        $sMessage = 123;
        $oQueue->push($sMessage);
        $oPop = $oQueue->pop();
        $this->assertInstanceOf('\SSMQ\Message', $oPop);
        $this->assertEquals($sMessage, $oPop->message);
        $this->assertNull($oPop->recipient);
        $this->assertNull($oPop->attributes);

        $oPop = $oQueue->pop();
        $this->assertNull($oPop);

        /*
         * String message with recipient, no attributes
         */
        $sMessage = 'This is test message';
        $oQueue->push($sMessage, 'recipientA');

        $oPop = $oQueue->pop();
        $this->assertNull($oPop);

        $oPop = $oQueue->pop('recipientA');
        $this->assertInstanceOf('\SSMQ\Message', $oPop);
        $this->assertEquals($sMessage, $oPop->message);
        $this->assertEquals('recipientA', $oPop->recipient);
        $this->assertNull($oPop->attributes);

        /*
         * String message, no recipient, attributes
         */
        $sMessage = 'This is test message';
        $oQueue->push($sMessage, null, array('key1' => 'val1', 'key2' => 'val2'));
        $oPop = $oQueue->pop();
        $this->assertInstanceOf('\SSMQ\Message', $oPop);
        $this->assertEquals($sMessage, $oPop->message);
        $this->assertNull($oPop->recipient);
        $this->assertInstanceOf('\stdClass', $oPop->attributes);
        $this->assertObjectHasAttribute('key1', $oPop->attributes);
        $this->assertObjectHasAttribute('key2', $oPop->attributes);
        $this->assertEquals('val1', $oPop->attributes->key1);
        $this->assertEquals('val2', $oPop->attributes->key2);

    }

}
 