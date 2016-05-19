import subprocess
import time
import os
import signal
import shlex
from subprocess import Popen

args = 'php julius_demo02/weather01.php'
p = Popen(args, shell=True)
print p.poll()
while p.poll() == None:
	print 'wata'
	time.sleep(4.0)

print 'ok'