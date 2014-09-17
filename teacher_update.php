<?php
$PageTitle = '添加教师';
$ClassName = 'teacher';
$PageName = 'teacher';
$MetaDesc = '';
if($_COOKIE["UserLevel"]!=5){
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
  $sql="select * from hupms_teacher where Id=$id and Del=0";
  $result=mysql_query($sql);
  $num=mysql_num_rows($result);
  if($num<=0){
    echo "<SCRIPT language=JavaScript>alert('没有您所要找的教师，请重新选择');";
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
            <h4 class="modal-title">修改教师</h4>
          </div>
          <div class="modal-body">
            <form id="tupdate" action="teacher_do.php?a=update&id=<?php echo $rs->Id; ?>" method="post" class="form-horizontal" role="form">
              
              <div class="form-group">
                <label class="col-sm-2 control-label">姓名</label>
                <div class="col-sm-4">
                  <?php echo $rs->Name; ?>
                </div>
                <label class="col-sm-2 control-label">昵称</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="tnickname" name="tnickname" placeholder="昵称（A.K.A）" value="<?php echo $rs->Nickname; ?>">
                </div>
              </div><!--tag-->

              <div class="form-group">
                <label class="col-sm-2 control-label">电话</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="ttel" name="ttel" placeholder="电话番号" value="<?php echo $rs->Tel; ?>">
                </div>
                <label class="col-sm-2 control-label">邮件</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="tmail" name="tmail" placeholder="电子邮件" value="<?php echo $rs->Mail; ?>">
                </div>
              </div><!--tag-->

              <div class="form-group">
                <label class="col-sm-2 control-label">级别</label>
                <div class="col-sm-4">
                  <select class="form-control" id="tlevel" name="tlevel">
                    <option id="zs" value="1">正式</option>
                    <option id="dk" value="2">代课</option>
                  </select>
                </div>
                <script language="javascript">
                  var lv = "<?php echo $rs->Level;?>";
                  switch(lv){
                    case "1": document.getElementById("zs").selected = "selected";break;  
                    default: document.getElementById("dk").selected = "selected";
                  }
                </script>

                <label class="col-sm-2 control-label">证件</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="tidentity" name="tidentity" placeholder="身份证/护照" value="<?php echo $rs->Identity; ?>">
                </div>  
              </div><!--tag-->

              <div class="form-group">
                <label class="col-sm-2 control-label">描述</label>
                <div class="col-sm-10">
                  <textarea type="text" class="form-control" id="tintro" name="tintro" placeholder="舞种简介之类"><?php echo $rs->Intro; ?></textarea>
                </div>
              </div><!--tag-->


            </form>

          </div>
          <div class="modal-footer">
            <a href="teacher.php"><button type="button" class="btn btn-default">关闭</button></a>
            <button type="button" class="btn btn-warning" onClick="tupdate.submit()">保存</button>
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
