<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../SSMQ.php';

echo 'Require OK<br />';

\SSMQ\MySqlQueue::setCredentials('localhost', 'ssmq', 'ssmq', 'ssmq');

$oQueue = \SSMQ\SSMQ::getInstance()->create('testQueue');

echo 'Created<br />';

$oQueue->push('testMessage');

echo 'Pushed empty <br />';

$oQueue->push('testMessage', 'recipientA');

echo 'Pushed with recipient <br />';

$oQueue->push('testMessage', null, array('val1' => 'val1', 'val2' => 'val2'));

echo 'Pushed with attributes <br />';

$oQueue->push('testMessage', 'recipientA', array('val1' => 'val1', 'val2' => 'val2'));

echo 'Pushed with recipient and attributes <br />';

echo '<pre>';

var_dump($oQueue->pop());
var_dump($oQueue->pop('recipientA'));

echo '</pre>';