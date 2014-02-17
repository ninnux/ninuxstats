<?php
$app_path="/root/ninuxstats/";
$rrd_path="/rrdstest/";
$ip_iface=$_GET['ip'];
$ip_iface_arr=explode("_",$ip_iface);
$ip1=$ip_iface_arr[0];
$iface=$ip_iface_arr[1];
//print $ip1." ".$iface;
exec("echo /mid | nc 127.0.0.1 2006 |grep ".escapeshellcmd($ip1)." | awk -F\"\\t\" '{print $1}'",$result);
$main_ip=$result[0];
if($main_ip!=""){
	$ip=$main_ip."_".$iface;
}else{
	$ip=$ip_iface;
}
if (preg_match( '/^(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:[.](?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}\_.*$/', $ip)) {
	//print "file:".$app_path.$rrd_path.$ip."\n";
	if(file_exists($app_path.$rrd_path.$ip.".rrd")){
		system($app_path."genera2.sh ".escapeshellcmd($ip)." 2d > /dev/null");
		print "<img src='graphs/".$ip."_2d.png'/>";
		system($app_path."/genera2.sh ".escapeshellcmd($ip)." 7d > /dev/null");
		print "<img src='graphs/".$ip."_7d.png'/>";
		system($app_path."/genera2.sh ".escapeshellcmd($ip)." 1m > /dev/null");
		print "<img src='graphs/".$ip."_1m.png'/>";
	}else{
		exec("ls ".escapeshellcmd($app_path.$rrd_path.$ip)."*",$filelist);
		foreach($filelist as $k => $v){
			$file=basename($v,".rrd");
			print "<a href='genera2.php?ip=".$file."'>".$file."</a><br/>";
		}
	}
}else{
print "fanculo!";
}




?>
