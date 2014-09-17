<?php
ini_set('default_charset','utf-8');
include("config.php");
$PageTitle = '管理员管理';
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
            <table width="100%">
              <tr>
                <td>
                  <h4 class="modal-title">管理员管理</h4>
                </td>
                <td align="right">
                  <a href="admin_add.php" class="btn btn-warning">
                    <span class="glyphicon glyphicon-plus"></span>
                    <span>添加管理员</span>
                  </a>
                </td>
              </tr>
            </table>
          </div>
          <div class="modal-body">

            <table class="table table-striped text-center">
              <thead>
                <tr>
                  <th class="text-center">用户名</th>
                  <th class="text-center">姓名</th>
                  <th class="text-center">编辑</th>
                  <th class="text-center">冻结</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  
                  $sql="select * from hupms_user where UserLevel!=0";
                  $result=mysql_query($sql);
                  while($rs=mysql_fetch_object($result)){
                ?>
                <tr>
                  <td><?php echo $rs->Username;?></td>
                  <td><?php echo $rs->Name;?></td>
                  <td>
                    <a class="btn btn-default btn-sm" href="admin_update.php?id=<?php echo $rs->Id;?>"><span class="glyphicon glyphicon-pencil"></span></a>
                  </td>
                  <td>
                    <a class="btn btn-danger btn-sm" href="#" onClick="confirmDelete(<?php echo $rs->Id;?>,'<?php echo $rs->Name;?>')"><span class="glyphicon glyphicon-remove-sign"></span></a>
                  </td>
                </tr>
                <?php 
                  } 
                  $sql="select * from hupms_user where UserLevel=0;";
                  $result=mysql_query($sql);
                  while($rs=mysql_fetch_object($result)){
                ?>
                <tr>
                  <td style="color:#39b3d7;"><?php echo $rs->Username;?></td>
                  <td style="color:#39b3d7;"><?php echo $rs->Name;?></td>
                  <td style="color:#39b3d7;"><p class="btn btn-info btn-sm"><span class="glyphicon glyphicon-ban-circle"></span></p></td>
                  <td>
                    <a class="btn btn-info btn-sm" href="#" onClick="confirmRecover(<?php echo $rs->Id;?>,'<?php echo $rs->Name;?>')"><span class="glyphicon glyphicon-repeat"></span></a>
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
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script language="javascript">
    function confirmDelete(id,name){
      var returnVal = window.confirm("确定冻结管理员"+name+"吗？冻结后他将无法管理系统，并且无法修改他的一切信息。","冻结");
      
      if(returnVal){
        window.location.href = "admin_do.php?a=delete&id="+id;
      }
    }
    function confirmRecover(id,name){
      var returnVal = window.confirm("确定恢复管理员"+name+"吗？","恢复");
      
      if(returnVal){
        window.location.href = "admin_do.php?a=recover&id="+id;
      }
    }
    </script>
  </body>
</html>
