<?php

namespace Database;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Database\Controller\AdapterController;
use Database\Controller\DoctrineORMController;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    
    'router' => [
        'routes' => [
            'database' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/database', //[/:action] video sau
                    'defaults' => [
                        '__NAMESPACE__' => 'Database\Controller',
                        'controller' => 'Adapter',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => array(
                        'sub' => array(
                            'type'    => Segment::class,
                            'options' => array(
                                'route'    => '/[:controller[/:action]]',
                                'constraints' => array(
                                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                ),
                                'defaults' => array(
                                ),
                            ),
                        ),
                ),
            ],
                    //doctrine
            'users' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/users[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller'    => Controller\DoctrineORMController::class,
                        'action'        => 'index',
                    ],
                ],
            ],      
            // 'user' => [
            //     'type' => Segment::class,
            //     'options' => [
            //         'route'    => '/user[/:action[/:id]]',
            //         'constraints' => [
            //             'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
            //             'id' => '[a-zA-Z0-9_-]*',
            //         ],
            //         'defaults' => [
            //             'controller'    => Controller\TableGatewayController::class,
            //             'action'        => 'index',
            //         ],
            //     ],
            // ],
            'paginator'=>[
                'type' => Segment::class,
                'options' => [
                    'route'    => '/paginator[/:action[/page/:page]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'page' => '[0-9_-]*',
                    ],
                    'defaults' => [
                        'controller'    => Controller\PaginatorController::class,
                        'action'        => 'index',
                        'page'          => 1
                    ],
                ],
            ]
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Database\Controller\AdapterController' => \Database\Controller\AdapterController::class,
            'Database\Controller\SqlController' => \Database\Controller\SqlController::class,
            //'Database\Controller\Paginator' => \Database\Controller\PaginatorController::class,
        ],
        'aliases' => [
            'Adapter' => 'Database\Controller\AdapterController',
            'Sql' => 'Database\Controller\SqlController',
            //'Paginator' => 'Database\Controller\Paginator'  ,     
        ],
        'factories' => [
            TableGatewayController::class => InvokableFactory::class, 
            Controller\DoctrineORMController::class => 
            Controller\Factory\DoctrineORMControllerFactory::class,
            Controller\PaginatorController::class => 
            Controller\Factory\PaginatorControllerFactory::class,
        ],
    ],

    'view_manager' => array(
        'template_path_stack' => array(__DIR__ . '/../view',),
    ),
    'service_manager' => [
        'factories' => [
            Service\UsersManager::class => Service\Factory\UsersManagerFactory::class,
        ],
    ],

    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ] ,
    
];


?>