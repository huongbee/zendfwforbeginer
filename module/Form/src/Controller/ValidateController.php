<?php
namespace Form\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Validator\ValidatorInterface;

class ValidateController extends AbstractActionController{

	
    public function stringAction(){
		//1: StringLength
		$validator = new \Zend\Validator\StringLength(array('max' => 6));
		//$validator->setMin(2);
		$value = "Test"; // returns true
		//$value = "Testing"; // returns false


		//2: Limiting a string
		$validator = new \Zend\Validator\StringLength(array('min' => 3, 'max' => 30));
		$value = "."; // returns false
		//$value = "Test"; // returns true
		//$value = "Testing"; // returns true
		$validator = new \Zend\Validator\StringLength(3,5);
		//video custom error mes
		
			// $validator->setMessages( array(
			// 	\Zend\Validator\StringLength::TOO_SHORT =>
			// 		'Dữ liệu nhập vào \'%value%\' quá ngắn, ít nhất %min% kí tự',
			// 	\Zend\Validator\StringLength::TOO_LONG  =>
			// 		'Dữ liệu nhập vào \'%value%\' quá dài, ít nhất %max% kí tự'
			// ));
		//end
		$value = ".";
		if ($validator->isValid ( $value )) {
    		echo $value;
		}
		else{
			$messages = $validator->getMessages();
			echo '<pre>';
    		print_r($messages);
    		echo '</pre>';
			//echo current($messages);
		}
    
    	return false;
    }


    // Yêu cầu dữ liệu kiểu number và nằm trong một khoảng nào đó
    public function numberAction()
    {
    	// Case 01	
    	$validator	= new \Zend\Validator\Between(2,5, false);
    	
    	// Case 02
    	// $validator	= new \Zend\Validator\Between(array(
    	// 		'min'		=> 2,
    	// 		'max'		=> 5,
    	// 		'inclusive'	=> false,
		// ));
		
		// NOT_BETWEEN        => "The input is not between '%min%' and '%max%', inclusively",
        // NOT_BETWEEN_STRICT => "The input is not strictly between '%min%' and '%max%'",
        // VALUE_NOT_NUMERIC  => "The min ('%min%') and max ('%max%') values are numeric, but the input is not",
        // VALUE_NOT_STRING   => "The min ('%min%') and max ('%max%') values are non-numeric strings, "
        // .    "but the input is not a string",
    	
    	$value		= 6;		// not Ok
    	$value		= 3;		// Ok
    	$value		= 'as';		// not Ok
    	$value		= '3';		// Ok
    	$value		= 'a3a';	// not Ok
    	$value		= 5;		// Ok
    	
    	if(!$validator->isValid($value)){
    		$message	= $validator->getMessages();
    		echo current($message);
    		echo '<pre>';
    		print_r($message);
    		echo '</pre>';
    	}
    	
    	return false;
	}
	
	public function dateAction()
    {
		//1 FORMAT_DEFAULT = 'Y-m-d';
		$validator = new \Zend\Validator\Date();
		
		$value = "2017-2-25"; 
		//value = "2017/2/25";   // returns false
		$value = "12-1-2017"; // returns false

		//2.1 Self defined date validation
		$validator 	= new \Zend\Validator\Date(array(
			'format'	=> 'Y/m/d',
		));


		// INVALID      => "Invalid type given. String, integer, array or DateTime expected",
        // INVALID_DATE => "The input does not appear to be a valid date",
		// FALSEFORMAT  => "The input does not fit the date format '%format%'",
		
		$value = "12-1-2017"; //not ok
		$value = "12.1.2017"; //not ok
		$value = "2017/2/25"; //ok

		//2.2: 
		// $validator = new \Zend\Validator\Date(array('format' => 'Y'));
		// $value = '2017';//ok
		// $value = '17'; //ok
		// $value = 'May';


		if(!$validator->isValid($value)){
    		$message	= $validator->getMessages();
    		//echo current($message);
    		echo '<pre>';
    		print_r($message);
    		echo '</pre>';
    	}
		return false;
	}


	public function emailAction(){
		$validator 	= new \Zend\Validator\EmailAddress(array('domain' => false));
    
		$email = 'huongnguyenak96@gmail.com';
		$email = 'huongnguyenak96@gmailcom';
	
		// INVALID            => "Invalid type given. String expected",
        // INVALID_FORMAT     => "The input is not a valid email address. Use the basic format local-part@hostname",
        // DOT_ATOM           => "'%localPart%' can not be matched against dot-atom format",
        // INVALID_HOSTNAME   => "'%hostname%' is not a valid hostname for the email address",
        // INVALID_MX_RECORD  => "'%hostname%' does not appear to have any valid MX or A records for the email address",
        // INVALID_SEGMENT    => "'%hostname%' is not in a routable network segment. The email address should not be resolved from public network",
        // QUOTED_STRING      => "'%localPart%' can not be matched against quoted-string format",
        // INVALID_LOCAL_PART => "'%localPart%' is not a valid local part for the email address",
        // LENGTH_EXCEEDED    => "The input exceeds the allowed length",
		if ($validator->isValid($email)) {
			// email appears to be valid
			echo $email;
		} else {
			// email is invalid; print the reasons
			foreach ($validator->getMessages() as $message) {
				echo "$message<br>";
			}
		}
    
    	return false;
    }

	// Yêu cầu dữ liệu thuộc kiểu số tự nhiên
    public function digitsAction(){
    	$validator 	= new \Zend\Validator\Digits ();
		
		//NOT_DIGITS   => "The input must contain only digits",
        //STRING_EMPTY => "The input is an empty string",
        //INVALID      => "Invalid type given. String, integer or float expected",

    	$value = '1989'; // Ok
     	$value = 0; 	// Ok
     	$value = 2.3; 	// Not Ok
     	$value = -2; 	// Not Ok
     	$value = -2.3; // Not Ok
     	$value = '2a'; 	// Not Ok
     	//$value = 1989; 	// Ok
    
		if ($validator->isValid ( $value )) {
    		echo $value;
		}
		else{
			$messages = $validator->getMessages();
			echo '<pre>';
    		print_r($messages);
    		echo '</pre>';
			//echo current($messages);
		}
    
    	return false;
	}

	// >
	public function greaterThanAction(){
		//$validator  = new \Zend\Validator\GreaterThan(array('min' => 10));
		

		//inclusive : cho phép >=
		$validator  = new \Zend\Validator\GreaterThan(array(
			'min' => 10,
			'inclusive' =>true
		));
		//'inclusive' =>false->NOT_GREATER, 'inclusive' =>true->NOT_GREATER_INCLUSIVE
			// $validator->setMessage(
			// 	"Vui lòng nhập giá trị lớn hơn hoặc bằng %min%",
			// 	\Zend\Validator\GreaterThan::NOT_GREATER_INCLUSIVE
			// );
		//
		$value  = 9;
		if ($validator->isValid($value)) {
			echo $value;
		} else {
			foreach ($validator->getMessages() as $message) {
				echo "$message<br>";
			}
		}
		return false;
	}
	public function lessThanAction(){
		
		$validator 	= new \Zend\Validator\LessThan(array(
				'max' 		=> 50,
				'inclusive' => false
		));
		//NOT_LESS_INCLUSIVE , NOT_LESS
			// $validator->setMessage(
			// 		"Vui lòng nhập giá trị bé hơn hoặc bằng %max%",
			// 		\Zend\Validator\LessThan::NOT_LESS_INCLUSIVE
			// 	);
		//
		$value		= 51;
	
		if (!$validator->isValid($value)) {
			$messages	= $validator->getMessages();
			echo current($messages);
		}
	
		return false;
	}


	public function inArrayAction(){
		//1
		//C1
		// $validator = new \Zend\Validator\InArray(array(
		// 	'haystack' => array(
		// 					'value1', 
		// 					'value2',
		// 					'value3'
		// 				)
		// 	));


		//C2:
		$validator = new \Zend\Validator\InArray();
		$validator->setHaystack(array('value1', 'value2','valueN'));
		
		//2
		$validator = new \Zend\Validator\InArray(
			array(
				'haystack' => array(
					'firstDimension' => array('value1', 'value2','valueN'),
					'secondDimension' => array('foo1', 'foo2','fooN')),
				'recursive' => true //cài đặt đệ quy, mặc định false
			)
		);
		//self::NOT_IN_ARRAY => 'The input was not found in the haystack',
		$value = "value1"; //false
		if ($validator->isValid($value)) {
			echo $value;
		} else {
			$messages	= $validator->getMessages();
			echo current($messages);
		}
		return false;
	}


	//NotEmpty
	public function notEmptyAction(){
		//1
		$validator = new \Zend\Validator\NotEmpty();
		$value = '';
		$value  = 0;

		//2 Returns false on 0
		$validator = new \Zend\Validator\NotEmpty(\Zend\Validator\NotEmpty::INTEGER);
		$value = 0;
		//$value = '0';

		// Returns false on 0 or '0'
		//C1
		$validator = new \Zend\Validator\NotEmpty(
			\Zend\Validator\NotEmpty::INTEGER + \Zend\Validator\NotEmpty::ZERO
		);
		$value = 0;
		$value = '0';

		//C2
		// Returns false on 0 or '0'
		$validator = new \Zend\Validator\NotEmpty(array(
			'integer',
			'zero',
		));
		$value = 0;
		$value = '0';

		// self::IS_EMPTY => "Value is required and can't be empty",
        // self::INVALID  => "Invalid type given. String, integer, float, boolean or array expected",
		
		if ($validator->isValid($value)) {
			echo $value;
		} else {
			$messages	= $validator->getMessages();
			echo current($messages);
		}
		return false;
	}


	public function RegexAction(){
		// self::INVALID   => "Invalid type given. String, integer or float expected",
        // self::NOT_MATCH => "The input does not match against pattern '%pattern%'",
        // self::ERROROUS  => "There was an internal error while using the pattern '%pattern%'",
		//1
		$validator = new \Zend\Validator\Regex(array(
				'pattern' => '/^Test/'
			));
		
		$value = "Test"; // returns true
		$value = "Testing"; // returns true
		$value = "Course"; // returns false

		//2
		$pattern = '/^[\d]{4}$/'; //\d:kiểm tra là số
		$validator = new \Zend\Validator\Regex($pattern);
		$value = 1234;
		$value = 123456;
		$value = '12c22';

		$pattern = "/^[a-zA-Z ]*$/"; //cho phép kí tự và khoảng trắng
		$validator = new \Zend\Validator\Regex($pattern);
		$value = 1234;
		//$value = '#3cvv';
		$value = 'Zend Fw';


		if ($validator->isValid($value)) {
			echo $value;
		} else {
			$messages	= $validator->getMessages();
			echo current($messages);
		}
		return false;
	}


	//file exits
	public function fileExitsAction(){
		//DOES_NOT_EXIST => "File does not exist",
		$validator = new \Zend\Validator\File\Exists();
		$value		= FILES_PATH. '/testFile1.txt';
		
		if ($validator->isValid($value)) {
			print_r('has File');
		}		
		else{
			$messages	= $validator->getMessages();
			echo current($messages);
		}
		return false;
	}

	public function notExitsAction(){
		//self::DOES_EXIST => "File exists",
		$validator = new \Zend\Validator\File\NotExists();
		$value		= FILES_PATH. '/1.jpg';
		
		if ($validator->isValid($value)) {
			print_r('File not exits in folder');
		}		
		else{
			$messages	= $validator->getMessages();
			echo current($messages);
		}
		return false;
	}


	//file Extension
	public function fileExtensionAction(){
		// self::FALSE_EXTENSION => "File has an incorrect extension",
        // self::NOT_FOUND       => "File is not readable or does not exist"
		//Allow files with 'php' or 'exe' extensions
		$validator = new \Zend\Validator\File\Extension('php,exe');
		$value		= FILES_PATH. '/testFile.txt';
		
		if ($validator->isValid($value)) {
			print_r('has File');
		}		
		else{
			$messages	= $validator->getMessages();
			echo current($messages);
		}
		return false;
	}


	//image size
	public function imageSizeAction(){
		// self::TOO_BIG   => "Maximum allowed size for file is '%max%' but '%size%' detected",
        // self::TOO_SMALL => "Minimum expected size for file is '%min%' but '%size%' detected",
        // self::NOT_FOUND => "File is not readable or does not exist",
		//1 Is image size between 320x200 (min) and 640x480 (max)?
		//C1
		$validator = new \Zend\Validator\File\ImageSize(320, 200, 640, 480);

		//C2:
		$validator = new \Zend\Validator\File\ImageSize(array(
			'minWidth' => 320, 'minHeight' => 200,
			'maxWidth' => 640, 'maxHeight' => 480,
		));

		//2 Is image size >= 320x200?
		$validator = new \Zend\Validator\File\ImageSize(array(
			'minWidth' => 320, 'minHeight' => 200,
		));

		//3 Is image size <= 640x480?
		$validator = new \Zend\Validator\File\ImageSize(array(
			'maxWidth' => 640, 'maxHeight' => 480,
		));

		$value		= FILES_PATH. '/images.jpg';
		
		if ($validator->isValid($value)) {
			print_r('Yes');
		}		
		else{
			$messages	= $validator->getMessages();
			echo current($messages);
		}
		return false;
	}

	//check file nén IsCompressed zip hoặc gzip
	public function isCompressedAction(){
		// self::FALSE_TYPE   => "File is not compressed, '%type%' detected",
        // self::NOT_DETECTED => "The mimetype could not be detected from the file",
        // self::NOT_READABLE => "File is not readable or does not exist",
		$validator = new \Zend\Validator\File\IsCompressed();
		$value		= FILES_PATH. '/test.zip';
		
		if ($validator->isValid($value)) {
			print_r('Yes');
		}		
		else{
			$messages	= $validator->getMessages();
			echo current($messages);
		}
		return false;
	}

	//IsImage
	public function isImageAction(){
		// self::FALSE_TYPE   => "File is no image, '%type%' detected",
        // self::NOT_DETECTED => "The mimetype could not be detected from the file",
        // self::NOT_READABLE => "File is not readable or does not exist",
		$validator = new \Zend\Validator\File\IsImage();
		$value		= FILES_PATH. '/11.jpg';
		
		if ($validator->isValid($value)) {
			print_r('Yes');
		}		
		else{
			$messages	= $validator->getMessages();
			echo current($messages);
		}
		return false;
	}

	//MimeType
	public function mimeTypeAction(){
		// self::FALSE_TYPE   => "File has an incorrect mimetype of '%type%'",
        // self::NOT_DETECTED => "The mimetype could not be detected from the file",
        // self::NOT_READABLE => "File is not readable or does not exist",
		//1 Only allow 'gif' or 'jpg' files
		$validator = new \Zend\Validator\File\MimeType('image/gif,image/jpeg');

		//2 restrict an entire group of types
		$validator = new \Zend\Validator\File\MimeType(array('image', 'audio'));
		$value		= FILES_PATH. '/2.jpg';
		
		if ($validator->isValid($value)) {
			print_r('Yes');
		}		
		else{
			$messages	= $validator->getMessages();
			echo current($messages);
		}
		return false;
	}

	//Size
	public function sizeAction(){
		//1 Limit the file size to 40000 bytes
		$validator = new \Zend\Validator\File\Size(40000);

		//2 Limit the file size to between 10kB and 4MB
		$validator = new \Zend\Validator\File\Size(array(
			'min' => '10kB', 'max' => '4MB'
		));

		// self::WIDTH_TOO_BIG    => "Maximum allowed width for image should be '%maxwidth%' but '%width%' detected",
        // self::WIDTH_TOO_SMALL  => "Minimum expected width for image should be '%minwidth%' but '%width%' detected",
        // self::HEIGHT_TOO_BIG   => "Maximum allowed height for image should be '%maxheight%' but '%height%' detected",
        // self::HEIGHT_TOO_SMALL => "Minimum expected height for image should be '%minheight%' but '%height%' detected",
        // self::NOT_DETECTED     => "The size of image could not be detected",
        // self::NOT_READABLE     => "File is not readable or does not exist",
		$value		= FILES_PATH. '/2.jpg';
		
		if ($validator->isValid($value)) {
			print_r('File thỏa mãn');
		}		
		else{
			$messages	= $validator->getMessages();
			echo current($messages);
		}
		return false;
	}



	//==============================V30==========================================
	public function pwStrengthAction(){
		
		$validator = new \Zend\Validator\PasswordStrength();
		$value = "Test2"; // returns true
		
		if ($validator->isValid ( $value )) {
    		echo $value;
		}
		else{
			$messages = $validator->getMessages();
			echo '<pre>';
    		print_r($messages);
    		echo '</pre>';
			//echo current($messages);
		}
    	return false;
	}
	

	public function confirmPasswordAction(){
		
		$validator = new \Zend\Validator\ConfirmPassword();

		$password = '123456';
		$confirmPassword = '12345xx6';
    	$validator->setConfirmPassword($confirmPassword);
    	
    	if (!$validator->isValid($password)) {
    		$messages	= $validator->getMessages();
    		echo current($messages);
    	}
    	return false;
    }
	
}
