import subprocess
import time
import os
import signal
import shlex

julius_path = 'julius'
jconf_path = '~/julius-4.3.1/julius-kits/dictation-kit-v4.3.1-linux/kudo_ken.jconf'
args = julius_path + ' -C ' + jconf_path + ' -module '
p = subprocess.Popen(args, stdout=subprocess.PIPE, shell=True)
pid = p.stdout.read() # juliusのプロセスIDを取得
time.sleep(5.0)
p.kill() # 起動スクリプトのプロセスを終了
subprocess.call(["kill " + pid], shell=True) # juliusのプロセスを終了