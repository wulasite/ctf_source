<?php
error_reporting(0);
include 'liuliang.php';
class message
{
	public $name = '';
	public $page = '';
}

function echo_main()
{
	$html = 'PCFET0NUWVBFIGh0bWw+PGh0bWwgbGFuZz0iZW4iPjxoZWFkPjxtZXRhIGh0dHAtZXF1aXY9IkNvbnRlbnQtVHlwZSIgY29udGVudD0idGV4dC9odG1sOyBjaGFyc2V0PVVURi04Ij48dGl0bGU+TG9naW48L3RpdGxlPjxtZXRhIG5hbWU9ImRlc2NyaXB0aW9uIiBjb250ZW50PSJMb2dpbiI+PGxpbmsgcmVsPSJzdHlsZXNoZWV0IiB0eXBlPSJ0ZXh0L2NzcyIgaHJlZj0iLi9Mb2dpbl9maWxlcy9zdHlsZS5jc3MiPjwvaGVhZD48Ym9keT48Zm9ybSBtZXRob2Q9InBvc3QiIGFjdGlvbj0iaW5kZXgucGhwIiBpZD0ic2xpY2stbG9naW4iPjxoMSBzdHlsZT0iZm9udC1zaXplOjM0cHg7dGV4dC1hbGlnbjpjZW50ZXI7Y29sb3I6I2ZmZjsiPkhhY2tlclpvbmU8L2gxPjxicj48YnI+PGxhYmVsIGZvcj0idXNlcm5hbWUiPnVzZXJuYW1lPC9sYWJlbD48aW5wdXQgdHlwZT0idGV4dCIgbmFtZT0idXNlcm5hbWUiIGNsYXNzPSJwbGFjZWhvbGRlciIgcGxhY2Vob2xkZXI9InVzZXJuYW1lIj48bGFiZWwgZm9yPSJwYXNzd29yZCI+cGFzc3dvcmQ8L2xhYmVsPjxpbnB1dCB0eXBlPSJwYXNzd29yZCIgbmFtZT0icGFzc3dvcmQiIGNsYXNzPSJwbGFjZWhvbGRlciIgcGxhY2Vob2xkZXI9InBhc3N3b3JkIiB2YWx1ZT0iYWRtaW4iPjxpbnB1dCB0eXBlPSJzdWJtaXQiIHZhbHVlPSJMb2dpbiI+PC9mb3JtPjwvYm9keT48L2h0bWw+';
	die(base64_decode($html));
}

function local_manage()
{
	$ip = getenv('HTTP_CLIENT_IP');
	if($ip !== "127.0.0.1") {
		die("<script>alert(\"Warning: Also allowed local manager to watch!\")</script>");
	}
}

function sql_filter($username)
{
	
	if(preg_match('/(\/|,|if|&&|and|limit|by|hex|mid|substring|substr|ascii|select|union|strcmp|exp|ord|>|<|\s)/i', $username) == 1) {
		echo "<script>alert(\"Not Allow!\")</script>";
		echo_main();
	}
	
	if(preg_match('/(:|{|}|\s)/i', $username) == 1) {
		echo "<script>alert(\"L0gin Fail!\")</script>";
		echo_main();
	}
}

function echo_page($page, $path, $p)
{

	if($page != '') {
		if(substr($page, ".php") !== false) {
			$page = str_replace('.php','',$page);
			if(!isset($p->super_token)) {
				if(strchr($page, "flag") != false ) {
					die("<script>alert(\"Warning: You can't read flag !\")</script>");
				}
			}
			else {
				if(md5($p->super_token) != '36f3433ec9e7b653dc8c3131081e2d44') {
					die("<script>alert(\"Warning: Token Error !\")</script>");
				}
			}
			include($path.$page);
			echo $source;
		}
		else {
			echo "<script>alert(\"Warning: Stop your hacker behavior !\")</script>";
			echo_main();
		}
	}
}

function login()
{
	$con = mysql_connect("localhost:3306","dog","123456");
	if(!$con) {
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db("admin", $con);
	$username = $_POST['username'];
	$password = md5($_POST['password']);
	
	if($_POST['username'] != '') {
		sql_filter($username);
		$sql = "select * from admin where username = '".$username."';";
		$res = mysql_query($sql,$con);
		$row = mysql_fetch_array($res);	
		
		if($row && $row['password'] === $password) {
			$data = $row['data'];
			$path = $row['path'];
			$p = unserialize($data);
			$name = $p->name;
			$page = $p->page;
			$page = base64_decode($page);
			if(strchr($path, "index") === false && strchr($path, 'data') === false && strchr($path, "file") === false && strchr($path, "input") === false) {
				echo "<script>alert(\"Welcome back hacker $name !\")</script>";
				echo_page($page, $path, $p);
			}
		}
		else {
			echo "<script>alert(\"LOgin Fail!\")</script>";
			echo_main();
		}
	}
	else {
		echo_main();
	}
}

local_manage();
login();

?>