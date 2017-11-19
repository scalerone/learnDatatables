<?php
/**
 * Created by PhpStorm.
 * User: wuhui
 * Date: 2017/11/10
 * Time: 14:13
 */
require_once('./db.php');

try {
    $connect = Db::getInstance()->connect();
} catch(Exception $e) {
    // $e->getMessage();
    fatal(
        "数据库连接出错" . $e->getMessage()
    );
}

//$sql = "select id,username,mobile,email,create_time,last_login_ip from os_user";
//$result = mysql_query($sql, $connect);
//$arr = array();
//while($user = mysql_fetch_assoc($result)) {
//    $arr[] = $user;
//}
//echo json_encode($arr);die;

//获取Datatables发送的参数 必要
$draw = $_GET['draw'];//这个值作者会直接返回给前台

//排序
$order_column = $_GET['order']['0']['column'];//那一列排序，从0开始
$order_dir = $_GET['order']['0']['dir'];//ase desc 升序或者降序

//拼接排序sql
$orderSql = "";
if(isset($order_column)){
    $i = intval($order_column);
    switch($i){
        case 0;$orderSql = " order by id ".$order_dir;break;
        case 1;$orderSql = " order by username ".$order_dir;break;
        case 2;$orderSql = " order by mobile ".$order_dir;break;
        case 3;$orderSql = " order by email ".$order_dir;break;
        case 4;$orderSql = " order by create_time ".$order_dir;break;
        case 5;$orderSql = " order by last_login_ip ".$order_dir;break;
        default;$orderSql = '';
    }
}
//搜索
$search = $_GET['search']['value'];//获取前台传过来的过滤条件

//分页
$start = $_GET['start'];//从多少开始
$length = $_GET['length'];//数据长度
$limitSql = '';
$limitFlag = isset($_GET['start']) && $length != -1 ;
if ($limitFlag ) {
    $limitSql = " LIMIT ".intval($start).", ".intval($length);
}
//获取前台传送过来的搜索配置
$searchData= $_GET['columns'];
$canSearchKey=array();
foreach ($searchData as $items){
    if($items["searchable"]=="true"){
        $canSearchKey[]=$items['data'];
    }
}
$searchKeyStr=implode(",'||',",$canSearchKey);
//echo $searchKeyStr;die;

//定义查询数据总记录数sql
$sumSql = "SELECT count(id) as sum FROM os_user";
//条件过滤后记录数 必要
$recordsFiltered = 0;
//表的总记录数 必要
$recordsTotal = 0;
$recordsTotalResult = mysql_query($sumSql, $connect);
while($row = mysql_fetch_assoc($recordsTotalResult)) {
    $recordsTotal =  $row['sum'];
}

//定义过滤条件查询过滤后的记录数sql
//$sumSqlWhere =" where concat(username,'||', id,'||',mobile,'||',email,'||',create_time,'||',last_login_ip)  LIKE "."  '%".$search."%'";
$sumSqlWhere =" where concat(".$searchKeyStr.")  LIKE "."  '%".$search."%'";
if(strlen($search)>0){
//    $recordsFilteredResult = $db->query($sumSql.$sumSqlWhere);
    $recordsFilteredResult = mysql_query($sumSql.$sumSqlWhere,$connect);
    while($row = mysql_fetch_assoc($recordsFilteredResult)) {
        $recordsFiltered =  $row['sum'];
    }
}else{
    $recordsFiltered = $recordsTotal;
}

//query data
$totalResultSql = "SELECT id,username,mobile,email,create_time,last_login_ip  FROM os_user";
$infos = array();
if(strlen($search)>0){
    //如果有搜索条件，按条件过滤找出记录
    $dataResult = mysql_query($totalResultSql.$sumSqlWhere.$orderSql.$limitSql,$connect);
    while ($row = mysql_fetch_assoc($dataResult)) {
        $obj = array($row['id'], $row['username'], $row['mobile'], $row['email'], $row['create_time'], $row['last_login_ip']);
        //array_push($infos,$obj);
        $infos[]=$row;
    }
}else{
    //直接查询所有记录
    $dataResult = mysql_query($totalResultSql.$orderSql.$limitSql);
    while ($row = mysql_fetch_assoc($dataResult)) {
        $obj = array($row['id'], $row['username'], $row['mobile'], $row['email'], $row['create_time'], $row['last_login_ip']);
        //array_push($infos,$obj);
        $infos[]=$row;
    }
}

/*
 * Output 包含的是必要的
 */
echo json_encode(array(
    "draw" => intval($draw),
    "recordsTotal" => intval($recordsTotal),
    "recordsFiltered" => intval($recordsFiltered),
    "data" => $infos
),JSON_UNESCAPED_UNICODE);


function fatal($msg)
{
    echo json_encode(array(
        "error" => $msg
    ));
    exit(0);
}