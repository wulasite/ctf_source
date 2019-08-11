<?php

include "secret.php";

error_reporting(0);

if(empty($_GET))
{
    highlight_file(__FILE__);
    die("get get get get args");
}
$a1=$_GET['a1'];
$a2=$_GET['a2'];
$a3=$_GET['a3'];
$a4=$_GET['a4'];
$obstacle_1=is_numeric($a2) and is_numeric($a1);
if(!$obstacle_1) exit("foolish");

if(!(intval($a2)<1024 and intval($a2+1)>1024)) exit("emmmmm");

if(isset($a1))
{
    $secret=hash_hmac('sha256',$a1,$secret);
}
$hmac=hash_hmac(sha256,$a2,$secret);
if($a3!==$hmac)
{

    die("OMG");
}

echo "gogogo   ".$url;

?>
