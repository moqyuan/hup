<?php
ini_set('default_charset','utf-8');
include("config.php");
if(isset($_COOKIE["Username"]) && $_COOKIE["Username"]!='' && $_COOKIE["UserLevel"]>0){

$today=Date('Y-m-d');
//special
if( strtolower($_GET["a"]) == "special" && isset($_GET["lid"])){
	$sql = "SELECT * FROM hupms_lesson where Id = $_GET[lid]";
	$result = mysql_query($sql);
	$num = mysql_num_rows($result);
	if($num <= 0 ){
		echo "<SCRIPT language=JavaScript>alert('没有这个课程，请注意');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	$sql = "INSERT INTO hupms_record_s (Sno, Lid, DT) 
			VALUES ('0000', $_GET[lid], now())";
	$result = mysql_query($sql);
	if($result){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "特殊卡上课，课程id：".$_GET["lid"];
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<script language=JavaScript>";	
		echo "location.href='index.php';</script>";
		exit;
	}else{
		echo "<SCRIPT language=JavaScript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}


}
//cancel
if( strtolower($_GET["a"]) == "cancel" && isset($_GET['id'])){
	$sql = "SELECT * FROM hupms_record_s where Id=$_GET[id];";
	$result=mysql_query($sql);
	$num = mysql_num_rows($result);
	if($num<=0){
		echo "<SCRIPT language=JavaScript>alert('对不起，该记录不存在。');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	$rs=mysql_fetch_object($result);
	if($rs->Sno != '0000'){
		$sql = "SELECT * FROM hupms_student where No = '$rs->Sno' and Del = 0";
		$result = mysql_query($sql);
		$num = mysql_num_rows($result);
		if($num<=0){
			echo "<SCRIPT language=JavaScript>alert('对不起，记录对应的学员不存在，肯定是被某人在数据库删伐删伐就出错了。');";
			echo "javascript:history.back()</SCRIPT>";
			exit;
		}
		$st=mysql_fetch_object($result);

		if($st->Cardtype == 0){
			$lcount = $st->Lcount + 1;
			$sql = "UPDATE hupms_student set Lcount = $lcount where Id = $st->Id";
			$result = mysql_query($sql);
		}
	}

	$sql = "UPDATE hupms_record_s set RT = 0 where Id=$_GET[id]";
	$result = mysql_query($sql);
	if($result){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "取消了id为".$_GET['id']."的签到";
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<script language=JavaScript>";	
		echo "location.href='index.php';</script>";
		exit;
	}else{
		echo "<SCRIPT language=JavaScript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
}

//zbbuy
if( strtolower($_GET["a"]) == "zbbuy" && isset($_POST)){

	if(!isset($_POST['zbnum']) || $_POST['zbnum']==""){
		echo "<SCRIPT language=JavaScript>alert('周边数量没有填。');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	if(!isset($_POST['zbsno']) || $_POST['zbsno']==""){
		echo "<SCRIPT language=JavaScript>alert('会员卡号没有填。');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	$total = $_POST['zbnum'] * $_POST['zbprice'];
	print_r($total);
	$sql="select * from hupms_student where Novip='$_POST[zbsno]'";
	$result=mysql_query($sql);
	$num=mysql_num_rows($result);
	if($num<=0){
		echo "<SCRIPT language=JavaScript>alert('对不起，编号为".$_POST['zbsno']."的会员不存在。');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	$rs=mysql_fetch_object($result);
	if($rs->Moneyleft < $total){
		echo "<script language=JavaScript>";
		echo "alert('".$rs->Novip."号会员".$rs->Name."卡内余额不足，买".$total."块钱的东西都不起啊');";
		echo "location.href='student.php?id=".$rs->Id."';</script>";
		exit;
	}
	$money = $rs->Moneyleft - $total;
	$content = "id为".$rs->Id."学员购买id为".$_POST['zbid']."的周边".$_POST['zbnum']."件";
	$sql1    = "INSERT INTO hupms_record_v 
				(Sid, Novip, Content, Zid, Fee, Datetime, Type)
		        VALUES 
		        ($rs->Id, '$rs->Novip', '$content', $_POST[zbid], $total, now(), 0);";
	$result1 = mysql_query($sql1);
	$sql2    = "UPDATE hupms_student SET Moneyleft = $money where Id = $rs->Id";
	$result2 = mysql_query($sql2);

	if($result1 && $result2){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = $content;
		$Log_Detail = str_replace('\'', '|', $sql1."--".$sql2);
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime,Detail) VALUES ('$Log_Content','$Log_Name','$Log_Username',now(),'$Log_Detail')";
		$result_log = mysql_query($sql_log);
		echo "<script language=Javascript>alert('购买成功');";
		echo "location.href='student.php?id=".$rs->Id."';</script>";
		exit;
	}else{
		echo "<script language=Javascript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</script>";
		exit;
	}

}


//buy_c
if( strtolower($_GET["a"]) == "buy_c"){
	//检验部分
	if( $_POST["sno"]=="" ){
		echo "<SCRIPT language=JavaScript>alert('请输入学员卡号');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	if( $_POST["count"]=="" ){
		echo "<SCRIPT language=JavaScript>alert('请输入次数');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	if( $_POST["money"]=="" ){
		echo "<SCRIPT language=JavaScript>alert('请输入金额');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	$sno=sprintf("%04d", $_POST["sno"]);
	$sql="select * from hupms_student where No='$sno'";
	$result=mysql_query($sql);
	$num=mysql_num_rows($result);

	if($num==0){
		echo "<SCRIPT language=JavaScript>alert('无法找到该学员卡号，请添加该学员');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}else{
		$st=mysql_fetch_object($result);
		if($st->Del==1){
			echo "<SCRIPT language=JavaScript>alert('该学员已被冻结，请解除其冻结状态或询问管理员冻结原因');";
			echo "javascript:history.back()</SCRIPT>";
			exit;
		}
	}

	if($st!=''){
		$count=$_POST["count"]+$st->Lcount;
	}else{
		$count=$_POST["count"];
	}
	//数据库部分

	$sql1 = "INSERT INTO hupms_record_b(Sno,Count,Markup,Money,Date)
			VALUES('$sno','$_POST[count]','$_POST[markup]','$_POST[money]',now());";
	$result1 = mysql_query($sql1);
	$sql2 = "UPDATE hupms_student set Lcount = $count where No='$_POST[sno]'";
	$result2 = mysql_query($sql2);
	if($result1){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = $_POST["sno"]."号学生购买了".$_POST["count"]."次课程，现在总次数为".$count."次";
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<script language=JavaScript>";
		echo "alert('学员".$st->Name."购买了".$_POST["count"]."节课程，总剩余课数为".$count."次');";
		echo "location.href='index.php';</script>";
		exit;
	}else{
		echo "<SCRIPT language=JavaScript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	
}

//buy_v
if( strtolower($_GET["a"]) == "buy_v"){
	//检验部分
	if( $_POST["sno"]=="" ){
		echo "<SCRIPT language=JavaScript>alert('请输入学员卡号');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	if( $_POST["money"]=="" ){
		echo "<SCRIPT language=JavaScript>alert('请输入金额');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	$sno=sprintf("%04d", $_POST["sno"]);
	$sql="select * from hupms_student where No='$sno'";
	$result=mysql_query($sql);
	$num=mysql_num_rows($result);
	if($num==0){
		echo "<SCRIPT language=JavaScript>alert('无法找到该学员卡号，请添加该学员');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}else{
		$st=mysql_fetch_object($result);
		if($st->Del==1){
			echo "<SCRIPT language=JavaScript>alert('该学员已被冻结，请解除其冻结状态或询问管理员冻结原因');";
			echo "javascript:history.back()</SCRIPT>";
			exit;
		}
	}

	//数据库部分
	if($_POST["type"]==1){
		$vname="月卡";
		$sqlv="update hupms_student set Vdate = Date_Add('$today',INTERVAL 1 MONTH) where Id=$st->Id";
	}elseif($_POST["type"]==2){
		$vname="季卡";
		$sqlv="update hupms_student set Vdate = Date_Add('$today',INTERVAL 3 MONTH) where Id=$st->Id";
	}elseif($_POST["type"]==3){
		$vname="半年卡";
		$sqlv="update hupms_student set Vdate = Date_Add('$today',INTERVAL 6 MONTH) where Id=$st->Id";
	}elseif($_POST["type"]==4){
		$vname="年卡";
		$sqlv="update hupms_student set Vdate = Date_Add('$today',INTERVAL 12 MONTH) where Id=$st->Id";
	}
	$resultv=mysql_query($sqlv);
	$sql="INSERT INTO hupms_record_b(Sno,Type,Markup,Money,Date)
			VALUES('$sno','$_POST[type]','$_POST[markup]','$_POST[money]',now())";
	$result=mysql_query($sql);
	if($result){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = $_POST["sno"]."号学生购买了一张".$vname;
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<script language=JavaScript>";
		echo "alert('学员".$st->Name."购买了一张".$vname."');";
		echo "location.href='index.php';</script>";
		exit;
	}else{
		echo "<SCRIPT language=JavaScript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
}
//scheck
if( strtolower($_GET["a"]) == "scheck"){
	if( $_POST["sno"]=="" ){
		echo "<SCRIPT language=JavaScript>alert('请输入学员卡号');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	$sno=sprintf("%04d", $_POST["sno"]);
	$sql="select * from hupms_student where No='$sno'";
	$result=mysql_query($sql);
	$num=mysql_num_rows($result);
	if($num==0){
		echo "<SCRIPT language=JavaScript>alert('无法找到该学员卡号，请添加该学员');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}else{
		$st=mysql_fetch_object($result);
		if($st->Del==1){
			echo "<SCRIPT language=JavaScript>alert('该学员已被冻结，请解除其冻结状态或询问管理员冻结原因');";
			echo "javascript:history.back()</SCRIPT>";
			exit;
		}
	}

	/*--------禁止多次刷卡
	$sql="select * from hupms_record_s where Sno=$sno and Lid='$_POST[lid]' and DT = '$today' and RT = 1";
	$result=mysql_query($sql);
	$ir=mysql_num_rows($result);

	if($ir>0){
		echo "<SCRIPT language=JavaScript>alert('该学员今天已经在这节课登陆过，请不要重复刷卡。');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	*/
	
	if($st->Vdate < $today){
		echo "<SCRIPT language=JavaScript>alert('该学员 ".$st->Name." 有效期已过，不能上课；请让他购买课程后才能上课。');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	if($st->Cardtype == 0){
		if($st->Lcount <= 0){
			echo "<SCRIPT language=JavaScript>alert('该学员 ".$st->Name." 课时数不够了，不能上课；请让他购买课程后才能上课。');";
			echo "javascript:history.back()</SCRIPT>";
			exit;
		}else{
			$lcount=$st->Lcount-1;
			$sql="update hupms_student set Lcount=$lcount where Id=$st->Id";
			$result=mysql_query($sql);
		}
	}

	$sql="select * from hupms_lesson where Id='$_POST[lid]'";
	$result=mysql_query($sql);
	$ln=mysql_fetch_object($result);

	//验证尚未添加
	$sql1 = "INSERT INTO hupms_record_s(Sno,Lid,DT)VALUES('$sno','$_POST[lid]',now());";
	$result1 = mysql_query($sql1);
	if($result1){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "登记了学生".$st->Name."上了课程".$ln->Name;
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<script language=JavaScript>";
		echo "alert('".$st->Name."要上一节今天的".$ln->Name."课程，已登记成功');";
		echo "location.href='student.php?id=". $st->Id ."';</script>";
		exit;
	}else{
		echo "<SCRIPT language=JavaScript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
}

//tcheck
if( strtolower($_GET["a"]) == "tcheck"){

	$sql="select * from hupms_teacher where Id='$_POST[tid]'";
	$result=mysql_query($sql);
	$tr=mysql_fetch_object($result);

	$sql="select * from hupms_lesson where Id='$_POST[lid]'";
	$result=mysql_query($sql);
	$ln=mysql_fetch_object($result);

	$sql1 = "INSERT INTO hupms_record_t(Lid,Tid,DT,Late)VALUES('$_POST[lid]','$_POST[tid]',now(),$_POST[late]);";
	$result1 = mysql_query($sql1);

	if($result){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "教师".$tr->Name."为课程:".$ln->Name."签到";
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<script language=JavaScript>";	
		echo "location.href='index.php';</script>";
		exit;
	}else{
		echo "<SCRIPT language=JavaScript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
}




}
?>