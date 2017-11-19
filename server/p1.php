<?php
/**
 * Created by PhpStorm.
 * User: wuhui
 * Date: 2017/11/13
 * Time: 14:07
 */
require_once('./pdodb.php');

try {
    $db = PdoDb::getInstance()->connect();
} catch(PDOException  $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
//插入数据
//$sql    = "insert into os_user (username,password) values (?, ?);";
//$preObj = $db->prepare($sql);
//$res    = $preObj->execute(array('小明', 'xxxx'));
//var_dump($res);

//删除数据
//$sql = "delete from os_user where id = ?";
//$preObj = $db->prepare($sql);
//$res    = $preObj->execute(array(20));
//var_dump($res);
//
////修改数据
//$sql = "update os_user set username = ? where id = ?;";
//$preObj = $db->prepare($sql);
//$res    = $preObj->execute(array('lucy', 5));
//var_dump($res);
//
////查询数据
//$sql = "select * from os_user where id > ? order by id desc;";
//$preObj = $db->prepare($sql);
//$preObj->execute(array(10));
//$arr = $preObj->fetchAll(PDO::FETCH_ASSOC);
//var_dump($arr);

//获取所有记录集中到一个二维数组中
//$sql = "select * from os_user";
//$sth = $db->prepare($sql);
//$sth->execute();
//$rst = $sth->fetchAll();
//var_dump($rst);

//使用预处理语句获取数据
//$username = 'test';
//$pass = '123';
//$sql = "select * from os_user where username=? and password=?";
//$stmt = $db->prepare($sql);
//if ($stmt->execute(array($username,$pass))) {
//    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//        var_dump($row);
//    }
//}

/*
 * FETCH_BOTH      是默认的，可省，返回关联和索引。
 * FETCH_ASSOC     参数决定返回的只有关联数组。
 * PDO::FETCH_NUM  返回索引数组
 * PDO::FETCH_OBJ  返回由对象组成的二维数组
 */

//$stmt->bindParam(':country', $country, PDO::PARAM_STR);


//执行一条使用问号占位符的预处理语句
//$id = 20;
//$username = '154pp';
//$sth = $db->prepare('SELECT *
//    FROM os_user
//    WHERE id < ? AND username = ?');
//$sth->bindParam(1, $id, PDO::PARAM_INT);
//$sth->bindParam(2, $username, PDO::PARAM_STR, 12);
//$sth->execute();
//$res = $sth->fetchAll(PDO::FETCH_ASSOC);
//var_dump($res);

//占位符的无效使用
//$name = 'test';
//$stmt = $db->prepare("SELECT * FROM os_user where username LIKE '%?%'");
//$stmt->execute(array($name));
//$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
//var_dump($res);
//// 占位符必须被用在整个值的位置
//$stmt = $db->prepare("SELECT * FROM os_user where username LIKE ?");
//$stmt->execute(array("%$name%"));
//$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
//var_dump($res);



/*  使用一个数组的值执行一条含有 IN 子句的预处理语句 */
$params = array(1, 21, 63, 171);
/*  创建一个填充了和params相同数量占位符的字符串 */
$place_holders = implode(',', array_fill(0, count($params), '?'));
//echo $place_holders;die;//?,?,?,?
/*
    对于 $params 数组中的每个值，要预处理的语句包含足够的未命名占位符 。
    语句被执行时， $params 数组中的值被绑定到预处理语句中的占位符。
    这和使用 PDOStatement::bindParam() 不一样，因为它需要一个引用变量。
    PDOStatement::execute() 仅作为通过值绑定的替代。
*/
$sth = $dbh->prepare("SELECT id, name FROM contacts WHERE id IN ($place_holders)");
$sth->execute($params);