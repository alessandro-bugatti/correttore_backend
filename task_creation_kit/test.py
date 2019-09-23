'''Modello per la creazione dei casi di test'''
__author__ = 'alessandro'

import random
import os

for i in range(0,5):
    fin = 'input' + str(i) + '.txt'
    fout = 'output' + str(i) + '.txt'
    f = open(fin,'w')
    f.write(str(random.randint(1,1000)*2) + ' ' + str(random.randint(1,1000)*2) + '\n')
    f.close()
    #togliere ./ davanti a main nel caso si esegua in Windows
    os.system('./main < ' + fin + ' > ' + fout) 

for i in range(5,10):
    fin = 'input' + str(i) + '.txt'
    fout = 'output' + str(i) + '.txt'
    f = open(fin,'w')
    f.write(str(random.randint(1,1000)*2 + 1) + ' ' + str(random.randint(1,1000)*2) + '\n')
    f.close()
    #togliere ./ davanti a main nel caso si esegua in Windows
    os.system('./main < ' + fin + ' > ' + fout) 
