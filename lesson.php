<?php
$PageTitle = '课程管理';
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
$sql="select * from hupms_flag where Name = 'class'";
$result=mysql_query($sql);
$type=mysql_fetch_object($result);
?>
<!DOCTYPE html>
<html lang="en">
  <?php include("include_head.php"); ?><!-- Head -->
  <body>    
    <?php include("include_nav.php"); ?><!-- Navbar -->
    <div class="container">
      <p class="text-right">
        <a href="lesson_add.php" class="btn btn-warning"><span class="glyphicon glyphicon-plus"></span><span> 添加课程</span></a>
      </p>
      
      <ul class="nav nav-tabs">
        <li class="active"><a href="#rooma" data-toggle="tab">Classroom A</a></li>
        <li><a href="#roomb" data-toggle="tab">Classroom B</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="rooma">
          <table class="table table-striped text-center">
            <thead>
              <tr>
                <th class="text-center">日期</th>
                <th class="text-center">时间</th>
                <th class="text-center">课程</th>
                <th class="text-center">教师</th>
                <th class="text-center">编辑</th>
                <th class="text-center">冻结</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sql="select * from hupms_lesson where Type=1 and Room=1 and Del=0 order by Day";
              $result=mysql_query($sql);
              while($rs=mysql_fetch_object($result)){
                $sql1="select * from hupms_teacher where Id = $rs->Teacher;";
                $result1=mysql_query($sql1);
                $tr=mysql_fetch_object($result1);
              ?>
              <tr>
                <td><?php 
                  switch ($rs->Day) {
                    case '1': echo "周一"; break;
                    case '2': echo "周二"; break;
                    case '3': echo "周三"; break;
                    case '4': echo "周四"; break;
                    case '5': echo "周五"; break;
                    case '6': echo "周六"; break;
                    default: echo "周日"; break;
                  }
                  ?>
                </td>
                <td><?php echo $rs->Time_s; ?>-<?php echo $rs->Time_e; ?></td>
                <td><?php echo $rs->Name; ?></td>
                <td><?php echo $tr->Nickname; ?></td>
                <td><a class="btn btn-default btn-sm" href="lesson_update.php?id=<?php echo $rs->Id;?>"><span class="glyphicon glyphicon-pencil"></span></a></td>
                <td>
                  <a class="btn btn-danger btn-sm" href="#" onClick = confirmDelete(<?php echo $rs->Id;?>)>
                    <span class="glyphicon glyphicon-remove-sign"></span>
                  </a>
                </td>
              </tr>
              <?php
              } 
              ?>

              <?php
              $sql="select * from hupms_lesson where Type=1 and Room=1 and Del=1 order by Day";
              $result=mysql_query($sql);
              while($rs=mysql_fetch_object($result)){
                $sql1="select * from hupms_teacher where Id = $rs->Teacher;";
                $result1=mysql_query($sql1);
                $tr=mysql_fetch_object($result1);
              ?>
              <tr style="color:#FF0000;">
                <td><?php 
                  switch ($rs->Day) {
                    case '1': echo "周一"; break;
                    case '2': echo "周二"; break;
                    case '3': echo "周三"; break;
                    case '4': echo "周四"; break;
                    case '5': echo "周五"; break;
                    case '6': echo "周六"; break;
                    default: echo "周日"; break;
                  }
                  ?>
                </td>
                <td><?php echo $rs->Time_s; ?>-<?php echo $rs->Time_e; ?></td>
                <td><?php echo $rs->Name; ?></td>
                <td><?php echo $tr->Nickname; ?></td>
                <td>
                  <p class="btn btn-info btn-sm">
                    <span class="glyphicon glyphicon-ban-circle"></span>
                  </p>
                </td>
                <td>
                  <a class="btn btn-info btn-sm" href="#" onClick="confirmRecover(<?php echo $rs->Id;?>)">
                    <span class="glyphicon glyphicon-repeat"></span>
                  </a>
                </td>
              </tr>
              <?php
              } 
              ?>
            </tbody>
          </table>
        </div>
        <div class="tab-pane" id="roomb">
          <table class="table table-striped text-center">
            <thead>
              <tr>
                <th class="text-center">日期</th>
                <th class="text-center">时间</th>
                <th class="text-center">课程</th>
                <th class="text-center">教师</th>
                <th class="text-center">编辑</th>
                <th class="text-center">冻结</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sql="select * from hupms_lesson where Type=1 and Room=2 and Del=0 order by Day";
              $result=mysql_query($sql);
              while($rs=mysql_fetch_object($result)){
                $sql1="select * from hupms_teacher where Id = $rs->Teacher;";
                $result1=mysql_query($sql1);
                $tr=mysql_fetch_object($result1);
              ?>
              <tr>
                <td><?php 
                  switch ($rs->Day) {
                    case '1': echo "周一"; break;
                    case '2': echo "周二"; break;
                    case '3': echo "周三"; break;
                    case '4': echo "周四"; break;
                    case '5': echo "周五"; break;
                    case '6': echo "周六"; break;
                    default: echo "周日"; break;
                  }
                  ?>
                </td>
                <td><?php echo $rs->Time_s; ?>-<?php echo $rs->Time_e; ?></td>
                <td><?php echo $rs->Name; ?></td>
                <td><?php echo $tr->Nickname; ?></td>
                <td><a class="btn btn-default btn-sm" href="lesson_update.php?id=<?php echo $rs->Id;?>"><span class="glyphicon glyphicon-pencil"></span></a></td>
                <td>
                  <a class="btn btn-danger btn-sm" href="#" onClick = confirmDelete(<?php echo $rs->Id;?>)>
                    <span class="glyphicon glyphicon-remove-sign"></span>
                  </a>
                </td>
              </tr>
              <?php
              } 
              ?>

              <?php
              $sql="select * from hupms_lesson where Type=1 and Room=2 and Del=1 order by Day";
              $result=mysql_query($sql);
              while($rs=mysql_fetch_object($result)){
                $sql1="select * from hupms_teacher where Id = $rs->Teacher;";
                $result1=mysql_query($sql1);
                $tr=mysql_fetch_object($result1);
              ?>
              <tr>
                <td><?php 
                  switch ($rs->Day) {
                    case '1': echo "周一"; break;
                    case '2': echo "周二"; break;
                    case '3': echo "周三"; break;
                    case '4': echo "周四"; break;
                    case '5': echo "周五"; break;
                    case '6': echo "周六"; break;
                    default: echo "周日"; break;
                  }
                  ?>
                </td>
                <td><?php echo $rs->Time_s; ?>-<?php echo $rs->Time_e; ?></td>
                <td><?php echo $rs->Name; ?></td>
                <td><?php echo $tr->Nickname; ?></td>
                <td><a class="btn btn-default btn-sm" href="lesson_update.php?id=<?php echo $rs->Id;?>"><span class="glyphicon glyphicon-pencil"></span></a></td>
                <td>
                  <a class="btn btn-danger btn-sm" href="#" onClick = confirmDelete(<?php echo $rs->Id;?>)>
                    <span class="glyphicon glyphicon-remove-sign"></span>
                  </a>
                </td>
              </tr>
              <?php
              } 
              ?>
            </tbody>
          </table>
        </div>
      </div>


    </div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <?php include("include_js.php");?>
    <script language="javascript">
    function confirmDelete(id){
      var returnVal = window.confirm("确定冻结这门课吗？删除后学员将无法上这节课","冻结");
      if(returnVal){
        window.location.href = "lesson_do.php?a=delete&id="+id;
      }
    }
    function confirmRecover(id,cname,tname){
      var returnVal = window.confirm("确定解冻这门课吗？","解冻");
      if(returnVal){
        window.location.href = "lesson_do.php?a=recover&id="+id;
      }
    }
    </script>
  </body>
</html>
