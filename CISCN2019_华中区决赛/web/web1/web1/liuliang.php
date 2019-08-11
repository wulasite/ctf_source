<?php
date_default_timezone_set('Asia/Shanghai');
$ip = $_SERVER['REMOTE_ADDR'];
$filename = $_SERVER["PHP_SELF"];
$parameter = $_SERVER['QUERY_STRING'];
$time = date('Y-m-d H:i:s', time());
$post = file_get_contents("php://input");
$logadd = '来访时间:'.$time.'-->'.'访问链接'.'http://'.$ip.$filename.'?'.$parameter.' post: _'.$post.'\r\n';

$fh =fopen('log.txt', 'a');
fwrite($fh, $logadd);
fclose($fh);