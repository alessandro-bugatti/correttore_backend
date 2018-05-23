#!/bin/bash
curl -v -X POST -H "X-Authorization-Token: 3427a80af08fdf717529d631339a090635acf72079712c12a8a0f2498c5f87da" \
-H "Content-Type: application/json" -d '{"description":"test2 category"}' \
https://auth-silex-test-alessandro-bugatti.c9users.io/v1/categories
