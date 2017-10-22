<?php

namespace Database\Controller;

use Zend\Db\Sql\Sql;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Database\Entity\Users;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

class PaginatorController extends AbstractActionController
{	
	private $entityManager;
    private $userManager;

    public function __construct($entityManager, $userManager)
    {
       $this->entityManager = $entityManager;
        $this->userManager = $userManager;
        
	}
	
	//\Zend\Paginator\Adapter\ArrayAdapter
	public function indexAction()
    {
    	$dataItems	= [
    			['name'=> 'Sản phẩm 1', 'price'=>20000],
    			['name'=> 'Sản phẩm 2', 'price'=>20000],
    			['name'=> 'Sản phẩm 3', 'price'=>30000],
    			['name'=> 'Sản phẩm 4', 'price'=>12000],
    			['name'=> 'Sản phẩm 5', 'price'=>50000],
    			['name'=> 'Sản phẩm 6', 'price'=>60000],
    			['name'=> 'Sản phẩm 7', 'price'=>40000],
    	];
    	
		$adapter	= new \Zend\Paginator\Adapter\ArrayAdapter($dataItems);
		$paginator	= new Paginator($adapter);
    	
    	$currentPage	= $this->params()->fromRoute('page', 1);
    	$paginator->setCurrentPageNumber($currentPage);
    	$paginator->setPageRange(2);
    	$paginator->setItemCountPerPage(2);
    	
		$view = new ViewModel([
			'paginator' => $paginator
		]);
		$view->setTemplate('paginator/index.phtml'); 
		return $view;
    }



	
    public function index02Action() 
    {
		$query = $this->entityManager->getRepository(Users::class)
				->findAllUsers();
		
		$adapter = new DoctrineAdapter(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
		
		$currentPage = $this->params()->fromQuery('page', 1);
		$paginator->setDefaultItemCountPerPage(2); 
		$paginator->setCurrentPageNumber($currentPage);
		//echo $paginator->count(); die;   
		//echo "<pre>";
		//print_r($query);
		//echo "</pre>";
        // Render the view template.
        $view = new ViewModel([
			'paginator' => $paginator
		]);
		$view->setTemplate('paginator/index02.phtml'); 
		return $view;
    }
    
    
}
