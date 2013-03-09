ARCH := $(shell getconf LONG_BIT)
C_FLAGS_64 := -L/usr/lib64/mysql
C_FLAGS_32 := -L/usr/lib

smatool: smatool.o
	gcc smatool.o $(C_FLAGS_$(ARCH)) -lbluetooth -lm -o smatool
smatool.o: smatool.c
	gcc -c smatool.c

