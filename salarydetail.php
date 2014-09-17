<?php
ini_set('default_charset','utf-8');
include("config.php");
$PageTitle = '调整工资';
$ClassName = 'salary';
$PageName = 'salary';
$MetaDesc = '';
if($_COOKIE["UserLevel"]<3){
  echo "<SCRIPT language=JavaScript>alert('不好意思哈，你权限还不够，问问Sim或MoQ怎么回事吧。');";
  echo "javascript:history.back()</SCRIPT>";
  exit;
}
if(!isset($_POST["sdate"]) || !isset($_POST["edate"])){
  echo "<SCRIPT language=JavaScript>alert('你不能这么访问该页面');";
  echo "javascript:history.back()</SCRIPT>";
  exit;
}
if($_POST["sdate"]=="" || $_POST["edate"]==""){
  echo "<SCRIPT language=JavaScript>alert('请输入日期');";
  echo "javascript:history.back()</SCRIPT>";
  exit;
}
$sDate = $_POST["sdate"];
$eDate = $_POST["edate"];


$sql = "select * from hupms_teacher where Del=0";
$result = mysql_query($sql);
/*

$sql = "select * from hupms_lesson where Del=0";
$result = mysql_query($sql);
$i = 0;
while($rs=mysql_fetch_object($result)){
  $L[$i]=$rs;
  $i++;
}
*/


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
            <h4 class="modal-title"><?php echo $sDate." 至 ".$eDate." 工资统计 ";?></h4>
          </div>
          <div class="modal-body">
          <?php
          
            while($teacherData=mysql_fetch_object($result)){//遍历所有老师
              $lesson0=0;
              $lesson1=0;
              $lesson2=0;
              $lesson3=0;
              $lessons=0;//代课

              $tid=$teacherData->Id;
              $tName=$teacherData->Name;
              $salary = explode(',', $teacherData->Salary);
              $flag=explode(',', $teacherData->Flag);

              echo '<div class="panel panel-warning">
                  <!-- Default panel contents -->
                  <div class="panel-heading">'.$teacherData->Name.'　|　'.$teacherData->Nickname.'</div>

                  <ul class="list-group">';
              
              $sql="select * from hupms_record_t where Tid = $tid and DT>='$sDate' and DT<='$eDate'";
              //echo $sql;
              $result1=mysql_query($sql);
              while($tRecord=mysql_fetch_object($result1)){//遍历每个老师上了什么课
                echo '<li class="list-group-item">';//start of <li>


                $sql="select * from hupms_lesson where Id = $tRecord->Lid";
                $resultLesson=mysql_query($sql);
                $lessonInfo=mysql_fetch_object($resultLesson);
                echo $tRecord->DT."　|　";
                echo $lessonInfo->Name."　";
                if($tRecord->Late>0){ 
                  echo '<span class="label label-danger">迟到'.$tRecord->Late.'分';
                }
                $sql="select * from hupms_record_s where DT = '$tRecord->DT' and RT = 1 and Lid = $tRecord->Lid";
                $resultStudent = mysql_query($sql);
                echo "　";
                $stuNum=mysql_num_rows($resultStudent);
                if($lessonInfo->Teacher!=$tRecord->Tid){//代课
                  $lessons++;
                }else{//0-6 7-15 16+
                  if($stuNum>=1 and $stuNum<=$flag[0]){
                    $lesson1++;
                  }elseif($stuNum>$flag[0] and $stuNum<=$flag[1]){
                    $lesson2++;
                  }elseif($stuNum>$flag[1]){
                    $lesson3++;
                  }elseif($stuNum==0){
                    $lesson0++;
                  }
                }
                echo '<div class="btn-group right">';
                
                echo '<button class="btn btn-warning btn-xs dropdown-toggle" style="width:'.(15*$stuNum+20).'px;" type="button" data-toggle="dropdown">
                          '.$stuNum.($lessonInfo->Teacher!=$tRecord->Tid ? ' *' : '');
                if($stuNum>0) echo ' <span class="caret">';
                
                echo '</span>
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
                

                               

                echo "</li>";//end of <li>

              }

              $total=$lesson1*$salary[0]+$lesson2*$salary[1]+$lesson3*$salary[2];
              $total0=$lesson0*$salary[0];
              $total1=$total-$total0;

              echo '<div class="panel-body">
                    <p>0人：'.$lesson0.'节　|　1-'.$flag[0].'人：'.$lesson1.'节　|　'.($flag[0]+1).'-'.$flag[1].'人：'.$lesson2.'节　|　'.$flag[1].'人以上：'.$lesson3.'节　|　代课：'.$lessons.'节'.'</p>
                    <p class="text-right"><span class="label label-success">总工资'.$total.'元</span> 
                       <span class="label label-warning">0人工资'.$total0.'元</span> 
                       <span class="label label-primary">扣除后'.$total1.'元</span> (不含代课费)</p>
                  </div></ul></div>';//end of panel
              



            }
            

          ?>       
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
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
