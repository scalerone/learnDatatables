<?php
/**
 * Created by PhpStorm.
 * User: wuhui
 * Date: 2017/11/13
 * Time: 11:44
 */

class PdoDb {
    static private $_instance;
    static private $_connectSource;
    private $_dbConfig ;

    private function __construct() {
//        $dbh = new PDO('mysql:host=localhost;dbname=test', $user, $pass);
        $this->_dbConfig = array(
            'dsn' => 'mysql:host=localhost;dbname=datatables',
            'user' => 'root',
            'password' => 'root',
        );
    }

    static public function getInstance() {
        if(!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function connect() {
        if(!self::$_connectSource) {
//            self::$_connectSource = new PDO('pgsql:host=10.66.83.2;dbname=logcenter','postgres','');
            self::$_connectSource = new PDO( $this->_dbConfig['dsn'],$this->_dbConfig['user'],$this->_dbConfig['password']);

            if(!self::$_connectSource) {
                throw new PDOException('mysql connect error ' );
                //die('mysql connect error' . mysql_error());
            }

        }
        return self::$_connectSource;
    }
}

//$db = PdoDb::getInstance()->connect();
//$sql = "select * from os_user";
//$sth = $db->prepare($sql);
//$sth->execute();
//$rst = $sth->fetchAll();
//$result = array();
////foreach ($rst as $k => $v) {
////    $result[$v['account']] = $v['owner'];
////}
////return $result;
//var_dump($rst);
