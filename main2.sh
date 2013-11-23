#!/bin/bash
echo "afissemammita" >> /root/ninuxstats/rrds/prova.txt
APP_DIR="/root/ninuxstats/"
cd $APP_DIR
cat /dev/null > rrd2.log
cat /dev/null > rrd2.err
#for i in $(route -n | grep -e "^192.168.*\|^10.*\|^172.16.*" | grep 255.255.255.255 | awk '{print $1}'); do 
#for i in $(echo "/mid" | nc 127.0.0.1 2004 | grep HNA | awk '{ print $1}' | sed 's/"//g' | sort | uniq ); do 
#for i in $(echo "/mid" | nc 127.0.0.1 2006 | sed '1,5d' | awk '{print $1}' | sort | sed 1d ); do 
for i in $(echo "/topology" | nc 127.0.0.1 2006 | sed 1,5d | awk '{print $1}' | sort | uniq | sed 1d ); do 
	echo $i
	#./rrd2.sh $i > rrd.log 2> rrd.err& 
	python snmptools.py $i >> rrd2.log 2>> rrd2.err &
done
