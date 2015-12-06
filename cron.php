<?php

// 让crontab定时执行的脚本程序  */5 * * * * /usr/bin/php/data/www/app/cron.php
// 获取table数据

require_once('./db.php');
require_once('./file.php');

$sql = "select * from help_category";
$dbs = array();

try {
	$connect = Db::getInstance()->connect();
}catch(Exception $e){
	// return Response::json(403,"数据库链接失败",$dbs);
	file_put_contents('./logs/' . date('y-m-d') . '.txt' , $e->getMessage());
	return;
}

$result = mysql_query($sql, $connect);

if (!$result) {

} else {
	while($db = mysql_fetch_assoc($result)){
		$dbs[] = $db;
	}
}

$file = new File();
if($dbs) {
	$file->cacheData('index_cron_cache', $dbs);
} else {
	file_put_contents('./logs/' . date('y-m-d') . '.txt' , '没有相关数据');
}

return;