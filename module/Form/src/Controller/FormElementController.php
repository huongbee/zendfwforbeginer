<?php
namespace Form\Controller;

use Form\Form\FormElement;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FormElementController extends AbstractActionController{

	//basic: in view use formRow
	public function indexAction(){
		
    	$view = new ViewModel(array('form'=>new \Form\Form\FormElement()));
	    $view->setTemplate('form/element/index.phtml'); 
	    return $view;
	}
	

	// in view use FormElement
	public function index02Action(){
		//video sau:
		// if($this->getRequest()->isPost()){
    	// 	$paramsPost	= $this->params()->fromPost();
    	// 	echo'<pre>';
    	// 	print_r($paramsPost);
		// 	echo'</pre>';
			
		// } 
		
    	$view = new ViewModel(array('form02'=>new \Form\Form\FormElement()));
	    $view->setTemplate('form/element/index02.phtml'); 
	    return $view;
	}
	
}

?>