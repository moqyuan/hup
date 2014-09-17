<?php
ini_set('default_charset','utf-8');
include("config.php");
$today=Date('Y-m-d');
if(isset($_COOKIE["Username"]) && $_COOKIE["Username"]!='' &&$_COOKIE["UserLevel"]==5){


if( strtolower($_GET["a"]) == "holiday" && isset($_POST["day"])){
	if($_POST["day"]>=30 or $_POST["day"]<=0){
		echo "<SCRIPT language=JavaScript>alert('延长时间要在0-30天之间');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	$sql = "update hupms_student set Vdate = Date_Add(Vdate, INTERVAL $_POST[day] DAY) where Vdate>='$today'"; 

	$result = mysql_query($sql); 
	if($result){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "延长了所有未过期学员的有效期".$_POST['day']."天";
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<SCRIPT language=JavaScript>";	
		echo "location.href='index.php';</SCRIPT>";
		exit;
	}else{
		echo "<SCRIPT language=JavaScript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
}


}
?>