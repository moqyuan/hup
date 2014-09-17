<?php
ini_set('default_charset','utf-8');
include("config.php");
$PageTitle = '教师管理';
$ClassName = 'teacher';
$PageName = 'teacher';
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
            <table width="100%">
              <tr>
                <td>
                  <h4 class="modal-title">教师管理</h4>
                </td>
                <td align="right">
                  <a href="teacher_add.php" class="btn btn-warning">
                    <span class="glyphicon glyphicon-plus"></span>
                    <span>添加教师</span>
                  </a>
                </td>
              </tr>
            </table>
          </div>
          <div class="modal-body">

            <table class="table table-striped text-center">
              <thead>
                <tr>
                  <th class="text-center">姓名</th>
                  <th class="text-center hidden-xs">昵称</th>
                  <th class="text-center hidden-xs">级别</th>
                  <th class="text-center ">编辑</th>
                  <th class="text-center ">冻结</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $sql="select * from hupms_teacher where Level=1 and Del=0;";
                  $result=mysql_query($sql);
                  while($rs=mysql_fetch_object($result)){
                ?>    
                <tr>
                  <td ><?php echo $rs->Name;?></td>
                  <td class="hidden-xs"><?php echo $rs->Nickname;?></td>
                  <td class="hidden-xs">正式</td>
                  <td>
                    <a class="btn btn-default btn-sm" href="teacher_update.php?id=<?php echo $rs->Id;?>">
                      <span class="glyphicon glyphicon-pencil"></span>
                    </a>
                  </td>
                  <td>
                    <a class="btn btn-danger btn-sm" href="#" onClick="confirmDelete(<?php echo $rs->Id;?>,'<?php echo $rs->Name;?>')">
                      <span class="glyphicon glyphicon-remove-sign"></span>
                    </a>
                  </td>
                </tr>
                <?php 
                  } 
                  $sql="select * from hupms_teacher where Level=2 and Del=0;";
                  $result=mysql_query($sql);
                  while($rs=mysql_fetch_object($result)){
                ?>
                <tr>
                  <td style="color:#eea236;"><?php echo $rs->Name;?></td>
                  <td class="hidden-xs" style="color:#eea236;"><?php echo $rs->Nickname;?></td>
                  <td class="hidden-xs" style="color:#eea236;">代课</td>
                  <td>
                    <a class="btn btn-warning btn-sm" href="teacher_update.php?id=<?php echo $rs->Id;?>">
                      <span class="glyphicon glyphicon-pencil"></span>
                    </a>
                  </td>
                  <td>
                    <a class="btn btn-danger btn-sm" href="#" onClick="confirmDelete(<?php echo $rs->Id;?>,'<?php echo $rs->Name;?>')">
                      <span class="glyphicon glyphicon-remove-sign"></span>
                    </a>
                  </td>
                </tr>
                <?php 
                  } 
                  $sql="select * from hupms_teacher where Del=1;";
                  $result=mysql_query($sql);
                  while($rs=mysql_fetch_object($result)){
                ?>
                <tr>
                  <td style="color:#39b3d7;"><?php echo $rs->Name;?></td>
                  <td class="hidden-xs" style="color:#39b3d7;"><?php echo $rs->Nickname;?></td>
                  <td style="color:#39b3d7;" class="hidden-xs">冻结</td>
                  <td style="color:#39b3d7;"><p class="btn btn-info btn-sm"><span class="glyphicon glyphicon-ban-circle"></span></p></td>
                  <td >
                    <a class="btn btn-info btn-sm" href="#" onClick="confirmRecover(<?php echo $rs->Id;?>,'<?php echo $rs->Name;?>')">
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
          <div class="modal-footer">
            <p class="text-center">. Copyright : Hurry Up Dance Studio 2013 .</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <?php include("include_js.php");?>
    <script language="javascript">
    function confirmDelete(id,name){
      var returnVal = window.confirm("确定冻结"+name+"老师吗？冻结后他将无法上课，其课表上的课将永久消失，并且无法修改他的一切信息。","冻结");
      
      if(returnVal){
        window.location.href = "teacher_do.php?a=delete&id="+id;
      }
    }
    function confirmRecover(id,name){
      var returnVal = window.confirm("确定恢复"+name+"老师吗？即使恢复，其过去存在的课程也必须手动重新添加","恢复");
      
      if(returnVal){
        window.location.href = "teacher_do.php?a=recover&id="+id;
      }
    }
    </script>
  </body>
</html>
