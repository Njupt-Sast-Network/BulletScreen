<?php

header("Content-Type: text/html; charset=utf-8");
ini_set("display_errors", "On");
error_reporting(E_ALL ^ E_DEPRECATED);
session_start();

define("DB_HOST", "localhost");
define("DB_NAME", "BulletScreen");
define("DB_USER", "root");
define("DB_PWD", "");

define("MEM_HOST", "localhost");
define("MEM_PORT", "11211");

class BulletScreen {

	private $DBhost = DB_HOST,
	$DBname = DB_NAME,
	$DBusr = DB_USER,
	$DBpwd = DB_PWD,
	$conn;
	private $salt = "_\oG*s#]u";
	public $mem;

	public function __instantiation()
	{
		return new Memcache();
	}

	public function __construct()
	{
		$this->mem = $this->__instantiation();
		$this->mem->connect(MEM_HOST, MEM_PORT) or die("Could not connect");
		$this->connect_mysql();
	}

	function safe($s)
	{
		if (get_magic_quotes_gpc())
		{
			$s = stripslashes($s);
		}
		$s = mysql_real_escape_string($s);
		$s = addslashes($s);
		$s = str_replace('_', '_', $s);
		$s = str_replace('%', '%', $s);
		return $s;
	}

	function curl_request($url, $post = '', $cookie_file = '', $fetch_cookie = 0, $referer)
	{

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Expect:"));
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
		curl_setopt($curl, CURLOPT_REFERER, $referer);
		if ($post)
		{
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
		}
		if ($fetch_cookie)
		{
			curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_file);
		}
		else
		{
			curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_file);
		}
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($curl);
		if (curl_errno($curl))
		{
			return false;
		}

		return $data;
	}

	function connect_mysql()
	{
		$this->conn = mysql_connect(
			$this->DBhost,
			$this->DBusr,
			$this->DBpwd
		);
		mysql_select_db(
			$this->DBname,
			$this->conn
		);
		mysql_query("SET NAMES 'UTF8'");
		return $this->conn;
	}

	function get_mysql($table, $field, $sql = 0, $order = 0, $limit = 0)
	{
		$sql   = "SELECT $field FROM `$table` WHERE 1     = 1 ".($sql ? "AND ".$sql : "").($order ? " ORDER BY ".$order : "").($limit ? " LIMIT 1" : "");
		$query = mysql_query($sql);
		if ($query)
		{
			return $query;
		}
		else
		{
			return false;
		}
	}

	function insert_mysql($table, $arr)
	{
		$array_key   = array();
		$array_value = array();
		foreach ($arr as $key => $value)
		{
			array_push($array_key, '`'.$key.'`');
			if ('now()' == $value || 'NULL' == $value)
			{
				array_push($array_value, $value);
			}
			else
			{
				array_push($array_value, "'".$value."'");
			}
		}
		$sql   = "INSERT INTO `$table` (".implode(',', $array_key).") VALUES (".implode(',', $array_value).")";
		$query = mysql_query($sql);
		return $query;
	}

	function modify_mysql($table, $sql, $res)
	{
		$sql = "UPDATE `$table` SET $sql WHERE $res";
		return mysql_query($sql);
	}

	function remove_mysql($table, $res)
	{
		$sql = "DELETE FROM `$table` WHERE $res";
		return mysql_query($sql);
	}

}
