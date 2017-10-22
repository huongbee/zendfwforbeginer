<?php

namespace Form\Form;
use Zend\Form\Form;
use Zend\Form\Element;


class Validate extends Form{

    public function __construct($name = null){
		parent::__construct();
		
		// FORM Attribute
		$this->setName('validate-form');
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
			'class' => 'label-control',
        ])->setAttributes([
			'class' => 'form-control',
			'placeholder' => 'Enter your fullname'
		]);
		$this->add($textbox);

        $submit = new Element\Submit('submit');
        $submit->setAttributes([
			'value'=>'Send',
			'class' =>'btn btn-success'
		]);

        $this->add($submit);
    }
}
