<?php
namespace Form\Form;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter;


    //V35
//class UploadFile extends Form {

    // public function __construct($name = null){
	// 	parent::__construct('Profile');
        
    //     $this->add(array(
    //         'name' => 'fileupload',
    //         'attributes' => array(
    //             'type'  => 'file',
    //         ),
    //         'options' => array(
    //             'label' => 'Chọn file',
    //         ),
    //     )); 
         
    // }

//}

//Upload file with validate
use Zend\Validator\File\Size; //kb
use Zend\Validator\File\MimeType;
use Zend\Validator\File\ImageSize; //width and height
use Zend\Validator\File\NotExists;

class UploadFile extends Form{
    //V36

    public function __construct($name = null){
		parent::__construct('Profile');
        
        $this->add(array(
            'name' => 'fileupload',
            'attributes' => array(
                'type'  => 'file',
                'required' => 'required',
                'multiple' => true, //video sau
            ),
            'options' => array(
                'label' => 'Chọn file',
            ),
        )); 
        $this->uploadInputFilter();
         
    }
    public function uploadInputFilter()
    {
        // File Input
        $fileInput = new InputFilter\FileInput('fileupload');
        
        $fileInput->setRequired(true);

        //validate
        
        $size = new Size(['max' => 2048000]); //200kb
        $size->setMessages([
            Size::TOO_BIG => 'File quá lớn'
        ]);

        $mimeType = new MimeType('image/png,image/x-png,image/jpeg'); 
        $mimeType->setMessages([
            MimeType::FALSE_TYPE   => "Kiểu file '%type%' không được phép chọn",
            MimeType::NOT_DETECTED => "Kiểu file không đúng"
        ]);

        $imageSize = new ImageSize(['maxWidth' => 1000, 'maxHeight' => 1000]); 
        $imageSize->setMessages([
            ImageSize::WIDTH_TOO_BIG    => "Chiều rộng file '%width%' vượt quá giới hạn '%maxwidth%'",
            ImageSize::HEIGHT_TOO_BIG   => "Chiều cao file '%height%' vượt quá giới hạn '%maxheight%'",
        ]);

        $fileInput->getValidatorChain()
                    ->attach($size,
                            true, //break on fail
                            3) //độ ưu tiên:Ưu tiên càng cao thì việc kiểm tra càng sớm.
                    ->attach($mimeType,true,2)
                    ->attach($imageSize,true,1);
        
        
        $inputFilter = new InputFilter\InputFilter();
                    
        $inputFilter->add($fileInput);

        $this->setInputFilter($inputFilter);
    }
}
