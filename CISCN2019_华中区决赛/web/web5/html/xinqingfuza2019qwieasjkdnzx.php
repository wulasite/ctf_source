<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>心情复杂</title>
</head>
<body>
    文章查询
    <form action="" method="post">
    <input type="text" name="usr"  placeholder="admin">
    <input type="submit" value="查询">
    </form>
</body>
</html>
<?php

error_reporting(0);
$host = "mysql:host=127.0.0.1;dbname=ciscn";
$username = "admin";
$password = "password987~!@";
$conn = new PDO($host,$username,$password);
if(!$con)
{
        echo "ERROR";
}
if(isset($_POST['usr'])) $usr=$_POST['usr'];
else exit();
$black_list=array('or','|','and','if','case','benchmark','GET_LOCK','rpad','repeat');
$usr=str_ireplace($black_list,'QAQ',$usr);
echo "查询:".$usr."<br>";
$sql="select * from article where username=':usr' limit 0,1";
$stmt = $con->prepare($sql);
$stmt->bindParam(':usr',$usr);
if($stmt->execute())
{
    echo "数据库操作异常";
}
?>
