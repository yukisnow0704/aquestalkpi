# -*- coding: UTF-8 -*-
from __future__ import print_function
import socket
from contextlib import closing
import commands

def main():
    host = 'localhost'
    port = 10500
    bufsize = 4096
    sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    sock.connect((host,port))

    while True:
        recv_data = sock.recv(bufsize)
        if recv_data.find('WHYPO') != -1:
            print (recv_data)
            if recv_data.find(u'天気') != -1:
                print(u"天気")
if __name__ == '__main__':
    main()