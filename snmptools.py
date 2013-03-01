import subprocess,re

def lancia(command):
	p = subprocess.Popen(command, shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
	return p.stdout.readlines()
	#    print line,
	#retval = p.wait()
	#return retval


def lista_iface(community,ip,snmpver):
	ifaces={}
	command='snmpwalk -c '+community+' -v'+snmpver+' '+ip+' -On .1.3.6.1.2.1.2.2.1.2'
	lines=lancia(command)
	for line in lines:
		#print line
		m = re.match(r"^.*\.(\d).*=.*\"(.*)\"",line)
		if m:
			ifaces[m.group(2)]=m.group(1)
	return ifaces

def getifacecounter(community,ip,snmpver,ifindex,ver):
	if ver == 'in':
		oid='.1.3.6.1.2.1.2.2.1.10.'
	else: 
		if ver == 'out':
			oid='.1.3.6.1.2.1.2.2.1.11.'
	command='snmpget -c '+community+' -v'+snmpver+' '+ip+' -On '+oid+ifindex
	lines=lancia(command)
	for line in lines:
		#print line
		m = re.match(r"^.*\.\d.*=.*:(.*)",line)
		if m:
			return m.group(1)

ip='10.162.0.14'
snmpver='1'
community='public'
for name,idx in lista_iface(community,ip,snmpver).iteritems():
	print name+'_in'+getifacecounter(community,ip,snmpver,idx,'in')
	print name+'_out'+getifacecounter(community,ip,snmpver,idx,'out')
