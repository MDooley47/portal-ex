<?php

namespace App;

return [
    'router' => [
        'app' => [
            'type' => Segment::class,
            'options' => '/app[/:action[/:id]]',
            'constraints' => [
                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                'id' => '[0-9]+'
            ],
            'defaults' => [
                'controller' => Controller\AppController::class,
                'action' => 'index',
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'app' => __DIR__ . '/../view',
        ],
    ],
];
