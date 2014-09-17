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
            <h4 class="modal-title">添加新课程</h4>
          </div>
          <div class="modal-body">
            <form action="lesson_do.php?a=add" class="form-horizontal" role="form" id="cadd" method="post">
              <div class="form-group"><!--tag-->
                <label class="col-xs-3 control-label">课程类别</label>
                <div class="col-xs-9">
                  <select class="form-control" id="ctype" name="ctype">
                    <option value="1">常规班</option>
                    <option value="2">暑假班</option>
                    <option value="3">寒假班</option>
                  </select>                  
                </div>
              </div><!--tag-->
              <div class="form-group">
                <label class="col-xs-3 control-label">教室</label>
                <div class="col-xs-9">
                  <select class="form-control" id="croom" name="croom" >
                    <option value="1">Classroom A（大教室）</option>
                    <option value="2">Classroom B（小教室）</option>
                  </select>                  
                </div>
              </div><!--tag-->
              <div class="form-group">
                <label class="col-xs-3 control-label">课程名称</label>
                <div class="col-xs-9">
                  <input type="text" class="form-control" id="cname" name="cname" placeholder="如：Lockin 鬼级（注意半角全角，注意课名统一）">
                </div>
              </div><!--tag-->            
              <div class="form-group">
                <?php 
                $sql="select * from hupms_teacher where Del=0 and Level=1";
                $result=mysql_query($sql);
                ?>
                <label class="col-xs-3 control-label">教师</label>
                <div class="col-xs-3">
                  <select class="form-control" id="cteacher" name="cteacher" >
                    <?php
                    while($rs=mysql_fetch_object($result)){
                    ?>
                    <option value="<?php echo $rs->Id;?>"><?php echo $rs->Name;?></option>
                    <?php
                    }
                    ?>
                  </select>                  
                </div>
                <label class="col-xs-3 control-label">日期</label>
                <div class="col-xs-3">
                  <select class="form-control" id="cday" name="cday" >
                    <option value="0">周日</option>
                    <option value="1">周一</option>
                    <option value="2">周二</option>
                    <option value="3">周三</option>
                    <option value="4">周四</option>
                    <option value="5">周五</option>
                    <option value="6">周六</option>
                  </select>
                </div>
              </div><!--tag-->
              <div class="form-group">
                <label class="col-xs-3 control-label">开始时间</label>
                <div class="col-xs-3">
                    <input type="text" class="form-control sm2" id="cshour" name="cshour" placeholder="时">
                    :
                    <input type="text" class="form-control sm2" id="csminute" name="csminute" placeholder="分">
                </div>
                <label class="col-xs-3 control-label">结束时间</label>
                <div class="col-xs-3">
                  <input type="text" class="form-control sm2" id="cehour" name="cehour" placeholder="时">
                  :
                  <input type="text" class="form-control sm2" id="ceminute" name="ceminute" placeholder="分">
                </div>
              </div>

            </form>



          </div>
          <div class="modal-footer">
            <a href="lesson.php"><button type="button" class="btn btn-default">关闭</button></a>
            <button type="button" class="btn btn-warning" onClick="cadd.submit()">保存</button>
          </div>
        </div>
      </div>

    </div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <?php include("include_js.php");?>
  </body>
</html>
