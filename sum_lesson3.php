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
$sql="select * from hupms_lesson where Del=0 ";

$result_l=mysql_query($sql);
$data='{"name":"Hurry Up Dance Studio", "children":[';//JSON start of lesson

while($r_l = mysql_fetch_object($result_l)){//lesson info
  $sql="select * from hupms_teacher where Id = $r_l->Teacher";
  $result=mysql_query($sql);
  $r_t = mysql_fetch_object($result);//teacher info
  $data.='{"name" : "'.$r_l->Name.'"';
  
  $sql="select * from hupms_record_t where Lid = $r_l->Id and DT>='$sDate' and DT<='$eDate'";
  $result1=mysql_query($sql);
  if(mysql_num_rows($result1)>0){
    $data.=',"children" : [';//start of records
    while($tRecord=mysql_fetch_object($result1)){//遍历每个老师上了什么课
      
      $sql="select * from hupms_record_s where DT = '$tRecord->DT' and RT = 1 and Lid = $tRecord->Lid";
      $resultStudent = mysql_query($sql);
      $stuNum=mysql_num_rows($resultStudent);
      $data.='{"name":"'.$tRecord->DT.'","children":[';//start of students
      if($stuNum>0){
        while($sRecord=mysql_fetch_object($resultStudent)){//便利学生
          //echo $sRecord->Sno;
          $data.='{ "name":"';//start of one students
          if($sRecord->Sno!='0000'){//特殊卡
            $sql="select * from hupms_student where No=$sRecord->Sno";
            $resultS=mysql_query($sql);
            $stuInfo=mysql_fetch_object($resultS);
            $data.=$stuInfo->Name;
          }else{
            $data.='【特殊卡】';
          }
          $data.=' '.$sRecord->Sno.'", "size":10';
          $data.='},';// end of one student
        }
      }else{
        $data.='{ "name":"没人","size":20},';
      }
      $data = substr($data,0,strlen($data)-1);
      $data .=']},';//end of students
    }
  $data = substr($data,0,strlen($data)-1);
  $data.=']},'; //end of records
  }else{
    $data .='},';//,"children":[{"name":"没课啊！","children":[{"name":"没人啊！","size":20}]}]"
  }
}
$data = substr($data,0,strlen($data)-1);
$data.=']}';//JSON end of Lesson and End of DATA
$file="flare.json";
$handle=fopen($file,"r+") or exit("Unable to open file!");
file_put_contents ($file, $data); 
fclose($handle);
unset($handle);
?>

<!DOCTYPE html>
<html lang="en">
  <?php include("include_head.php"); ?><!-- Head -->
  

  <body>    
    <?php echo include("include_nav.php"); ?><!-- Navbar -->
    <style>
    .node {
      cursor: pointer;
    }

    .node:hover {
      stroke: #000;
      stroke-width: 1.5px;
    }

    .node--leaf {
      fill: white;
    }

    .label {
      font: 10px "Helvetica Neue", Helvetica, Arial, sans-serif;
      font-weight: bold;
      text-anchor: middle;
      text-shadow: 0 1px 0 #fff, 1px 0 0 #fff, -1px 0 0 #fff, 0 -1px 0 #fff;
    }

    .label,
    .node--root,
    .node--leaf {
      pointer-events: none;
    }
    svg{
      top:-10px;
    }

    </style>
    <script>
    var w = window.innerWidth, h = window.innerHeight;

    var margin = 50,
        diameter = window.innerHeight-margin;

    var color = d3.scale.linear()
        .domain([-1, 5])
        .range(["hsl(32,100%,50%)", "hsl(32,30%,40%)"])
        .interpolate(d3.interpolateHcl);

    var pack = d3.layout.pack()
        .padding(2)
        .size([diameter - margin, diameter - margin])
        .value(function(d) { return d.size; })

    var svg = d3.select("body").append("svg")
        .attr("width", w - margin/2)
        .attr("height", h - margin/2)
      .append("g")
        .attr("transform", "translate(" + w / 2 + "," + h / 2 + ")");

    d3.json("flare.json", function(error, root) {
      if (error) return console.error(error);

      var focus = root,
          nodes = pack.nodes(root),
          view;

      var circle = svg.selectAll("circle")
          .data(nodes)
        .enter().append("circle")
          .attr("class", function(d) { return d.parent ? d.children ? "node" : "node node--leaf" : "node node--root"; })
          .style("fill", function(d) { return d.children ? color(d.depth) : null; })
          .on("click", function(d) { if (focus !== d) zoom(d), d3.event.stopPropagation(); });

      var text = svg.selectAll("text")
          .data(nodes)
        .enter().append("text")
          .attr("class", "label")
          .style("fill-opacity", function(d) { return d.parent === root ? 1 : 0; })
          .style("display", function(d) { return d.parent === root ? null : "none"; })
          .text(function(d) { return d.name; });

      var node = svg.selectAll("circle,text");

      d3.select("body")
          .style("background", color(-1))
          .on("click", function() { zoom(root); });

      zoomTo([root.x, root.y, root.r * 2 + margin]);

      function zoom(d) {
        var focus0 = focus; focus = d;

        var transition = d3.transition()
            .duration(d3.event.altKey ? 7500 : 750)
            .tween("zoom", function(d) {
              var i = d3.interpolateZoom(view, [focus.x, focus.y, focus.r * 2 + margin]);
              return function(t) { zoomTo(i(t)); };
            });

        transition.selectAll("text")
          .filter(function(d) { return d.parent === focus || this.style.display === "inline"; })
            .style("fill-opacity", function(d) { return d.parent === focus ? 1 : 0; })
            .each("start", function(d) { if (d.parent === focus) this.style.display = "inline"; })
            .each("end", function(d) { if (d.parent !== focus) this.style.display = "none"; });
      }

      function zoomTo(v) {
        var k = diameter / v[2]; view = v;
        node.attr("transform", function(d) { return "translate(" + (d.x - v[0]) * k + "," + (d.y - v[1]) * k + ")"; });
        circle.attr("r", function(d) { return d.r * k; });
      }
    });

    d3.select(self.frameElement).style("height", diameter + "px");
    </script>



    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
