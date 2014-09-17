<?php
$PageTitle = '修改密码';
$ClassName = 'pw';
$PageName = 'pw';
$MetaDesc = '';
if($_COOKIE["UserLevel"]<3){
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
            <h4 class="modal-title">修改密码</h4>
          </div>
          <div class="modal-body">
            <form id="pwd" action="login_do.php?a=pw" method="post" role="form">
              <div class="form-group">
                <input id="opwd" name="opwd" type="password" class="form-control" placeholder="请输入原密码，注意大小写"></div>
              <div class="form-group">
                <input id="npwd1" name="npwd1" type="password" class="form-control" placeholder="请输入新密码，注意大小写"></div>
              <div class="form-group">
                <input id="npwd2" name="npwd2" type="password" class="form-control" placeholder="请再次输入新密码，注意大小写"></div>
            </form>
          </div>
          <div class="modal-footer">
            <a href="index.php"><button type="button" class="btn btn-default">关闭</button></a>
            <button id="submitBtn" type="button" class="btn btn-warning" onClick="pwd.submit()">保存</button>
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
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
