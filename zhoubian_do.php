<?php
ini_set('default_charset','utf-8');
include("config.php");
if(isset($_COOKIE["Username"]) && $_COOKIE["Username"]!='' &&$_COOKIE["UserLevel"]==5){
//add
if( strtolower($_GET["a"]) == "add"){

	if( $_POST["zbname"]=="" ){
		echo "<script language=Javascript>alert('名称不得为空，请重新输入');";
		echo "javascript:history.back()</script>";
		exit;
	}
	if( $_POST["price"]=="" ){
		echo "<script language=Javascript>alert('价格不得为空，请重新输入');";
		echo "javascript:history.back()</script>";
		exit;
	}
	if( $_POST["price"]<=0 || $_POST["price"]>=1000 ){
		echo "<script language=Javascript>alert('价格不合适，必须介于1-1000之间，请重新输入');";
		echo "javascript:history.back()</script>";
		exit;
	}
	$sql0 = "select * from hupms_zhoubian where Name='$_POST[zbname]'"; 
	$result = mysql_query($sql0); 
	$num = mysql_num_rows($result);
	if($num>0){
		echo "<script language=Javascript>alert('名称已被使用，请选用其他名称');";
		echo "javascript:history.back()</script>";
		exit;
	}

	$sql1 = "INSERT INTO hupms_zhoubian( Name,Price ) VALUES( '$_POST[zbname]','$_POST[price]' )";
	$result1 = mysql_query($sql1);
	if($result1){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "添加新周边:".$_POST["zbname"]."单价:".$_POST["price"];
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<script language=Javascript>";
		echo "location.href='zhoubian.php';</script>";
		exit;
	}else{
		echo "<script language=Javascript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</script>";
		exit;
	}
}


//Delete
if( strtolower($_GET["a"]) == "delete" && isset($_GET["id"])){
	$sql="select * from hupms_zhoubian where Id=$_GET[id]";
	$result=mysql_query($sql);
	$rs=mysql_fetch_object($result);
	$sql = "update hupms_zhoubian set Del= 1 where Id='$_GET[id]' "; 
	$result = mysql_query($sql); 
	if($result){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "冻结了周边".$rs->Name;
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<script language=Javascript>";	
		echo "location.href='zhoubian.php';</script>";
		exit;
	}else{
		echo "<script language=Javascript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</script>";
		exit;
	}
}

//Recover
if( strtolower($_GET["a"]) == "recover" && isset($_GET["id"])){
	$sql="select * from hupms_zhoubian where Id=$_GET[id]";
	$result=mysql_query($sql);
	$rs=mysql_fetch_object($result);
	$sql = "update hupms_zhoubian set Del = 0 where Id='$_GET[id]' "; 
	$result = mysql_query($sql); 
	if($result){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "恢复了周边".$rs->Name;
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<script language=Javascript>";	
		echo "location.href='zhoubian.php';</script>";
		exit;
	}else{
		echo "<script language=Javascript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</script>";
		exit;
	}
}

//edit
if( strtolower($_GET["a"]) == "edit" && isset($_GET["id"])){


	
	if( $_POST["zbname"]=="" ){
		echo "<script language=Javascript>alert('名称不得为空，请重新输入');";
		echo "javascript:history.back()</script>";
		exit;
	}
	if( $_POST["price"]=="" ){
		echo "<script language=Javascript>alert('价格不得为空，请重新输入');";
		echo "javascript:history.back()</script>";
		exit;
	}
	if( $_POST["price"]<=0 || $_POST["price"]>=1000 ){
		echo "<script language=Javascript>alert('价格不合适，必须介于1-1000之间，请重新输入');";
		echo "javascript:history.back()</script>";
		exit;
	}


	$sql = "UPDATE hupms_zhoubian set Name = '$_POST[zbname]', Price = '$_POST[price]' where Id='$_GET[id]' "; 

	$result1 = mysql_query($sql);
	if($result1){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "修改周边名称".$_POST["zbname"]."单价".$_POST["price"];
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<script language=Javascript>";
		echo "location.href='zhoubian.php';</script>";
		exit;
	}else{
		echo "<script language=Javascript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</script>";
		exit;
	}
	
	
}


}
?>