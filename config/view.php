<?php

use Illuminate\Support\Facades\File;

return [
    'paths' => [
        resource_path('views'),
    ],

    'compiled' => \Phar::running()
        ? $_SERVER['HOME'].'/.a80_cli/cache/views'
        : env('VIEW_COMPILED_PATH', realpath(storage_path('framework/views'))),
];
