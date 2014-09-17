<?php
ini_set('default_charset','utf-8');
include("config.php");
$PageTitle = '学员管理';
$ClassName = 'student';
$PageName = 'student';
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
            <table width="100%">
              <tr>
                <td>
                  <h4 class="modal-title">学员管理</h4>
                </td>
                <td align="right">
                  <a href="student_add.php" class="btn btn-warning">
                    <span class="glyphicon glyphicon-plus"></span>
                    <span>添加学员</span>
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
                  <th class="text-center hidden-xs">学员卡号</th>
                  <th class="text-center hidden-xs">会员卡号</th>
                  <!--
                  <th class="text-center hidden-xs">课时</th>
                  <th class="text-center hidden-xs">有效期</th>
                  -->
                  <th class="text-center hidden-xs">编辑</th>
                  <th class="text-center hidden-xs">冻结</th>
                  
                </tr>
              </thead>
              <tbody>
                <?php
                  $sql="select * from hupms_student where Del=0 order by No;";
                  $result=mysql_query($sql);
                  while($rs=mysql_fetch_object($result)){
                ?>    
                <tr>
                  <td><strong><?php echo $rs->Name;?></strong></td>
                  <td class="hidden-xs">
                    <?php echo $rs->No; ?>
                    
                  </td>
                  <td>
                    <?php
                    echo $rs->Novip;
                    ?>
                  </td>
                  <!--
                  <td class="hidden-xs">
                    <a class="btn btn-warning btn-sm" href="student_lcount.php?id=<?php echo $rs->Id;?>">
                      <strong><?php echo $rs->Lcount;?></strong>
                    </a>
                  </td>
                  <td class="hidden-xs">
                    <a class="btn btn-warning btn-sm" href="student_vdate.php?id=<?php echo $rs->Id;?>">
                      <?php 
                        if($rs->Vdate==''){
                      ?>
                      <span class="glyphicon glyphicon-plus"></span>
                      <?php
                        }else{
                      ?>
                      <strong><?php echo $rs->Vdate;?></strong>
                      <?php 
                        }
                      ?>
                    </a>
                  </td>
                -->
                  <td class="hidden-xs">
                    <a class="btn btn-warning btn-sm" href="student.php?id=<?php echo $rs->Id;?>">
                      <span class="glyphicon glyphicon-pencil"></span>
                    </a>
                  </td>
                  <td class="hidden-xs">
                    <a class="btn btn-danger btn-sm" href="#" onClick="confirmDelete(<?php echo $rs->Id;?>,'<?php echo $rs->Name;?>','<?php echo $rs->No;?>')">
                      <span class="glyphicon glyphicon-remove-sign"></span>
                    </a>
                  </td>
                  
                </tr>
                <?php 
                  } 
                  $sql="select * from hupms_student where Del=1 order by No;";
                  $result=mysql_query($sql);
                  while($rs=mysql_fetch_object($result)){
                ?>
                <tr>
                  <td style="color:#39b3d7;"><?php echo $rs->Name;?></td>
                  <td class="hidden-xs" style="color:#39b3d7;"><?php echo $rs->No;?></td>
                  <td class="hidden-xs" style="color:#39b3d7;"><?php echo $rs->Novip;?></td>
                  <!--
                  <td class="hidden-xs" style="color:#39b3d7;"><?php echo $rs->Lcount;?></td>
                  <td class="hidden-xs" style="color:#39b3d7;"><?php echo $rs->Vdate;?></td>
                -->
                  <td class="hidden-xs" style="color:#39b3d7;"><p class="btn btn-info btn-sm"><span class="glyphicon glyphicon-ban-circle"></span></p></td>
                  <td class="hidden-xs">
                    <a class="btn btn-info btn-sm" href="#" onClick="confirmRecover(<?php echo $rs->Id;?>,'<?php echo $rs->Name;?>','<?php echo $rs->No;?>')">
                      <span class="glyphicon glyphicon-repeat"></span>
                    </a>
                  </td>
                  <td class="visible-xs">
                    <a class="btn btn-info btn-sm" href="#" onClick="confirmRecover(<?php echo $rs->Id;?>,'<?php echo $rs->Name;?>','<?php echo $rs->No;?>')">
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
    function confirmDelete(id,name,no){
      var returnVal = window.confirm("确定冻结"+no+"号学员"+name+"吗？冻结后他将无法上课，并且无法修改他的一切信息。","冻结");
      
      if(returnVal){
        window.location.href = "student_do.php?a=delete&id="+id;
      }
    }
    function confirmRecover(id,name,no){
      var returnVal = window.confirm("确定恢复"+no+"号学员"+name+"吗？","恢复");
      
      if(returnVal){
        window.location.href = "student_do.php?a=recover&id="+id;
      }
    }
    </script>
  </body>
</html>
