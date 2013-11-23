import subprocess,re,os,sys,time

def ping(hostname):
	response = os.system("ping -c 1 " + hostname)
	#and then check the response...
	if response == 0:
	  return True
	else:
	  return False

def lancia(command):
	p = subprocess.Popen(command, shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
	return p.stdout.readlines()
	#    print line,
	#retval = p.wait()
	#return retval


def ifacelist(community,ip,snmpver):
	ifaces={}
	command='snmpwalk -c '+community+' -v'+snmpver+' '+ip+' -On .1.3.6.1.2.1.2.2.1.2'
	lines=lancia(command)
	for line in lines:
		#print line
		m = re.match(r"^.*\.(\d+).*=.*\"(.*)\"",line)
		if m:
			#ifaces[m.group(2)]=m.group(1)
			#i = re.match(r"^([eth|ath|br|bgp|vlan].*)",m.group(2))
			i = re.match(r"^((eth|ath|br|bgp|vlan).*)",m.group(2))
			if i:
				#print i.group(1)
				ifaces[i.group(1)]=m.group(1)
	return ifaces

def getifacecounter(community,ip,snmpver,ifindex,ver):
	if ver == 'in':
		oid='.1.3.6.1.2.1.2.2.1.10.'
	else: 
		if ver == 'out':
			oid='.1.3.6.1.2.1.2.2.1.16.'
	command='snmpget -c '+community+' -v'+snmpver+' '+ip+' -On '+oid+ifindex
	#print command
	lines=lancia(command)
	for line in lines:
		#print line
		m = re.match(r"^.*\.\d+.*=.*:\ (\d+)",line)
		if m:
			return m.group(1)

#ip='10.162.0.14'
snmpver='1'
community='public'
datapath='rrdstest/'
ip=sys.argv[1]
if ping(ip):
	for ifacename,idx in ifacelist(community,ip,snmpver).iteritems():
		filename=ip+'_'+ifacename+'.rrd'
		incounter=getifacecounter(community,ip,snmpver,idx,'in')
		outcounter=getifacecounter(community,ip,snmpver,idx,'out')
		#print "CONTROLLO SE ESISTE"+datapath+filename	
		if not os.path.exists(datapath+filename):
			#print "CREO FILE\n"
			command='rrdtool create -b 946684800 '+datapath+filename+' DS:out:COUNTER:600:U:U DS:in:COUNTER:600:U:U RRA:LAST:0.5:1:8640 RRA:AVERAGE:0.5:6:600 RRA:AVERAGE:0.5:24:600 RRA:AVERAGE:0.5:288:600'
			lines=lancia(command)
		#print "AGGIORNO "+filename+'con '+str(outcounter)+':'+str(incounter)
		if (int(incounter) > 0) or (int(outcounter) > 0):
			command='rrdtool update '+datapath+filename+' '+str(int(time.time()))+':'+outcounter+':'+incounter
			print command
			lines=lancia(command)
			#print lines
