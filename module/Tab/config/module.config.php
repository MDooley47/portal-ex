<?php

namespace Tab;

use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'tab' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/tab[/:action[/:slug]][/]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'slug'     => '[a-zA-Z0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\TabController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'tab' => __DIR__ . '/../view',
        ],
    ],
];
