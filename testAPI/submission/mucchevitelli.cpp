/*
    Autore: Alessandro Bugatti
    Data: 20 gennaio 2009
    Descrizione: Esercizio per il correttore che usa un array. Risolve il problema Mucche e vitelli
    */

#include <stdio.h>
#include <stdlib.h>

int n, i, pesi[10000];
float mediaM = 0, mediaV = 0;
int M=0,V=0,nM=0,nV=0;

int main()
{

	scanf("%d",&n);
    for (i = 0; i<n; i++){
		scanf("%d",&pesi[i]);
		if (pesi[i] <=150)
		{
			V++;
			mediaV+=pesi[i];
		}
		else
		{
			M++;
			mediaM+=pesi[i];
		}
	}
	mediaV/=V;
	mediaM/=M;
	for (i = 0; i<n; i++){	
    	if (pesi[i]<=150 && pesi[i]>=mediaV)
    		nV++;
    	if (pesi[i]>150 && pesi[i]>=mediaM)
    		nM++;
    	}
    printf("%d %d\n",nV,nM);
    return 0;
}
