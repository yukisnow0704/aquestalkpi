import subprocess
import time
from subprocess import Popen

api = Popen('python stop.py', shell=True);
time.sleep(100)
api.kill()
time.sleep(2)

if api.poll() is not None:
	print 'ok!stop'

print 'exit ok!!!'