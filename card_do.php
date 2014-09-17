<?php
ini_set('default_charset','utf-8');
include("config.php");
if(isset($_COOKIE["Username"]) && $_COOKIE["Username"]!='' &&$_COOKIE["UserLevel"]==5){
//add
if( strtolower($_GET["a"]) == "add"){
	if( $_POST["name"]=="" ){
		echo "<script language=JavaScript>";
		echo "alert('卡名不得为空');";
		echo "location.href='card_add.php';</script>";
		exit;
	}
	if( $_POST["price"]=="" ){
		echo "<script language=JavaScript>";
		echo "alert('单价不得为空');";
		echo "location.href='card_add.php';</script>";
		exit;
	}
	if( $_POST["day"]=="" ){
		echo "<script language=JavaScript>";
		echo "alert('有效期不得为空');";
		echo "location.href='card_add.php';</script>";
		exit;
	}
	if( $_POST["type"]=="" ){
		echo "<script language=JavaScript>";
		echo "alert('种类不得为空');";
		echo "location.href='card_add.php';</script>";
		exit;
	}
	if( $_POST["type"]==0 && $_POST["count"]=="" ){
		echo "<script language=JavaScript>";
		echo "alert('课时数不得为空');";
		echo "location.href='card_add.php';</script>";
		exit;
	}
	if( $_POST["type"]==1){
	$sql1 = "INSERT INTO hupms_card(
		Name,Type,Price,PriceVIP,Day,Del
	) VALUES(
	'$_POST[name]','$_POST[type]','$_POST[price]','$_POST[pricevip]','$_POST[day]',0
	)";
	}
	if( $_POST["type"]==0){
	$sql1 = "INSERT INTO hupms_card(
		Name,Type,Price,PriceVIP,Day,Del,Count
	) VALUES(
	'$_POST[name]','$_POST[type]','$_POST[price]','$_POST[pricevip]','$_POST[day]',0,'$_POST[count]'
	)";
	}
	$result1 = mysql_query($sql1);
	if($result1){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "添加新卡片:".$_POST["name"]." 单价:".$_POST["price"]." ID:".$rs->Id;
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<script language=JavaScript>";
		echo "location.href='card.php';</script>";
		exit;
	}else{
		echo "<SCRIPT language=JavaScript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
}


//Delete
if( strtolower($_GET["a"]) == "delete" && isset($_GET["id"])){
	$sql="select * from hupms_card where Id=$_GET[id]";
	$result=mysql_query($sql);
	$rs=mysql_fetch_object($result);
	$sql = "update hupms_card set Del= 1 where Id='$_GET[id]' "; 
	$result = mysql_query($sql); 
	if($result){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "冻结了卡片".$rs->Name." ID:".$rs->Id;
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<SCRIPT language=JavaScript>";	
		echo "location.href='card.php';</script>";
		exit;
	}else{
		echo "<SCRIPT language=JavaScript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
}

//Recover
if( strtolower($_GET["a"]) == "recover" && isset($_GET["id"])){
	$sql="select * from hupms_card where Id=$_GET[id]";
	$result=mysql_query($sql);
	$rs=mysql_fetch_object($result);
	$sql = "update hupms_card set Del = 0 where Id='$_GET[id]' "; 
	$result = mysql_query($sql); 
	if($result){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "恢复了卡片".$rs->Name." ID:".$rs->Id;
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<SCRIPT language=JavaScript>";	
		echo "location.href='card.php';</script>";
		exit;
	}else{
		echo "<SCRIPT language=JavaScript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
}

//update
if( strtolower($_GET["a"]) == "update" && isset($_GET["id"])){
	$sql="select * from hupms_card where id=$_GET[id]";
	$result=mysql_query($sql);
	$num =mysql_num_rows($result);
	if($num <=0){
		echo "<SCRIPT language=JavaScript>alert('没有所需要的id，请重新选择');";
		echo "location.href='card_update.php?id=".$rs->Id."'</SCRIPT>";
		exit;
	}
	$rs=mysql_fetch_object($result);

	if( $_POST["price"]=="" ){
		echo "<script language=JavaScript>";
		echo "alert('单价不得为空');";
		echo "location.href='card_update.php?id=".$rs->Id."';</script>";
		exit;
	}
	if( $_POST["day"]=="" ){
		echo "<script language=JavaScript>";
		echo "alert('有效期不得为空');";
		echo "location.href='card_update.php?id=".$rs->Id."';</script>";
		exit;
	}
	if( $rs->Type==0 && $_POST["count"]=="" ){
		echo "<script language=JavaScript>";
		echo "alert('课时数不得为空');";
		echo "location.href='card_update.php?id=".$rs->Id."';</script>";
		exit;
	}
	if($rs->Type==1){
	  $sql1 = "update hupms_card set Price='$_POST[price]', PriceVIP='$_POST[pricevip]', Day='$_POST[day]' where id=$_GET[id];";
	}
	if($rs->Type==0){
	  $sql1 = "update hupms_card set Price='$_POST[price]', PriceVIP='$_POST[pricevip]', Day='$_POST[day]', Count='$_POST[count]' where id=$_GET[id];";
	}
	
	$result1 = mysql_query($sql1);
	if($result1){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "修改卡片".$rs->Name;
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<SCRIPT language=JavaScript>";
		echo "location.href='card.php';</script>";
		exit;
	}else{
		echo "<SCRIPT language=JavaScript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
}


}
?>