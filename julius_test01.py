import subprocess
import time
import os
julius_path = 'julius'
jconf_path = '~/julius-4.3.1/julius-kits/dictation-kit-v4.3.1-linux/kudo_ken.jconf'
args = julius_path + ' -C ' + jconf_path + ' -module '
proc1 = subprocess.Popen(args, shell=True)
time.sleep(5.0)
os.kill(proc1.pid, signal.SIGTERM)