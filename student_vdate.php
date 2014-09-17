<?php
$PageTitle = '修改学员有效日';
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
    echo "<SCRIPT language=JavaScript>alert('没有您所要找的用户，请重新选择');";
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
            <h4 class="modal-title">修改<?php echo $rs->No;?>号学员<?php echo $rs->Name;?>的有效期：</h4>
          </div>
          <div class="modal-body">
            <form action="student_do.php?a=vdate" id="vdate" method="post" class="form-horizontal" role="form">
              
              <div class="form-group">
                <label class="col-sm-2 control-label">有效期</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="svdate" name="svdate" placeholder="例：2013-07-28">
                  <input type="text" id="sid" name="sid" hidden="hidden" value="<?php echo $id; ?>">
                </div>
                <label class="col-sm-2 control-label">原因</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="sreason" name="sreason" placeholder="为什么要修改">
                </div>
              </div><!--tag-->
            </form>

          </div>
          <div class="modal-footer">
            <a href="student.php"><button type="button" class="btn btn-default">关闭</button></a>
            <button type="button" class="btn btn-warning" onClick="vdate.submit()">保存</button>
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
