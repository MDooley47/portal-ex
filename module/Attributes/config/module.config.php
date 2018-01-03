<?php

namespace Attribute;

use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'attribute' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/attribute[/:action[/:slug]][/]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'slug'     => '[a-zA-Z0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\AttributeController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'attribute' => __DIR__ . '/../view',
        ],
    ],
];
