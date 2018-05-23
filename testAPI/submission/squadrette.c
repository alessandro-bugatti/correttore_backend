/*
    Autore: Alessandro Bugatti
    Descrizione: Problemi dei triangoli rettangoli
    Festa degli algoritmi prima edizione
    Creazione: 17/05/2010 21:33:47 
*/

#include <stdio.h>
#include <stdlib.h>

int main()
{
	FILE *in, *out;
	in = fopen("input.txt","r");
	out = fopen("output.txt","w");
	int c1,c2,ipo,N,i;
	int rett = 0, acuti = 0, ottusi = 0;
    fscanf(in,"%d",&N);
    
	for(i = 0; i < N; i++)
	{	
		fscanf(in,"%d %d %d",&c1, &c2, &ipo);
		int somma_cateti = c1*c1 + c2*c2;
		int ipotenusa = ipo*ipo;
		if (somma_cateti == ipotenusa)
			rett++;
		else if (somma_cateti > ipotenusa)
			acuti++;
		else 
			ottusi++;
	}
	fprintf(out,"%d %d %d", rett, acuti, ottusi);	
    return 0;
}
