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
    print 'INFO : invoke julius complete.'
    print 'INFO : wait 2 seconds.'
    time.sleep(4.0)
    print 'INFO : invoke julius complete'
    return p

def kill_julius(julius):
    print 'INFO : terminate julius'
    julius.kill()
    while julius.poll() is None:
        print 'INFO : wait for 0.1 sec julius\' termination'
        time.sleep(0.1)
    print 'INFO : terminate julius complete'


def get_OS_PID(process):
    psef = 'ps -ef | grep ' + process + ' | grep -ve grep -vie python |head -1|awk \'{print($2)}\''
    if sys.version_info.major == 3:
        PID = str(subprocess.check_output(psef, shell=True), encoding='utf-8').rstrip ()
    else:
        PID = subprocess.check_output(psef, shell=True).rstrip ()
    return PID


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
    sf = julius_socket.makefile('')
    return (julius, julius_socket, sf)


def main():
    global julius
    global julius_socket
    julius, julius_socket, sf = invoke_julius_set()

    # ###
    # # re definition
    # ###
    reWATSON = re.compile(r'WHYPO WORD="WATSON" .* CM="(\d\.\d*)"')

    while True:
        if julius.poll() is not None:   # means , julius dead
            delete_socket(julius_socket)
            julius, julius_socket, sf = invoke_julius_set()
        else:
            line = sf.readline().decode('utf-8')
            print line
            tmp = reWATSON.search(line)
            if tmp:
                # print line
                if float(tmp.group(1)) > 0.8:
                    print 'WARN : DIE julius, call WATSON'
                    kill_julius(julius)
                    delete_socket(julius_socket)
                    print '====================================='
                    time.sleep(2.0)
                    print '====================================='
                    time.sleep(2.0)
                    print '====================================='

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