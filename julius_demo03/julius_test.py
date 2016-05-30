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

def loop(args):
	global api
	global sleep
	if api.poll() is not None and sleep.poll() is not None:
		p = Popen(args, shell=True)
	else:
		p = api
		print 'call now!! or sleep now!!'
	return p

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

s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
s.connect(('localhost', 10500))

sf = s.makefile('')
sleep = Popen('python stop.py', shell=True)
sleep.kill()
api = Popen('php start.php', shell=True)

while True:
	line = sf.readline().decode('utf-8')
	if line.find('WHYPO') != -1:
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
			sleep = Popen('python stop.py', shell=True)

		if line.find(u"おはよう") != -1:
			print 'call start'
			sleep.kill()
			api = Popen('php start.php', shell=True)

