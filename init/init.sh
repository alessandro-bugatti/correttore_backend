#!/bin/bash
#Building some fixtures
{
    rm -R ../tasks
    rm -R ../users
    rm -R ../temp
    mkdir ../tasks
    mkdir ../users
    mkdir ../temp
    cp -R fibonacci/ ../tasks/
    cp -R mucche_vitelli/ ../tasks/
    cp -R pacchi_2/ ../tasks/
    cp -R perfetti/ ../tasks/
    rm -R ../users/alex
    mkdir ../users/alex
    cp pacchi_2.cpp ../users/alex/pacchi_2.cpp
    php nuke.php
    php create_user.php
    php create_task.php
} &> /dev/null
