<?php

function dateadd($part,$n,$datetime){
$year=date("Y",$datetime);
$month=date("m",$datetime);
$day=date("d",$datetime);
$hour=date("H",$datetime);
$min=date("i",$datetime);
$sec=date("s",$datetime);
$part=strtolower($part);
$ret=0;
switch ($part) {
case "year":
$year+=$n;
break;
case "month":
$month+=$n;
break;
case "day":
$day+=$n;
break;
case "hour":
$hour+=$n;
break;
case "min":
$min+=$n;
break;
case "sec":
$sec+=$n;
break;
default:
return $ret;
break;
}
$ret=mktime($hour,$min,$sec,$month,$day,$year);
return $ret;
}
?>