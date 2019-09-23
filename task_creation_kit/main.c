/*
    Autore: Alessandro Bugatti
    Descrizione: soluzione di intro
*/

#include <stdio.h>
#include <stdlib.h>

int main()
{
    int N, M;
    scanf("%d",&N);
    scanf("%d",&M);
    printf("%d ", N + M);
    if ((N + M)%2 == 0)
		printf("%d\n", N * M);
    else
		printf("%d\n", N - M);
    return 0;
}

