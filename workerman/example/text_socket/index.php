<?php

$client = stream_socket_client('tcp://127.0.0.1:5678', $errno, $errmsg, 1);
$data = array(
	'uid' => 'uid1',
	'name' => '闫志鹏',
	'msg' => '修仙悟道',
	'age' => '25'
);
fwrite($client, json_encode($data) . "\n");

echo fread($client, 8192);

?>