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
            'dashboard' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/dashboard[/]',
                    'defaults' => [
                        'controller' => Controller\ApplicationController::class,
                        'action'     => 'dashboard',
                    ],
                ],
            ],
            'api' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/api/v1[/]',
                    'constraints' => [
                        'model'    => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'       => '[a-zA-Z0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\APIController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'api2' => [
                'type'          => Segment::class,
                'may_terminate' => true,
                'options'       => [
                    'route'       => '/api/v2[/:segment0[/:segment1[/:segment2[/:segment3[/:segment4[/:segment5[/:segment6[/:segment7[/:segment8[/:segment9]]]]]]]]]][/]',
                    'defaults'    => [
                        'controller' => Controller\API2Controller::class,
                        'action'     => 'api',
                    ],
                ],
            ],
            'app' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/dashboard/app[/:action[/:slug]][/]',
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
                    'route'       => '/dashboard/attribute[/:action[/:slug]][/]',
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
                    'route'       => '/dashboard/configuration[/:action[/:slug]][/]',
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
                    'route'       => '/dashboard/group[/:action[/:slug]][/]',
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
                    'route'       => '/dashboard/grouptype[/:action[/:slug]][/]',
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
                    'route'       => '/dashboard/ipaddress[/:action[/:slug]][/]',
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
                    'route'       => '/dashboard/ownertype[/:action[/:slug]][/]',
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
                    'route'       => '/dashboard/privilege[/:action[/:slug]][/]',
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
                    'route'       => '/dashboard/setting[/:action[/:slug]][/]',
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
                    'route'       => '/t[/:slug[/:action]][/]',
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
                    'route'       => '/dashboard/user[/:action[/:slug]][/]',
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
            Controller\APIController::class            => Controller\APIControllerFactory::class,
            Controller\API2Controller::class           => Controller\API2ControllerFactory::class,
            Controller\ApplicationController::class    => Controller\ApplicationControllerFactory::class,
            Controller\AppController::class            => Controller\AppControllerFactory::class,
            Controller\AttributeController::class      => Controller\AttributeControllerFactory::class,
            Controller\ConfigurationController::class  => Controller\ConfigurationControllerFactory::class,
            Controller\GroupController::class          => Controller\GroupControllerFactory::class,
            Controller\GroupTypeController::class      => Controller\GroupTypeControllerFactory::class,
            Controller\IpAddressController::class      => Controller\IpAddressControllerFactory::class,
            Controller\OwnerTypeController::class      => Controller\OwnerTypeControllerFactory::class,
            Controller\PrivilegeController::class      => Controller\PrivilegeControllerFactory::class,
            Controller\SettingController::class        => Controller\SettingControllerFactory::class,
            Controller\TabController::class            => Controller\TabControllerFactory::class,
            Controller\UserController::class           => Controller\UserControllerFactory::class,
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
