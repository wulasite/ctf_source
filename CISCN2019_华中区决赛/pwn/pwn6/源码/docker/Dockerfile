FROM ubuntu:16.04

RUN sed -i "s/http:\/\/archive.ubuntu.com/http:\/\/mirrors.aliyun.com/g" /etc/apt/sources.list
RUN apt-get update && apt-get -y dist-upgrade
RUN apt-get install -y lib32z1 xinetd build-essential ssh



RUN useradd -m ctf

COPY ./flag /flag
COPY ./library /pwn/library
COPY ./ctf.xinetd /etc/xinetd.d/ctf

RUN chown root:ctf /pwn/library && chmod 750 /pwn/library

RUN echo 'ctf - nproc 1500' >>/etc/security/limits.conf

CMD exec /bin/bash -c "/etc/init.d/xinetd start; trap : TERM INT; sleep infinity & wait"

EXPOSE 8888 
