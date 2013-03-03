#!/bin/bash
echo "afissemammita" >> /root/ninuxstats/rrds/prova.txt
APP_DIR="/root/ninuxstats/"
cd $APP_DIR
for i in $(route -n | grep 172. | grep 255.255.255.255 | awk '{print $1}'); do 
	echo $i
	./rrd2.sh $i > rrd.log 2> rrd.err& 
	#python snmptools.py $i > rrdpy.log 2> rrdpy.err 
done
