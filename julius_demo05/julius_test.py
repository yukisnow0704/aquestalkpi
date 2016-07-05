#!/usr/bin/python
# -*- coding: utf-8 -*-
import os
import sys
import socket
import requests
import re
import subprocess
import shlex
import time
from subprocess import Popen

# API起動チェック用
def loop(args):
	global api
	global sleep
	if api.poll() is not None and sleep.poll() is not None:
		p = Popen(args, shell=True)
	else:
		p = api
		print 'call now!! or sleep now!!'
	return p

#スタートandストップ管理用
def stop(tmp):
	global sleep
	if tmp == 'stop':
		if sleep.poll() is not None:
			Popen("/home/pi/aquestalkpi/AquesTalkPi 'おやすみなさい' | aplay", shell=True)
			sleep = Popen('python stop.py', shell=True)
	elif tmp == 'start':
		if sleep.poll() is None:
			sleep.kill()
			Popen("/home/pi/aquestalkpi/AquesTalkPi 'おはようございます' | aplay", shell=True)
			time.sleep(2)
			if sleep.poll() is not None:
				print 'ok!stop'

#ジュリアス起動
julius_path = 'julius'
jconf_path = '~/julius-4.3.1/julius-kits/dictation-kit-v4.3.1-linux/kudo_ken.jconf'
julius = None
julius_socket = None

print 'INFO : invoke julius'
args = julius_path + ' -C ' + jconf_path + ' -module '
julius = subprocess.Popen( args,shell=True )
print 'INFO : invoke julius complete.'
print 'INFO : wait 2 seconds.'
time.sleep(3.0)
print 'INFO : invoke julius complete'

#ソケット接続
s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
s.connect(('localhost', 10500))

#ファーストキル
sf = s.makefile('')
sleep = Popen('python stop.py', shell=True)
sleep.kill()
api = Popen("/home/pi/aquestalkpi/AquesTalkPi 'おはようございます' | aplay", shell=True)
time.sleep(1.0)
api.kill()

while True:
	line = sf.readline().decode('utf-8')
	if line.find('WHYPO') != -1:
		lime = '<WHYPO WORD=“さようなら” CLASSID=“さようなら” PHONE=“silB s a y o u n a r a sliE” CM=“0.994"/>'
		line_score = line[-8:-3]

		line_score = float(line_score)
		print line_score
		if line_score > 0.8:
			print line
			if line.find(u"天気") != -1:
				print 'call tenki'
				f = open('tmp.txt','w')
				f.write("袋井")
				f.close()
				api = loop('php weather01.php')

			if line.find(u"ニュース") != -1:
				print 'call news'
				api = loop("php news.php")

			if line.find(u"停止") != -1 or line.find(u"おやすみ") != -1 or line.find(u"黙れ") != -1:
				print 'call stop'
				stop('stop')

			if line.find(u"おはよう") != -1:
				print 'call start'
				stop('start')

			if line.find(u"予定") != -1:
				if line.find(u"伊藤") != -1:
					print 'call yuki-itou plan'
					f = open('tmp.txt','w')
					f.write("yukisnow0704@gmail.com")
					f.close()
					api = loop("php calendar_test01.php")

				if line.find(u"怜真") != -1 or line.find(u"小山") != -1:
					print 'call koyama plan'
					f = open('tmp.txt','w')
					f.write("koyamaryoma@gmail.com")
					f.close()
					api = loop("php calendar_test01.php")

				if line.find(u"芹沢") != -1:
					print 'call koyama plan'
					f = open('tmp.txt','w')
					f.write("kodoukenn.seri@gmail.com")
					f.close()
					api = loop("php calendar_test01.php")

				if line.find(u"長澤") != -1:
					print 'call koyama plan'
					f = open('tmp.txt','w')
					f.write("nagasawa.ichi@gmail.com")
					f.close()
					api = loop("php calendar_test01.php")

				if line.find(u"長谷川") != -1:
					print 'call koyama plan'
					f = open('tmp.txt','w')
					f.write("ryomu810@gmail.com")
					f.close()
					api = loop("php calendar_test01.php")

				if line.find(u"みんな") != -1 or line.find(u"皆さん") != -1:
					print 'call day plan'
					api = loop("php calendar_test02.php")

