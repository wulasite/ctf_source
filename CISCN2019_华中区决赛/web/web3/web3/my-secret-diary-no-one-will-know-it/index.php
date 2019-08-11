<?php
require 'includes/db.php';
include 'liuliang.php';
session_start();
$action = $_GET['action'] ?? 'pages/index';
if(preg_match('/(php|phar)/i',$action))
{
	$action='pages/index';
}
require $action . '.php';
