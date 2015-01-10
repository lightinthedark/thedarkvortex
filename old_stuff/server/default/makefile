CC=g++
CFLAGS=-c -I/usr/include/postgresql
LFLAGS=-L/usr/lib -lpq

include webserver.mk simulator.mk

all: web sim

clean : clean_web clean_sim
