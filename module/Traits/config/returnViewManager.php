<?php

$viewPath = APPLICATION_PATH . '/module/Application/view';

$return_view_manager = [
    'view_manager' => [
        'template_path_stack' => [
            'app' => $viewPath,
            'attribute' => $viewPath,
            'configuration' => $viewPath,
            'group' => $viewPath,
            'grouptype' => $viewPath,
            'ipaddress' => $viewPath,
            'ownertype' => $viewPath,
            'privilege' => $viewPath,
            'setting' => $viewPath,
            'tab' => $viewPath,
            'user' => $viewPath,
        ],
    ],
];
