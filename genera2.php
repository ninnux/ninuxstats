<?php
$app_path="/root/ninuxstats/";
$rrd_path="/rrdstest/";
$ip_iface=$_GET['ip'];
$ip_iface_arr=explode("_",$ip_iface);
$ip1=$ip_iface_arr[0];
$iface=$ip_iface_arr[1];
//print $ip1." ".$iface;
exec("echo /mid | nc 127.0.0.1 2006 |grep -E \"".escapeshellcmd($ip1)."( |$)\"| awk -F\"\\t\" '{print $1}'",$result);
$main_ip=$result[0];
if($main_ip!=""){
	$ip=$main_ip."_".$iface;
}else{
	$ip=$ip_iface;
}
if (preg_match( '/^(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:[.](?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}\_.*$/', $ip)) {
	//print "file:".$app_path.$rrd_path.$ip."\n";
	if(file_exists($app_path.$rrd_path.$ip.".rrd")){
		if(isset($_GET['img'])){
			$img=$_GET['img'];
			if($img=="2d" or $img=="7d" or $img=="1m" or $img=="2y"){
				system($app_path."genera2.sh ".escapeshellcmd($ip)." ".$img." > /dev/null");
				$file='graphs/'.$ip.'_'.$img.'.png';
				header('Content-Type:image/png');
				header('Content-Length: '.filesize($file));
				readfile($file);
			}
		}else{
			system($app_path."genera2.sh ".escapeshellcmd($ip)." 2d > /dev/null");
			print "<img src='graphs/".$ip."_2d.png'/>";
			system($app_path."/genera2.sh ".escapeshellcmd($ip)." 7d > /dev/null");
			print "<img src='graphs/".$ip."_7d.png'/>";
			system($app_path."/genera2.sh ".escapeshellcmd($ip)." 1m > /dev/null");
			print "<img src='graphs/".$ip."_1m.png'/>";
			system($app_path."/genera2.sh ".escapeshellcmd($ip)." 2y > /dev/null");
			print "<img src='graphs/".$ip."_2y.png'/>";
		}
	}else{
		$command="find ".$app_path.$rrd_path." -name \"".escapeshellcmd($ip)."*\" -print";
		//print $command."\n";
		exec($command,$filelist);
		foreach($filelist as $k => $v){
			$file=basename($v,".rrd");
			print "<a href='genera2.php?ip=".$file."'>".$file."</a><br/>";
		}
	}
}else{
print "fanculo!";
}




?>
