#!/usr/bin/python
# -*- coding: utf-8 -*-
import socket
import requests
import re

s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
s.connect(('localhost', 10500))

sf = s.makefile('')

reWATSON = re.compile(r'WHYPO WORD="天気" .* CM="(\d\.\d*)"')

while True:
    line = sf.readline().decode('utf-8')
    tmp = reWATSON.search( line )
    if tmp:
        print line
        if float(tmp.group(1)) > 0.8:
                print 'call WATSON'