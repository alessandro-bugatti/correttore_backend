/*
    Autore: Alessandro Bugatti
    Descrizione: problema basato sulla congettura di collatz
*/

#include <stdio.h>
#include <stdlib.h>

int collatz(int n)
{
	int contatore = 1;
	while (n!=1)
	{
		if (n%2==0)
			n/=2;
		else
			n=n*3+1;
		contatore++;
	}
	return contatore;
}

int main()
{
    int N, n, l, i;
    int contatore = 0;
    scanf("%d",&N);
	for(i = 0; i < N; i++)
	{	
		scanf("%d",&n);
		l = collatz(n);
		if (l >= 20)
			contatore++;
	}
	printf("%d\n", contatore);	
    return 0;
}
