<?php
$PageTitle = '查看工资';
$ClassName = 'salary';
$PageName = 'salary';
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
            <h4 class="modal-title">工资查询</h4>
          </div>
          <div class="modal-body">
            <form id="f" action="salarydetail.php" method="post" role="form">
              <div class="form-group">
                <input id="sdate" name="sdate" type="text" class="form-control" placeholder="起始日期：格式2000-01-01"></div>
              <div class="form-group">
                <input id="npwd1" name="edate" type="text" class="form-control" placeholder="截止日期：格式2000-01-01"></div>
            </form>
          </div>
          <div class="modal-footer">
            <a href="index.php"><button type="button" class="btn btn-default">返回</button></a>
            <button id="submitBtn" type="button" class="btn btn-warning" onClick="f.submit()">查询</button>
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
