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
//make data
$sDate = "2013-01-01";
$eDate = "2015-05-30";
//$lid   = 14;
$sql="select * from hupms_lesson where Del=0 and Id<7";

$result_l=mysql_query($sql);
$data='{"name":"Hurry Up Dance Studio", "lessons":[';//JSON start of lesson

while($r_l = mysql_fetch_object($result_l)){//lesson info
  $sql="select * from hupms_teacher where Id = $r_l->Teacher";
  $result=mysql_query($sql);
  $r_t = mysql_fetch_object($result);//teacher info
  $data.='{"name" : "'.$r_l->Name.'",';
  $data.='"teacher" : "'.$r_t->Name.'",';
  $data.='"records" : [';//start of records
  $sql="select * from hupms_record_t where Lid = $r_l->Id and DT>='$sDate' and DT<='$eDate'";
  $result1=mysql_query($sql);
  while($tRecord=mysql_fetch_object($result1)){//遍历每个老师上了什么课
    
    $sql="select * from hupms_record_s where DT = '$tRecord->DT' and RT = 1 and Lid = $tRecord->Lid";
    $resultStudent = mysql_query($sql);
    $stuNum=mysql_num_rows($resultStudent);
    $data.='{"date":"'.$tRecord->DT.'","students":[';//start of students
    while($sRecord=mysql_fetch_object($resultStudent)){//便利学生
      //echo $sRecord->Sno;
      $data.='{ "No": "'.$sRecord->Sno.'","name":"';//start of one students
      if($sRecord->Sno!='0000'){//特殊卡
        $sql="select * from hupms_student where No=$sRecord->Sno";
        $resultS=mysql_query($sql);
        $stuInfo=mysql_fetch_object($resultS);
        $data.=$stuInfo->Name;
      }else{
        $data.='【特殊卡】';
      }
      $data.='"},';// end of one student
    }
    $data = substr($data,0,strlen($data)-1);
    $data .=']},';//end of students
  }
  $data = substr($data,0,strlen($data)-1);
  $data.=']},'; //end of records
}
$data = substr($data,0,strlen($data)-1);
$data.=']}';//JSON end of Lesson and End of DATA
?>

<!DOCTYPE html>
<html lang="en">
  <?php include("include_head.php"); ?><!-- Head -->
  

  <body>    
    <?php echo include("include_nav.php"); ?><!-- Navbar -->
    
    <script type="text/javascript">

   var JSONData = <?php echo $data;?>;

    d3.select("body").selectAll("div")
        .data(JSONData["lessons"])
        .enter()
        .append("div")
        .attr("class", "bar")
        .style("height", function(d) {
          var recordsNum = d.length;

          var barHeight = recordsNum * 5;
          return barHeight + "px";
        });

    
    //Create SVG element
    




    </script>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
