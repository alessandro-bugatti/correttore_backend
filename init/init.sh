#!/bin/bash
#Building some fixtures
{
    rm -R ../tasks/*
    cp -R fibonacci/ ../tasks/
    cp -R mucche_vitelli/ ../tasks/
    cp -R pacchi_2/ ../tasks/
    rm -R ../users/alex
    mkdir ../users/alex
    cp pacchi_2.cpp ../users/alex/pacchi_2.cpp
    php nuke.php
    php create_user.php
    php create_task.php
} &> /dev/null