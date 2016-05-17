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

julius_path = 'julius'
jconf_path = '~/julius-4.3.1/julius-kits/dictation-kit-v4.3.1-linux/kudo_ken.jconf'
julius = None
julius_socket = None


def invoke_julius():
    print 'INFO : invoke julius'
    args = julius_path + ' -C ' + jconf_path + ' -module '
    print args
    p = subprocess.Popen(args, shell=True)
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
            if line.find(u"WHYPO WORD") != -1:
                print line
                if line.find(u"天気") != -1:
                    print 'call weather02'
                    delete_socket(julius_socket)
                    f = open('tmp.txt','w')
                    f.write("袋井")
                    f.close()
                    os.system("php weather01.php")
                    
                    if line.find(u"予報") != -1:
                        print 'call weather02'
                        os.system("php weather02.php")
                
                if line.find(u"ニュース") != -1:
                    delete_socket(julius_socket)
                    print 'call news'
                    os.system("php news.php")

                if line.find(u"予定") != -1:
                    if line.find(u"伊藤") != -1:
                        print 'call yuki-itou plan'
                        delete_socket(julius_socket)
                        f = open('tmp.txt','w')
                        f.write("伊藤祐輝")
                        f.close()
                        os.system("php calendar_test01.php")

                    if line.find(u"小山") != -1:
                        print 'call koyama plan'
                        delete_socket(julius_socket)
                        f = open('tmp.txt','w')
                        f.write("koyama ryoma")
                        f.close()
                        os.system("php calendar_test01.php")

                    if line.find(u"怜真") != -1:
                        print 'call koyama plan'
                        delete_socket(julius_socket)
                        f = open('tmp.txt','w')
                        f.write("koyama ryoma")
                        f.close()
                        os.system("php calendar_test01.php")

                    if line.find(u"芹沢") != -1:
                        print 'call serigawa plan'
                        delete_socket(julius_socket)
                        f = open('tmp.txt','w')
                        f.write("芹澤勇輝")
                        f.close()
                        os.system("php calendar_test01.php")

                    if line.find(u"長澤") != -1:
                        print 'call nagagawa plan'
                        delete_socket(julius_socket)
                        f = open('tmp.txt','w')
                        f.write("長澤")
                        f.close()
                        os.system("php calendar_test01.php")

                    if line.find(u"長谷川") != -1:
                        print 'call hasegawa plan'
                        delete_socket(julius_socket)
                        f = open('tmp.txt','w')
                        f.write("長谷川")
                        f.close()
                        os.system("php calendar_test01.php")

                    if line.find(u"みんな") != -1:
                        print 'call day plan'
                        delete_socket(julius_socket)
                        os.system("php calendar_test02.php")

                    if line.find(u"皆さん") != -1:
                        print 'call day plan'
                        delete_socket(julius_socket)
                        os.system("php calendar_test02.php")

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