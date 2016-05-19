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
	p = Popen(args, shell=True)
	while p.poll() == None:
		print 'wita'
		time.sleep(2.0)

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

while True:
	line = sf.readline().decode('utf-8')
	if line.find('WHYPO') != -1:
		print line
		if line.find(u"天気") != -1:
			print 'call tenki'
			loop('php weather01.php')
			if line.find(u"予報") != -1:
				print 'call weather02'
				loop('php weather02.php')
            
		if line.find(u"ニュース") != -1:
			print 'call news'
			loop('php news.php')

		if line.find(u"予定") != -1:
			if line.find(u"伊藤") != -1:
				print 'call yuki-itou plan'
				f = open('tmp.txt','w')
				f.write("伊藤祐輝")
				f.close()
				os.system("php calendar_test01.php")

			if line.find(u"小山") != -1:
				print 'call koyama plan'
				f = open('tmp.txt','w')
				f.write("koyama ryoma")
				f.close()
				os.system("php calendar_test01.php")

			if line.find(u"怜真") != -1:
				print 'call koyama plan'
				f = open('tmp.txt','w')
				f.write("koyama ryoma")
				f.close()
				os.system("php calendar_test01.php")

			if line.find(u"芹沢") != -1:
				print 'call serigawa plan'
				f = open('tmp.txt','w')
				f.write("芹澤勇輝")
				f.close()
				os.system("php calendar_test01.php")

			if line.find(u"長澤") != -1:
				print 'call nagagawa plan'
				f = open('tmp.txt','w')
				f.write("長澤")
				f.close()
				os.system("php calendar_test01.php")

			if line.find(u"長谷川") != -1:
				print 'call hasegawa plan'
				f = open('tmp.txt','w')
				f.write("長谷川")
				f.close()
				os.system("php calendar_test01.php")

			if line.find(u"みんな") != -1:
				print 'call day plan'
				os.system("php calendar_test02.php")

			if line.find(u"皆さん") != -1:
				print 'call day plan'
				os.system("php calendar_test02.php")