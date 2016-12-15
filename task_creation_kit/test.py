'''Modello per la creazione dei casi di test'''
__author__ = 'alessandro'

import random
import os

casi_test = 7
for i in range(casi_test):
    fin = 'input' + str(i) + '.txt'
    fout = 'output' + str(i) + '.txt'
    f = open(fin,'w')
    N = random.randint(100,1000)
    M = random.randint(1,100000)
    f.write(str(N) + " " + str(M) + "\n")
    for j in range(N):
        f.write(str(random.randint(1,10000)) + '\n')
    f.close()
    os.system('./main < ' + fin + ' > ' + fout)
casi_test = 2
for i in range(casi_test):
    fin = 'input' + str(i+7) + '.txt'
    fout = 'output' + str(i+7) + '.txt'
    f = open(fin,'w')
    N = random.randint(1,100)
    M = random.randint(1,1000000)
    f.write(str(N) + " " + str(M) + "\n")
    for j in range(N):
        f.write(str(random.randint(1,100)) + '\n')
    f.close()
    os.system('./main < ' + fin + ' > ' + fout)
    
