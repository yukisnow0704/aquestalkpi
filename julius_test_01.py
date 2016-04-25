#!/usr/bin/python
# -*- coding: utf-8 -*-
import socket
import requests
import re

s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
s.connect(('localhost', 10500))

sf = s.makefile('')

while True:
    line = sf.readline().decode('utf-8')
    if line.find('WHYPO') != -1:
    	print line
       	if line.find(u"天気") != -1:
        	print 'call WATSON'