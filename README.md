SSMQ
====

Simple (via) Sql Messaging Queue

##Instalation

Almost no installation required. All you need is:
* Ensure you have PHP5 and MySQL
* create MySQL user and database
* execute `ssms-create.sql` to create required tables

And that's all, SSMQ ready to use

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
