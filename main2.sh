#!/bin/bash
echo "afissemammita" >> /root/ninuxstats/rrds/prova.txt
APP_DIR="/root/ninuxstats/"
cd $APP_DIR
cat /dev/null > rrd2.log
cat /dev/null > rrd2.err
for i in $(route -n | grep -e "^192.168.*\|^10.*" | grep 255.255.255.255 | awk '{print $1}'); do 
	echo $i
	#./rrd2.sh $i > rrd.log 2> rrd.err& 
	python snmptools.py $i >> rrd2.log 2>> rrd2.err &
done
