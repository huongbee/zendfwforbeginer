<?php

namespace Form;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Form\Controller\FormElementController;
use Form\Controller\ValidateController;
use Form\Controller\ValidatorChainController;
use Form\Controller\InputFilterController;
use Form\Controller\FileController;
/*
return [
    'controllers' => [
        'factories' => [
            Controller\FormControllerElement::class => InvokableFactory::class,
        ],
    ],

    'router' => [
        'routes' => [
            'form' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/form[/:action]', //[/:action] video sau
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ],
                    'defaults' => [
                        'controller' => Controller\FormControllerElement::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => array(
        'template_path_stack' => array(__DIR__ . '/../view',),
    ),
];
*/

return [
    
    'router' => [
        'routes' => [
            'form' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/form', //[/:action] video sau
                    'defaults' => [
                        '__NAMESPACE__' => 'Form\Controller',
                        'controller' => 'FormElement',
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
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Form\Controller\FormElement' => \Form\Controller\FormElementController::class,
            'Form\Controller\Validate' => \Form\Controller\ValidateController::class,
            'Form\Controller\ValidatorChain' => \Form\Controller\ValidatorChainController::class,
            'Form\Controller\InputFilter' => \Form\Controller\InputFilterController::class,
            'Form\Controller\File' => \Form\Controller\FileController::class,
            
        ],
        'aliases' => [
            'FormElement' => 'Form\Controller\FormElement',
            'Validate'=>'Form\Controller\Validate',
            'ValidatorChain' => 'Form\Controller\ValidatorChain',
            'InputFilter' => 'Form\Controller\InputFilter',
            'File'=>'Form\Controller\File'
        ]
    ],

    'view_manager' => array(
        'template_path_stack' => array(__DIR__ . '/../view',),
    ),
];


?>