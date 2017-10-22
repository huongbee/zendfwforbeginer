<?php
namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use User\Entity\User;

class UserForm extends Form
{
    /**
     * Constructor.     
     */
    public function __construct($scenario = 'create',$entityManager = null, $user = null)
    {
        // Define form name
        parent::__construct('user-form');
        
        // Set POST method for this form    
        $this->setAttribute('method', 'POST');
        $this->setAttributes([
            'class'		=> 'form-horizontal',
            'id'		=> 'post-form',
        ]);

        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->user = $user;
        $this->addElements();
        $this->addInputFilter();         
    }
    
    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements() 
    {
                
        // Add "username" field
        $this->add([           
            'type'  => 'text',
            'name' => 'username',
            'attributes' => [
                'id' => 'username',
                'class' => 'form-control',
                'placeholder' => 'Nhập username'
            ],            
            'options' => [
                'label' => 'Username',
                'label_attributes'=>[
                    'for'		=> 'username',
                    'class'		=> 'col-sm-3 control-label',
                ],
            ]
            
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
        // Add "confirm_password" field
        $this->add([
            'type'  => 'password',
            'name' => 'confirm_password',
            'attributes' => [                
                'id' => 'confirm_password',
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Nhập lại mật khẩu',
                'label_attributes'=>[
                    'for'		=> 'confim_password',
                    'class'		=> 'col-sm-3 control-label',
                ],
            ],
        ]);
        
        // Add "fullname" field
        $this->add([
            'type'  => 'text',
            'name' => 'fullname',
            'attributes' => [                
                'id' => 'fullname',
                'class' => 'form-control',
                'placeholder' => 'Nhập họ tên'
            ],
            'options' => [
                'label' => 'Họ tên',
                'label_attributes'=>[
                    'for'		=> 'fullname',
                    'class'		=> 'col-sm-3 control-label',
                ],
            ],
        ]);

       
        // Add "birthday" field
        $this->add([
            'type'  => 'Date',
            'name' => 'birthdate',
            'attributes' => [                
                'id' => 'birthdate',
                'class' => 'form-control',
                'required' => false
            ],
            'options' => [
                'label' => 'Ngày sinh',
                'label_attributes'=>[
                    'for'		=> 'birthdate',
                    'class'		=> 'col-sm-3 control-label',
                ],
            ],
        ]);

        $this->add([            
            'type'  => 'select',
            'name' => 'gender',
            'options' => [
                'label' => 'Giới tính',
                'value_options' => [
                    'nữ' => 'Nữ',
                    'nam' => 'Nam',  
                    'other' => 'Khác'                  
                ],
                'label_attributes'=>[
                    'for'		=> 'gender',
                    'class'		=> 'col-sm-3 control-label',
                ],
            ],
            'attributes' => [                
                'id' => 'gender',
                'class' => 'form-control'
            ],
        ]);

        // Add "fullname" field
        $this->add([
            'type'  => 'text',
            'name' => 'address',
            'attributes' => [                
                'id' => 'address',
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Địa chỉ',
                'label_attributes'=>[
                    'for'		=> 'address',
                    'class'		=> 'col-sm-3 control-label',
                ],
            ],
        ]);

        // Add "fullname" field
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

        // Add "fullname" field
        $this->add([
            'type'  => 'text',
            'name' => 'phone',
            'attributes' => [                
                'id' => 'phone',
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Điện thoại',
                'label_attributes'=>[
                    'for'		=> 'phone',
                    'class'		=> 'col-sm-3 control-label',
                ],
            ],
        ]);
        $this->add([            
            'type'  => 'select',
            'name' => 'roles',
            'attributes' => [
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Role(s)',
                'label_attributes'=>[
                    'for'		=> 'roles',
                    'class'		=> 'col-sm-3 control-label',
                ],
                'value_options' => [
                    'editor' => 'Editor',
                    'guest' => 'Guest',  
                    'admin' => 'Admin' ,
                    'staff' => 'Staff'              
                ],
            ],
        ]);
        $this->add([            
            'type'  => 'select',
            'name' => 'status',
            'attributes' => [
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Status',
                'label_attributes'=>[
                    'for'		=> 'status',
                    'class'		=> 'col-sm-3 control-label',
                ],
                'value_options' => [
                    1 => 'Active',
                    0 => 'Not Active',                    
                ]
            ],
        ]);
        
        // Add the submit button
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [                
                'value' => 'Lưu',
                'id' => 'submitbutton',
                'class'=>'btn btn-primary'
            ],
        ]);
    }
    
    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter() 
    {
        
        $inputFilter = new InputFilter();        
        $this->setInputFilter($inputFilter);
        
        $inputFilter->add([
                'name'     => 'username',
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                    ['name' => 'StripNewlines'],
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 5,
                            'max' => 20,
                            'messages'	=>[
                                \Zend\Validator\StringLength::TOO_SHORT => 'Username ít nhất %min% ký tự',
                                \Zend\Validator\StringLength::TOO_LONG => 'Username không quá %max% ký tự',
                            ]
                        ],
                    ],
                    [
                        'name' => 'NotEmpty',
                        'options' => [
                            'messages'=>[
                                \Zend\Validator\NotEmpty::IS_EMPTY  => 'Username không được rỗng'
                            ]
                        ]
                    ]
                ],
            ]);
        
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
        
        // Add input for "full_name" field
        $inputFilter->add([
            'name'     => 'fullname',
            'required' => true,
            'filters'  => [                    
                ['name' => 'StringTrim'],
            ],                
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 5,
                        'max' => 100,
                        'messages'	=>[
							\Zend\Validator\StringLength::TOO_SHORT => 'Họ tên ít nhất %min% ký tự',
							\Zend\Validator\StringLength::TOO_LONG => 'Họ tên không quá %max% ký tự'
						]
                    ],
                ],
                [
                    'name' => 'NotEmpty',
                    'options' => [
                        'messages'=>[
                            \Zend\Validator\NotEmpty::IS_EMPTY  => 'Họ tên không được rỗng'
                        ]
                    ]
                ]
            ],
        ]);

        if ($this->scenario == 'create') {
            
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
            
            // Add input for "confirm_password" field
            $inputFilter->add([
                'name'     => 'confirm_password',
                'required' => true,
                'filters'  => [                        
                ],                
                'validators' => [
                    [
                        'name'    => 'Identical',
                        'options' => [
                            'token' => 'password',  
                            'messages'	=>[
                                \Zend\Validator\Identical::NOT_SAME  => 'Mật khẩu không giống nhau',
                                \Zend\Validator\Identical::MISSING_TOKEN => 'Missing token'
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
        }
    }
}