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


julius_path = 'julius'
jconf_path = '~/julius-4.3.1/julius-kits/dictation-kit-v4.3.1-linux/kudo_ken.jconf'
julius = None
julius_socket = None


def invoke_julius():
    print 'INFO : invoke julius'
    args = julius_path + ' -C ' + jconf_path + ' -module '
    print shlex.split(args)
    p = subprocess.Popen( args,shell=True )
    print 'INFO : invoke julius complete.'
    print 'INFO : wait 2 seconds.'
    time.sleep(3.0)
    print 'INFO : invoke julius complete'
    return p


def kill_julius(julius):
    print 'INFO : terminate julius'
    julius.kill()
    while julius.poll() is None:
        print 'INFO : wait for 0.1 sec julius\' termination'
        time.sleep(0.1)
    print 'INFO : terminate julius complete'

def create_socket():
    print 'INFO : create a socket to connect julius'
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s.connect(('localhost', 10500))
    print 'INFO : create a socket to connect julius complete'
    return s


def delete_socket(s):
    print 'INFO : delete a socket'
    s.close()
    print 'INFO : delete a socket complete'
    return True


def invoke_julius_set():
    julius = invoke_julius()
    julius_socket = create_socket()
    sf = julius_socket.makefile('rb')
    return (julius, julius_socket, sf)

def system_pros(pros):
	p1 = Popen(pros, shell=True)
	while p1.poll() == None:
		print 'wait!!'
		time.sleep(1.0)

def main():
    global julius
    global julius_socket
    os.system('/home/pi/aquestalkpi/AquesTalkPi "ちょっと待ってね" | aplay')
    julius, julius_socket, sf = invoke_julius_set()
    os.system('/home/pi/aquestalkpi/AquesTalkPi "ご命令ください" | aplay')

    # ###
    # # re definition
    # ###
    while True:
        if julius.poll() is not None:   # means , julius dead
            delete_socket(julius_socket)
            os.system('/home/pi/aquestalkpi/AquesTalkPi "ちょっと待ってね" | aplay')
            julius, julius_socket, sf = invoke_julius_set()
            time.sleep(2.0)
            os.system('/home/pi/aquestalkpi/AquesTalkPi "ご命令ください" | aplay')
        else:
            line = sf.readline().decode('utf-8')
            print line
            if line.find(u"天気") != -1:
                print 'call weather02'
                f = open('tmp.txt','w')
                f.write("袋井")
                f.close()
                pros = "php julius_demo02/weather01.php"
                system_pros(pros)
                
                if line.find(u"予報") != -1:
                    print 'call weather02'
                    pros = "php julius_demo02/weather02.php"
                    system_pros(pros)
            
    print 'WARN : while loop breaked'
    print 'INFO : exit'


if __name__ == '__main__':
    try:
        main()
    except KeyboardInterrupt:
        print 'Interrupted. Exit sequence start..'
        kill_julius(julius)
        delete_socket(julius_socket)
        print 'INFO : Exit sequence done.'
        sys.exit(0)