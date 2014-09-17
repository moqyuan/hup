<?php
	$db_host = "127.0.0.1";
	$db_name = "hupms";
	$db_user = "root";
	$db_pass = "";
	$mydbLink = mysql_connect($db_host, $db_user, $db_pass) or die("数据库连接失败");
	mysql_select_db($db_name, $mydbLink) or die ("数据库打开失败");
	mysql_query("SET NAMES utf8");
	date_default_timezone_set('ASIA/SHANGHAI');
?>