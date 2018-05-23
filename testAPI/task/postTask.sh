#!/bin/bash
curl -v -H "X-Authorization-Token: 3427a80af08fdf717529d631339a090635acf72079712c12a8a0f2498c5f87da" \
-F title="Somma quattro numeri" -F short_title="somma4" -F is_public="0" -F level="1" -F test_cases="10" \
-F category_id="1" -F description=@prova.pdf -F solution=@test.zip -F material=@prova.pdf \
https://auth-silex-test-alessandro-bugatti.c9users.io/v1/tasks