<?php

// http://app.com/list.php?page=1&pagesize=12

require_once('./response.php');
require_once('./db.php');
require_once('./file.php');

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 1;

/// 判断参数是否正确
if (!is_numeric($page) || !is_numeric($pageSize)) {
	return Response::json(401, "参数错误");
}

$dbs = array();
$offset = ($page - 1) * $pagesize;
$sql = "select * from help_category limit ". $offset ." , ". $pageSize;
$cache = new File();

/// 假如不存在缓存数据
if(!$dbs = $cache->cacheData('index_om_cache' . $page . '-' . $pageSize)) {
	try {
		$connect = Db::getInstance()->connect();
	}catch(Exception $e){
		return Response::json(403,"数据库链接失败",$dbs);
	}

	$result = mysql_query($sql, $connect);

	if (!$result) {

	} else {
		while($db = mysql_fetch_assoc($result)){
			$dbs[] = $db;
		}
	}

	/// 把新数据缓存一份
	if($bds) {
		$cache->cacheData('index_om_cache' . $page . '-'. $pageSize, $dbs, 1200);
	}
}


if($dbs) {
	return Response::json(200,"数据获取成功",$dbs);
} else {
	return Response::json(400,"数据获取失败",$dbs);
}
