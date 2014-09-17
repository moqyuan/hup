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
            <h4 class="modal-title">添加卡片</h4>
          </div>
          <div class="modal-body">
            <form action="card_do.php?a=add" id="form" method="post" class="form-horizontal" role="form">
              
              <div class="form-group">
                <label class="col-sm-2 control-label">类型</label>
                <div class="col-sm-4">
                  <select class="form-control" id="type" name="type">
                    <option id="type0"value="">（必选）</option>
                    <option id="type2" value="0">课时卡</option>
                    <option id="type1" value="1">时段卡</option>
                    
                  </select>
                </div>
                <label id="labelname" class="col-sm-2 control-label">卡片名</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="name" name="name" placeholder="卡片名（必填）">
                </div>
                
              </div><!--tag-->

              <div id="divprice" class="form-group">
                <label class="col-sm-2 control-label">单价</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="price" name="price" placeholder="单价（必填）">
                </div>
                <label class="col-sm-2 control-label">会员价</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="pricevip" name="pricevip" placeholder="（可不填）">
                </div>
              </div><!--tag-->

              <div id="divday" class="form-group">
                <label id="labelday" class="col-sm-2 control-label">有效期</label>
                <div id="labelday" class="col-sm-4">
                  <input type="text" class="form-control" id="day" name="day" placeholder="天数（必填）">
                </div>
                <label id="labelcount" class="col-sm-2 control-label">课时数</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="count" name="count" placeholder="课时数（必填）">
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
    document.getElementById("labelname").style.display="none",
    document.getElementById("name").style.display="none",
    document.getElementById("divprice").style.display="none",
    document.getElementById("labelday").style.display="none",
    document.getElementById("day").style.display="none",
    document.getElementById("labelcount").style.display="none",
    document.getElementById("count").style.display="none";
    document.getElementById("divday").style.display="none";

    document.getElementById("type").onchange=f;
    function f(){
      if(document.getElementById("type0").selected){
        document.getElementById("labelname").style.display="none",
        document.getElementById("name").style.display="none",
        document.getElementById("divprice").style.display="none",
        document.getElementById("labelday").style.display="none",
        document.getElementById("day").style.display="none",
        document.getElementById("labelcount").style.display="none",
        document.getElementById("count").style.display="none";
        document.getElementById("divday").style.display="none";
      }
      if(document.getElementById("type1").selected){
        document.getElementById("labelname").style.display="block",
        document.getElementById("name").style.display="block",
        document.getElementById("divprice").style.display="block",
        document.getElementById("labelday").style.display="block",
        document.getElementById("day").style.display="block",
        document.getElementById("divday").style.display="block";
        document.getElementById("labelcount").style.display="none",
        document.getElementById("count").style.display="none";
      }
      if(document.getElementById("type2").selected){
        document.getElementById("labelname").style.display="block",
        document.getElementById("name").style.display="block",
        document.getElementById("divprice").style.display="block",
        document.getElementById("labelday").style.display="block",
        document.getElementById("day").style.display="block",
        document.getElementById("labelcount").style.display="block",
        document.getElementById("divday").style.display="block";
        document.getElementById("count").style.display="block";
      }
    }

    function conf(){
      if(document.getElementById("type0").selected){
        alert("必须选择卡片类型");
        return;
      }
      if(document.getElementById("name").value==""){
        alert("必须填写卡片名称");
        return;
      }
      if(document.getElementById("price").value==""){
        alert("必须填单价");
        return;
      }
      if(document.getElementById("price").value<0 || document.getElementById("price").value>10000 || isNaN(document.getElementById("price").value)){
        alert("单价必须是0-10000的整数");
        return;
      }
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
      if(document.getElementById("type2").selected){
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
      if(document.getElementById("type1").selected){
        content+="时段卡";
      }
      if(document.getElementById("type2").selected){
        content+="课时卡";
      }
      content+="\n";
      content+="卡片名："+document.getElementById("name").value+"\n";
      content+="单价："+document.getElementById("price").value+"\n";
      if(document.getElementById("pricevip").value==''){
        content+="会员价：无\n";
      }else{
        content+="会员价："+document.getElementById("pricevip").value+"\n";
      }
      content+="有效期："+document.getElementById("day").value+"\n";
      if(document.getElementById("type2").selected){
        content+="课时："+document.getElementById("count").value+"\n";
      }

      var returnVal=window.confirm(content,"做卡！");
      
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
