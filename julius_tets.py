#!/usr/bin/env python
# -*- coding: utf-8 -*-
import socket
#import serial
import xml.etree.ElementTree as ET
import subprocess
import time

host = 'localhost'
port = 10500
 
# port setting
SERVO = 4

clientsock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
clientsock.connect((host, port))

sf = clientsock.makefile('rb')

while True:
    line = sf.readline().decode('utf-8')
    if line.find('WHYPO') != -1:
        print line
        if line.find(u'天気') != -1:
            print("miku")
        elif line.find(u'天気予報') != -1:
            print("hello")
            hello()