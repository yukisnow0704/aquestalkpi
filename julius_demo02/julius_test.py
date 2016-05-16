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

julius_path = 'ALSADEV="plughw:1,0" julius'
jconf_path = '~/julius-4.3.1/julius-kits/dictation-kit-v4.3.1-linux/kudo_ken.jconf'
julius = None
julius_socket = None


def invoke_julius():
    print 'INFO : invoke julius'
    args = julius_path + ' -C ' + jconf_path + ' -module '
    print args
    p = subprocess.Popen(args, shell=True)
    time.sleep(4.0)
    return p

def kill_julius(julius):
    julius.kill()
    while julius.poll() is None:
        time.sleep(0.1)

def create_socket():
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s.connect(('localhost', 10500))
    return s


def delete_socket(s):
    s.close()
    return True

def invoke_julius_set():
    julius = invoke_julius()
    julius_socket = create_socket()
    sf = julius_socket.makefile('')
    return (julius, julius_socket, sf)

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
            os.system('/home/pi/aquestalkpi/AquesTalkPi "ご命令ください" | aplay')
        else:
            line = sf.readline().decode('utf-8')
            print line
            if line.find(u"天気") != -1:
                print 'call weather02'
                kill_julius(julius)
                delete_socket(julius_socket)
                f = open('tmp.txt','w')
                f.write("袋井")
                f.close()
                os.system("php weather01.php")
                
                if line.find(u"予報") != -1:
                    print 'call weather02'
                    os.system("php weather02.php")
                time.sleep(4.0)
            
            if line.find(u"ニュース") != -1:
                kill_julius(julius)
                delete_socket(julius_socket)
                print 'call news'
                os.system("php news.php")
                time.sleep(4.0)

            if line.find(u"予定") != -1:
                if line.find(u"伊藤") != -1:
                    print 'call yuki-itou plan'
                    kill_julius(julius)
                    delete_socket(julius_socket)
                    f = open('tmp.txt','w')
                    f.write("伊藤祐輝")
                    f.close()
                    os.system("php calendar_test01.php")
                    time.sleep(4.0)
                if line.find(u"小山") != -1:
                    print 'call koyama plan'
                    kill_julius(julius)
                    delete_socket(julius_socket)
                    f = open('tmp.txt','w')
                    f.write("koyama ryoma")
                    f.close()
                    os.system("php calendar_test01.php")
                    time.sleep(4.0)
                if line.find(u"怜真") != -1:
                    print 'call koyama plan'
                    kill_julius(julius)
                    delete_socket(julius_socket)
                    f = open('tmp.txt','w')
                    f.write("koyama ryoma")
                    f.close()
                    os.system("php calendar_test01.php")
                    time.sleep(4.0)
                if line.find(u"芹沢") != -1:
                    print 'call serigawa plan'
                    kill_julius(julius)
                    delete_socket(julius_socket)
                    f = open('tmp.txt','w')
                    f.write("芹澤勇輝")
                    f.close()
                    os.system("php calendar_test01.php")
                    time.sleep(4.0)
                if line.find(u"長澤") != -1:
                    print 'call nagagawa plan'
                    kill_julius(julius)
                    delete_socket(julius_socket)
                    f = open('tmp.txt','w')
                    f.write("長澤")
                    f.close()
                    os.system("php calendar_test01.php")
                    time.sleep(4.0)
                if line.find(u"長谷川") != -1:
                    print 'call hasegawa plan'
                    kill_julius(julius)
                    delete_socket(julius_socket)
                    f = open('tmp.txt','w')
                    f.write("長谷川")
                    f.close()
                    os.system("php calendar_test01.php")
                    time.sleep(4.0)    

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