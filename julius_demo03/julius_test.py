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

def loop(args, api):
	if api.poll() is not None or api == 'test':
		p = Popen(args, shell=True)
	else:
		p = api
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
api = 'test';

while True:
	line = sf.readline().decode('utf-8')
	if line.find('WHYPO') != -1:
		print line
		if line.find(u"天気") != -1:
			print 'call tenki'
			api = loop('php weather01.php', api)
			
			if line.find(u"予報") != -1:
				print 'call weather02'
				api = loop('php weather02.php', api)
            
