import subprocess
import time
import os
import signal
import shlex
from subprocess import Popen

julius_path = 'julius'
jconf_path = '~/julius-4.3.1/julius-kits/dictation-kit-v4.3.1-linux/kudo_ken.jconf'
args = julius_path + ' -C ' + jconf_path + ' -module '
p = Popen(args, shell=True)
time.sleep(5.0)
p.terminate()