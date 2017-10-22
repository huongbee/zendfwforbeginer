<?php

namespace User\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Entity\User;
use User\Form\UserForm;
use User\Form\PasswordChangeForm;
use  User\Form\PasswordResetForm;

class UserController extends AbstractActionController{
    
    private $entityManager;
    private $userManager;

    public function __construct($entityManager, $userManager)
    {
       $this->entityManager = $entityManager;
        $this->userManager = $userManager;
        
    }

    public function indexAction() 
    {
        $users = $this->entityManager->getRepository(User::class)
                ->findAll();
        return new ViewModel([
            'users' => $users
        ]);
    } 

   
    public function addAction(){
        // Create user form
        $form = new UserForm('create', $this->entityManager);
        
        if ($this->getRequest()->isPost()) {
            
            $data = $this->params()->fromPost();    
            $form->setData($data);
            
            // Validate form
            if($form->isValid()) {
                
                // Get filtered and validated data
                $data = $form->getData();
                $user = $this->userManager->addUser($data);

                $this->flashMessenger()->addSuccessMessage('Thêm thành công');    
                return $this->redirect()->toRoute('user', 
                                        ['action'=>'index']);               
            }               
        } 
        
        return new ViewModel(['form' => $form]);
       
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
        $user = $this->entityManager->getRepository(User::class)
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
                return $this->redirect()->toRoute('user', ['action'=>'index']);
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
                'roles'=>$user->getRole(),
                'status'=>$user->getStatus()
            ];
             
            $form->setData($data);
        }
            
        // Render the view template.
        return new ViewModel([
            'form' => $form,
            'user' => $user
        ]);  
    }

    public function deleteAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id<1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        $user = $this->entityManager->getRepository(User::class)
                    ->findOneById($id);        
        if ($user == null) {
            $this->getResponse()->setStatusCode(404);
            return;                        
        }        
            
        $this->userManager->removeUser($user);
            
        // Redirect the user to "index" page.
        return $this->redirect()->toRoute('user', ['action'=>'index']);
    }


    public function changePasswordAction() 
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id<1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        $user = $this->entityManager->getRepository(User::class)
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
                    return $this->redirect()->toRoute('user', 
                                ['action'=>'change-password','id'=>$user->getId()]);
                } 
                else {
                    $this->flashMessenger()->addSuccessMessage('Mật khẩu đã thay đổi');
                }
                
                // Redirect to "view" page
                return $this->redirect()->toRoute('user', 
                        ['action'=>'index']);                
            }      
                    
        } 
        
        return new ViewModel([
            'user' => $user,
            'form' => $form
        ]);
    }


    /**
     * This action displays the "Reset Password" page.
     */
     public function resetPasswordAction()
     {
         // Create form
         $form = new PasswordResetForm();
         
         // Check if user has submitted the form
         if ($this->getRequest()->isPost()) {
             
             // Fill in the form with POST data
             $data = $this->params()->fromPost();            
             
             $form->setData($data);
             
             // Validate form
             if($form->isValid()) {
                 
                 // Look for the user with such email.
                 $user = $this->entityManager->getRepository(User::class)
                         ->findOneByEmail($data['email']);                
                 if ($user!=null) {
                     // Generate a new password for user and send an E-mail 
                     // notification about that.
                     $this->userManager->generatePasswordResetToken($user);
                     
                     // Redirect to "message" page
                     return $this->redirect()->toRoute('user', 
                             ['action'=>'message', 'id'=>'sent']);                 
                 } else {
                     return $this->redirect()->toRoute('user', 
                             ['action'=>'message', 'id'=>'invalid-email']);                 
                 }
             }               
         } 
         
         return new ViewModel([                    
             'form' => $form
         ]);
     }
     
     /**
      * This action displays an informational message page. 
      * For example "Your password has been resetted" and so on.
      */
     public function messageAction() 
     {
         // Get message ID from route.
         $id = (string)$this->params()->fromRoute('id');
         
         // Validate input argument.
         if($id!='invalid-email' && $id!='sent' && $id!='set' && $id!='failed') {
            $this->getResponse()->setStatusCode(404);
            return;
         }
         
         return new ViewModel([
             'id' => $id
         ]);
     }
     
     /**
      * This action displays the "Reset Password" page. 
      */
     public function setPasswordAction()
     {
         $token = $this->params()->fromRoute('token', null);
         //echo $token; die;
         // Validate token length
         if ($token!=null && (!is_string($token) || strlen($token)!=32)) {
             throw new \Exception('Token không hợp lệ');
         }
         
         if($token===null || 
            !$this->userManager->validatePasswordResetToken($token)) {
             return $this->redirect()->toRoute('user', 
                     ['action'=>'message', 'id'=>'failed']);
         }
                 
         // Create form
         $form = new PasswordChangeForm('reset');
         
         // Check if user has submitted the form
         if ($this->getRequest()->isPost()) {
             
             // Fill in the form with POST data
             $data = $this->params()->fromPost();            
             
             $form->setData($data);
             
             // Validate form
             if($form->isValid()) {
                 
                 $data = $form->getData();
                                                
                 // Set new password for the user.
                 if ($this->userManager->setNewPasswordByToken($token, $data['new_password'])) {
                     
                     // Redirect to "message" page
                     return $this->redirect()->toRoute('user', 
                             ['action'=>'message', 'id'=>'set']);                 
                 } else {
                     // Redirect to "message" page
                     return $this->redirect()->toRoute('user', 
                             ['action'=>'message', 'id'=>'failed']);                 
                 }
             }               
         } 
         
         return new ViewModel([                    
             'form' => $form
         ]);
     }
    
}