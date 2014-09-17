<?php
$PageTitle = '添加管理员';
$ClassName = 'admin';
$PageName = 'admin';
$MetaDesc = '';
if($_COOKIE["UserLevel"]!=5){
  echo "<SCRIPT language=JavaScript>alert('不好意思哈，你权限还不够，问问Sim或MoQ怎么回事吧。');";
  echo "javascript:history.back()</SCRIPT>";
  exit;
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
            <h4 class="modal-title">添加新管理员</h4>
          </div>
          <div class="modal-body">
            <form action="admin_do.php?a=add" id="aadd" method="post" class="form-horizontal" role="form">
              
              <div class="form-group">
                <label class="col-sm-2 control-label">用户名</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="ausername" name="ausername" placeholder="用户名（必填）">
                </div>
                <label class="col-sm-2 control-label">姓名</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="aname" name="aname" placeholder="姓名（必填）">
                </div>
              </div><!--tag-->

              <div class="form-group">
                <label class="col-sm-2 control-label">初始密码</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="apassword" name="apassword" placeholder="初始密码">
                </div>
                <label class="col-sm-2 control-label"></label>
                <div class="col-sm-4">
                  
                </div>
              </div><!--tag-->

            </form>

          </div>
          <div class="modal-footer">
            <a href="admin.php"><button type="button" class="btn btn-default">关闭</button></a>
            <button type="button" class="btn btn-warning" onClick="aadd.submit()">保存</button>
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
