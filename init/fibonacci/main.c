/*
    Autore: Gianlorenzo Ceresoli - Alessandro Bugatti
    Data: 22 Ottobre 2009
    Descrizione: Programma che calcola il numero di Fibonacci.
	Il numero di Fibonacci è definito nel seguente modo:
	f0 = 0
	f1 = 1
	fn = fn-1 + fn-2
*/

#include <stdio.h>
#include <stdlib.h>

int main()
{
    int fibonacci = 0, fibonacci2=0, fibonacci1=1;
    int n, i = 2;
    //printf("Inserire il numero: ");
    scanf("%d",&n);
    while ( i<= n)
    {
        fibonacci = fibonacci1 + fibonacci2;
        fibonacci2 = fibonacci1;
        fibonacci1 = fibonacci;
        i++;
    }
    if (n ==  1) fibonacci = 1;
    //printf("Il numero di Fibonacci di %d e' %d\n",n,fibonacci);
	printf("%d\n",fibonacci);    
	return 0;
}
