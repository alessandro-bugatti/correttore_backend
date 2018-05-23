#!/bin/bash
if [[ $# -ne 1 ]]; then
    echo "Usage: ${BASH_SOURCE[0]} task_id"
    exit
fi
curl -v -X PUT -H "X-Authorization-Token: 3427a80af08fdf717529d631339a090635acf72079712c12a8a0f2498c5f87da" \
-H "Content-Type: application/json" -d '{"description":"nuova descrizione", "is_on":"0"}' \
https://auth-silex-test-alessandro-bugatti.c9users.io/v1/tests/$1
