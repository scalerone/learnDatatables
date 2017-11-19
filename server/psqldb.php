<?php

class Db {
	static private $_instance;
	static private $_connectSource;
	private $_dbConfig ;

	private function __construct() {
       // $this->_dbConfig = 'host=10.66.83.2 port=5432 dbname=logcenter user=postgres';
	}

	static public function getInstance() {
		if(!(self::$_instance instanceof self)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function connect() {
		if(!self::$_connectSource) {
			self::$_connectSource = new PDO('pgsql:host=10.66.83.2;dbname=logcenter','postgres','');

			if(!self::$_connectSource) {
				throw new PDOException('mysql connect error ' );
				//die('mysql connect error' . mysql_error());
			}

		}
		return self::$_connectSource;
	}
}
$db = Db::getInstance()->connect();

$sql = "select account,owner from usr_obj";
$sth = $db->prepare($sql);
$sth->execute();
$rst = $sth->fetchAll();
$result = array();
foreach ($rst as $k => $v) {
    $result[$v['account']] = $v['owner'];
}
//return $result;
var_dump($rst);
