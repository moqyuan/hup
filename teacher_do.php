<?php
ini_set('default_charset','utf-8');
include("config.php");
if(isset($_COOKIE["Username"]) && $_COOKIE["Username"]!='' && $_COOKIE["UserLevel"]==5){
//add
if( strtolower($_GET["a"]) == "add"){
	if( $_POST["tname"]=="" ){
		echo "<SCRIPT language=JavaScript>alert('姓名不得为空，请重新输入');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	$sql1 = "INSERT INTO hupms_teacher(
		Name,Nickname,Level,Tel,Mail,Identity,Intro,Del
	) VALUES(
	'$_POST[tname]','$_POST[tnickname]','$_POST[tlevel]',
	'$_POST[ttel]','$_POST[tmail]','$_POST[tidentity]','$_POST[tintro]',0
	)";
	$result1 = mysql_query($sql1);
	if($result1){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "建立新教师:".$_POST["tname"];
		$Log_Detail = "姓名:".$_POST["tname"].
					  ",昵称:".$_POST["tnickname"].
					  ",等级:".$_POST["tlevel"].
					  ",电话:".$_POST["ttel"].
					  ",邮件:".$_POST["tmail"].
					  ",证件:".$_POST["tidentity"].
					  ",介绍:".$_POST["tintro"];
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime,Detail) VALUES ('$Log_Content','$Log_Name','$Log_Username',now(),'$Log_Detail')";
		$result_log = mysql_query($sql_log);
		echo "<script language=JavaScript>";
		echo "location.href='teacher.php';</script>";
		exit;
	}else{
		echo "<SCRIPT language=JavaScript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
}


//Delete
if( strtolower($_GET["a"]) == "delete" && isset($_GET["id"])){
	$sql="select * from hupms_teacher where Id=$_GET[id]";
	$result=mysql_query($sql);
	$rs=mysql_fetch_object($result);
	$sql = "update hupms_teacher set Del= 1 where Id='$_GET[id]' "; 
	$result = mysql_query($sql); 
	if($result){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "冻结了教师:".$rs->Name;
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<SCRIPT language=JavaScript>";	
		echo "location.href='teacher.php';</script>";
		exit;
	}else{
		echo "<SCRIPT language=JavaScript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
}

//Recover
if( strtolower($_GET["a"]) == "recover" && isset($_GET["id"])){
	$sql="select * from hupms_teacher where Id=$_GET[id]";
	$result=mysql_query($sql);
	$rs=mysql_fetch_object($result);
	$sql = "update hupms_teacher set Del= 0 where Id='$_GET[id]' "; 
	$result = mysql_query($sql); 
	if($result){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "恢复了教师:".$rs->Name;
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<SCRIPT language=JavaScript>";	
		echo "location.href='teacher.php';</script>";
		exit;
	}else{
		echo "<SCRIPT language=JavaScript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
}

//update
if( strtolower($_GET["a"]) == "update" && isset($_GET["id"])){
	$sql="select * from hupms_teacher where id=$_GET[id]";
	$result=mysql_query($sql);
	$num =mysql_num_rows($result);
	if($num <=0){
		echo "<SCRIPT language=JavaScript>alert('没有所需要的id，请重新选择');";
		echo "location.href='admin.php'</SCRIPT>";
		exit;
	}
	$rs=mysql_fetch_object($result);

	$sql1 = "update hupms_teacher set 
		Nickname='$_POST[tnickname]',
		Level='$_POST[tlevel]',
		Tel='$_POST[ttel]',
		Mail='$_POST[tmail]',
		Identity='$_POST[tidentity]',
		Intro='$_POST[tintro]' where id=$_GET[id];";
	$result1 = mysql_query($sql1);
	if($result1){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "修改教师".$rs->Name."的信息";
		$Log_Detail = "昵称:".$_POST["tnickname"].
					  ",级别:".$_POST["tlevel"].
					  ",电话:".$_POST["ttel"].
					  ",邮件:".$_POST["tmail"].
					  ",证件:".$_POST["tidentity"].
					  ",介绍:".$_POST["tintro"];	
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<SCRIPT language=JavaScript>";
		echo "location.href='teacher.php';</script>";
		exit;
	}else{
		echo "<SCRIPT language=JavaScript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
}

//update
if( strtolower($_GET["a"]) == "lcount" && isset($_GET["id"])){
	$sql="select * from hupms_student where id=$_GET[id]";
	$result=mysql_query($sql);
	$num =mysql_num_rows($result);
	if($num <=0){
		echo "<SCRIPT language=JavaScript>alert('没有所需要的id，请重新选择');";
		echo "location.href='admin.php'</SCRIPT>";
		exit;
	}
	$rs=mysql_fetch_object($result);
	if( $_POST["slcount"]=="" ){
		echo "<SCRIPT language=JavaScript>alert('输入课时不得为空，请重新输入');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	if( $_POST["slcount"]<=0 or $_POST>=9999 ){
		echo "<SCRIPT language=JavaScript>alert('您输入的好像不是一个合理的数字，请重新输入');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	$sql1 = "update hupms_student set vdate='$_POST[sintro]' where id=$_GET[id];";
	$result1 = mysql_query($sql1);
	if($result1){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "修改".$rs->No."号学员".$rs->Name."的学员信息";
		$Log_Detail = "姓名:".$_POST["sname"].
					  ",昵称:".$_POST["snickname"].
					  ",会员号:".$_POST["snovip"].
					  ",电话:".$_POST["stel"].
					  ",邮件:".$_POST["smail"].
					  ",证件:".$_POST["sidentity"].
					  ",学校:".$_POST["sschool"].
					  ",介绍:".$_POST["sintro"];	
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<SCRIPT language=JavaScript>";
		echo "location.href='student.php';</script>";
		exit;
	}else{
		echo "<SCRIPT language=JavaScript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
}

}
?>