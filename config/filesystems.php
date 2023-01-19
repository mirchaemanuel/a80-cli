<?php

return [
    'default' => 'local',
    'disks'   => [
        'local' => [
            'driver' => 'local',
            'root'   => getcwd(),
        ],
        'app'   => [
            'driver' => 'local',
            'root'   => $_SERVER['HOME'] . '/.a80_cli/',
        ],
        'root'  => [
            'driver' => 'local',
            'root'   => $_SERVER['HOME'],
        ]
    ],
];
