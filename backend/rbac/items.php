<?php
return [
    'admin' => [
        'type' => 2,
        'description' => 'Admin',
    ],
    'adminRole' => [
        'type' => 1,
        'children' => [
            'admin',
        ],
    ],
];
