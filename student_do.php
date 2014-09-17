<?php
ini_set('default_charset','utf-8');
include("config.php");
$today=Date('Y-m-d');

//   Type 
//   0 - BuyClass; 
//   1 - BuyVip;

if(isset($_COOKIE["Username"]) && $_COOKIE["Username"]!='' && $_COOKIE["UserLevel"]>0){
//buy
if( strtolower($_GET["a"]) == "buy" && isset($_POST)){
	if(!isset($_POST['bmoney']) || $_POST['bmoney']==''){
		echo "<script language=Javascript>alert('金额没写');";
		echo "javascript:history.back()</script>";
		exit;
	}
	if($_POST['bmoney']>10000 || $_POST['bmoney']<0){
		echo "<script language=Javascript>alert('金额必须是0-10000的整数');";
		echo "javascript:history.back()</script>";
		exit;
	}

	if(!isset($_POST['bcardtype']) || $_POST['bcardtype']==''){
		echo "<script language=Javascript>alert('要选择卡种');";
		echo "javascript:history.back()</script>";
		exit;
	}

	$sql = "select * from hupms_student where Id='$_POST[bid]' and Del = 0"; 
	$result = mysql_query($sql); 
	$num = mysql_num_rows($result);
	if($num <=0){
		echo "<script language=Javascript>alert('该学生不存在');";
		echo "javascript:history.back()</script>";
		exit;
	}
	$rs=mysql_fetch_object($result);

	if($rs->No == '' or $rs->No == null){
		$sno = sprintf("%04d", $_POST["bsno"]);
		if($sno>=0001 and $sno<=4999){
			$sql = "select * from hupms_student where No='$sno'"; 
			$result = mysql_query($sql); 
			$num = mysql_num_rows($result);
			if($num>0){
				echo "<script language=Javascript>alert('学员卡号已被使用，请使用其他卡号');";
				echo "javascript:history.back()</script>";
				exit;
			}
			$sql = "select * from hupms_invalid where No='$sno'"; 
			$result = mysql_query($sql); 
			$numv = mysql_num_rows($result);
			if($numv>0){
				echo "<script language=Javascript>alert('此学员卡已作废，请使用其他卡号');";
				echo "javascript:history.back()</script>";
				exit;
			}
		}else{
			echo "<script language=Javascript>alert('学员卡号必须为0001-4999之间');";
			echo "javascript:history.back()</script>";
			exit;
		}
	}else{
		$sno = $rs->No;
	}
	$cardid=$_POST["bcardtype"];
	$sql="select * from hupms_card where Del = 0 and Id = ".$cardid;
	$resultCard=mysql_query($sql);
	$rsCard=mysql_fetch_object($resultCard);

	$ct = $rsCard->Type;//卡类别——课时卡还是时段卡
	$lc = $rsCard->Count;//卡课时
	$cn = $rsCard->Name;//卡名
	$d = $rsCard->Day;//卡有效期

	$vdate = "'$today'";//有效期初始化

	// switch($_POST['bcardtype']){
 //        case '0': $ct = 0; $v=2; $lc = 10; $cn='课时卡'; break;
 //        case '1': $ct = 1; $v=1; $lc = 0; $cn='月卡'; break;
 //        case '2': $ct = 1; $v=3; $lc = 0; $cn='季卡'; break;
 //        case '3': $ct = 1; $v=6; $lc = 0; $cn='半年卡'; break;
 //        case '4': $ct = 1; $v=12; $lc = 0; $cn='年卡'; break;
 //        default : $ct = 0; $v=0; $lc = 0;
	// }

	if( $rs->Vdate >= $today){//如果有效期大于今天
		if( $rs->Cardtype == 0  &&  $ct == 0 ){//如果学生当前卡种为课时卡，并且要买卡种为课时卡。
			if( $rs->Lcount == 0 ){//如果目前学生课时数用完
				$vdate = "Date_Add('$today', INTERVAL ".$d." DAY)";//从今天开始记录有效期
				$lc = $rs->Lcount + $lc;//课程数为lc+0
			}else{//如果没用完
				$vdate = "Date_Add('$rs->Vdate', INTERVAL ".$d." DAY)";//叠加有效期
				$lc = $rs->Lcount + $lc;//课程数为lc+0
			}	
		}elseif( $rs->Cardtype == 0  &&  $ct == 1 ){//如果学生当前卡种为课时卡，但是要买卡种为时段卡。
			$vdate = "Date_Add('$today', INTERVAL ".$d." DAY)";//从今天起记录有效期
			$lc = 0;//课时变为0
		}elseif ( $rs->Cardtype == 1 &&  $ct == 0 ) {//如果学生当前卡种为时段卡，但是要买卡种为课时卡。
			$vdate = "Date_Add('$today', INTERVAL ".$d." DAY)";//从今天起记录有效期
			$lc = $lc;//课时为卡片课时
		}elseif ( $rs->Cardtype == 1 &&  $ct == 1 ) {//如果学生当前卡种为时段卡，且要买卡种为课时卡。
			$vdate = "Date_Add('$rs->Vdate', INTERVAL ".$d." DAY)";
			$lc = 0;
		}
	}else{
		if( $ct == 0){
			//$vdate = "Date_Add('$today', INTERVAL 2 MONTH)";
			$vdate = "Date_Add('$today', INTERVAL ".$d." DAY)";
			$lc = $lc;
		}elseif ( $ct ==1) {
			$vdate = "Date_Add('$today', INTERVAL ".$d." DAY)";
			$lc = 0;
		}
	}
	$sql1="UPDATE hupms_student SET No = '$sno', Lcount = $lc, Vdate = $vdate, Cardtype = $ct where Id = '$_POST[bid]'";
	$result1=mysql_query($sql1);
	$content= "学员 ".$rs->Name." 购买课程，课程种类：".$cn."，金额：".$_POST['bmoney']."元";
	$sql2="INSERT INTO hupms_record_b (Datetime, Content, Type, Fee, Sid) VALUES ( now(), '$content', 0, $_POST[bmoney], $rs->Id)";
	$result2=mysql_query($sql2);
	if($result1 && $result2){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = $content;
		$Log_Detail = str_replace('\'', '|', $sql1."--".$sql2);
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime,Detail) VALUES ('$Log_Content','$Log_Name','$Log_Username',now(),'$Log_Detail')";
		$result_log = mysql_query($sql_log);
		echo "<script language=Javascript>";
		echo "location.href='student.php?id=".$rs->Id."';</script>";
		exit;
	}else{
		echo "<script language=Javascript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</script>";
		exit;
	}

}

//add
if( strtolower($_GET["a"]) == "add"){

	if(!isset($_POST["money"]) || !isset($_POST["sname"]) || $_POST["money"]=='' || $_POST["sname"]==''){
		echo "<script language=Javascript>alert('访问页面错误，请联系管理员');";
		echo "javascript:history.back()</script>";
		exit;
	}
	if($_POST["sno"]=='' && $_POST["snovip"]==''){
		echo "<script language=Javascript>alert('会员卡或课程卡必须二选一');";
		echo "javascript:history.back()</script>";
		exit;
	}
	if($_POST["sno"]!=''){
		$sno=sprintf("%04d", $_POST["sno"]);
		if($sno>=0001 and $sno<=4999){
			$sql = "select * from hupms_student where No='$sno'"; 
			$result = mysql_query($sql); 
			$num = mysql_num_rows($result);
			if($num>0){
				echo "<script language=Javascript>alert('学员卡号已被使用，请使用其他卡号');";
				echo "javascript:history.back()</script>";
				exit;
			}
			$sql = "select * from hupms_invalid where No='$sno'"; 
			$result = mysql_query($sql); 
			$numv = mysql_num_rows($result);
			if($numv>0){
				echo "<script language=Javascript>alert('此学员卡已作废，请使用其他卡号');";
				echo "javascript:history.back()</script>";
				exit;
			}
		}else{
			echo "<script language=Javascript>alert('学员卡号必须为0001-4999之间');";
			echo "javascript:history.back()</script>";
			exit;
		}
	}
	if($_POST["snovip"]!=''){
		if($_POST["snovip"]>=5001 and $_POST["snovip"]<=9999){
			$sql = "select * from hupms_student where Novip='$_POST[snovip]'"; 
			$result = mysql_query($sql); 
			$num = mysql_num_rows($result);
			if($num>0){
				echo "<script language=Javascript>alert('会员卡号已被使用，请使用其他卡号');";
				echo "javascript:history.back()</script>";
				exit;
			}
			$sql = "select * from hupms_invalid where Novip='$_POST[snovip]'"; 
			$result = mysql_query($sql); 
			$numvip = mysql_num_rows($result);
			if($numvip>0){
				echo "<script language=Javascript>alert('此会员卡已作废，请使用其他卡号');";
				echo "javascript:history.back()</script>";
				exit;
			}
		}else{
			echo "<script language=Javascript>alert('会员卡号必须为5001-9999之间');";
			echo "javascript:history.back()</script>";
			exit;
		}	
	}

	if(isset($_POST['cardtype']) && $_POST['cardtype']!="" && $_POST['cardtype']!=null){
		$cardid=$_POST['cardtype'];
	}else{
		$cardid=0;//没有选择卡片（只办会员卡）
	}

	$sql="select * from hupms_card where Del = 0 and Id = ".$cardid;

	$resultCard=mysql_query($sql);
	if(mysql_num_rows($resultCard) > 0){
		$rs=mysql_fetch_object($resultCard);
		$ct=$rs->Type;
		if($rs->Count==""){
			$lc=0;
		}else{
			$lc=$rs->Count;
		}
		$vdate="Date_Add('$today',INTERVAL $rs->Day DAY)";
	}else{
		$ct=0;
		$lc=0;
		$vdate="'$today'";
	}

	$vipdate="'$today'";
	if($_POST['snovip']!=''){
		$vipdate="Date_Add('$today',INTERVAL 365 DAY)";//会员卡
	}

	if(!isset($_POST["way"]) || $_POST["way"] == null ) {
		$way = "";
	}else{
		$way = implode($_POST["way"], ',');
	}

	if(!isset($_POST["interest"]) || $_POST["interest"] == null ) {
		$interest = "";
	}else{
		$interest = implode($_POST["interest"], ',');
	}
	echo $interest;
	if(!isset($_POST["sex"]) || $_POST["sex"] == null ) {
		$_POST["sex"] = "";
	}
	if(!isset($_POST["isstu"]) || $_POST["isstu"] == null ) {
		$_POST["isstu"] = "";
	}
	
	//------------------
	//会员卡价格
	//------------------
	$moneyvip=200;

	$sql1 = "INSERT INTO hupms_student(
		No,Novip,Cardtype,Name,
		Birth,Sex,Identity,Tel,Mail,
		Address,Isstu,School,Way,Way_other,
		Learned,Interest,Intro,Lcount,Vdate,Vipdate,Createtime
	) VALUES(
		'$sno','$_POST[snovip]','$ct','$_POST[sname]',
		'$_POST[birth]','$_POST[sex]','$_POST[sidentity]','$_POST[stel]','$_POST[smail]',
		'$_POST[saddress]','$_POST[isstu]','$_POST[sschool]','$way','$_POST[wayother]',
		'$_POST[learned]','$interest','$_POST[sintro]','$lc',$vdate,$vipdate,now()
	)";
	$result1 = mysql_query($sql1);
	if(!$result1){
		//echo "error:".$sql1;
		echo "<script language=Javascript>alert('写入数据库失败1，请联系管理员');";
		echo "javascript:history.back()</script>";
		exit;
	}

	$sql ="SELECT * FROM hupms_student where No='$_POST[sno]' or Novip='$_POST[snovip]'";
	$resultS = mysql_query($sql);
	$rsS=mysql_fetch_object($resultS);


	if($_POST["sno"]!='' && $_POST["snovip"]!=''){

		//a: 学员卡
		$contenta= "学员 ".$_POST['sname']." 购买课程，课程种类：".$rs->Name."，金额：".$_POST['money']."元";
		$money=$_POST["money"]-$moneyvip;
		$sqla = "INSERT INTO hupms_record_b(
			Type, Content, Fee, Sid, Card, Sno, Datetime
		) VALUES(
			'$rs->Type', '$contenta', '$money', $rsS->Id, '$ct', '$_POST[sno]', now() 
		)";

		$resulta=mysql_query($sqla);
		if(!$resulta){
			//echo "error:".$sql1;
			echo "<script language=Javascript>alert('写入数据库失败2，请联系管理员');";
			echo "javascript:history.back()</script>";
			exit;
		}

		//b: 会员卡
		$contentb= "学员 ".$_POST['sname']." 购买会员卡，会员卡号：".$_POST["snovip"]."，金额：".$moneyvip."元";
		$sqlb = "INSERT INTO hupms_record_b(
			Type, Content, Fee, Sid, Card, Sno, Datetime
		) VALUES(
			'$rs->Type', '$contentb', '$moneyvip', $rsS->Id, '$ct', '$_POST[snovip]', now() 
		)";
		$resultb=mysql_query($sqlb);
		if(!$resultb){
			//echo "error:".$sql1;
			echo "<script language=Javascript>alert('写入数据库失败3，请联系管理员');";
			echo "javascript:history.back()</script>";
			exit;
		}

	}

	if($_POST["sno"]!='' && $_POST["snovip"]==''){
		//a: 学员卡
		$contenta= "学员 ".$_POST['sname']." 购买课程，课程种类：".$rs->Name."，金额：".$_POST['money']."元";
		$money=$_POST["money"];
		$sqla = "INSERT INTO hupms_record_b(
			Type, Content, Fee, Sid, Card, Sno, Datetime
		) VALUES(
			'$rs->Type', '$contenta', '$money', $rsS->Id,'$ct',  '$_POST[sno]', now() 
		)";
		$resulta=mysql_query($sqla);
		if(!$resulta){
			//echo "error:".$sql1;
			echo "<script language=Javascript>alert('写入数据库失败4，请联系管理员');";
			echo "javascript:history.back()</script>";
			exit;
		}
	}

	if($_POST["sno"]=='' && $_POST["snovip"]!=''){
		//b: 会员卡
		$contentb= "学员 ".$_POST['sname']." 购买会员卡，会员卡号：".$_POST["snovip"]."，金额：".$moneyvip."元";
		$sqlb = "INSERT INTO hupms_record_b(
			Type, Content, Fee, Sid, Card, Sno, Datetime
		) VALUES(
			'$rs->Type', '$contentb', '$moneyvip', $rsS->Id, '$ct', '$_POST[snovip]', now() 
		)";
		$resultb=mysql_query($sqlb);
		if(!$resultb){
			//echo "error:".$sql1;
			echo "<script language=Javascript>alert('写入数据库失败5，请联系管理员');";
			echo "javascript:history.back()</script>";
			exit;
		}
	}


	if($result1){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "建立新学员:" . $_POST["sname"]." 费用：".$_POST["money"];
		$Log_Detail = str_replace('\'', '|', $sql1);
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime,Detail) VALUES ('$Log_Content','$Log_Name','$Log_Username',now(),'$Log_Detail')";
		$result_log = mysql_query($sql_log);
		//var_dump($_POST);
		echo "<script language=Javascript>";
		echo "location.href='students.php';</script>";
		exit;
	}else{
		echo "error:".$sql1;
		echo "<script language=Javascript>alert('写入数据库失败6，请联系管理员');";
		echo "javascript:history.back()</script>";
		exit;
	}

}



//Addcount
if( strtolower($_GET["a"]) == "addcount" && isset($_POST["aid"])){
	if($_POST['a_mode']==1){
		$sql = "update hupms_student set 
			Lcount = Lcount + $_POST[a_count],
			Vdate  = Date_Add('$today',INTERVAL $_POST[a_day] DAY)
			where Id=$_POST[aid]";
	}elseif($_POST['a_mode']==2){
		$sql = "update hupms_student set 
			Lcount = Lcount + $_POST[a_count],
			Vdate  = Date_Add(Vdate,INTERVAL $_POST[a_day] DAY)
			where Id=$_POST[aid]";
	}
	$content = "为".$_POST["aid"]."号学员续卡".$_POST["a_count"]."次，添加有效期"
				.$_POST['a_day']."天，模式为".$_POST['a_mode']."收款".$_POST['a_money']."元";
	$sql2="INSERT INTO hupms_record_b (Datetime, Content, Type, Fee, Sid) VALUES ( now(), '$content', 9, $_POST[a_money], $_POST[aid])";
	
	$result1=mysql_query($sql);
	$result2=mysql_query($sql2);
	if($result1 && $result2){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = $content;
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<script language=Javascript>";	
		echo "location.href='student.php?id=".$_POST["aid"]."';</script>";
		exit;
	}else{
		echo "<script language=Javascript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</script>";
		exit;
	}
}


//Delete
if( strtolower($_GET["a"]) == "delete" && isset($_GET["id"])){
	$sql="select * from hupms_student where Id=$_GET[id]";
	$result=mysql_query($sql);
	$rs=mysql_fetch_object($result);
	$sql = "update hupms_student set Del= 1 where Id='$_GET[id]' "; 
	$result = mysql_query($sql); 
	if($result){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "冻结了".$rs->No."号学员".$rs->Name;
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<script language=Javascript>";	
		echo "location.href='students.php';</script>";
		exit;
	}else{
		echo "<script language=Javascript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</script>";
		exit;
	}
}

//Recover
if( strtolower($_GET["a"]) == "recover" && isset($_GET["id"])){
	$sql="select * from hupms_student where Id=$_GET[id]";
	$result=mysql_query($sql);
	$rs=mysql_fetch_object($result);
	$sql = "update hupms_student set Del= 0 where Id='$_GET[id]' "; 
	$result = mysql_query($sql); 
	if($result){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "恢复了".$rs->No."号学员".$rs->Name;
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<script language=Javascript>";	
		echo "location.href='students.php';</script>";
		exit;
	}else{
		echo "<script language=Javascript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</script>";
		exit;
	}
}

//update
if( strtolower($_GET["a"]) == "update" && isset($_GET["id"])){
	$sql="select * from hupms_student where id=$_GET[id]";
	$result=mysql_query($sql);
	$num =mysql_num_rows($result);
	if($num <=0){
		echo "<script language=Javascript>alert('没有所需要的id，请重新选择');";
		echo "location.href='student.php'</script>";
		exit;
	}
	$rs=mysql_fetch_object($result);
	if( $_POST["sname"]=="" ){
		echo "<script language=Javascript>alert('姓名不得为空，请重新输入');";
		echo "javascript:history.back()</script>";
		exit;
	}
	$sql = "select * from hupms_student where Name='$_POST[sname]' and Id!=$_GET[id]"; 
	$result = mysql_query($sql); 
	$num = mysql_num_rows($result);
	if($num>0){
		echo "<script language=Javascript>alert('姓名已被使用，请使用其他姓名');";
		echo "javascript:history.back()</script>";
		exit;
	}
	
	$sql1 = "update hupms_student set 
		Name='$_POST[sname]',
		Birth='$_POST[birth]',
		Sex='$_POST[sex]',
		Identity='$_POST[sidentity]',
		Tel='$_POST[stel]',
		Mail='$_POST[smail]',
		Address='$_POST[saddress]',
		Isstu='$_POST[isstu]',
		School='$_POST[sschool]',
		Way='$_POST[way]',
		Way_other='$_POST[wayother]',
		Learned='$_POST[learned]',
		Interest='$_POST[interest]',
		Intro='$_POST[sintro]' where id=$_GET[id];";
	$result1 = mysql_query($sql1);
	if($result1){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "修改".$rs->No."号学员".$rs->Name."的学员信息";
		$Log_Detail = str_replace('\'', '|', $sql1);
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',now())";
		$result_log = mysql_query($sql_log);
		echo "<script language=Javascript>";
		echo "location.href='student.php?id=".$_GET['id']."';</script>";
		exit;
	}else{
		echo "<script language=Javascript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</script>";
		exit;
	}
}

//buyvip
if( strtolower($_GET["a"]) == "buyvip" && isset($_GET["id"])){
	$sql="select * from hupms_student where id= $_GET[id]";
	$result=mysql_query($sql);
	$num =mysql_num_rows($result);
	if($num <=0){
		echo "<script language=Javascript>alert('错误，没有所需要的id，该行为对系统不安全，请联系MoQ');";
		echo "location.href='student.php'</script>";
		exit;
	}
	$rs=mysql_fetch_object($result);
	if($rs->Novip != ''){
		echo "<script language=Javascript>alert('该学生已有会员卡".$rs->Novip."，请刷新页面');";
		echo "javascript:history.back()</script>";
		exit;
	}

	if($_POST["buyvip_no"]>=5001 and $_POST["buyvip_no"]<=9999){
		$sql = "select * from hupms_student where Novip='$_POST[buyvip_no]'"; 
		$result = mysql_query($sql); 
		$num = mysql_num_rows($result);
		if($num>0){
			echo "<script language=Javascript>alert('会员卡号已被使用，请使用其他卡号');";
			echo "javascript:history.back()</script>";
			exit;
		}
		$sql = "select * from hupms_invalid where Novip='$_POST[buyvip_no]'"; 
		$result = mysql_query($sql); 
		$numvip = mysql_num_rows($result);
		if($numvip>0){
			echo "<script language=Javascript>alert('此会员卡已作废，请使用其他卡号');";
			echo "javascript:history.back()</script>";
			exit;
		}
	}else{
		echo "<script language=Javascript>alert('会员卡号必须为5001-9999之间');";
		echo "javascript:history.back()</script>";
		exit;
	}

	$sql1 = "UPDATE hupms_student set Novip='$_POST[buyvip_no]', Vipdate = Date_Add('$today',INTERVAL 12 MONTH) where Id=$_GET[id];";
	$result1 = mysql_query($sql1);
	$content = "为".$rs->No."号学员 ".$rs->Name." 添加会员卡".$_POST["buyvip_no"]."，收费".$_POST["buyvip_money"];
	$sql2 = "INSERT INTO hupms_record_b ( Datetime, Content, Type, Fee, Sid )VALUES( now(), '$content', 3, $_POST[buyvip_money], $_GET[id] ) ";
	$result2 = mysql_query($sql2);
	if($result1 & $result2){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "为".$rs->No."号学员".$rs->Name."添加会员卡".$_POST["buyvip_no"]."，收费".$_POST["buyvip_money"];
		$Log_Detail = str_replace('\'', '|', $sql1.$sql2);
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,Detail,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username','$Log_Detail',now())";
		$result_log = mysql_query($sql_log);
		echo "<script language=Javascript>";
		echo "location.href='student.php?id=".$_GET['id']."';</script>";
		exit;
	}else{
		echo "<script language=Javascript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</script>";
		exit;
	}
}

//charge
if( strtolower($_GET["a"]) == "charge" && isset($_GET["id"])){
	if(!isset($_POST['charge_money'])){
		echo "<script language=Javascript>alert('错误，该行为对系统不安全，请联系MoQ');";
		echo "location.href='students.php'</script>";
		exit;
	}
	$sql="select * from hupms_student where id= $_GET[id]";
	$result=mysql_query($sql);
	$num =mysql_num_rows($result);
	if($num <=0){
		echo "<script language=Javascript>alert('错误，该行为对系统不安全，请联系MoQ');";
		echo "location.href='students.php'</script>";
		exit;
	}
	$rs=mysql_fetch_object($result);
	if($rs->Novip == ''){
		echo "<script language=Javascript>alert('该学生没有会员卡，请先购买会员卡再充值');";
		echo "javascript:history.back()</script>";
		exit;
	}
	$moneyleft = $rs->Moneyleft + $_POST['charge_money'];
	$content = "会员卡充值，卡号【".$rs->Novip."】，金额【".$_POST['charge_money']."】";
	$sql1 = "UPDATE hupms_student set Moneyleft='$moneyleft' where Id=$_GET[id];";
	$result1 = mysql_query($sql1);
	$sql2 = "INSERT INTO hupms_record_v ( Sid, Novip, Content, Fee, Type, Datetime )VALUES( $_GET[id], '$rs->Novip', '$content', $_POST[charge_money], 1, now() ) ";
	$result2 = mysql_query($sql2);
	if($result1 & $result2){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "会员卡充值，卡号【".$rs->Novip."】，金额【".$_POST['charge_money']."】";
		$Log_Detail = str_replace('\'', '|', $sql1.$sql2);
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,Detail,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username','$Log_Detail',now())";
		$result_log = mysql_query($sql_log);
		echo "<script language=Javascript>";
		echo "location.href='student.php?id=".$_GET['id']."';</script>";
		exit;
	}else{
		echo "<script language=Javascript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</script>";
		exit;
	}
}


//continue
if( strtolower($_GET["a"]) == "continue" && isset($_GET["id"])){
	if(!isset($_POST['continue_money'])){
		echo "<script language=Javascript>alert('错误，该行为对系统不安全，请联系MoQ');";
		echo "location.href='students.php'</script>";
		exit;
	}
	$sql="select * from hupms_student where id= $_GET[id]";
	$result=mysql_query($sql);
	$num =mysql_num_rows($result);
	if($num <=0){
		echo "<script language=Javascript>alert('错误，该行为对系统不安全，请联系MoQ');";
		echo "location.href='students.php'</script>";
		exit;
	}
	$rs=mysql_fetch_object($result);
	if($rs->Novip == ''){
		echo "<script language=Javascript>alert('该学生没有会员卡，请先购买会员卡再充值');";
		echo "javascript:history.back()</script>";
		exit;
	}
	if($today >= $rs->Vipdate){
		$vipdate = "Date_Add('$today',INTERVAL 12 MONTH)";
	}elseif($today < $rs->Vipdate){
		$vipdate = "Date_Add('$rs->Vipdate',INTERVAL 12 MONTH)";
	}
	$content = "会员卡续期一年，卡号【".$rs->Novip."】，金额【".$_POST['continue_money']."】";
	$sql1 = "UPDATE hupms_student set Vipdate=$vipdate where Id=$_GET[id];";
	$result1 = mysql_query($sql1);
	$sql2 = "INSERT INTO hupms_record_v ( Sid, Novip, Content, Fee, Type, Datetime )VALUES( $_GET[id], '$rs->Novip', '$content', $_POST[continue_money], 2, now() ) ";
	$result2 = mysql_query($sql2);
	if($result1 & $result2){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "会员卡续卡一年，卡号【".$rs->Novip."】，金额【".$_POST["continue_money"]."】";
		$Log_Detail = str_replace('\'', '|', $sql1.$sql2);
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,Detail,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username','$Log_Detail',now())";
		$result_log = mysql_query($sql_log);
		echo "<script language=Javascript>";
		echo "location.href='student.php?id=".$_GET['id']."';</script>";
		exit;
	}else{
		echo "<script language=Javascript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</script>";
		exit;
	}
}

//lcount
if( strtolower($_GET["a"]) == "lcount" ){
	if($_COOKIE["UserLevel"]!=5){
	  echo "<script language=Javascript>alert('不好意思哈，你权限还不够，问问Sim或MoQ怎么回事吧。');";
	  echo "javascript:history.back()</script>";
	  exit;
	}
	$sql="select * from hupms_student where id=$_POST[sid]";
	$result=mysql_query($sql);
	$num =mysql_num_rows($result);
	if($num <=0){
		echo "<script language=Javascript>alert('错误，没有所需要的id，请联系MoQ');";
		echo "location.href='student.php'</script>";
		exit;
	}
	$rs=mysql_fetch_object($result);
	if( $_POST["slcount"]=="" ){
		echo "<script language=Javascript>alert('输入课时不得为空，请重新输入');";
		echo "javascript:history.back()</script>";
		exit;
	}
	if( $_POST["slcount"]<=0 or $_POST["slcount"]>=9999 ){
		echo "<script language=Javascript>alert('您输入的好像不是一个合理的数字，请重新输入');";
		echo "javascript:history.back()</script>";
		exit;
	}
	if( $_POST["sreason"]=="" ){
		echo "<script language=Javascript>alert('必须输入改动原因，因为这不是一个普通的改动');";
		echo "javascript:history.back()</script>";
		exit;
	}
	$sql1 = "update hupms_student set Lcount='$_POST[slcount]' where id=$_POST[sid];";
	$result1 = mysql_query($sql1);
	if($result1){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "修改".$rs->No."号学员".$rs->Name."的课时信息";
		$Log_Detail = "课时设为:".$_POST["slcount"]."，修改原因:".$_POST["sreason"];
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,Detail,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',$Log_Detail,now())";
		$result_log = mysql_query($sql_log);
		echo "<script language=Javascript>";
		echo "location.href='student.php';</script>";
		exit;
	}else{
		echo "<script language=Javascript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</script>";
		exit;
	}
}

//vdate
if( strtolower($_GET["a"]) == "vdate" ){
	if($_COOKIE["UserLevel"]!=5){
	  echo "<script language=Javascript>alert('不好意思哈，你权限还不够，问问Sim或MoQ怎么回事吧。');";
	  echo "javascript:history.back()</script>";
	  exit;
	}
	$sql="select * from hupms_student where id=$_POST[sid]";
	$result=mysql_query($sql);
	$num =mysql_num_rows($result);
	if($num <=0){
		echo "<script language=Javascript>alert('错误，没有所需要的id，请联系MoQ');";
		echo "location.href='student.php'</script>";
		exit;
	}
	$rs=mysql_fetch_object($result);
	if( $_POST["svdate"]=="" ){
		echo "<script language=Javascript>alert('输入日期不得为空，请重新输入');";
		echo "javascript:history.back()</script>";
		exit;
	}
	$chd = explode("-", $_POST["svdate"]);
	if(isset($chd[2])){
		if(!checkdate($chd[1], $chd[2], $chd[0])){
			echo "<script language=Javascript>alert('输入的日期有误，请参照格式重新输入');";
			echo "javascript:history.back()</script>";
			exit;
		}
	}else{
		echo "<script language=Javascript>alert('输入的日期有误，请参照格式重新输入');";
		echo "javascript:history.back()</script>";
		exit;
	}
	if( $_POST["sreason"]=="" ){
		echo "<script language=Javascript>alert('必须输入改动原因，因为这不是一个普通的改动');";
		echo "javascript:history.back()</script>";
		exit;
	}
	$sql1 = "update hupms_student set Vdate='$_POST[svdate]' where id=$_POST[sid];";
	$result1 = mysql_query($sql1);
	if($result1){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "修改".$rs->No."号学员".$rs->Name."的有效期信息";
		$Log_Detail = "有效期设为:".$_POST["vdate"]."，修改原因:".$_POST["sreason"];
		$sql_log = "INSERT INTO hupms_log(Content,Name,Username,Detail,DoTime) VALUES ('$Log_Content','$Log_Name','$Log_Username',$Log_Detail,now())";
		$result_log = mysql_query($sql_log);
		echo "<script language=Javascript>";
		echo "location.href='student.php';</script>";
		exit;
	}else{
		echo "<script language=Javascript>alert('写入数据库失败，请联系管理员');";
		echo "javascript:history.back()</script>";
		exit;
	}
}


//lost_normal
if( strtolower($_GET["a"]) == "lost_normal"){
	if( $_POST["ono"]=="" ){
		echo "<SCRIPT language=JavaScript>alert('请输入要挂失学员卡号');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	if( $_POST["nno"]=="" ){
		echo "<SCRIPT language=JavaScript>alert('请输入新学员卡号');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}

	$ono=sprintf("%04d", $_POST["ono"]);
	$nno=sprintf("%04d", $_POST["nno"]);
	if($nno>=0001 and $nno<=4999){
		$sql = "select * from hupms_student where No='$nno'"; 
		$result = mysql_query($sql); 
		$num = mysql_num_rows($result);
		if($num>0){
			echo "<script language=Javascript>alert('学员卡号已被使用，请使用其他卡号');";
			echo "javascript:history.back()</script>";
			exit;
		}
		$sql = "select * from hupms_invalid where No='$nno'"; 
		$result = mysql_query($sql); 
		$numv = mysql_num_rows($result);
		if($numv>0){
			echo "<script language=Javascript>alert('此学员卡已作废，请使用其他卡号');";
			echo "javascript:history.back()</script>";
			exit;
		}
	}else{
		echo "<script language=Javascript>alert('学员卡号必须为0001-4999之间');";
		echo "javascript:history.back()</script>";
		exit;
	}
	$sql="select * from hupms_student where No='$ono'";
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

	$sql="update hupms_student set No='$nno' where No='$ono'";
	$result=mysql_query($sql);
	$sql="update hupms_record_s set Sno ='$nno' where Sno = '$ono'";
	$result=mysql_query($sql);
	$sql="INSERT INTO hupms_invalid (No)VALUES('$ono')";
	$result=mysql_query($sql);
	if($result){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "挂失了".$ono."号学员的学员卡，改为".$nno."，原卡作废";
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

//lost_vip
if( strtolower($_GET["a"]) == "lost_vip"){
	if( $_POST["onovip"]=="" ){
		echo "<SCRIPT language=JavaScript>alert('请输入要挂失会员卡号');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	if( $_POST["nnovip"]=="" ){
		echo "<SCRIPT language=JavaScript>alert('请输入新会员卡号');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}

	$onovip=sprintf("%04d", $_POST["onovip"]);
	$nnovip=sprintf("%04d", $_POST["nnovip"]);
	$sql="select * from hupms_student where Novip='$onovip'";
	$result=mysql_query($sql);
	$num=mysql_num_rows($result);
	if($num==0){
		echo "<SCRIPT language=JavaScript>alert('无法找到该会员卡号，请添加该会员卡');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}else{
		$st=mysql_fetch_object($result);
		if($st->Del==1){
			echo "<SCRIPT language=JavaScript>alert('该会员已被冻结，请解除其冻结状态或询问管理员冻结原因');";
			echo "javascript:history.back()</SCRIPT>";
			exit;
		}
	}
	$sql="select * from hupms_student where Novip='$nnovip'";
	$result=mysql_query($sql);
	$num=mysql_num_rows($result);
	if($num>0){
		echo "<SCRIPT language=JavaScript>alert('会员卡号已被使用，请使用其他卡号');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	$sql = "select * from hupms_invalid where Novip='$nnovip'"; 
	$result = mysql_query($sql); 
	$numv = mysql_num_rows($result);
	if($numv>0){
		echo "<SCRIPT language=JavaScript>alert('此会员卡已作废，请使用其他卡号');";
		echo "javascript:history.back()</SCRIPT>";
		exit;
	}
	$sql="update hupms_student set Novip='$nnovip' where Novip='$onovip'";
	$result=mysql_query($sql);
	$sql="update hupms_record_v set Novip='$nnovip' where Novip='$onovip'";
	$result=mysql_query($sql);
	$sql="INSERT INTO hupms_invalid (Novip)VALUES('$onovip')";
	$result=mysql_query($sql);
	if($result){
		$Log_Username = $_COOKIE["Username"];
		$Log_Name = $_COOKIE["Name"];
		$Log_Content = "挂失了".$onovip."号学员的会员卡，改为".$nnovip."，原卡作废";
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