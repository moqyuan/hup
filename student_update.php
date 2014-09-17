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
if(!isset($_GET["id"])){
  echo "<SCRIPT language=JavaScript>alert('没设置id，请重新设置');";
  echo "javascript:history.back()</SCRIPT>";
  exit;
}else{
  $id=$_GET["id"];
  $sql="select * from hupms_student where Id=$id and Del=0";
  $result=mysql_query($sql);
  $num=mysql_num_rows($result);
  if($num<=0){
    echo "<SCRIPT language=JavaScript>alert('没有您所要找的学员，请重新选择');";
    echo "javascript:history.back()</SCRIPT>";
    exit;
  }else{
    $rs=mysql_fetch_object($result);
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <?php include("include_head.php"); ?><!-- Head -->
  <body>    
    <?php include("include_nav.php"); ?><!-- Navbar -->
    <div class="container">
      <div class="modal-dialog">     
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">修改学员</h4>
          </div>
          <div class="modal-body">
            <form action="student_do.php?a=update&id=<?php echo $rs->Id;?>" id="supdate" method="post" class="form-horizontal" role="form">
              
              <div class="form-group">
                <label class="col-sm-2 control-label">姓名</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="sname" name="sname" placeholder="姓名（必填）" value="<?php echo $rs->Name;?>">
                </div>
                <label class="col-sm-2 control-label">昵称</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="snickname" name="snickname" placeholder="昵称（A.K.A）" value="<?php echo $rs->Nickname;?>">
                </div>
              </div><!--tag-->

              <div class="form-group">
                <label class="col-sm-2 control-label">学员号</label>
                <div class="col-sm-4">
                  <?php echo $rs->No;?>
                </div>
                <label class="col-sm-2 control-label">会员号</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="snovip" name="snovip" placeholder="会员卡号" value="<?php echo $rs->Novip;?>">
                </div>
              </div><!--tag-->

              <div class="form-group">
                <label class="col-sm-2 control-label">电话</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="stel" name="stel" placeholder="电话番号" value="<?php echo $rs->Tel;?>">
                </div>
                <label class="col-sm-2 control-label">邮件</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="smail" name="smail" placeholder="电子邮件" value="<?php echo $rs->Mail;?>">
                </div>
              </div><!--tag-->

              <div class="form-group">
                <label class="col-sm-2 control-label">证件</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="sidentity" name="sidentity" placeholder="身份证/护照" value="<?php echo $rs->Identity;?>">
                </div>
                <label class="col-sm-2 control-label">学校</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="sschool" name="sschool" placeholder="大学/高中" value="<?php echo $rs->School;?>">
                </div>
              </div><!--tag-->

              <div class="form-group">
                <label class="col-sm-2 control-label">简介</label>
                <div class="col-sm-10">
                  <textarea type="text" class="form-control" id="sintro" name="sintro" placeholder="学员简介之类" ><?php echo $rs->Intro;?></textarea>
                </div>
              </div><!--tag-->

            </form>

          </div>
          <div class="modal-footer">
            <a href="student.php"><button type="button" class="btn btn-default">关闭</button></a>
            <button type="button" class="btn btn-warning" onClick="supdate.submit()">保存</button>
          </div>
        </div>
      </div>
      <br/>
      <div class="footer">
        <p class="text-center">. Copyright : Hurry Up Dance Studio 2013 .</p>
      </div>
    </div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <?php include("include_js.php");?>
  </body>
</html>
