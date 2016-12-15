#include <stdio.h>
#include <stdlib.h>
#include <time.h>
#define NUM_CASI 10

int main()
{
	srand(time(NULL));
	FILE *in, *out;
	char nomefile[100], comando[500];
	int i,j;
	char s[1001];
	for (i = 9; i<NUM_CASI; i++)
	{
		sprintf(nomefile,"input%d.txt",i);
		in = fopen(nomefile,"w");
		for (j = 0; j < 1000; j++)
			s[j] = 'a' + rand()%26;
		s[j] = '\0';
		fprintf(in,"%s\n",s);
		fclose(in);
		sprintf(comando,"./main < input%d.txt > output%d.txt",i,i);
		system(comando);
	}
	return 0;
}
	
