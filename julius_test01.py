#!/usr/bin/python
# -*- coding: utf-8 -*-
import socket
import requests
import re
import os
import time

s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
s.connect(('localhost', 10500))

sf = s.makefile('')

while True:
    line = sf.readline().decode('utf-8')
    if line.find('WHYPO') != -1:
    	print line
       	if line.find(u"天気予報") != -1:
        	print 'call tenki' 
            os.system("pause /n") 
        	os.system("/home/pi/source/aquestalkpi/motion_test02.sh")
        if line.find(u"ニュース") != -1:
        	print 'call news'
        	os.system("/home/pi/source/aquestalkpi/motion_test04.sh")
        	time.sleep(5)