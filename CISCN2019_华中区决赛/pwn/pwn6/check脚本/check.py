#!/usr/bin/python2.7
# coding:utf-8
# 请勿修改整体模板，check 函数中内容需要根据题目进行完善
# eg: python check.py 192.168.8.101 8888
from sys import *
from pwn import *
import time

host = argv[1]
port = int(argv[2])
timeout = 30

# except return Correct/Error info, not return another info
context.log_level = 'critical'

def cal(cipher):
    key=[61, 73, 83, 97, 109, 113, 127, 131, 137, 139, 149, 151, 167, 179, 193, 199, 211, 223, 229]
    keyposs=[[] for i in range(4)]
    for i in range(0xa3):
        ciphermid=cipher+(i<<32)
        for j in range(4):
            for k in key:
                if ciphermid % k == 0:
                    ciphermid=ciphermid//k
                    break
        if ciphermid in range(64,256):
            cipher=ciphermid
            break
    return cipher

def getIO():
    return remote(host, port, timeout=timeout)

def check1():
    try:
        p = getIO()
        for i in range(4):
            cipher=p.recv()[:-1]
            # print(cipher)
            cipher=int(cipher,16)
            # print(cipher)
            password=cal(cipher)
            p.sendline(str(password))
        info = p.recvline()
        print info
        if info != "you can manage your library\n":
            raise Exception, "returnInfo no same"
    except Exception as e:
        # add your check error description
        raise Exception, "Check1 error, "+str(e)
    return True

# simple all defend check
def check2():
    return True

# if want to add check, Please insert function like check3(p),check4(p) ...
def check3():
    try:
        pass
        # ... your check demo
    except Exception as e:
        # add your check error description
        raise Exception, "Check3 error, "+"something..."
    return True

def check4():
    return True

def checker():
    try:
        # add your check function name
        if check1() and check2():
            return (True, "IP: "+host+" OK")
    except Exception as e:
        return (False, "IP: "+host+" is down, "+str(e))

if __name__ == '__main__':
    print(checker())
