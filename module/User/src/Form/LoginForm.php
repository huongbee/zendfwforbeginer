<?php
namespace User\Form;

use Zend\Form\Form;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilter;

/**
 * This form is used to collect user's login, password and 'Remember Me' flag.
 */
class LoginForm extends Form
{
    /**
     * Constructor.     
     */
    public function __construct()
    {
        // Define form name
        parent::__construct('login-form');
     
        // Set POST method for this form
        $this->setAttribute('method', 'post');
                
        $this->addElements();
        $this->addInputFilter();          
    }
    
    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements() 
    {
        // Add "email" field
        $this->add([
            'type'  => 'email',
            'name' => 'email',
            'attributes' => [                
                'id' => 'email',
                'class' => 'form-control',
                'placeholder' => 'example@domain.com'
            ],
            'options' => [
                'label' => 'Email',
                'label_attributes'=>[
                    'for'		=> 'email',
                    'class'		=> 'col-sm-3 control-label',
                ],
            ],
        ]);
        
        // Add "password" field
        $this->add([
            'type'  => 'password',
            'name' => 'password',
            'attributes' => [                
                'id' => 'password',
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Mật khẩu',
                'label_attributes'=>[
                    'for'		=> 'password',
                    'class'		=> 'col-sm-3 control-label',
                ],
            ],
        ]);
        
        // Add "remember_me" field
        $this->add([            
            'type'  => 'checkbox',
            'name' => 'remember_me',
            'attributes' => [
                'id' =>'remember_me',
            ],
            'options' => [
                'label' => 'Remember me',
                'label_attributes'=>[
                    'for'		=> 'remember_me'
                ],
            ],
        ]);
        
        // Add "redirect_url" field
        $this->add([            
            'type'  => 'hidden',
            'name' => 'redirect_url'
        ]);
        
        // Add the CSRF field
        $this->add([
            'type' => 'csrf',
            'name' => 'csrf',
            'options' => [
                'csrf_options' => [
                'timeout' => 600
                ]
            ],
        ]);
        
        // Add the Submit button
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [                
                'value' => 'Login',
                'id' => 'submit',
                'class'=>'btn btn-primary'
            ],
        ]);
    }
    
    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter() 
    {
        // Create main input filter
        $inputFilter = new InputFilter();        
        $this->setInputFilter($inputFilter);
                
        // Add input for "email" field
        $inputFilter->add([
            'name'     => 'email',
            'required' => true,
            'filters'  => [
                ['name' => 'StringTrim'],                    
            ],                
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 10,
                        'max' => 128
                    ],
                ],
                [
                    'name' => 'EmailAddress',
                    'options' => [
                        'allow' => \Zend\Validator\Hostname::ALLOW_DNS,
                        'useMxCheck'    => false,  
                        'messages' => [
							\Zend\Validator\EmailAddress::INVALID_FORMAT=>'Email không đúng định dạng',
						]                            
                    ],
                ],
                [
                    'name' => 'NotEmpty',
                    'options' => [
                        'messages'=>[
                            \Zend\Validator\NotEmpty::IS_EMPTY  => 'Email không được rỗng'
                        ]
                    ]
                ]
                // [
                //     'name' => UserExistsValidator::class,
                //     'options' => [
                //         'entityManager' => $this->entityManager,
                //         'user' => $this->user
                //     ],
                // ],                    
            ],
        ]);
        // Add input for "password" field
        $inputFilter->add([
            'name'     => 'password',
            'required' => true,
            'filters'  => [                        
            ],                
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 6,
                        'max' => 64,
                        'messages'	=>[
                            \Zend\Validator\StringLength::TOO_SHORT => 'Mật khẩu ít nhất %min% ký tự',
                            \Zend\Validator\StringLength::TOO_LONG => 'Mật khẩu không quá %max% ký tự'
                        ]
                    ],
                ],
                [
                    'name' => 'NotEmpty',
                    'options' => [
                        'messages'=>[
                            \Zend\Validator\NotEmpty::IS_EMPTY  => 'Mật khẩu không được rỗng'
                        ]
                    ]
                ]
            ],
        ]);
        
        // Add input for "remember_me" field
        $inputFilter->add([
            'name'     => 'remember_me',
            'required' => false,
            'filters'  => [                    
            ],                
            'validators' => [
                [
                    'name'    => 'InArray',
                    'options' => [
                        'haystack' => [0, 1],
                        'messages' =>[
                            \Zend\Validator\InArray::NOT_IN_ARRAY  => 'Dữ liệu không hợp lệ',
                        ]
                    ]
                ],
            ],
        ]);
        
        // Add input for "redirect_url" field
        $inputFilter->add([
            'name'     => 'redirect_url',
            'required' => false,
            'filters'  => [
                ['name'=>'StringTrim']
            ],                
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 0,
                        'max' => 2048,
                        'messages'	=>[
                            \Zend\Validator\StringLength::TOO_SHORT => 'Mật khẩu ít nhất %min% ký tự',
                            \Zend\Validator\StringLength::TOO_LONG => 'Mật khẩu không quá %max% ký tự'
                        ]
                    ]
                ],
            ],
        ]);
    }        
}

