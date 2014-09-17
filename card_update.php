<?php
$PageTitle = '添加卡片';
$ClassName = 'card';
$PageName = 'card';
$MetaDesc = '';
if($_COOKIE["UserLevel"]!=5){
  echo "<SCRIPT language=JavaScript>alert('不好意思哈，你权限还不够，问问Sim或MoQ怎么回事吧。');";
  echo "javascript:history.back()</SCRIPT>";
  exit;
}
ini_set('default_charset','utf-8');
include("config.php");
if(!isset($_GET["id"])){
  echo "<SCRIPT language=JavaScript>alert('没设置id，请重新设置');";
  echo "javascript:history.back()</SCRIPT>";
  exit;
}else{
  $id=$_GET["id"];
  $sql="select * from hupms_card where Id=$id and Del=0";
  $result=mysql_query($sql);
  $num=mysql_num_rows($result);
  if($num<=0){
    echo "<SCRIPT language=JavaScript>alert('没有您所要找的卡片，请重新选择');";
    echo "javascript:history.back()</SCRIPT>";
    exit;
  }else{
    $rs=mysql_fetch_object($result);
  }
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
            <h4 class="modal-title">编辑卡片: <?php echo $rs->Name;?></h4>
          </div>
          <div class="modal-body">
            <form action="card_do.php?a=update&id=<?php echo $_GET['id'];?>" id="form" method="post" class="form-horizontal" role="form">
              
              <div id="divprice" class="form-group">
                <label class="col-sm-2 control-label">单价</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="price" name="price" placeholder="单价（必填）" value="<?php echo $rs->Price;?>" >
                </div>
                <label class="col-sm-2 control-label">会员价</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="pricevip" name="pricevip" placeholder="（可不填）" value="<?php echo $rs->PriceVIP;?>">
                </div>
              </div><!--tag-->

              <div id="divday" class="form-group">
                <label id="labelday" class="col-sm-2 control-label">有效期</label>
                <div id="labelday" class="col-sm-4">
                  <input type="text" class="form-control" id="day" name="day" placeholder="天数（必填）"  value="<?php echo $rs->Day;?>">
                </div>
                <label id="labelcount" class="col-sm-2 control-label"><?php if($rs->Type==0){echo "课时数";} ?></label>
                  <div class="col-sm-4">
                    <?php if($rs->Type==0){echo '<input type="text" class="form-control" id="count" name="count" placeholder="课时数（必填）" value="'.$rs->Count.'">';}?>
                  </div>
                <div class="col-sm-4">
                  
                </div>
              </div><!--tag-->

            </form>

          </div>
          <div class="modal-footer">
            <a href="card.php"><button type="button" class="btn btn-default">取消</button></a>
            <button type="button" class="btn btn-warning" onClick="conf()">保存</button>
          </div>
        </div>
      </div>
      <br/>
      <div class="footer">
        <p class="text-center">. Copyright : Hurry Up Dance Studio 2013 .</p>
      </div>
    </div>
    <script type="text/javascript">
    var type=<?php echo $rs->Type;?>

    
    function conf(){

      // if(document.getElementById("price").value==""){
      //   alert("必须填单价");
      //   return;
      // }
      // if(document.getElementById("price").value<0 || document.getElementById("price").value>10000 || isNaN(document.getElementById("price").value)){
      //   alert("单价必须是0-10000的整数");
      //   return;
      // }
      if(document.getElementById("pricevip").value<0 || document.getElementById("pricevip").value>10000 || isNaN(document.getElementById("pricevip").value)){
        alert("会员价必须是0-10000的整数");
        return;
      }
      if(document.getElementById("day").value==""){
        alert("必须填有效期");
        return;
      }
      if(document.getElementById("day").value<0 || document.getElementById("day").value>1000 || isNaN(document.getElementById("day").value)){
        alert("有效期必须是0-1000的整数");
        return;
      }
      if(type==0){
        if(document.getElementById("count").value==""){
          alert("必须填课时");
          return;
        }
        if(document.getElementById("count").value<0 || document.getElementById("count").value>1000 || isNaN(document.getElementById("count").value)){
          alert("课时必须是0-1000的整数");
          return;
        }
      }

      var content="请核对信息：\n课程类型：";
      if(type==1){
        content+="时段卡";
      }
      if(type==0){
        content+="课时卡";
      }
      content+="\n";
      content+="卡片名：<?php echo $rs->Name;?>\n";
      content+="单价："+document.getElementById("price").value+"\n";
      if(document.getElementById("pricevip").value==''){
        content+="会员价：无\n";
      }else{
        content+="会员价："+document.getElementById("pricevip").value+"\n";
      }
      content+="有效期："+document.getElementById("day").value+"\n";
      if(type==0){
        content+="课时："+document.getElementById("count").value+"\n";
      }

      var returnVal=window.confirm(content,"修改！");
      
      if(returnVal){
        form.submit();
      }

    }



    </script>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <?php include("include_js.php");?>
  </body>
</html>
