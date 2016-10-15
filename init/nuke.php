<?php
include '../lib/RedBeanPHP.php';

R::setup( 'mysql:host=127.0.0.1;dbname=c9',
        'alessandro_bugat', '' );
R::nuke();