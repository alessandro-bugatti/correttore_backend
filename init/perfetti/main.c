/*
    Autore: Alessandro Bugatti
    Data: 22 Ottobre 2009
    Descrizione: Verifica se un numero è perfetto o no.
    Un numero è perfetto quando è uguale alla somma dei
    suoi divisori propri (ad esempio il 6 è perfetto
    perchè somma di 1, 2 e 3
    */

#include <stdio.h>
#include <stdlib.h>

int perfetto(int n)
{	
	int i;
	int somma = 0;
	for (i = 1; i<n; i++)
		if (n%i==0) somma+=i;
	if (somma == n)
		return 1;
	else
		return 0;
}

int main()
{
	int n;
	scanf("%d",&n);
	if (perfetto(n))
		printf("SI\n");
	else
		printf("NO\n");
    /*int i = 0;
    for (int i = 0; i<10000; i++)
    if (perfetto(i))
    	printf("%d\n",i);
    */    
	return 0;
}
