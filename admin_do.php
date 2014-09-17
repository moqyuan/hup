<?php
ini_set('default_charset','utf-8');
include("config.php");
if(isset($_COOKIE["Username"]) && $_COOKIE["Username"]!='' &&$_COOKIE["UserLevel"]==5){
//add
if( strtolower($_GET["a"]) == "add"){
	if( $_POST["ausername"]=="" ){
		echo "<SCRIPT language=JavaScript>alert('用户名不得为空，请重新输入');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	if( $_POST["aname"]=="" ){
		echo "<SCRIPT language=JavaScript>alert('姓名不得为空，请重新输入');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	$sql0 = "select * from hupms_user where Username='$_POST[ausername]'"; 
	$result = mysql_query($sql0); 
	$num = mysql_num_rows($result);
	if($num>0){
	echo "<SCRIPT language=JavaScript>alert('用户名已被使用，请选用其他用户名');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	$sql0 = "select * from hupms_user where Name='$_POST[aname]'"; 
	$result = mysql_query($sql0); 
	$num = mysql_num_rows($result);
	if($num>0){
	echo "<SCRIPT language=JavaScript>alert('姓名已被使用，请使用其他姓名');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	$np=md5($_POST["apassword"]);
	$sql1 = "INSERT INTO hupms_user(
		Username,Name,UserLevel,Password
	) VALUES(
	'$_POST[ausername]','$_POST[aname]',3,'$np'
	)";
	$result1 = mysql_query($sql1);
	if($result1){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "添加新管理员:".$_POST["ausername"]."姓名:".$_POST["aname"]."密码".$_POST["apassword"];
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<SCRIPT language=JavaScript>";
		echo "location.href='admin.php';</script>";
		exit;
	}else{
		echo "<SCRIPT language=JavaScript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
}


//Delete
if( strtolower($_GET["a"]) == "delete" && isset($_GET["id"])){
	$sql="select * from hupms_user where Id=$_GET[id]";
	$result=mysql_query($sql);
	$rs=mysql_fetch_object($result);
	$sql = "update hupms_user set UserLevel= 0 where Id='$_GET[id]' "; 
	$result = mysql_query($sql); 
	if($result){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "冻结了管理员".$rs->Username.$rs->Name;
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<SCRIPT language=JavaScript>";	
		echo "location.href='admin.php';</script>";
		exit;
	}else{
		echo "<SCRIPT language=JavaScript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
}

//Recover
if( strtolower($_GET["a"]) == "recover" && isset($_GET["id"])){
	$sql="select * from hupms_user where Id=$_GET[id]";
	$result=mysql_query($sql);
	$rs=mysql_fetch_object($result);
	$sql = "update hupms_user set UserLevel = 3 where Id='$_GET[id]' "; 
	$result = mysql_query($sql); 
	if($result){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "恢复了管理员".$rs->Username.$rs->Name;
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<SCRIPT language=JavaScript>";	
		echo "location.href='admin.php';</script>";
		exit;
	}else{
		echo "<SCRIPT language=JavaScript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
}

//update
if( strtolower($_GET["a"]) == "update" && isset($_GET["id"])){
	$sql="select * from hupms_user where id=$_GET[id]";
	$result=mysql_query($sql);
	$num =mysql_num_rows($result);
	if($num <=0){
		echo "<SCRIPT language=JavaScript>alert('没有所需要的id，请重新选择');";
		echo "location.href='admin.php'</SCRIPT>";
		exit;
	}
	$rs=mysql_fetch_object($result);
	if( $_POST["aname"]=="" ){
		echo "<SCRIPT language=JavaScript>alert('姓名不得为空，请重新输入');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	$sql0 = "select * from hupms_user where Name='$_POST[aname]' and Id!=$_GET[id]"; 
	$result = mysql_query($sql0); 
	$num = mysql_num_rows($result);
	if($num>0){
		echo "<SCRIPT language=JavaScript>alert('姓名已被使用，请使用其他姓名');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	if($_POST["apassword"]!=''){
		$np=md5($_POST["apassword"]);
		$sql1 = "update hupms_user set Password='$np', Name='$_POST[aname]' where id=$_GET[id];";
	}else{
		$sql1 = "update hupms_user set Name='$_POST[aname]' where id=$_GET[id];";
	}
	$result1 = mysql_query($sql1);
	if($result1){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		if($_POST["apassword"]!=''){
			$Log_Content = "修改管理员".$rs->Username."姓名".$_POST["aname"]."密码".$_POST["apassword"];
		}else{
			$Log_Content = "修改管理员".$rs->Username."姓名".$_POST["aname"];
		}
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<SCRIPT language=JavaScript>";
		echo "location.href='admin.php';</script>";
		exit;
	}else{
		echo "<SCRIPT language=JavaScript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
}


}
?>