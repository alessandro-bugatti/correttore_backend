'''Modello per la creazione dei casi di test se i file di input vengono creati manualmente'''
__author__ = 'alessandro'

import os
import sys

if (len(sys.argv) != 3 or sys.argv[1].isdigit() == False or sys.argv[2].isdigit() == False ):
	print("Usage: python " + sys.argv[0] + " start_index end_index")
	exit(1)
for i in range(int(sys.argv[1]), int(sys.argv[2])+1):
    fin = 'input' + str(i) + '.txt'
    fout = 'output' + str(i) + '.txt'
    os.system('./a.out < ' + fin + ' > ' + fout)
    
