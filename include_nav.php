<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">        
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php"><strong>HupMS</strong></a>
    </div>
    <div class="collapse navbar-collapse">
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $_COOKIE["Name"] ?> <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="admin.php">管理管理员</a></li>
            <li><a href="card.php">管理卡片</a></li>
            <li><a href="zhoubian.php">管理周边</a></li>
            <li><a href="fangjia.php">放假有效期延迟</a></li>
            <li class="divider"></li>
            <li><a href="pw.php">修改密码</a></li>
            <li class="divider"></li>
            <li><a href="login_do.php?a=logout">退出登录</a></li>
          </ul>
        </li>
      </ul>
      <ul class="nav navbar-nav">
        <li <?php if($PageName=="index"){echo "class='active'";} ?>><a href="index.php">首页（日常）</a></li>
        <li <?php if($PageName=="student"){echo "class='active'";} ?>><a href="students.php">学生</a></li>
        <?php 
        if($_COOKIE["UserLevel"]==5){
        ?>
        <li <?php if($PageName=="teacher"){echo "class='active'";} ?>><a href="teacher.php">教师</a></li>
        <li <?php if($PageName=="lesson"){echo "class='active'";} ?>><a href="lesson.php">课程</a></li>
        <!--<li <?php if($PageName=="money"){echo "class='active'";} ?>><a href="money.php">财务</a></li>-->
        <li <?php if($PageName=="salary"){echo "class='active'";} ?>><a href="salary.php">工资</a></li>
        <li class="dropdown ">
          <a class="dropdown-toggle <?php if($PageName=="summary"){echo "class='active'";} ?>" data-toggle="dropdown" href="#">
            统计 <span class="caret"></span>
          </a>
          <ul class="dropdown-menu">
            <li><a href="sum_lesson.php">课程</a></li>
            <li><a href="sum_teacher.php">教师</a></li>
            <li><a href="sum_student.php">学员</a></li>
          </ul>
        </li>
        <!--<li <?php if($PageName=="record"){echo "class='active'";} ?>><a href="record.php">记录</a></li>-->
        <?php 
        }
        ?>
        <form action="student.php" method="post" class="navbar-form navbar-left" role="search">
          <div class="form-group">
            <input name="query" type="text" class="form-control" placeholder="手机/卡号/姓名">
          </div>
          <button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-search"></span></button>
        </form>

      </ul>
    </div>

    
    <!-- /将过期学员 -->
  </div>
</div>