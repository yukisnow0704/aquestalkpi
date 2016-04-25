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
        print (recv_data)
if __name__ == '__main__':
    main()