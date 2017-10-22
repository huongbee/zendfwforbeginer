<?php
namespace Form\Form;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter;

class Login extends Form {
	
	public function __construct($name = null){
		parent::__construct();
	    
        $this->loginForm();
		$this->loginInputFilter();
	}

	private function loginForm(){
		$email = new Element\Email('email');
		$email->setLabel('Email: ')
				 ->setLabelAttributes([
                        'for'		=> 'email',
                        'class'		=> 'col-sm-3 control-label',
				    ]);
		$email->setAttributes([
			'class'			=> 'form-control',
            'id'			=> 'email',
            'placeholder'	=> 'example@mail.com',
		]);
		$this->add($email);
		
        

		$password = new Element\Password('password');
		$password->setLabel('Password: ')
				 ->setLabelAttributes([
                        'for'		=> 'password',
                        'class'		=> 'col-sm-3 control-label',
				    ]);
		$password->setAttributes([
			'class'			=> 'form-control',
            'id'			=> 'password',
            'placeholder'	=> 'Password',
		]);
		$this->add($password);
        
        

		$remember = new Element\Checkbox('remember');
        $remember->setLabel('Remember me')
               ->setLabelAttributes([
                    'for'		=> 'remember',
                ]);
        $remember->setAttributes([
            'id' =>'remember',
            'value'=>1,
            'required'=>false,            
        ]);
		$this->add($remember);
        
        
        $submit = new Element\Submit('submit');
		$submit->setAttributes([
			'value' => 'Login',
            'class'	=> 'btn btn-success btn-sm',
		]);
		$this->add($submit);
	}
	private function loginInputFilter(){
		$inputFilter = new InputFilter\InputFilter();
		$this->setInputFilter($inputFilter);
		$inputFilter->add([
			'name'     => 'email',
			'required' => true,
			'filters'  => [
			   	['name' => 'StringToLower'],                    
			],                
			'validators' => [
			   	[
					'name' => 'EmailAddress',
					'options' => [
						'messages' => [
							\Zend\Validator\EmailAddress::INVALID_FORMAT=>'Email không đúng định dạng',
						]                     
				  	],
			  	],
			],
		]);
		  
		$inputFilter->add([
			'name'     => 'password',
			'required' => true,
			'filters'  => [
				['name' => 'StringTrim'],
				['name' => 'StripTags'],
				['name' => 'StripNewlines'],
				['name' => 'StringToLower'] 
			],                
			'validators' => [
				[
					'name' => 'StringLength',
					'options' => [
						'min' => 6,
						'max' => 20,
						'messages'	=>[
							\Zend\Validator\StringLength::TOO_SHORT => 'Mật khẩu ít nhất %min% ký tự',
							\Zend\Validator\StringLength::TOO_LONG => 'Mật khẩu không quá %max% ký tự'
						]
					],
				],
			],
		]);
	}
}