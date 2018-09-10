<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 *
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\ApplicationController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'login' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/login[/]',
                    'defaults' => [
                        'controller' => Controller\ApplicationController::class,
                        'action'     => 'login',
                    ],
                ],
            ],
            'loginsso' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/loginsso[/]',
                    'defaults' => [
                        'controller' => Controller\ApplicationController::class,
                        'action'     => 'loginsso',
                    ],
                ],
            ],
            'logout' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/logout[/]',
                    'defaults' => [
                        'controller' => Controller\ApplicationController::class,
                        'action'     => 'logout',
                    ],
                ],
            ],
            'app' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/app[/:action[/:slug]][/]',
                    'constraints' => [
                        'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'slug'     => '[a-zA-Z0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\AppController::class,
                    ],
                ],
            ],
            'attribute' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/attribute[/:action[/:slug]][/]',
                    'constraints' => [
                        'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'slug'     => '[a-zA-Z0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\AttributeController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'configuration' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/configuration[/:action[/:slug]][/]',
                    'constraints' => [
                        'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'slug'     => '[a-zA-Z0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ConfigurationController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'group' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/group[/:action[/:slug]][/]',
                    'constraints' => [
                        'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'slug'     => '[a-zA-Z0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\GroupController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'grouptype' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/grouptype[/:action[/:slug]][/]',
                    'constraints' => [
                        'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'slug'     => '[a-zA-Z0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\GroupTypeController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'ipaddress' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/ipaddress[/:action[/:slug]][/]',
                    'constraints' => [
                        'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'slug'     => '[a-zA-Z0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\IpAddressController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'ownertype' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/ownertype[/:action[/:slug]][/]',
                    'constraints' => [
                        'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'slug'     => '[a-zA-Z0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\OwnerTypeController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'privilege' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/privilege[/:action[/:slug]][/]',
                    'constraints' => [
                        'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'slug'     => '[a-zA-Z0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\PrivilegeController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'setting' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/setting[/:action[/:slug]][/]',
                    'constraints' => [
                        'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'slug'     => '[a-zA-Z0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\SettingController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'tab' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/tab[/:slug[/:action]][/]',
                    'constraints' => [
                        'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'slug'     => '[a-zA-Z0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\TabController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'user' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/user[/:action[/:slug]][/]',
                    'constraints' => [
                        'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'slug'     => '[a-zA-Z0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\ApplicationController::class   => Controller\ApplicationControllerFactory::class,
            Controller\AppController::class           => Controller\AppControllerFactory::class,
            Controller\AttributeController::class     => Controller\AttributeControllerFactory::class,
            Controller\ConfigurationController::class => Controller\ConfigurationControllerFactory::class,
            Controller\GroupController::class         => Controller\GroupControllerFactory::class,
            Controller\GroupTypeController::class     => Controller\GroupTypeControllerFactory::class,
            Controller\IpAddressController::class     => Controller\IpAddressControllerFactory::class,
            Controller\OwnerTypeController::class     => Controller\OwnerTypeControllerFactory::class,
            Controller\PrivilegeController::class     => Controller\PrivilegeControllerFactory::class,
            Controller\SettingController::class       => Controller\SettingControllerFactory::class,
            Controller\TabController::class           => Controller\TabControllerFactory::class,
            Controller\UserController::class          => Controller\UserControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map'             => [
            'layout/layout'           => __DIR__.'/../view/layout/layout.phtml',
            'application/index/index' => __DIR__.'/../view/application/index/index.phtml',
            'error/404'               => __DIR__.'/../view/error/404.phtml',
            'error/index'             => __DIR__.'/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__.'/../view',
        ],
    ],
];
