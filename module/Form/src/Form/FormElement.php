<?php

namespace Form\Form;
use Zend\Form\Form;
use Zend\Form\Element;


class FormElement extends Form{
	public function __construct($name = null){
		parent::__construct();

		//Textbox - Hidden - Number - Email - Password
		
		// FORM Attribute
		$this->setName('contact-form');
		$this->setAttribute('action', '#');
		$this->setAttribute('method', 'POST');
		$this->setAttributes([
				'class'		=> 'form-horizontal',
				'id'		=> 'my-form-id',
				'enctype'	=> 'multipart/form-data',
				'style'		=> 'margin: 20px',
		]);

		
		// \Zend\Form\Element\Text
		$textbox = new Element\Text('my-textbox');
		$textbox->setLabel('Fullname: ')
			->setLabelAttributes([
			'class' => 'control-label'
		]);
		$textbox->setAttributes([
			'class' => 'form-control',
			'placeholder' => 'Enter Your Name',
			'required' => 'true'
		]);
		$this->add($textbox);
		
		//\Zend\Form\Element\Hidden
		$hidden = new Element\Text('input-hidden');
		$hidden->setAttributes([
			'type'=>'hidden',
			'value' => 'zend framework'
		]);
		$this->add($hidden);
		// Zend\Form\Element\Number
		$number = new Element\Number('my-number');
		$number->setLabel('Age: ')
				->setLabelAttributes([
					'class'=>'control-label'
				])
				->setAttributes([
					'min'=>0,
					'max'=>10,
					'value'=>2,
				]);
		$this->add($number);

		// Zend\Form\Element\email
		$email = new Element\Email('email');
		$email->setLabel('Your email address: ');
		$email->setLabelAttributes([
			'class' => 'control-label'
		]);
		$email->setAttributes([
			'class' => 'form-control',
			'style' => 'width:300px',
			'required' => 'true',
			'placeholder'=>'example@gmail.com'
		]);
		$this->add($email);
		
		// Zend\Form\Element\Password
		$password = new Element\Password('my-password');
		$password->setLabel('Password: ')
				->setLabelAttributes([
					'class'=>'control-label'
				]);
		$password->setAttributes([
			'class'=>'form-control',
			'id' => 'password',
			'style' =>'width:300px'
		]);
		$this->add($password);

		$radio = new Element\Radio('my-radio');
		$radio->setLabel('Giới tính: ')
				->setAttributes([
					'value'=>'nam' //checked for value_options
				])
				->setValueOptions([
						'nam'=>'Men',
						'nu'=>'Girl'
					
				]);
		$this->add($radio);


		// Zend\Form\Element\Textarea
		$this->add([
				'name'			=> 'my-textarea',
				'type'			=> 'Textarea',
				'attributes'	=> array(
						'value'		=> 10,
 						'rows'		=> 4,
						'cols'		=> 50,
						'style'		=> 'resize:none;min-width:300px;max-width:300px;min-height:100px;max-height:100px;'
						
				),
		]);
		
		
		
		//SelectOption
		$select = new Element\Select('select');
		$select->setLabel('Tỉnh: ')
				->setAttributes([
					'class'=>'form-control'
				])
				->setValueOptions([
						'hanoi' => 'Hà Nội',
						'haiphong' => 'Hải Phòng'
			
				]);
		$this->add($select);
	
		
		// Zend\Form\Element\File
		$this->add([
			'name'			=> 'my-file',
			'type'			=> 'File',
			'attributes'	=> array(
					'multiple'	=> true
			),
		]);
	
		// Zend\Form\Element\Checkbox
		$checbox	= new Element\Checkbox('my-checkbox');
		$checbox->setLabel('Checkbox: ')
				->setChecked(true);
		$this->add($checbox);
		

		$multiCheckbox 	= new Element\MultiCheckbox('my-multicheckbox');
		$multiCheckbox->setLabel('Multi Checkbox: ')
					  ->setAttributes(array(
					  		'class'		=> 'my-class',
					  		'id'		=> 'abc-id',
					  		'value'		=> array('php', 'ios'), //checked for value_options
					  ))
					  ->setValueOptions(array(
					  				'php'	=> 'Learn PHP',
					  				'ios'	=> 'Learn iOS',
					  				'nodejs'=> 'NodeJS',
					  		)
					);
		$this->add($multiCheckbox);
	
		// Zend\Form\Element\Color
		$this->add(array(
				'name'			=> 'my-color',
				'type'			=> 'Color',
		));
		
		// Zend\Form\Element\Date
		$this->add(array(
				'name'			=> 'my-date',
				'type'			=> 'Date',
				'options'		=> array(
						'label'	=> 'Date'
				),
				'attributes'	=> array(
						'min'		=> '2000-01-01',
						'max'		=> '2010-01-01',
				),
		));
		
		// Zend\Form\Element\Range
		$this->add(array(
				'name'			=> 'my-range',
				'type'			=> 'Range',
				'options'		=> array(
						'label'	=> 'Range: '
				),
				'attributes'	=> array(
						'min'		=> '1',
						'max'		=> '5',
						'class' =>'form-control'
				),
		));
		
	

		/*$this->add([
				'name'			=> 'my-multicheckbox',
				'type'			=> 'MultiCheckbox',
				'attributes'	=> array(
						'class'		=> 'abc-class',
				),
				'options'	=> array(
						'value_options'	=> array(
								array(
									'value'		=> 'php',
									'label'		=> 'PHP Programming',
									'selected'	=> false,
									'label_attributes' => array(
											'class' => 'label-PHP',
									),
									'attributes' => array(
											'class' => 'input-php',
									),
								),
								array(
									'value'		=> 'zend',
									'label'		=> 'Zend 2 Programming',
									'selected'	=> true,
								),
								array(
									'value'		=> 'joomla',
									'label'		=> 'Joomla Programming',
									'selected'	=> true,
								),
						)
				),
		]);*/
		
		// Zend\Form\Element\Submit
		$this->add([
			'name'			=> 'my-submit',
			'type'			=> 'Submit',
			'attributes'	=> array(
					'value'			=> 'Submit form',
					'class' => 'btn btn-success'
			),
		]);
	
		$this->add(array(
			'type' => 'Zend\Form\Element\Csrf',
			'name' => 'csrf',
			'options' => array(
					'csrf_options' => array(
							'timeout' => 600
					)
			)
		));
		// Zend\Form\Element\Button
		$this->add([
				'name'			=> 'my-button',
				'type'			=> 'button',
				'attributes'	=> array(
					'type' => 'reset',
					'class' => 'btn btn-danger'
				),
				'options'	=> array(
						'label'		=> 'Reset form'
				),
		]);
		
	
		// Add the CAPTCHA field
        $this->add([
            'type' => 'captcha',
            'name' => 'captcha',
            'options' => [
                'label' => 'Human check',
                'captcha' => [
                    'class' => 'Image',
                    'imgDir' => 'public/img/captcha',
                    'suffix' => '.png',
                    'imgUrl' => '../img/captcha/',
                    'imgAlt' => 'CAPTCHA Image',
                    'font' => APPLICATION_PATH .'/data/font/GiveYouGlory.ttf',
                    'fsize' => 50,
                    'width' => 350,
                    'height' => 100,
                    'expiration' => 100,
                    'dotNoiseLevel' => 100,
                    'lineNoiseLevel' => 3
                ],
            ],
        ]);
		
	}
}



?>