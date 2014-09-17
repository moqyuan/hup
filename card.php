<?php
ini_set('default_charset','utf-8');
include("config.php");
$PageTitle = '管理卡片';
$ClassName = 'card';
$PageName = 'card';
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
      <div class="modal-dialog" style="width:900px">
        <div class="modal-content">
          <div class="modal-header">
            <table width="100%">
              <tr>
                <td>
                  <h4 class="modal-title">管理卡片</h4>
                </td>
                <td align="right">
                  <a href="card_add.php" class="btn btn-warning">
                    <span class="glyphicon glyphicon-plus"></span>
                    <span>添加卡片</span>
                  </a>
                </td>
              </tr>
            </table>
          </div>
          <div class="modal-body">

            <table class="table table-striped text-center">
              <thead>
                <tr>
                  <th class="text-center">名称</th>
                  <th class="text-center">类型</th>
                  <th class="text-center">有效期</th>
                  <th class="text-center">课时</th>
                  <th class="text-center">单价</th>
                  <th class="text-center">会员价</th>
                  <th class="text-center">编辑</th>
                  <th class="text-center">冻结</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  
                  $sql="select * from hupms_card where Del=0;";
                  $result=mysql_query($sql);
                  while($rs=mysql_fetch_object($result)){
                ?>
                <tr>
                  <td><?php echo $rs->Name;?></td>
                  <td><?php if($rs->Type==1){echo "时段卡";} if($rs->Type==0){echo "课时卡";} ?></td>
                  <td><?php echo $rs->Day;?>天</td>
                  <td><?php if($rs->Type==1){echo "∞";} if($rs->Type==0){echo $rs->Count;} ?></td>
                  <td><?php echo $rs->Price;?>元</td>
                  <td><?php if($rs->PriceVIP!='' || $rs->PriceVIP!=null){echo $rs->PriceVIP."元";}else{echo "无";}?></td>
                  <td>
                    <a class="btn btn-default btn-sm" href="card_update.php?id=<?php echo $rs->Id;?>"><span class="glyphicon glyphicon-pencil"></span></a>
                  </td>
                  <td>
                    <a class="btn btn-danger btn-sm" href="#" onClick="confirmDelete(<?php echo $rs->Id;?>,'<?php echo $rs->Name;?>')"><span class="glyphicon glyphicon-remove-sign"></span></a>
                  </td>
                </tr>
                <?php 
                  } 
                  $sql="select * from hupms_card where Del=1;";
                  $result=mysql_query($sql);
                  while($rs=mysql_fetch_object($result)){
                ?>
                <tr>
                  <td><?php echo $rs->Name;?></td>
                  <td><?php if($rs->Type==1){echo "时段卡";} if($rs->Type==0){echo "课时卡";} ?></td>
                  <td><?php echo $rs->Day;?>天</td>
                  <td><?php if($rs->Type==1){echo "∞";} if($rs->Type==0){echo $rs->Count;} ?></td>
                  <td><?php echo $rs->Price;?>元</td>
                  <td><?php if($rs->PriceVIP!='') echo $rs->PriceVIP;?>元</td>
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
      var returnVal = window.confirm("确定冻结卡片："+name+"吗？冻结后将无法购买该卡片。","冻结");
      
      if(returnVal){
        window.location.href = "card_do.php?a=delete&id="+id;
      }
    }
    function confirmRecover(id,name){
      var returnVal = window.confirm("确定恢复该卡片："+name+"吗？","恢复");
      
      if(returnVal){
        window.location.href = "card_do.php?a=recover&id="+id;
      }
    }
    </script>
  </body>
</html>
