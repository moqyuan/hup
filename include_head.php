<?php 
if($PageName!='login'){
    if ( !isset($_COOKIE["Username"]) or $_COOKIE["Username"]==''){
        echo "<script>location.href='login.php';</script>";
    }
}
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.png">

    <title>Hurry Up Management System - <?php echo $PageTitle; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <!-- HurryUp CSS -->
    <link href="css/hup.css" rel="stylesheet"> 
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
    <script src="js/d3.min.js"></script>
    
</head>