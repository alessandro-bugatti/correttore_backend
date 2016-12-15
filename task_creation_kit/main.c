/*
    Autore: Alessandro Bugatti
    Descrizione: soluzione di altezze e pesi
*/

#include <stdio.h>
#include <stdlib.h>


int main()
{
    int i, N, a, a_max, a_min, p, p_min, p_max; 
    scanf("%d",&N);
    scanf("%d", &a);
	scanf("%d", &p);
    a_max = a_min = a;
    p_max = p_min = p;
    for (i = 0; i < N-1 ; i++)
    {
		scanf("%d", &a);
		scanf("%d", &p);
		if (a > a_max)
			a_max = a;
		if (a < a_min)
			a_min = a;
		if (p > p_max)
			p_max = p;
		if (p < p_min)
			p_min = p;
	}
	printf("%d %d\n",a_max - a_min, p_max - p_min);
	return 0;
}
