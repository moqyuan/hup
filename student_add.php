<?php
$PageTitle = '添加学员';
$ClassName = 'student';
$PageName = 'student';
$MetaDesc = '';
if($_COOKIE["UserLevel"]<3){
  echo "<SCRIPT language=JavaScript>alert('不好意思哈，你权限还不够，问问Sim或MoQ怎么回事吧。');";
  echo "javascript:history.back()</SCRIPT>";
  exit;
}
ini_set('default_charset','utf-8');
include("config.php");
$sql="select * from hupms_card where Del=0";
$result=mysql_query($sql);
?>
<!DOCTYPE html>
<html lang="en">
  <?php include("include_head.php"); ?><!-- Head -->
  <body>    
    <?php include("include_nav.php"); ?><!-- Navbar -->
    <div class="container">
      <div class="modal-dialog" style="width:880px">     
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">添加新学员</h4>
          </div>
          <div class="modal-body">
            <form action="student_do.php?a=add" id="sadd" method="post" class="form-horizontal" role="form">
            <div class="well">

              <div class="form-group">
                <label class="col-sm-2 control-label">学员号</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="sno" name="sno" placeholder="两卡至少填一个">
                </div>
                <label class="col-sm-2 control-label">会员号</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="snovip" name="snovip" placeholder="两卡至少填一个">
                </div>
              </div><!--tag-->

              <div class="form-group">
                <label class="col-sm-2 control-label">卡种</label>
                <div class="col-sm-7">
                  <div class="btn-group" data-toggle="buttons">
                    <select class="form-control" id="cardtype" name="cardtype">
                      <option value="">只办会员卡</option>
                      <?php 
                      while($rs=mysql_fetch_object($result)){
                        $content = '<option value="'.$rs->Id.'">'.$rs->Name.'　　'.$rs->Price."/";
                        if($rs->PriceVIP=='' || $rs->PriceVIP==null){
                          $content.="无";
                        }else{
                          $content.=$rs->PriceVIP;
                        }
                        $content .='　　'.$rs->Day.'天</option>';
                        echo $content;
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="input-group">
                  <input type="text" class="form-control" id="money" name="money" placeholder="金额(必填)">
                  <span class="input-group-addon">
                    元*
                  </span>
                </div> 
                </div>
              </div><!--tag-->

              <div class="form-group">
                <label class="col-sm-2 control-label">*姓名</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="sname" name="sname" placeholder="姓名（必填）">
                </div>
              </div><!--tag-->

            </div><!--well-->

              <div class="form-group">
                
                <label class="col-sm-2 control-label">生日</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="birth" name="birth" placeholder="例：1990-09-10">
                </div>
              </div><!--tag-->

              <div class="form-group">
                <label class="col-sm-2 control-label">性别</label>
                <div class="col-sm-4">
                  <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-warning">
                      <input type="radio" name="sex" id="sex1" value="男"> 男
                    </label>
                    <label class="btn btn-warning">
                      <input type="radio" name="sex" id="sex2" value="女"> 女
                    </label>
                  </div>
                </div>
                <label class="col-sm-2 control-label">证件</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="sidentity" name="sidentity" placeholder="身份证/护照">
                </div>
              </div><!--tag-->

              <div class="form-group">
                <label class="col-sm-2 control-label">手机</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="stel" name="stel" placeholder="手机号">
                </div>
                <label class="col-sm-2 control-label">邮件</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="smail" name="smail" placeholder="电子邮件">
                </div>
              </div><!--tag-->

              <div class="form-group">
                <label class="col-sm-2 control-label">住址</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="saddress" name="saddress" placeholder="xx市xx区xx路xx号xx室">
                </div>
              </div><!--tag-->

              <div class="form-group">
                <label class="col-sm-2 control-label">学生</label>
                <div class="col-sm-2">
                  <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-warning">
                      <input type="radio" name="isstu" id="isstu1" value="是"> 是
                    </label>
                    <label class="btn btn-warning">
                      <input type="radio" name="isstu" id="isstu2" value="否"> 否
                    </label>
                  </div>
                </div>
                <label class="col-sm-2 control-label">学校</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="sschool" name="sschool" placeholder="大学/高中/初中/小学">
                </div>
              </div><!--tag-->

              <div class="form-group">
                <label class="col-sm-2 control-label">了解渠道</label>
                <div class="col-sm-8">
                  <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-warning">
                      <input type="checkbox" name="way[]" id="way1" value="朋友"> 朋友
                    </label>
                    <label class="btn btn-warning">
                      <input type="checkbox" name="way[]" id="way2" value="网络"> 网络
                    </label>
                    <label class="btn btn-warning">
                      <input type="checkbox" name="way[]" id="way3" value="传单"> 传单
                    </label>
                    <label class="btn btn-warning">
                      <input type="checkbox" name="way[]" id="way4" value="平面媒体"> 平面媒体
                    </label>
                    <label class="btn btn-warning">
                      <input type="checkbox" name="way[]" id="way5" value="喜爱老师"> 喜爱老师
                    </label>
                    <label class="btn btn-warning">
                      <input type="checkbox" name="way[]" id="way6" value="活动参与"> 活动参与
                    </label>
                  </div>
                </div>
                <div class="col-sm-2">
                  <input type="text" class="form-control" id="wayother" name="wayother" placeholder="其他">
                </div>
              </div><!--tag-->

              <div class="form-group">
                <label class="col-sm-2 control-label">曾学过的</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="learned" name="learned" placeholder="学过的艺术类课程">
                </div>
              </div><!--tag-->

              <div class="form-group">
                <label class="col-sm-2 control-label">感兴趣的</label>
                <div class="col-sm-10">
                  <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-warning">
                      <input type="checkbox" name="interest[]" id="interest1" value="POPPING"> POPPING
                    </label>
                    <label class="btn btn-warning">
                      <input type="checkbox" name="interest[]" id="interest2" value="LOCKING"> LOCKING
                    </label>
                    <label class="btn btn-warning">
                      <input type="checkbox" name="interest[]" id="interest3" value="HIPHOP"> HIPHOP
                    </label>
                    <label class="btn btn-warning">
                      <input type="checkbox" name="interest[]" id="interest4" value="HOUSE"> HOUSE
                    </label>
                    <label class="btn btn-warning">
                      <input type="checkbox" name="interest[]" id="interest5" value="JAZZ"> JAZZ
                    </label>
                    <label class="btn btn-warning">
                      <input type="checkbox" name="interest[]" id="interest6" value="BREAKING"> BREAKING
                    </label>
                    <label class="btn btn-warning">
                      <input type="checkbox" name="interest[]" id="interest7" value="PUNKING"> PUNKING
                    </label>
                    <label class="btn btn-warning">
                      <input type="checkbox" name="interest[]" id="interest8" value="PUNKING"> CHOREO
                    </label>
                  </div>
                </div>
              </div><!--tag-->



              <div class="form-group">
                <label class="col-sm-2 control-label">期许和建议</label>
                <div class="col-sm-10">
                  <textarea type="text" class="form-control" id="sintro" name="sintro" placeholder="对自己舞蹈学习的期许和对工作室的任何建议意见都可以写在这里哟"></textarea>
                </div>
              </div><!--tag-->

            </form>

          </div>
          <div class="modal-footer">
            <a href="student.php"><button type="button" class="btn btn-default">关闭</button></a>
            <button type="button" class="btn btn-warning" onClick="conf()">保存</button>
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
  <script type="text/javascript">

  function conf(){

    var sno = $('#sno').val();
    var sname = $('#sname').val();
    var money = $('#money').val();
    var snovip = $('#snovip').val();
    var ct = $('select[name=cardtype]').val();

    //------------------------------------------------------------------
    // 常规判断
    //------------------------------------------------------------------
    if(sname == ''){
      window.alert("学员姓名不能为空");
      return;
    } 
    if(money == ''){
      window.alert("金额不能为空");
      return;
    }
    if(isNaN(money)){
      window.alert("金额必须是数字");
      return;
    }
    if(money>10000 || money <0){
      window.alert("金额需在0-10000之间");
      return;
    }

    //------------------------------------------------------------------
    // 学员卡会员卡判断
    //------------------------------------------------------------------
    if(  sno == '' && snovip == ''  ){  window.alert("学员卡或会员卡至少一个不能为空");  return;  }

    if(  sno != ''  ){

      if(  isNaN(sno)              ){  window.alert("会员卡号必须为0001-4999的整数");  return;  }
      if(  sno < 0001 || sno>4999  ){  window.alert("会员卡号必须为0001-4999的整数");  return;  }
      if(  ct == '' || ct == null  ){  window.alert("请选择卡种");  return;  }

      var maxLength = 4;
      var originalLen = sno.length ;
      var no=sno;

      if(  originalLen < maxLength  ){
        for(var i = 0; i < maxLength-originalLen; i++)
         no = "0" + no;
      }

      if(snovip == ''){
        snovip = "无";
      }else{
        if(  isNaN(snovip)                 ){  window.alert("会员卡号必须为5001-9999的整数");  return;  }
        if(  snovip < 5001 || snovip>9999  ){  window.alert("会员卡号必须为5001-9999的整数");  return;  }
      }

    }//end if(  sno != ''  )

    if(  sno == ''  ){
      var no = '无';
      var ct = '无';
      if( snovip == ''                   ){  window.alert("学员卡或会员卡至少一个不能为空");  return;  } 
      if(  isNaN(snovip)                 ){  window.alert("会员卡号必须为5001-9999的整数");  return;  }
      if(  snovip < 5001 || snovip>9999  ){  window.alert("会员卡号必须为5001-9999的整数");  return;  }
    }//end if(  sno == ''  )
 
    
    
    

    var returnVal = window.confirm("请核对信息：\n姓名："
      +sname+"\n学员卡："
      +no+"\n卡种："
      +$("#cardtype").find("option:selected").text()+"\n会员卡："
      +snovip+"\n金额：【"
      +money+"】元",
      "确认");
 
    if(returnVal){
       $("#sadd").submit();
    }

  }

  </script>
</html>
