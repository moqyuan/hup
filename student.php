<?php
$PageTitle = '修改学员';
$ClassName = 'student';
$PageName = 'student';
$MetaDesc = '';
if($_COOKIE["UserLevel"]<3){
  echo "<SCRIPT language=JavaScript>alert('不好意思哈，你权限还不够，问问Sim或MoQ怎么回事吧。');";
  echo "javascript:history.back()</SCRIPT>";
  exit;
}
ini_set('default_charset','utf-8');
include("config.php");
$today=Date('Y-m-d');//Date should be under the include;
if(!isset($_GET["id"])){ 
  if(!isset($_POST["query"])){
    echo "<SCRIPT language=JavaScript>alert('查询不得为空，请重新设置');";
    echo "javascript:history.back()</SCRIPT>";
    exit;
  }else{
    if($_POST["query"]==''){
      echo "<SCRIPT language=JavaScript>alert('查询不得为空，请重新设置');";
      echo "javascript:history.back()</SCRIPT>";
      exit;
    }
    $query=$_POST["query"];
    $sql="select * from hupms_student where ( No='$query' or Novip='$query' or Tel='$query' or Name='$query' ) and Del=0";
    $result=mysql_query($sql);
    $num=mysql_num_rows($result);
    if($num<=0){
      echo "<SCRIPT language=JavaScript>alert('没有您所要找的学员，或该学员已被冻结，请重新选择');";
      echo "javascript:history.back()</SCRIPT>";
      exit;
    }else{
      $rs=mysql_fetch_object($result);
    }
  }
}else{
  if($_GET["id"]==''){
      echo "<SCRIPT language=JavaScript>alert('查询不得为空，请重新设置');";
      echo "javascript:history.back()</SCRIPT>";
      exit;
  }
  $id=$_GET["id"];
  $sql="select * from hupms_student where ( Id='$id' ) and Del=0";
  $result=mysql_query($sql);
  $num=mysql_num_rows($result);
  if($num<=0){
    echo "<SCRIPT language=JavaScript>alert('没有您所要找的学员，或该学员已被冻结，请重新选择');";
    echo "javascript:history.back()</SCRIPT>";
    exit;
  }else{
    $rs=mysql_fetch_object($result);
    //var_dump($rs);
  }
}

$sqlC="select * from hupms_card where Del=0";
$resultC=mysql_query($sqlC);

$day1=strtotime($rs->Vdate);
$day2=strtotime($today);
$diff=$day2-$day1;
$day =floor($diff / (60*60*24));

?>
<!DOCTYPE html>
<html lang="en">
  <?php include("include_head.php"); ?><!-- Head -->
  <body>
    <?php include("include_nav.php"); ?><!-- Navbar -->
    <div class="container">
      <div class="row" style="margin-top: 10px">
        <div class="col-md-3">
          <a href="index.php" class="btn btn-default btn-lg btn-block"><span class="glyphicon glyphicon-circle-arrow-left">　</span>返回首页</a>
          <button class="btn btn-default btn-lg btn-block" data-toggle="modal" data-target="#buy">
            <span class="glyphicon glyphicon-usd">　</span>购买课程
          </button>

          <?php
           if($rs->Cardtype == 0 && $rs->No!='' && $rs->No!=null ){
          ?>

          <button class="btn btn-default btn-lg btn-block" data-toggle="modal" data-target="#seniorcount">
            <span class="glyphicon glyphicon-cog">　</span>次卡高级修改
          </button>

          <?php
           }
          ?>

          <button class="btn btn-default btn-lg btn-block" data-toggle="modal" data-target="#info">
            <span class="glyphicon glyphicon-list-alt">　</span>学员信息
          </button>
          <?php
            if($rs->Novip==''){
          ?>
          <button class="btn btn-warning btn-lg btn-block" data-toggle="modal" data-target="#buyvip">
            <span class="glyphicon glyphicon-star">　</span>购买会员卡
          </button>
          <?php
            }else{
          ?>
          <button class="btn btn-warning btn-lg btn-block" data-toggle="modal" data-target="#charge">
            <span class="glyphicon glyphicon-star">　</span>会员卡充值
          </button>
          <button class="btn btn-warning btn-lg btn-block" data-toggle="modal" data-target="#continue">
            <span class="glyphicon glyphicon-tint">　</span>会员续卡
          </button>
          <?php    
            }
          ?>
          <button class="btn btn-danger btn-lg btn-block" data-toggle="modal" data-target="#lost">
            <span class="glyphicon glyphicon-exclamation-sign">　</span>卡片挂失
          </button>
        </div>
        <div class="col-md-9">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">学员状态</h3>
            </div>

            <ul class="list-group">
              <li class="list-group-item">
                <?php echo $rs->Name."　　".$rs->Tel;?>
              </li>
              <li class="list-group-item">
                <?php
                  if($rs->No==''){
                    echo "学员卡号：无";
                  }else{
                    $htmlcode="学员卡号：".$rs->No."<span>　　</span>卡种：";
                    if($rs->Cardtype==0){
                      $htmlcode.="次卡"."<span>　　</span>剩余次数：".$rs->Lcount;
                    }else{
                      $htmlcode.="时段卡";
                    }
                    $htmlcode.="　　有效期至：<span class='";
                    if($rs->Vdate>=$today){
                      $htmlcode.="label label-success";
                    }else{
                      $htmlcode.="label label-danger";
                    }
                    $htmlcode.="' data-toggle='modal' data-target='#buy' style='cursor:pointer'>".$rs->Vdate."</span>";
                    echo $htmlcode;
                  }
                ?>
              </li>
              <li class="list-group-item">
                <?php
                  if($rs->Novip==''){
                    echo "会员卡号：无";
                  }else{
                    $htmlcode="会员卡号：".$rs->Novip;

                    $htmlcode.="　　有效期至：<span class='";
                    if($rs->Vipdate>=$today){
                      $htmlcode.="label label-success";
                    }else{
                      $htmlcode.="label label-danger";
                    }
                    $htmlcode.="' data-toggle='modal' data-target='#continue' style='cursor:pointer'>".$rs->Vipdate."</span>";
                    $htmlcode.="<span>　　</span>卡内余额：".$rs->Moneyleft;
                    echo $htmlcode;
                  }
                ?>
              </li>
              <li class="list-group-item">
              <?php
                if($rs->No!=''){
                  $sql="select * from hupms_record_s where Sno=$rs->No";
                  echo $sql."<br/>";
                  $result=mysql_query($sql);
                  if(mysql_num_rows($result)==0){
                    $recordRs=0;
                  }else{
                    $recordRs=array();
                    $i=0;
                    while($d=mysql_fetch_object($result)){
                      $recordRs[$i]=$d;
                      $i++;
                    }
                  }
                  
                  //var_dump($recordRs);

                  echo json_encode($recordRs);
              ?>

              <?php
                }
              ?>
              </li>
              
              <!--
              <li class="list-group-item">
                至今共上课程：面包会有的<br/>
                至今共消费　：面包会有的
              </li>
              <li class="list-group-item">
                上课历史图表：面包会有的
              </li>
              -->
            </ul>
          </div>
        </div>
      </div>
      
      
      <!--购买课程-->
      <div class="modal fade" id="buy" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="myModalLabel">购买课程
                <small> 当前卡种：<span class="label label-warning">
                <?php
                if($rs->Cardtype=='' ){
                  echo "无学员卡";
                }elseif($rs->Cardtype==0){
                  echo "课时卡";
                }elseif($rs->Cardtype==1){
                  echo "时段卡";
                }
                if($day>0){
                  echo " 已过期".$day."天";
                }
                ?>
                </span></small>
              </h4>
            </div>
            <div class="modal-body">
              <div class="panel-body">

                <form id="form_buy" class="row" action="student_do.php?a=buy" method="post">
                <input type="hidden" id="bid" name="bid" value="<?php echo $rs->Id;?>">
                <div class="form-group">
                <div class="col-sm-6">
                  <div class="btn-group">
                    <select class="form-control" id="bcardtype" name="bcardtype">
                    <option value="">请选择卡种（必须）</option>
                      <?php 
                      while($rsCard=mysql_fetch_object($resultC)){
                        $content = '<option value="'.$rsCard->Id.'">'.$rsCard->Name.'　　'.$rsCard->Price."/";
                        if($rsCard->PriceVIP=='' || $rsCard->PriceVIP==null){
                          $content.="无";
                        }else{
                          $content.=$rsCard->PriceVIP;
                        }
                        $content .='　　'.$rsCard->Day.'天</option>';
                        echo $content;
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="input-group">
                    <input type="text" class="form-control" id="bmoney" name="bmoney" placeholder="金额">
                    <span class="input-group-addon">
                      元
                    </span>
                    <?php
                    if($rs->No=='' || $rs->No == null){
                    ?>
                    <input type="text" class="form-control" id="bsno" name="bsno" value="<?php echo $rs->No;?>" placeholder="卡号">
                    <?php
                    }
                    ?>
                  </div> 
                </div>
                <div class="col-sm-2">
                  <div class="input-group">
                    <input type="button" class="btn btn-warning" value="购买" onclick="conf_buy()">
                  </div> 
                </div>
              </div><!--tag-->
              </form>

              <br><br>

                <div class="alert alert-warning">
                  <p>
                    <b>有效期内的课时卡</b><br>
                    * 买时段卡：该卡将转为时段卡，并从现在开始记录有效期。<em>原有课时卡数据归零</em><br>
                    * 买课时卡：在现在的基础上叠加课时数量和有效期<br><br>
                    </p>
                  <p>
                    <b>有效期内的时段卡</b><br>
                    * 买时段卡：在原有有效期上叠加新的有效期<br>
                    * 买课时卡：该卡将转为课时卡，并从现在开始计算2个月的有效期<br><br>
                    </p>
                  <p>
                    <b>过期学员卡</b><br>
                    * 按新学员开卡时的标准计算有效期和课时数<br>
                  </p>
                </div>

              </div>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div>

      <!-- 次卡高级修改 -->
      <div class="modal fade" id="seniorcount" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="myModalLabel">次卡高级修改
                <small> 当前卡种：<span class="label label-warning">
                <?php
                if($day>0){
                  echo " 已过期".$day."天";
                }else{
                  echo " 还剩".$day."天";
                }
                ?>
                </span></small>
              </h4>
            </div>
            <div class="modal-body">
              <form action="student_do.php?a=addcount" id="form_addcount" method="post" class="form-horizontal" role="form">
              <input type="hidden" id="aid" name="aid" value="<?php echo $rs->Id;?>">
              <div class="form-group">

                <div class="col-sm-6 input-group">
                  <input type="text" class="form-control" id="a_count" name="a_count" placeholder="要增加的次数" value="<?php

                    if ($day >= 0 && $day <=30 ){
                      echo 1;
                    }elseif($day > 30 and $day <= 60 ){
                      echo 10;
                    }else{
                      echo 10;
                    }

                   ?>">
                  <span class="input-group-addon">次</span>
                </div>
                
                <div class="col-sm-6 input-group">
                  <input type="text" class="form-control" id="a_day" name="a_day" placeholder="有效期" value="<?php

                    if ($day >= 0 && $day <=30 ){
                      echo 28;
                    }elseif($day > 30 and $day <= 60 ){
                      echo 56;
                    }else{
                      echo 56;
                    }

                   ?>">
                  <span class="input-group-addon">天</span>
                </div>
              </div><!--tag-->

              <div class="form-group">
                <div class="col-sm-6 input-group">
                  <input type="text" class="form-control" id="a_money" name="a_money" placeholder="金额" value="<?php 

                    if ($day >= 0 && $day <=30 ){
                      echo 150;
                    }elseif($day > 30 and $day <= 60 ){
                      echo 630;
                    }else{
                      echo 630;
                    }

                  ?>">
                  <span class="input-group-addon">元</span>
                </div>
                <div class="col-sm-6">

                  <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-warning">
                      <input type="radio" name="a_mode" id="a_mode1" value="1" > 1. 从今天开始
                    </label>
                    <label class="btn btn-warning">
                      <input type="radio" name="a_mode" id="a_mode2" value="2" > 2. 叠加有效期
                    </label>
                  </div>

                </div>


              </div><!--tag-->

            </form>
                

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">返回</button>
              <button type="button" class="btn btn-warning" onClick="conf_addcount()">添加</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div>



      <!-- 学员信息 -->
      <div class="modal fade" id="info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog"><!-- .modal -->    
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">学员信息</h4>
          </div>
          <div class="modal-body">


            <form action="student_do.php?a=update&id=<?php echo $rs->Id;?>" id="supdate" method="post" class="form-horizontal" role="form">
              
              <div class="form-group">

                <label class="col-sm-2 control-label">*姓名</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="sname" name="sname" placeholder="姓名（必填）" value="<?php echo $rs->Name;?>">
                </div>
                
                <label class="col-sm-2 control-label">生日</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="birth" name="birth" placeholder="例：1990-09-10" value="<?php echo $rs->Birth;?>">
                </div>
              </div><!--tag-->

              <div class="form-group">
                <label class="col-sm-2 control-label">性别</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="sex" name="sex" placeholder="性别" value="<?php echo $rs->Sex;?>">
                  <!--
                  <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-warning">
                      <input type="radio" name="sex" id="sex1" value="男" > 男
                    </label>
                    <label class="btn btn-warning">
                      <input type="radio" name="sex" id="sex2" value="女" > 女
                    </label>
                  </div>
                -->
                </div>
                <script type="text/javascript">
                
                </script>

                <label class="col-sm-2 control-label">证件</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="sidentity" name="sidentity" placeholder="身份证/护照" value="<?php echo $rs->Identity;?>">
                </div>
              </div><!--tag-->

              <div class="form-group">
                <label class="col-sm-2 control-label">手机</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="stel" name="stel" placeholder="手机号" value="<?php echo $rs->Tel;?>">
                </div>
                <label class="col-sm-2 control-label">邮件</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="smail" name="smail" placeholder="电子邮件" value="<?php echo $rs->Mail;?>">
                </div>
              </div><!--tag-->

              <div class="form-group">
                <label class="col-sm-2 control-label">住址</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="saddress" name="saddress" placeholder="xx市xx区xx路xx号xx室" value="<?php echo $rs->Address;?>">
                </div>
              </div><!--tag-->

              <div class="form-group">
                <label class="col-sm-2 control-label">学生</label>
                <div class="col-sm-2">
                  <input type="text" class="form-control" id="isstu" name="isstu" placeholder="学生" value="<?php echo $rs->Isstu;?>">
                  
                  <!--
                  <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-warning">
                      <input type="radio" name="isstu" id="isstu1" value="是"> 是
                    </label>
                    <label class="btn btn-warning">
                      <input type="radio" name="isstu" id="isstu2" value="否"> 否
                    </label>
                  </div>
                -->
                </div>
                <label class="col-sm-2 control-label">学校</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="sschool" name="sschool" placeholder="大学/高中/初中/小学" value="<?php echo $rs->School;?>">
                </div>
              </div><!--tag-->

              <div class="form-group">
                <label class="col-sm-2 control-label">了解渠道</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="way" name="way" placeholder="大学/高中/初中/小学" value="<?php echo $rs->Way;?>">
                  <!--
                  <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-warning">
                      <input type="checkbox" name="way" id="way1" value="朋友"> 朋友
                    </label>
                    <label class="btn btn-warning">
                      <input type="checkbox" name="way" id="way2" value="网络"> 网络
                    </label>
                    <label class="btn btn-warning">
                      <input type="checkbox" name="way" id="way3" value="传单"> 传单
                    </label>
                    <label class="btn btn-warning">
                      <input type="checkbox" name="way" id="way4" value="平面媒体"> 平面媒体
                    </label>
                    <label class="btn btn-warning">
                      <input type="checkbox" name="way" id="way5" value="喜爱老师"> 喜爱老师
                    </label>
                    <label class="btn btn-warning">
                      <input type="checkbox" name="way" id="way6" value="活动参与"> 活动参与
                    </label>
                  </div>
                -->
                </div>
                <div class="col-sm-2">
                  <input type="text" class="form-control" id="wayother" name="wayother" placeholder="其他" value="<?php echo $rs->Way_other;?>">
                </div>
              </div><!--tag-->

              <div class="form-group">
                <label class="col-sm-2 control-label">曾学过的</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="learned" name="learned" placeholder="学过的艺术类课程" value="<?php echo $rs->Learned;?>">
                </div>
              </div><!--tag-->

              <div class="form-group">
                <label class="col-sm-2 control-label">感兴趣的</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="interest" name="interest" placeholder="感兴趣的" value="<?php echo $rs->Interest; ?>">
                </div>
                
                <!--
                <div class="col-sm-10">
                  <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-warning">
                      <input type="checkbox" name="interest" id="interest1" value="POPPING"> POPPING
                    </label>
                    <label class="btn btn-warning">
                      <input type="checkbox" name="interest" id="interest2" value="LOCKING"> LOCKING
                    </label>
                    <label class="btn btn-warning">
                      <input type="checkbox" name="interest" id="interest3" value="HIPHOP"> HIPHOP
                    </label>
                    <label class="btn btn-warning">
                      <input type="checkbox" name="interest" id="interest4" value="HOUSE"> HOUSE
                    </label>
                    <label class="btn btn-warning">
                      <input type="checkbox" name="interest" id="interest5" value="JAZZ"> JAZZ
                    </label>
                  </div>
                </div>
              -->
              </div><!--tag-->



              <div class="form-group">
                <label class="col-sm-2 control-label">期许和建议</label>
                <div class="col-sm-10">
                  <textarea type="text" class="form-control" id="sintro" name="sintro" placeholder="对自己舞蹈学习的期许和对工作室的任何建议意见都可以写在这里哟"><?php echo $rs->Intro;?></textarea>
                </div>
              </div><!--tag-->


            </form>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">返回</button>
            <button type="button" class="btn btn-warning" onClick="supdate.submit()">修改</button>
          </div>
        </div>
      </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->
      <!-- /学员信息 -->

      <!-- 挂失 -->
      <div class="modal fade" id="lost" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="myModalLabel">卡片挂失</h4>
            </div>
            <div class="modal-body">
              <div class="alert alert-danger">说明：挂失该学生的卡片。【注意：原卡将被作废，如需再利用，需联系高级管理员】</div>

              <table class="table table-striped">
              <tbody>
                <?php 
                if($rs->No!=''){
                ?>
                <form action="student_do.php?a=lost_normal" method="post" >
                  <tr>
                    <td nowrap="nowrap">学员卡</td>
                    <td>
                      <input class="form-control" type="text" name="ono" readonly="readonly" value="<?php echo $rs->No;?>"></td>
                    <td>
                      <input class="form-control" type="text" name="nno"></td>
                    <td>
                      <input type="submit" class="btn btn-danger" value="挂失"></td>
                  </tr>
                </form>
                <?php
                }
                if($rs->Novip!=''){
                ?>
                <form action="student_do.php?a=lost_vip" method="post">
                  <tr>
                    <td nowrap="nowrap">会员卡</td>
                    <td>
                      <input class="form-control" type="text" name="onovip" readonly="readonly"  value="<?php echo $rs->Novip;?>"></td>
                    <td>
                      <input class="form-control" type="text" name="nnovip"></td>
                    <td>
                      <input type="submit" class="btn btn-danger" value="挂失"></td>
                  </tr>
                </form>
                <?php
                }
                ?>
              </tbody>
            </table>
            
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->
      <!-- /挂失 -->
      <?php
        if($rs->Novip==''){
      ?>
      <div class="modal fade" id="buyvip" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="myModalLabel">购买会员卡</h4>
            </div>
            <div class="modal-body">
              <div class="panel-body">
              <form action="student_do.php?a=buyvip&id=<?php echo $rs->Id;?>" id="form_buyvip" method="post" class="form-horizontal" role="form">
              <div class="form-group">

                <label class="col-sm-2 control-label">金额</label>

                                   
                <div class="col-sm-3">
                  <div class="input-group">  
                    <input type="text" class="form-control" id="buyvip_money" name="buyvip_money" placeholder="金额" value="100">
                    <span class="input-group-addon">
                    元
                    </span>
                  </div> 
                </div>

                <label class="col-sm-2 control-label">会员卡号</label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="buyvip_no" name="buyvip_no" placeholder="新会员卡">
                </div>
                
                <div class="col-sm-2">
                  <input type="button" class="btn btn-warning btn-block" onClick="conf_buyvip()" value="确定">
                </div>
              </div>
              </form>
                
              </div>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div>
      <?php
        }else{
      ?>
      <div class="modal fade" id="charge" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="myModalLabel">会员卡充值</h4>
            </div>
            <div class="modal-body">
              <div class="panel-body">
                <div class="alert alert-warning">说明：为该会员的会员卡充值，默认充值为100元</div>

                <form action="student_do.php?a=charge&id=<?php echo $rs->Id;?>" id="form_charge" method="post" class="form-horizontal" role="form">
                <div class="form-group">

                  <label class="col-sm-2 control-label">金额</label>

                                     
                  <div class="col-sm-4">
                    <div class="input-group">  
                      <input type="text" class="form-control" id="charge_money" name="charge_money" placeholder="金额" value="100">
                      <span class="input-group-addon">
                      元
                      </span>
                    </div> 
                  </div>

                  
                  <div class="col-sm-3">
                    <input type="button" class="btn btn-warning btn-block" onClick="conf_charge()" value="确定">
                  </div>
                </div>
                </form>

              </div>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div>

      <div class="modal fade" id="continue" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="myModalLabel">会员续卡</h4>
            </div>
            <div class="modal-body">
              <div class="panel-body">
                <div class="alert alert-warning">说明：为该会员延长会员卡的有效期 1 年，默认收费为100元</div>

                <form action="student_do.php?a=continue&id=<?php echo $rs->Id;?>" id="form_continue" method="post" class="form-horizontal" role="form">
                <div class="form-group">

                  <label class="col-sm-2 control-label">金额</label>

                                     
                  <div class="col-sm-4">
                    <div class="input-group">  
                      <input type="text" class="form-control" id="continue_money" name="continue_money" placeholder="金额" value="100">
                      <span class="input-group-addon">
                      元
                      </span>
                    </div> 
                  </div>

                  
                  <div class="col-sm-3">
                    <input type="button" class="btn btn-warning btn-block" onClick="conf_continue()" value="确定">
                  </div>
                </div>
                </form>

              </div>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div>
      <?php    
        }
      ?>
      

      <br/>
      <!--
      <div class="footer">
        <p class="text-center">. Copyright : Hurry Up Dance Studio 2013 .</p>
      </div>
      -->
    </div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <?php include("include_js.php");?>
    <script type="text/javascript">
    function conf_buyvip(){
      var novip = $('#buyvip_no').val();
      var money = $('#buyvip_money').val();
      if(  isNaN(novip)                  ){  window.alert("会员卡号必须为5001-9999的整数");  return;  }
      if(  novip < 5001 || novip > 9999  ){  window.alert("会员卡号必须为5001-9999的整数");  return;  }
      if(  isNaN(money)                  ){  window.alert("金额必须为1-1000的整数");  return;  }
      if(  money < 1 || money > 1000     ){  window.alert("金额必须为1-1000的整数");  return;  }

      var returnVal = window.confirm("请核对信息：\n即将为<?php echo $rs->No;?>号学员【<?php echo $rs->Name;?>】"
        +"\n购买一张会员卡，卡号为："+novip+"\n收费：【"
        +money+"】元，有效期为1年",
        "确认");
 
      if(returnVal){
         $("#form_buyvip").submit();
      }
    }

    function conf_charge(){
      var money = $('#charge_money').val();
      if(  isNaN(money)                   ){  window.alert("金额必须为1-10000的整数");  return;  }
      if(  money < 1 || money > 10000     ){  window.alert("金额必须为1-10000的整数");  return;  }

      var returnVal = window.confirm("请核对信息：\n即将为<?php echo $rs->Novip;?>号会员【<?php echo $rs->Name;?>】"
        +"\n充值【"+money+"】元",
        "确认");
 
      if(returnVal){
         $("#form_charge").submit();
      }
    }

    function conf_continue(){
      var money = $('#continue_money').val();
      if(  isNaN(money)                   ){  window.alert("金额必须为1-10000的整数");  return;  }
      if(  money < 1 || money > 10000     ){  window.alert("金额必须为1-10000的整数");  return;  }

      var returnVal = window.confirm("请核对信息：\n即将为<?php echo $rs->Novip;?>号会员【<?php echo $rs->Name;?>】续卡一年"
        +"\n收费【"+money+"】元",
        "确认");
 
      if(returnVal){
         $("#form_continue").submit();
      }
    }

    function conf_addcount(){
      var count = $('#a_count').val();
      var money = $('#a_money').val();
      var day   = $('#a_day').val();
      var mode  = $('input[name="a_mode"]:checked').val()

      if(  count == ''                      ){  window.alert("请填写次数");  return;  }
      if(  day   == ''                      ){  window.alert("请填写日期");  return;  }
      if(  money == ''                      ){  window.alert("请填写金额");  return;  }
      if(  isNaN(money)                     ){  window.alert("金额必须为1-10000的整数");  return;  }
      if(  money < 0 || money > 10000       ){  window.alert("金额必须为1-10000的整数");  return;  }
      if(  isNaN(day)                       ){  window.alert("有效期必须为1-365的整数");  return;  }
      if(  day < 1 || day > 365             ){  window.alert("有效期必须为1-365的整数");  return;  }
      if(  mode == null                     ){  window.alert("请选择模式");  return;  }

      var returnVal = window.confirm("请核对信息：\n即将为学员【<?php echo $rs->Name;?>】添加课程次数："
        +count+"次\n收费【"+money+"】元"+"\n有效期模式为【"+mode+"】",
        "确认");
 
      if(returnVal){
         $("#form_addcount").submit();
      }



    }

    function conf_buy(){
      var issno = '<?php echo $rs->No;?>';
      if(issno == ''){
        var sno = $('#bsno').val();
        if(  sno == ''                      ){  window.alert("请填写学员卡号");  return;  }
        if(  isNaN(sno)                     ){  window.alert("学员卡号必须为0001-4999的整数");  return;  }
      }

      var money = $('#bmoney').val();
      var cardtype = $('select[name=bcardtype]').val(); 



      if(  cardtype == null || cardtype =='' ){  window.alert("请选择卡种");  return;  }
      if(  isNaN(money)                      ){  window.alert("金额必须为1-10000的整数");  return;  }
      if(  money < 1 || money > 10000        ){  window.alert("金额必须为1-10000的整数");  return;  }
      
      ct = $("#cardtype").find("option:selected").text();

      var returnVal = window.confirm("请核对信息：\n即将为学员【<?php echo $rs->Name;?>】购买课程卡：\n"
        +ct+"\n实际收费【"+money+"】元",
        "确认");
 
      if(returnVal){
         $("#form_buy").submit();
      }
    }


    </script>

  </body>
</html>
