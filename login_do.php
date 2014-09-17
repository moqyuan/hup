<?php
ini_set('default_charset','utf-8');
include("config.php");

//登录
if( strtolower($_GET["a"]) == "login" ){
	
	if( $_POST["username"]=="" ){
		echo "<SCRIPT language=JavaScript>alert('用户名为空，请重新输入');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	
	if( $_POST["password"]=="" ){
		echo "<SCRIPT language=JavaScript>alert('密码为空，请重新输入');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	
	$sql = "select * from hupms_user where Username='$_POST[username]'";
	$result = mysql_query($sql);
	$rs=mysql_fetch_object($result);
	if($rs){
		//能找到用户
		if( $rs->Password != MD5($_POST["password"]) ){		
			echo "<SCRIPT language=JavaScript>alert('密码错误，请重新输入');";
			echo "javascript:history.back()</SCRIPT>";
			exit;
		}else{//密码正确
			//被冻结
			if( $rs->UserLevel == 0 ){
				echo "<SCRIPT language=JavaScript>alert('你的帐户已被冻结，请联系管理员');";
				echo "javascript:history.back()</SCRIPT>";
				exit;
			}else{
				
				session_start();
				$SessionID=session_id();
				
				$sql2 = "UPDATE hupms_user SET Sess='$SessionID',LastLogin=now() where Username='$_POST[username]'";
				$result2 = mysql_query($sql2);
				if($result2){
					//写入sess成功
					SetCookie("Name", $rs->Name);
					SetCookie("UserLevel", $rs->UserLevel);
					SetCookie("Username", $_POST["username"]);
					SetCookie("SessionID", $SessionID);
					
					$Log_Username = $_POST["username"];
					$Log_Name = $rs->Name;
					$Log_Content = "登录系统";
					$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
					$result_log = mysql_query($sql_log);
					
					echo "<script>location.href='index.php';</script>";
					exit;
				}else{
					echo "<SCRIPT language=JavaScript>alert('数据写入失败，联系管理员');";
					echo "javascript:history.back()</SCRIPT>";
					exit;
				}
			}
		}
		
	}else{
		echo "<SCRIPT language=JavaScript>alert('无法找到用户');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
}

//退出
if( strtolower($_GET["a"]) == "logout" ){

	$Log_Username = $_COOKIE["Username"];
	$Log_Name = $_COOKIE["Name"];
	$Log_Content = "退出系统";
	$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
	$result_log = mysql_query($sql_log);

	SetCookie("Username", '');
	SetCookie("SessionID", '');
	SetCookie("Name", '');
	SetCookie("UserLevel", '');
	
	echo "<SCRIPT language=JavaScript>alert('成功退出');";
	echo "location.href='login.php';</SCRIPT>";
	
}

//pw
if( strtolower($_GET["a"]) == "pw" ){
	$sql = "select * from hupms_user where Username='$_COOKIE[Username]'";
	$result = mysql_query($sql);
	$rs = mysql_fetch_object($result);

	if( ($_POST["opwd"]=="")or( $_POST["npwd1"]=="" )or( $_POST["npwd2"]=="" ) ){
		echo "<SCRIPT language=JavaScript>alert('YoPeace！填写不完整，请重新输入');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	if( $_POST["npwd1"] != $_POST["npwd2"] ){
		echo "<SCRIPT language=JavaScript>alert('YoPeace！两次输入不一致，请重新输入');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	$np = MD5($_POST["npwd1"]);
	if( $rs->Password != MD5($_POST["opwd"])){
		echo "<SCRIPT language=JavaScript>alert('YoPeace！原密码输入错误，请重新输入');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}else{
		$sql2 = "UPDATE hupms_user SET Password='$np' where Username='$_COOKIE[Username]'";
		$result2 = mysql_query($sql2);
		if($result2){
			$Log_Username = $rs->Username;
			$Log_Name = $rs->Name;
			$Log_Content = "修改密码";
			$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
			$result_log = mysql_query($sql_log);
			echo "<script language=JavaScript>alert('密码修改成功');";
			echo "location.href='index.php';</script>";
			exit;
		}else{
			echo "<SCRIPT language=JavaScript>alert('数据写入失败，联系管理员');";
			echo "javascript:history.back()</SCRIPT>";
			exit;
		}
	}
}
?>