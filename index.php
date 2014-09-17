<?php
$PageTitle = '日常管理';
$ClassName = 'index';
$PageName = 'index';
$MetaDesc = '';
ini_set('default_charset','utf-8');
include("config.php");
$sql="select * from hupms_flag where Name = 'class'";
$result=mysql_query($sql);
$type=mysql_fetch_object($result);
$d=date('w');
$today=date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">
  <?php include("include_head.php"); ?><!-- Head -->
  <?php 
  if($_COOKIE["UserLevel"]<3){
  echo "<SCRIPT language=JavaScript>alert('不好意思哈，你权限还不够，问问Sim或MoQ怎么回事吧。');";
  echo "javascript:history.back()</SCRIPT>";
  exit;
  }
  ?>
  <body>    
    <?php include("include_nav.php"); ?><!-- Navbar -->
    <div class="container">
      <div class="row">
        <div class="col-md-9">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">学员登记</h3>
            </div>
            <div class="panel-body">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>课程</th>
                    <th class="hidden-xs">时间</th>
                    <th class="hidden-xs">教室</th>
                    <th>卡号</th>
                    <th></th>
                    <th>操作</th>
                    <th>人数</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $sql="select * from hupms_lesson where Type=$type->Value and Day=$d and Del=0 order by Time_s;";
                  $result=mysql_query($sql);
                  while($rs=mysql_fetch_object($result)){
                  ?>    
                  <form method="post" action="index_do.php?a=scheck">
                    <tr>
                      <td><?php echo $rs->Name; ?></td>
                      <td class="hidden-xs"><?php echo $rs->Time_s; ?>-<?php echo $rs->Time_e; ?></td>
                      <td class="hidden-xs"><?php if($rs->Room==1){echo "A";}elseif($rs->Room==2){echo "B";}?></td>
                      <td>
                        <input name="sno" class="form-control" type="text" placeholder="学员卡号">
                        <input name="lid" hidden="hidden" type="text" value="<?php echo $rs->Id; ?>">
                      </td>
                      <td>
                        <input type="submit" class="btn btn-default" value="登记">
                      </td>
                      <td>
                        
                        <a class="btn btn-warning" onclick="location.href='index_do.php?a=special&lid=<?php echo $rs->Id;?>'">特殊卡</a>
                        <button  class="btn btn-warning" data-toggle="modal" data-target="#detail<?php echo $rs->Id; ?>">详细</button>
                      </td>
                      <td>
                      <?php
                      $people=0;
                      $sql_d="select * from hupms_record_s where Lid=$rs->Id and DT='$today' and RT=1;";
                      $result_d=mysql_query($sql_d);
                      while($rs_d=mysql_fetch_object($result_d)){
                        $people++;
                      }
                      echo $people."人";
                      ?>
                      </td>
                    </tr>
                  </form>
                  <?php
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div><!--end of panel-->
          <?php
          $sql="select * from hupms_lesson where Type=$type->Value and Day=$d and Del=0;";
          $result=mysql_query($sql);
          while($rs=mysql_fetch_object($result)){
          ?>
          <div class="modal fade" id="detail<?php echo $rs->Id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title" id="myModalLabel">【<?php echo $rs->Name; ?>】登记详情</h4>
                </div>
                <div class="modal-body">
                  <ul class="list-group">
                    <?php
                    $sql_d="select * from hupms_record_s where Lid=$rs->Id and DT='$today';";
                    $result_d=mysql_query($sql_d);
                    $people=0;
                    while($rs_d=mysql_fetch_object($result_d)){//$rs_d record
                      if($rs_d->Sno=='0000'){
                    ?>
                      <li class="list-group-item">
                      特殊卡
                    <?php
                      if($rs_d->RT == 1){
                        $people++;
                        echo '<a href="index_do.php?a=cancel&id='.$rs_d->Id.'"><button class="btn btn-xs btn-danger" style="float:right">取消</button></a>';
                      }elseif($rs_d->RT == 0){
                        echo '<button class="btn btn-xs btn-default" disabled="disabled" style="float:right">已取消</button>';
                      }
                      echo '</li>';
                      
                      }else{
                      $sq="select * from hupms_student where No='$rs_d->Sno';";
                      $result_s=mysql_query($sq);
                      $rs_s=mysql_fetch_object($result_s);//$rs_s student

                    ?>
                    <li class="list-group-item">
                      <?php echo $rs_s->No;?>
                      <span>　　</span>
                      <?php echo $rs_s->Name;?>       
                      <?php
                      if($rs_d->RT == 1){
                        $people++;
                        echo '<a href="index_do.php?a=cancel&id='.$rs_d->Id.'"><button class="btn btn-xs btn-danger" style="float:right">取消</button></a>';
                      }elseif($rs_d->RT == 0){
                        echo '<button class="btn btn-xs btn-default" disabled="disabled" style="float:right">已取消</button>';
                      }
                      ?>

                    </li>
                    <?php
                      }
                    }
                    echo "<br>共".$people."人上课";
                    ?>
                  </ul>      
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div>
          <?php
          }
          ?>

          <!--=============ZBZBZBZBZB==============-->
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">周边贩卖</h3>
            </div>
            <div class="panel-body">
              <div class="row">
                <?php
                $sql="select * from hupms_zhoubian where Del=0;";
                $result=mysql_query($sql);
                while($rs=mysql_fetch_object($result)){
                ?>
                <div class="col-md-12" style="margin-bottom: 15px">
                  <form id="zbform<?php echo $rs->Id; ?>" action ="index_do.php?a=zbbuy" method="post" >
                    <div class="input-group">
                      <span class="input-group-addon" style="width: 300px;">
                        <?php echo "<strong>".$rs->Name."</strong>　".$rs->Price."元";?>
                      </span>
                      <span class="input-group-addon small">
                        数量
                      </span>
                      <input type="hidden" name="zbid" value="<?php echo $rs->Id;?>">
                      <input type="hidden" name="zbprice" value="<?php echo $rs->Price;?>">
                      <input type="text" class="form-control" name="zbnum" value="1">
                      <span class="input-group-addon">
                        会员卡号
                      </span>
                      <input type="text" class="form-control" name="zbsno" value="" placeholder="请刷卡">
                      <span class="input-group-btn">
                        <button class="btn btn-warning" type="submit">购买</button>
                      </span>
                    </div>
                  </form>
                </div>
                <?php
                }
                ?>
              </div>
            </div>
          </div>


          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">教师签到</h3>
            </div>
            <div class="panel-body">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>课程</th>
                    <th class="hidden-xs">时间</th>
                    <th class="hidden-xs">教室</th>
                    <th>教师</th>
                    <th>迟到</th>
                    <th>操作</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $sql="select * from hupms_lesson where Type=$type->Value and Day=$d and Del=0;";
                  $result1=mysql_query($sql);
                  while($rs=mysql_fetch_object($result1)){
                    $sqlx="select * from hupms_record_t where Lid=$rs->Id and DT='$today'";
                    $resultx=mysql_query($sqlx);
                    $flag=mysql_num_rows($resultx);
                    if($flag>0){
                      $lr=mysql_fetch_object($resultx);
                      $sqla="select * from hupms_teacher where Id=$lr->Tid";
                      $resulta=mysql_query($sqla);
                      $tc=mysql_fetch_object($resulta);
                    }
                  ?>  
                  <form method="post" action="index_do.php?a=tcheck">
                    <tr>
                      <td><?php echo $rs->Name; ?></td>
                      <td class="hidden-xs"><?php echo $rs->Time_s; ?>-<?php echo $rs->Time_e; ?></td>
                      <td class="hidden-xs"><?php if($rs->Room==1){echo "A";}elseif($rs->Room==2){echo "B";}?></td>
                      <td>
                        <?php
                        if($flag>0){
                          echo $tc->Name;
                        }else{
                        ?>
                        <select class="form-control" name="tid">
                        <?php
                        $sql="select * from hupms_teacher where Del=0";
                        $result=mysql_query($sql);
                        while($tr=mysql_fetch_object($result)){
                        ?>
                          <option id="t<?php echo $rs->Id;?><?php echo $tr->Id;?>" value="<?php echo $tr->Id;?>"><?php echo $tr->Name;?></option>
                        <?php
                        }
                        ?>
                        </select>
                        <script language="javascript">
                          document.getElementById("t<?php echo $rs->Id;?><?php echo $rs->Teacher;?>").selected = "selected";
                        </script>
                        <?php 
                        }
                        ?>
                      </td>
                      <td>
                        <?php
                        if($flag>0){
                          echo $lr->Late."分";
                        }else{
                        ?>
                        <input name="lid" hidden="hidden" type="text" value="<?php echo $rs->Id; ?>">
                        <input class="form-control sm2" name="late" type="text" placeholder="分" value="0">
                        <?php 
                        }
                        ?>
                        </td>
                      <td>
                        <?php
                        if($flag>0){
                        ?>
                        完成
                        <?php
                        }else{
                        ?>
                        <input type="submit" class="btn btn-warning" value="签到">
                        <?php
                        }
                        ?>
                        </td>
                    </tr>
                  </form>
                  <?php
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div><!--end of panel-->
        </div>
        <div class="col-md-3">
          <button class="btn btn-warning btn-block" data-toggle="modal" data-target="#goingtos">
            <span class="glyphicon glyphicon-exclamation-sign">　</span>将到期学员
          </button>
          <button class="btn btn-warning btn-block" data-toggle="modal" data-target="#goingtov">
            <span class="glyphicon glyphicon-exclamation-sign">　</span>将到期会员
          </button>
          <button class="btn btn-danger btn-block" data-toggle="modal" data-target="#deads">
            <span class="glyphicon glyphicon-remove-sign">　</span>已过期学员
          </button>
          <button class="btn btn-danger btn-block" data-toggle="modal" data-target="#deadv">
            <span class="glyphicon glyphicon-remove-sign">　</span>已过期会员
          </button>

        </div>
      </div>
      
      

      <br/>
      <p class="text-center">. Copyright : Hurry Up Dance Studio 2013 .</p>

      
    </div>
    <!-- 将过期学员 -->
    <div class="modal fade" id="goingtos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">将到期学员</h4>
          </div>
          <div class="modal-body">
            <ul class="list-group">
            <?php
            $sql="SELECT * FROM hupms_student WHERE (DATEDIFF( Vdate, NOW( ) ) <=7 AND DATEDIFF( Vdate, NOW( ) ) >=0) and No !=''";
            $result=mysql_query($sql);
            $num = mysql_num_rows($result);
            if($num == 0){
              echo '<li class="list-group-item">没有哦</li>';
            }else{
              while($rs_will = mysql_fetch_object($result)){
              echo '<li class="list-group-item"><a class="text-warning" href="student.php?id='.$rs_will->Id.'">'.$rs_will->Name.'<span class="label label-warning right">'.$rs_will->Vdate.'</span>'.'</a></li>';
              }
            }
            ?>
            </ul>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- /将过期学员 -->

    <!-- 已过期学员 -->
    <div class="modal fade" id="deads" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">已过期学员</h4>
          </div>
          <div class="modal-body">
            <ul class="list-group">
            <?php
            $sql="SELECT * FROM hupms_student WHERE ( DATEDIFF( Vdate, NOW( ) ) < 0 ) and No !=''";
            $result=mysql_query($sql);
            $num = mysql_num_rows($result);
            if($num == 0){
              echo '<li class="list-group-item">没有哦</li>';
            }else{
              while($rs_already = mysql_fetch_object($result)){
              echo '<li class="list-group-item"><a class="text-danger" href="student.php?id='.$rs_already->Id.'">'.$rs_already->Name.'<span class="label label-danger right">'.$rs_already->Vdate.'</span>'.'</a></li>';
              }
            }
            ?>
            </ul>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- /已过期学员 -->

    <!-- 将过期会员 -->
    <div class="modal fade" id="goingtov" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">将到期会员</h4>
          </div>
          <div class="modal-body">

            <ul class="list-group">
            <?php
            $sql="SELECT * FROM hupms_student WHERE (DATEDIFF( Vipdate, NOW( ) ) <=7 AND DATEDIFF( Vipdate, NOW( ) ) >=0) and Novip !='' ";
            $result=mysql_query($sql);
            $num = mysql_num_rows($result);
            if($num == 0){
              echo '<li class="list-group-item">没有哦</li>';
            }else{
              while($rsv_will = mysql_fetch_object($result)){
              echo '<li class="list-group-item"><a class="text-warning" href="student.php?id='.$rsv_will->Id.'">'.$rsv_will->Name.'<span class="label label-warning right">'.$rsv_will->Vipdate.'</span>'.'</a></li>';
              }
            }
            ?>
            </ul>

          
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- /将过期会员 -->

    <!-- 已过期会员 -->
    <div class="modal fade" id="deadv" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">已过期会员</h4>
          </div>
          <div class="modal-body">
            <ul class="list-group">
            <?php
            $sql="SELECT * FROM hupms_student WHERE DATEDIFF( Vipdate, NOW( ) ) < 0 and Novip !=''";
            $result=mysql_query($sql);
            $num = mysql_num_rows($result);
            if($num == 0){
              echo '<li class="list-group-item">没有哦</li>';
            }else{
              while($rsv_already = mysql_fetch_object($result)){
              echo '<li class="list-group-item"><a class="text-danger" href="student.php?id='.$rsv_already->Id.'">'.$rsv_already->Name.'<span class="label label-danger right">'.$rsv_already->Vipdate.'</span>'.'</a></li>';
              }
            }
            ?>
            </ul>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- /已过期会员 -->

    <!-- 已过期会员 -->
    <div class="modal fade" id="checksalary" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">工资查询</h4>
          </div>
          <div class="modal-body">
            
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- /已过期会员 -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
