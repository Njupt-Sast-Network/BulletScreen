<?php
header('Access-Control-Allow-Origin:*');
require_once "config.php";
$object = new BulletScreen();

if (isset($_GET['id']))
{
	$id = @$_GET['id'];
	$query = $object->get_mysql(
		'bulletscreen',
		'*',
		"`id` = '$id'",
		false,
		true
	);
	if ($query && mysql_num_rows($query) > 0)
	{
		$data = mysql_fetch_array($query);
		$output = array(
			'id'       => $data['id'],
			'nickname' => $data['nickname'],
			'time'     => $data['time'],
			'content'  => $data['content'],
		);
		echo json_encode($output);
	}
	else
	{
		exit('{"error":"id"}');
	}
}
else
{
	$status = $object->mem->get("BulletScreen-Display");

	if ( ! $status)
	{
		exit('{"display":false}');
	}

	$query = $object->get_mysql(
		'bulletscreen',
		'`id`,`status`',
		'`status`>0',
		'`id` ASC',
		false
	);
	$temp = array();
	while ($data = mysql_fetch_array($query))
	{
		$temp[$data[0]] = $data[1];
	}

	$output = array(
		"display" => true,
		"list"    => $temp,
	);

	echo json_encode($output);
}