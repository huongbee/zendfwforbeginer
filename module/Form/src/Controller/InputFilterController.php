<?php
namespace Form\Controller;

use Zend\Mvc\Controller\AbstractActionController;
//use Zend\Filter;
use Zend\View\Model\ViewModel;
//use Zend\Validator\ValidatorInterface;

class InputFilterController extends AbstractActionController{

    public function indexAction(){
        $form	= new \Form\Form\Login();
    	//$form->setInputFilter(new LoginFilter());
        
        if($this->getRequest()->isPost()) 
        {
            $data = $this->params()->fromPost();            
            $form->setData($data);
            
            if($form->isValid()) {                
                $data = $form->getData();            
                print_r($data);
            }
            else{
                print_r($form->getMessages());
            }
        }
        $view = new ViewModel(['form'=> $form]);
        $view->setTemplate('form/inputfilter/index.phtml'); 
        
	    return $view;
    }
}