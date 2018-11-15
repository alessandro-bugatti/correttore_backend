#include <stdio.h>
#include <stdlib.h>
#include <iostream>

using namespace std;
int main()
{
    int N;
    int H;
    cin >> H >> N;
    int altezze[N];
    int armadi[N];
    int conta=0;
    for(int i = 0; i < N ; i++)
    {
        cin >> altezze[i];
        armadi[i]=H;
    }
    for(int i = 0 ; i < N ; i++)
    {
        for(int k = 0; k < N ; k++)
        {
            if((armadi[k]-altezze[i])>= 0)
            {
                if(armadi[k]==H)
                    conta++;
                armadi[k]-=altezze[i];
                break;
            }
        }
    }
    cout << conta;
    return 0;
}



