<?php

namespace App;

use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'view_manager' => [
        'template_path_stack' => [
            'app' => APPLICATION_PATH . '/module/Application/view',
        ],
    ],
];
