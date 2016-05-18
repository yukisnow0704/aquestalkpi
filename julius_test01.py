import subprocess
import time
import os
import signal
julius_path = 'julius'
jconf_path = '~/julius-4.3.1/julius-kits/dictation-kit-v4.3.1-linux/kudo_ken.jconf'
args = julius_path + ' -C ' + jconf_path + ' '
proc1 = subprocess.Popen(args, shell=True).pid
time.sleep(5.0)
proc1.kill()
subprocess.call(["kill " + proc1], shell=True)