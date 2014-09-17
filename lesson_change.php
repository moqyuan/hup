<?php
$PageTitle = '添加课程';
$ClassName = 'lesson';
$PageName = 'lesson';
$MetaDesc = '';
if($_COOKIE["UserLevel"]!=5){
  echo "<SCRIPT language=JavaScript>alert('不好意思哈，你权限还不够，问问Sim或MoQ怎么回事吧。');";
  echo "javascript:history.back()</SCRIPT>";
  exit;
}
ini_set('default_charset','utf-8');
include("config.php");
$sql="select * from hupms_flag where Name='class'";
$result=mysql_query($sql);
$rs=mysql_fetch_object($result);
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
            <h4 class="modal-title">课程时间变更</h4>
          </div>
          <div class="modal-body">
            <form action="lesson_do.php?a=change" class="form-horizontal" role="form" id="cchange" method="post">
              <div class="form-group"><!--tag-->
                <label class="col-xs-3 control-label">课程类别</label>
                <div class="col-xs-3">
                  <select class="form-control" id="ctype" name="ctype">
                    <option id="c1" value="1">常规班</option>
                    <option id="c2" value="2">暑假班</option>
                    <option id="c3" value="3">寒假班</option>
                  </select>                  
                </div>
                <script language="javascript">
                  var tp = "<?php echo $rs->Value;?>";
                  switch(tp){
                    case "1": document.getElementById("c1").selected = "selected";break; 
                    case "2": document.getElementById("c2").selected = "selected";break; 
                    default: document.getElementById("c3").selected = "selected";
                  }
                </script>
              </div><!--tag-->
            </form>
          </div>
          <div class="modal-footer">
            <a href="lesson.php"><button type="button" class="btn btn-default">关闭</button></a>
            <button type="button" class="btn btn-warning" onClick="cchange.submit()">保存</button>
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
