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
                  <h4 class="modal-title">管理周边</h4>
                </td>
                <td align="right">
                  <button class="btn btn-warning" data-toggle="modal" data-target="#new">
                    <span class="glyphicon glyphicon-plus"></span>
                    <span>添加新周边</span>
                  </button>
                </td>
              </tr>
            </table>
          </div>
          <div class="modal-body">

            <table class="table table-striped text-center">
              <thead>
                <tr>
                  <th class="text-center">名称</th>
                  <th class="text-center">单价</th>
                  <th class="text-center">编辑</th>
                  <th class="text-center">冻结</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $sql="select * from hupms_zhoubian where Del=0;";
                  $result=mysql_query($sql);
                  while($rs=mysql_fetch_object($result)){
                ?>
                <tr>
                  <td><?php echo $rs->Name;?></td>
                  <td><?php echo $rs->Price;?></td>
                  <td>
                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#edit<?php echo $rs->Id;?>"><span class="glyphicon glyphicon-pencil"></span></button>
                  </td>
                  <td>
                    <a class="btn btn-danger btn-sm" href="#" onClick="confirmDelete(<?php echo $rs->Id;?>,'<?php echo $rs->Name;?>')"><span class="glyphicon glyphicon-remove-sign"></span></a>
                  </td>
                </tr>
                <?php 
                  } 
                  $sql="select * from hupms_zhoubian where Del=1;";
                  $result=mysql_query($sql);
                  while($rs=mysql_fetch_object($result)){
                ?>
                <tr>
                  <td style="color:#39b3d7;"><?php echo $rs->Name;?></td>
                  <td style="color:#39b3d7;"><?php echo $rs->Price;?></td>
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

    <div class="modal fade" id="new" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="myModalLabel">添加新周边</h4>
            </div>
            <div class="modal-body">
              <form action="zhoubian_do.php?a=add" method="post">
                <div class="row">
                  <div class="col-lg-4">
                    <div class="input-group">
                      <span class="input-group-addon">
                        名称
                      </span>
                      <input type="text" class="form-control" name="zbname">
                    </div><!-- /input-group -->
                  </div><!-- /.col-lg-4 -->
                  <div class="col-lg-5">
                    <div class="input-group">
                      <span class="input-group-addon">
                        单价
                      </span>
                      <input type="text" class="form-control" name="price">
                      <span class="input-group-addon">
                        元
                      </span>
                    </div><!-- /input-group -->
                  </div><!-- /.col-lg-5 -->
                  <div class="col-lg-3">
                    <div class="input-group">
                      <input type="submit" class="btn btn-warning">
                    </div><!-- /input-group -->
                  </div><!-- /.col-lg-3 -->
                </div><!-- /.row -->
              </form>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div>

      <?php
        $sql="select * from hupms_zhoubian where Del=0;";
        $result=mysql_query($sql);
        while($rs=mysql_fetch_object($result)){
      ?>
      <div class="modal fade" id="edit<?php echo $rs->Id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="myModalLabel">添加新周边</h4>
            </div>
            <div class="modal-body">
              <form action="zhoubian_do.php?a=edit&id=<?php echo $rs->Id;?>" method="post">
                <div class="row">
                  <div class="col-lg-4">
                    <div class="input-group">
                      <span class="input-group-addon">
                        名称
                      </span>
                      <input type="text" class="form-control" name="zbname" value="<?php echo $rs->Name;?>">
                    </div><!-- /input-group -->
                  </div><!-- /.col-lg-4 -->
                  <div class="col-lg-5">
                    <div class="input-group">
                      <span class="input-group-addon">
                        单价
                      </span>
                      <input type="text" class="form-control" name="price" value="<?php echo $rs->Price;?>">
                      <span class="input-group-addon">
                        元
                      </span>
                    </div><!-- /input-group -->
                  </div><!-- /.col-lg-5 -->
                  <div class="col-lg-3">
                    <div class="input-group">
                      <input type="submit" class="btn btn-warning">
                    </div><!-- /input-group -->
                  </div><!-- /.col-lg-3 -->
                </div><!-- /.row -->
              </form>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div>
      <?php
      }
      ?>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script language="javascript">
    function confirmDelete(id,name){
      var returnVal = window.confirm("确定冻结周边"+name+"吗？冻结后它就不能进行买卖咯。","冻结");
      
      if(returnVal){
        window.location.href = "zhoubian_do.php?a=delete&id="+id;
      }
    }
    function confirmRecover(id,name){
      var returnVal = window.confirm("确定恢复周边"+name+"吗？","恢复");
      
      if(returnVal){
        window.location.href = "zhoubian_do.php?a=recover&id="+id;
      }
    }
    </script>
  </body>
</html>
