<?php
$app_path="/root/ninuxstats/";
$ip_iface=$_GET['ip'];
$ip_iface_arr=explode("_",$ip_iface);
$ip1=$ip_iface_arr[0];
$iface=$ip_iface_arr[1];
//print $ip1." ".$iface;
exec("echo /mid | nc 127.0.0.1 2006 |grep ".$ip1." | awk -F\"\\t\" '{print $1}'",$result);
$main_ip=$result[0];
if($main_ip!=""){
	$ip=$main_ip."_".$iface;
}else{
	$ip=$ip_iface;
}
if (preg_match( '/^(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:[.](?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}\_.*$/', $ip)) {
	system($app_path."genera2.sh ".$ip." 2d > /dev/null");
	print "<img src='graphs/".$ip."_2d.png'/>";
	system($app_path."/genera2.sh ".$ip." 7d > /dev/null");
	print "<img src='graphs/".$ip."_7d.png'/>";
	system($app_path."/genera2.sh ".$ip." 1m > /dev/null");
	print "<img src='graphs/".$ip."_1m.png'/>";
}else{
print "fanculo!";
}




?>
