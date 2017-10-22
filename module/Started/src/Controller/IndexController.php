<?php
namespace Started\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
       
        $view = new ViewModel();
        $view->setTemplate('home');
        return $view;
    }
    public function registerAction()
    {
        $view = new ViewModel();
        $view->setTemplate('register');
        return $view;
    }
    public function loginAction()
    {
        $view = new ViewModel();
        $view->setTemplate('login');
        return $view;
    }
}