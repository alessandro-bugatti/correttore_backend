/*
    Autore: Alessandro Bugatti
    Descrizione: programma risolutore del problema dei pacchi postali 2
*/

#include <stdio.h>
#include <stdlib.h>

int armadi[10000];

int main()
{
    int H,N,i,j,s;
    scanf("%d",&H);
    scanf("%d",&N);
	for(i = 0; i < N; i++)
	{	
		j=0;
		scanf("%d",&s);
		while((H - armadi[j]) < s) j++;
		armadi[j]+=s;
	}
	j=0;
	while(armadi[j] != 0) j++;
	printf("%d\n", j);	
    return 0;
}
