<?php
<<<<<<< HEAD
$app_path="/root/ninuxstats/";
$ip=$_GET['ip'];
if (preg_match( '/^(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:[.](?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}$/', $ip)) {
	system($app_path."genera.sh ".$ip." 2d > /dev/null");
	print "<img src='graphs/".$ip."_2d.png'/>";
	system($app_path."/genera.sh ".$ip." 7d > /dev/null");
	print "<img src='graphs/".$ip."_7d.png'/>";
	system($app_path."/genera.sh ".$ip." 1m > /dev/null");
	print "<img src='graphs/".$ip."_1m.png'/>";
=======
$ip=$_GET['ip'];
if (preg_match( '/^(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:[.](?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}$/', $ip)) {
	system("/root/ninuxstats/genera.sh ".$ip." > /dev/null");
	print "<img src='graphs/".$ip.".png'/>";
>>>>>>> db9b3a20cd467716555704b34da3683bbb0f7dfe
}else{
print "fanculo!";
}




?>
