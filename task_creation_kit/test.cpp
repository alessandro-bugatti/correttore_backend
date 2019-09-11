#include <stdio.h>
#include <stdlib.h>
#include <time.h>
#define NUM_CASI 10

int main()
{
	srand(time(NULL));
	FILE *in;
	int i;
	char nomefile[100], comando[500];
	//Coppia di numeri pari
    for (i = 0; i < NUM_CASI/2; i++)
	{
		sprintf(nomefile,"input%d.txt",i);
		in = fopen(nomefile,"w");
		fprintf(in,"%d %d\n", (rand()%1000)*2, (rand()%1000)*2);
		fclose(in);
		sprintf(comando,"./main < input%d.txt > output%d.txt",i,i);
		system(comando);
	}
	
	//Coppie di numeri uno pari e uno dispari
	for (i = NUM_CASI/2; i < NUM_CASI; i++)
	{
		sprintf(nomefile,"input%d.txt",i);
		in = fopen(nomefile,"w");
		fprintf(in,"%d %d\n", (rand()%1000)*2 + 1, (rand()%1000)*2);
		fclose(in);
		sprintf(comando,"./main < input%d.txt > output%d.txt",i,i);
		system(comando);
	}
	return 0;
}
	
