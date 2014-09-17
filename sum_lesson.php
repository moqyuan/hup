<?php
error_reporting(-1);
$PageTitle = '课程统计';
$ClassName = 'summary';
$PageName = 'summary';
$MetaDesc = '';
if($_COOKIE["UserLevel"]<3){
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
      <?php
      //no post information
      if( isset($_POST["sdate"]) && isset($_POST["edate"]) && isset($_POST["lid"]) ){

        $sDate = $_POST["sdate"];
        $eDate = $_POST["edate"];
        $lid   = $_POST["lid"];

        if($sDate=="" or $eDate==""){
          echo "<script language=Javascript>alert('日期不得为空');";
          echo "javascript:history.back()</script>";
          exit;

        }

        $sql="select * from hupms_lesson where Id = $lid";
        $result=mysql_query($sql);
        $r_l = mysql_fetch_object($result);//lesson info
        $sql="select * from hupms_teacher where Id = $r_l->Teacher";
        $result=mysql_query($sql);
        $r_t = mysql_fetch_object($result);//teacher info

        $sql="select * from hupms_record_t where Lid = $lid and DT>='$sDate' and DT<='$eDate'";
        $result1=mysql_query($sql);
        echo '<div class="panel panel-warning">
              <div class="panel-heading">'.$r_l->Name.'　|　'.$r_t->Name.'　|　'.$sDate.'　-　'.$eDate.'</div>
              <ul class="list-group">';
              echo '<li class="list-group-item">';//start of <li>
        while($tRecord=mysql_fetch_object($result1)){//遍历每个老师上了什么课
          


          $sql="select * from hupms_lesson where Id = $tRecord->Lid";
          $resultLesson=mysql_query($sql);
          $lessonInfo=mysql_fetch_object($resultLesson);
          echo $tRecord->DT;
          // echo $tRecord->DT."　|　";
          // echo $lessonInfo->Name."　";


          $sql="select * from hupms_record_s where DT = '$tRecord->DT' and RT = 1 and Lid = $tRecord->Lid";
          $resultStudent = mysql_query($sql);
          echo "　";
          $stuNum=mysql_num_rows($resultStudent);
          // if($lessonInfo->Teacher!=$tRecord->Tid){//代课
          //   $lessons++;
          // }else{//0-6 7-15 16+
          //   if($stuNum>=1 and $stuNum<=6){
          //     $lesson1++;
          //   }elseif($stuNum>=7 and $stuNum<=15){
          //     $lesson2++;
          //   }elseif($stuNum>=16){
          //     $lesson3++;
          //   }elseif($stuNum==0){
          //     $lesson0++;
          //   }
          // }
          echo '<div class="btn-group">';
          
          echo '<button class="btn btn-warning btn-xs dropdown-toggle" style="width:'.(40*$stuNum+20).'px;" type="button" data-toggle="dropdown">
                    '.$stuNum.($lessonInfo->Teacher!=$tRecord->Tid ? ' *' : '');
          
          
          echo '
                  </button>
                  <ul class="dropdown-menu">';
          while($sRecord=mysql_fetch_object($resultStudent)){//便利学生
            echo '<li><a>'.$sRecord->Sno;
            if($sRecord->Sno!='0000'){//特殊卡
              $sql="select * from hupms_student where No=$sRecord->Sno";
              $resultS=mysql_query($sql);
              $stuInfo=mysql_fetch_object($resultS);
              echo ' '.$stuInfo->Name;
            }else{
              echo ' 【特殊卡】';
            }

            echo '</a></li>';
            
          }

                          
          echo '</ul> </div>';
          echo '<br/>';

        }
        echo "</li>";//end of <li>
        echo "</ul>";
      ?>



      <?php
      }else{
      ?>
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">课程统计</h4>
          </div>
          <div class="modal-body">
            <form id="f" action="sum_lesson.php" method="post" role="form">
              <div class="form-group">
                <select name="lid" class="form-control">
                  <option value="">选择课程：</option>
                  <?php
                  $sql="select * from hupms_lesson where Del = 0";
                  $result=mysql_query($sql);
                  while($rs=mysql_fetch_object($result)){
                    $sql="select * from hupms_teacher where Id=".$rs->Teacher;
                    $result_t=mysql_query($sql);
                    $rt=mysql_fetch_object($result_t);
                    echo '<option value='.$rs->Id.'>'.$rs->Name.' - '.$rt->Name.'</option>';
                  }
                  $sql="select * from hupms_lesson where Del = 1";
                  $result=mysql_query($sql);
                  if(mysql_num_rows($result)>0){
                    echo '<option value="">-------------------------</option>';
                    while($rs=mysql_fetch_object($result)){
                      $sql="select * from hupms_teacher where Id=".$rs->Teacher;
                      $result_t=mysql_query($sql);
                      $rt=mysql_fetch_object($result_t);
                      echo '<option value='.$rs->Id.'>'.$rs->Name.' - '.$rt->Name.'</option>';
                    } 
                  }
                  ?>
                </select>
              </div>
              <div class="form-group">
                <input id="sdate" name="sdate" type="text" class="form-control" placeholder="起始日期： 格式2000-01-01" value="2014-01-01"></div>
              <div class="form-group">
                <input id="npwd1" name="edate" type="text" class="form-control" placeholder="截止日期： 格式2014-12-31"></div>
            </form>
          </div>
          <div class="modal-footer">
            <a href="index.php"><button type="button" class="btn btn-default">返回</button></a>
            <button id="submitBtn" type="button" class="btn btn-warning" onClick="f.submit()">查询</button>
          </div>
        </div>
      </div>
      <br/>



      <?php
      }
      ?>
      <div class="footer">
        <p class="text-center">. Copyright : Hurry Up Dance Studio 2013 .</p>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
