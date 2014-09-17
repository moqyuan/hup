<?php
ini_set('default_charset','utf-8');
$PageTitle = '登陆系统';
$ClassName = 'login';
$PageName = 'login';
$MetaDesc = '';
?>
<!DOCTYPE html>
<html lang="en">
  <?php include("include_head.php"); ?>
  <body>
    <!-- Navbar -->
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="index.php"><strong>HupMS</strong></a>
        </div>
      </div>
    </div>

    <!-- Container -->
    <div class="container">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">登陆</h3>
        </div>
        <div class="panel-body text-center">
          <form action="login_do.php?a=login" method="post" role="form">
            <div class="form-group">
              <label for="Email">用户名</label>
              <input type="text" class="form-control" id="username" name="username" placeholder="用户名/学员卡号/会员卡号" value="">
            </div>
            <div class="form-group">
              <label for="Password">密码</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="请注意大小写" value="">
            </div>
            <button type="submit" class="btn btn-warning">确定</button>
          </form>
        </div>
        <div class="panel-footer">Copyright：HurryUpDanceStudio 2013</div>
      </div>
    </div>
    <!-- /.container -->    


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <?php include("include_js.php");?>
    
  </body>
</html>
