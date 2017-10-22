<?php
namespace Form\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Validator\ValidatorInterface;

class ValidatorChainController extends AbstractActionController{


    public function demoValiChainAction(){
        // Create a validator chain and add validators to it
        $validatorChain = new \Zend\Validator\ValidatorChain();
        $validatorChain->attach(
                            new \Zend\Validator\StringLength(array('min' => 6,
                                                                'max' => 30)))
                        ->attach(new \Zend\Validator\EmailAddress());

        $username = "huongnguyen@gmail.com";
       
        // Validate the username
        if ($validatorChain->isValid($username)) {
            echo $username;// username passed validation
        } else {
            // username failed validation; print reasons
            foreach ($validatorChain->getMessages() as $message) {
                echo "$message\n";
            }
        }
        return false;
    }
    public function demo2ValiChainAction(){
        $string = new \Zend\Validator\StringLength(array('min' => 6, 'max' => 30));
        $string->setMessages([
            \Zend\Validator\StringLength::TOO_SHORT =>
					'Dữ liệu nhập vào \'%value%\' quá ngắn, ít nhất %min% kí tự',
				\Zend\Validator\StringLength::TOO_LONG  =>
					'Dữ liệu nhập vào \'%value%\' quá dài, ít nhất %max% kí tự'
        ]);
        $email = new \Zend\Validator\EmailAddress();
        $email->setMessages([
            \Zend\Validator\EmailAddress::INVALID            => "Email không hợp lệ",
            \Zend\Validator\EmailAddress::INVALID_FORMAT     => "Email không đúng định dạng, ví dụ: local-part@hostname",
        ]);
        // Create a validator chain and add validators to it
        $validatorChain = new \Zend\Validator\ValidatorChain();
        $validatorChain->attach($string,
                                false, //break on fail
                                1) //độ ưu tiên:Ưu tiên càng cao thì việc kiểm tra càng sớm.
                        ->attach($email,true,2);

        $username = "huong@gmail.com";
       
        // Validate the username
        if ($validatorChain->isValid($username)) {
            echo $username;// username passed validation
        } else {
            // username failed validation; print reasons
            foreach ($validatorChain->getMessages() as $message) {
                echo "$message\n";
            }
        }
        return false;
    }
}