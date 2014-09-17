<?php
ini_set('default_charset','utf-8');
include("config.php");
if(isset($_COOKIE["Username"]) && $_COOKIE["Username"]!='' && $_COOKIE["UserLevel"]==5){
//add
if( strtolower($_GET["a"]) == "add"){
	if( $_POST["cname"]=="" ){
		echo "<SCRIPT language=JavaScript>alert('课程名称不得为空，请重新输入');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	if( $_POST["cshour"]=="" or $_POST["csminute"]=="" or $_POST["cehour"]=="" or $_POST["ceminute"]=="" ){
		echo "<SCRIPT language=JavaScript>alert('时间不得为空，请重新输入');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	$sm=sprintf("%02d", $_POST["csminute"]);
	$sh=sprintf("%02d", $_POST["cshour"]);
	$em=sprintf("%02d", $_POST["ceminute"]);
	$eh=sprintf("%02d", $_POST["cehour"]);
	$time_s=$sh.":".$sm;
	$time_e=$eh.":".$em;
	//验证尚未添加
	$sql1 = "INSERT INTO hupms_lesson(
		Type,Room,Name,Teacher,Day,Time_s,Time_e,Del
	) VALUES(
	'$_POST[ctype]','$_POST[croom]','$_POST[cname]',
	'$_POST[cteacher]','$_POST[cday]','$time_s','$time_e',0
	)";
	$result1 = mysql_query($sql1);
	if($result1){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "建立新课程:" . $_POST["cname"];
		$Log_Detail = "种类:".$_POST["ctype"].
					  ",教室:".$_POST["croom"].
					  ",教师:".$_POST["cteacher"].
					  ",日期:".$_POST["cday"].
					  ",开始时间:".$time_s.
					  ",结束时间:".$time_e;
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime,Detail) VALUES ('$Log_Content','$Log_Name','$Log_Username',now(),'$Log_Detail')";
		$result_log = mysql_query($sql_log);
		echo "<script language=JavaScript>";
		echo "location.href='lesson.php';</script>";
		exit;
	}else{
		echo "<SCRIPT language=JavaScript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
}


//Delete
if( strtolower($_GET["a"]) == "delete" && isset($_GET["id"])){
	$sql="select * from hupms_lesson where Id=$_GET[id]";
	$result=mysql_query($sql);
	$rs=mysql_fetch_object($result);
	$sql = "update hupms_lesson set Del= 1 where Id='$_GET[id]' "; 
	$result = mysql_query($sql); 
	if($result){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "冻结了课程".$rs->Name;
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<script language=JavaScript>";	
		echo "location.href='lesson.php';</script>";
		exit;
	}else{
		echo "<SCRIPT language=JavaScript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
}

//Recover
if( strtolower($_GET["a"]) == "recover" && isset($_GET["id"])){
	$sql="select * from hupms_lesson where Id=$_GET[id]";
	$result=mysql_query($sql);
	$rs=mysql_fetch_object($result);
	$sql = "update hupms_lesson set Del= 0 where Id='$_GET[id]' "; 
	$result = mysql_query($sql); 
	if($result){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "回复了课程".$rs->Name;
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<script language=JavaScript>";	
		echo "location.href='lesson.php';</script>";
		exit;
	}else{
		echo "<SCRIPT language=JavaScript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
}

//Change
if( strtolower($_GET["a"]) == "change"){
	if($_POST["ctype"]==1){
		$lessontype="常规班";
	}elseif($_POST["ctype"]==2){
		$lessontype="暑假班";
	}elseif($_POST["ctype"]==3){
		$lessontype="寒假班";
	}
	$sql = "update hupms_flag set Value=$_POST[ctype] where Name='class' "; 
	$result = mysql_query($sql); 
	if($result){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "将课程种类更改为:".$lessontype;
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<script language=JavaScript>";	
		echo "location.href='lesson.php';</script>";
		exit;
	}else{
		echo "<SCRIPT language=JavaScript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
}


//update
if( strtolower($_GET["a"]) == "update" && isset($_GET["id"])){
	$sql="select * from hupms_lesson where id=$_GET[id]";
	$result=mysql_query($sql);
	$num =mysql_num_rows($result);
	if($num <=0){
		echo "<SCRIPT language=JavaScript>alert('没有所需要的id，请重新选择');";
		echo "location.href='lesson.php'</SCRIPT>";
		exit;
	}
	if( $_POST["cname"]=="" ){
		echo "<SCRIPT language=JavaScript>alert('课程名称不得为空，请重新输入');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	if( $_POST["cshour"]=="" or $_POST["csminute"]=="" or $_POST["cehour"]=="" or $_POST["ceminute"]=="" ){
		echo "<SCRIPT language=JavaScript>alert('时间不得为空，请重新输入');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	$sm=sprintf("%02d", $_POST["csminute"]);
	$sh=sprintf("%02d", $_POST["cshour"]);
	$em=sprintf("%02d", $_POST["ceminute"]);
	$eh=sprintf("%02d", $_POST["cehour"]);
	$time_s=$sh.":".$sm;
	$time_e=$eh.":".$em;
	$sql1 = "update hupms_lesson set 
		Type='$_POST[ctype]',
		Room='$_POST[croom]',
		Name='$_POST[cname]',
		Teacher='$_POST[cteacher]',
		Day='$_POST[cday]',
		Time_s='$time_s',
		Time_e='$time_e' where id=$_GET[id];";
	$result1 = mysql_query($sql1);
	if($result1){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "修改课程:".$_POST["cname"];
		$Log_Detail = "种类:".$_POST["ctype"].
					  ",教室:".$_POST["croom"].
					  ",教师:".$_POST["cteacher"].
					  ",日期:".$_POST["cday"].
					  ",开始时间:".$time_s.
					  ",结束时间:".$time_e;	
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<script language=JavaScript>";
		echo "location.href='lesson.php';</script>";
		exit;
	}else{
		echo "<SCRIPT language=JavaScript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
}


}
?>