<?php

namespace Database\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Database\Entity\Users;
use Database\Form\UserForm;
use Database\Form\PasswordChangeForm;

class DoctrineORMController extends AbstractActionController{
    
    private $entityManager;
    private $userManager;

    public function __construct($entityManager, $userManager)
    {
       $this->entityManager = $entityManager;
        $this->userManager = $userManager;
        
    }

    public function indexAction() 
    {
        $users = $this->entityManager->getRepository(Users::class)
                // ->findBy(
                //     ['gender'=>'nam'], 
                //     ['id'=>'DESC'], 2)
                ->findAll()
                ;
        //findAll();
        //find($id)
        //findBy($criteria, $orderBy, $limit, $offset)
        //findOneBy($criteria, $orderBy)
        
        $view =  new ViewModel([
            'users' => $users
        ]);
        $view->setTemplate('doctrine/index.phtml'); 
	    return $view;
    } 

    // public function index02Action() 
    // {
    //     $entityManager = $this->entityManager;
    
    //     $queryBuilder = $entityManager->createQueryBuilder();
        
    //     $queryBuilder->select('u')
    //         ->from(Users::class, 'u')
    //         ->join('p.role_resource')
    //         ->join('p.role')
    //         ->where('p.id = ?1')
    //         ->orderBy('p.username', 'DESC')
    //         ->setParameter('1', 34);
        
    //     return $queryBuilder->getQuery()->getResults();
        
    //     $view =  new ViewModel([
    //         'users' => $users
    //     ]);
    //     $view->setTemplate('doctrine/index.phtml'); 
	//     return $view;
    // } 
    

    public function addAction(){
        // Create user form
        $form = new UserForm('create', $this->entityManager);
        
        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {
            
            // Fill in the form with POST data
            $data = $this->params()->fromPost();            
            
            $form->setData($data);
            
            // Validate form
            if($form->isValid()) {
                
                // Get filtered and validated data
                $data = $form->getData();
                // echo '<pre>';
                // print_r($data);
                // echo '</pre>';
                // die;
                // Add user.
                $user = $this->userManager->addUser($data);
                
                // Redirect to "view" page
                //return $this->redirect()->toRoute('users', 
                //        ['action'=>'view', 'id'=>$user->getId()]);  
                $this->flashMessenger()->addSuccessMessage('Thêm thành công');    
                return $this->redirect()->toRoute('users', 
                                        ['action'=>'index']);               
            }               
        } 
        
       $view = new ViewModel(['form' => $form]);
       $view->setTemplate('doctrine/add.phtml'); 
       return $view;
    }


    public function editAction() 
    {
        
        // Get post ID.    
        $userId = (int)$this->params()->fromRoute('id', -1);
        if ($userId<1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        // Find existing post in the database.    
        $user = $this->entityManager->getRepository(Users::class)
                    ->findOneById($userId);        
        if ($user == null) {
            $this->getResponse()->setStatusCode(404);
            return;                        
        } 
            
        // Create user form
        $form = new UserForm('update', $this->entityManager, $user);
        // Check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {
                
            // Get POST data.
            $data = $this->params()->fromPost();
                
            // Fill form with data.
            $form->setData($data);
            if ($form->isValid()) {
                                    
                // Get validated form data.
                $data = $form->getData();
                        
                // Use post manager service to add new post to database.                
                $this->userManager->updateUser($user, $data);
                $this->flashMessenger()->addSuccessMessage('Cập nhật thành công');
                // Redirect the user to "admin" page.
                return $this->redirect()->toRoute('users', ['action'=>'index']);
            }
            else{
                foreach ($form->getMessages() as $message) {
                   print_r($message);
                }
            }
        } 
        else {
            $data = [
                'username' =>$user->getUsername(),
                'fullname' =>$user->getFullName(),
                'birthdate'=>$user->getBirthday(),
                'email'=>$user->getEmail(),
                'gender'=>$user->getGender(),
                'address'=>$user->getAddress(),
                'phone'=>$user->getPhone(),
                'roles'=>$user->getRole()
            ];
                
            $form->setData($data);
        }
            
        // Render the view template.
        $view = new ViewModel([
            'form' => $form,
            'user' => $user
        ]);  
        $view->setTemplate('doctrine/edit.phtml'); 
        return $view;
    }

    public function deleteAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id<1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        $user = $this->entityManager->getRepository(Users::class)
                    ->findOneById($id);        
        if ($user == null) {
            $this->getResponse()->setStatusCode(404);
            return;                        
        }        
            
        $this->userManager->removeUser($user);
            
        // Redirect the user to "index" page.
        return $this->redirect()->toRoute('users', ['action'=>'index']);
    }


    public function changePasswordAction() 
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id<1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        $user = $this->entityManager->getRepository(Users::class)
                ->find($id);
        
        if ($user == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        // Create "change password" form
        $form = new PasswordChangeForm('change');
        
        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {
            
            // Fill in the form with POST data
            $data = $this->params()->fromPost();            
            
            $form->setData($data);
            
            // Validate form
            if($form->isValid()) {
                
                // Get filtered and validated data
                $data = $form->getData();
                
                // Try to change password.
                if (!$this->userManager->changePassword($user, $data)) {
                    $this->flashMessenger()->addErrorMessage('Mật khẩu cũ chưa đúng, vui lòng kiểm tra lại');
                    return $this->redirect()->toRoute('users', 
                                ['action'=>'change-password','id'=>$user->getId()]);
                } 
                else {
                    $this->flashMessenger()->addSuccessMessage('Mật khẩu đã thay đổi');
                }
                
                // Redirect to "view" page
                return $this->redirect()->toRoute('users', 
                        ['action'=>'index']);                
            }      
                    
        } 
        
        $view =  new ViewModel([
            'user' => $user,
            'form' => $form
        ]);
        $view->setTemplate('doctrine/change-password.phtml'); 
        return $view;
    }
    
}