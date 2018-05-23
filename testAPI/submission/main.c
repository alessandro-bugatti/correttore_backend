/*
    Autore: Alessandro Bugatti
    Descrizione: data una stringa di soli caratteri minuscoli
    ristamparla senza le a
*/

#include <stdio.h>
#include <stdlib.h>

int main()
{
    char s[1001];
    int i;
    scanf("%s",s);
    for (i = 0; s[i] != '\0'; i++)
		if (s[i] != 'a')
			printf("%c", s[i]);
	return 0;
}
