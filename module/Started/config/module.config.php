<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Started\Controller\Index' => 'Started\Controller\IndexController',
        ),
        'aliases' => [
            'Index' => 'Started\Controller\Index',
           
        ]
    ),
    'router' => array(
        'routes' => array(
            'started' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/started',
                    'defaults' => array(
                       // '__NAMESPACE__' => 'Started\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                
            ),
            'started-login' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/started/login',
                    'defaults' => array(
                        'controller' => 'Index',
                        'action'     => 'login',
                    ),
                ),
            ),
            'started-register' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/started/register',
                    'defaults' => array(
                        'controller' => 'Index',
                        'action'     => 'register',
                    ),
                ),
            )
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'started' => __DIR__ . '/../view',
        ),
    ),
);