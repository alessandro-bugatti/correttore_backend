#!/bin/bash
if [[ $# -ne 2 ]]; then
    echo "Usage: ${BASH_SOURCE[0]} test_id task_id" 
    exit
fi
curl -v -X DELETE -H "X-Authorization-Token: 3427a80af08fdf717529d631339a090635acf72079712c12a8a0f2498c5f87da" \
https://auth-silex-test-alessandro-bugatti.c9users.io/v1/tests/$1/task/$2
