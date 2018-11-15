#!/bin/bash
if [[ $# -ne 1 ]]; then
    echo "Usage: ${BASH_SOURCE[0]} student_id"
    exit
fi
curl -v -X PUT -H "X-Authorization-Token: 3427a80af08fdf717529d631339a090635acf72079712c12a8a0f2498c5f87da" \
-H "Content-Type: application/json" -d '{"name":"Alekos", "surname":"Filino", "username":"alekos", "role":"student"}' \
https://auth-silex-test-alessandro-bugatti.c9users.io/v1/students/$1
