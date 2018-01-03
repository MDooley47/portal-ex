<?php

namespace IpAddress;

use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'ip-address' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/ip-address[/:action[/:slug]][/]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'slug'     => '[a-zA-Z0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\IpAddressController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'ip-address' => __DIR__ . '/../view',
        ],
    ],
];
