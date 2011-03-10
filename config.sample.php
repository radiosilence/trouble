<?php

$cfg['general'] = array(
    'backend' => 'PDO'
);

$cfg['database'] = array(
    'driver' => 'pgsql',
    'host' => 'localhost',
    'port' => 5432,
    'database' => 'DB_NAME',
    'user' => 'DB_USER',
    'password' => 'DB_PASSWORD',
    'persistent' => True
);

$cfg['crypto'] = array(
    'keyphrase' => "RANDOM_PHRASE_HERE",
    'base_salt' => "DIFFERENT RANDOM PHRASE",
);

$cfg['memcached'] = array(
    array(
        'host' => 'localhost',
        'port' => '11211'
    )
);
