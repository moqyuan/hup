<?php
$PageTitle = '放假延长有效期';
$ClassName = 'holiday';
$PageName = 'holiday';
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
            <h4 class="modal-title">放假延长有效期</h4>
          </div>
          <div class="modal-body">
            <div class="alert alert-warning">这个功能会把所有未过期的学员的有效期延长指定的天数。</div>
            <form id="holiday" action="fangjia_do.php?a=holiday" method="post" role="form">
              <div class="input-group">
                <input id="day" name="day" type="text" class="form-control">
                <span class="input-group-addon">天</span>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <a href="index.php"><button type="button" class="btn btn-default">关闭</button></a>
            <button id="submitBtn" type="button" class="btn btn-warning" onClick="conf()">延长</button>
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
  <script type="text/javascript">
  function conf(){
    var day = $('#day').val();

    if(day <=30 || day >= 0){
      
    }else{
      window.alert("日期必须在0-30天");
      return;
    } 
    var returnVal = window.confirm("确定要给所有有效期内的学生延长 "+ day +" 天的有效期吗？","确认");
 
    if(returnVal){
       $("#holiday").submit();
    }
  }
  </script>
</html>
