SSMQ
====

Simple (via) Sql Messaging Queue

##Example usage

###Set db access and obtain queue object

```
require_once 'SSMQ.php';
\SSMQ\MySqlQueue::setCredentials('localhost', 'ssmq', 'ssmq', 'ssmq');
$oQueue = \SSMQ\SSMQ::getInstance()->create('testQueue');
```

###Push message info queue

```
//Push message
$oQueue->push('testMessage');

//Push message and specify recipient
$oQueue->push('testMessage', 'recipientA');

//Push message, specify recipient and attach additional attributes
$oQueue->push('testMessage', 'recipientA', array('key1' => 'val1', 'key2' => 'val2'));

```

###Get message from queue

```
$oResponse = $oQueue->pop();
//or
$oResponse = $oQueue->pop('recipientA');
```
