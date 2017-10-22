<?php

namespace Database\Controller;



use Zend\Db\ResultSet\ResultSet;

use Database\Model\User;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Database\Model\UserTable;
use Database\Forms\UserForm;
use Database\Forms\PasswordChangeForm;

class TableGatewayController extends AbstractActionController
{
	private $table;
    
    public function __construct($table)
    {
        $this->table = $table;
    }

    public function indexAction(){
		// $name = $this->table->getTableName();
		// echo $name;
		//$users = $this->table->select01();
		$users = $this->table->select02();
		foreach($users as $row){
            echo '<pre>';
            print_r($row);
            echo '</pre>';
        }
		//\Zend\Debug\Debug::dump($users, $label = null, $echo = true);
		return false;
        
	}
	
	public function listUserAction(){
		$view = new ViewModel([
            'users' => $this->table->fetchAll(),
        ]);
        $view->setTemplate('user/index.phtml'); 
        return $view;
	}
	
	//use Database\Forms\UserForm;
	public function addAction(){
        $form = new UserForm('create');
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
			$view = new ViewModel([
				'form' =>  $form,
			]);
			$view->setTemplate('user/add.phtml'); 
			return $view;
        }

        $user = new User();
        $form->setData($request->getPost());

        if (! $form->isValid()) {
			$view = new ViewModel([
				'form' =>  $form,
			]);
			$view->setTemplate('user/add.phtml'); 
			return $view;
        }
		$data = $form->getData();
		//print_r($data); die;
        $user->exchangeArray($form->getData());
		$this->table->saveUser($user);
		$this->flashMessenger()->addSuccessMessage('Thêm thành công');    
		return $this->redirect()->toRoute('user', 
								['action'=>'listUser']);
		

		
		
    }
	
    
	public function editAction(){
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('user', ['action' => 'add']);
        }
        try {
            $user = $this->table->getUser($id);
		} 
		catch (\Exception $e) {
            return $this->redirect()->toRoute('user', ['action' => 'listUser']);
        }
        //\Zend\Debug\Debug::dump($user, $label = null, $echo = true);
		$form = new UserForm('update');
		$form->bind($user);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if (! $request->isPost()) {
			$view = new ViewModel([
				'form' => $form
			]);
			$view->setTemplate('user/edit.phtml'); 
			return $view;
        }

        $form->setData($request->getPost());
        //end
        if (! $form->isValid()) {
			$view = new ViewModel([
				 'form' => $form
			]);
			$view->setTemplate('user/edit.phtml'); 
			return $view;
        }
        $this->table->saveUser($user);
        $this->flashMessenger()->addSuccessMessage('Sửa thành công'); 
        // Redirect to user list
        return $this->redirect()->toRoute('user', ['action' => 'listUser']);
    }

    public function deleteAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('user',['action'=>'listUser']);
        }
        
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $user = $this->table->getUser($id);
            
            $view =  new ViewModel([
                'user' => $user
            ]);
            $view->setTemplate('user/delete.phtml'); 
            return $view;
        }
        else{
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                
                $this->table->deleteUser($id);
                // Redirect to list of albums
                $this->flashMessenger()->addSuccessMessage('Xóa thành công'); 
                return $this->redirect()->toRoute('user',['action' => 'listUser']);
            }
            // Redirect to list of albums
            return $this->redirect()->toRoute('user',['action' => 'listUser']);
        }

    }

    public function changePasswordAction() 
    {
        $id = (int) $this->params()->fromRoute('id',0);
        
        if ($id == 0) {
            return $this->redirect()->toRoute('user', ['action' => 'listUser']);
        }
        $user = $this->table->getUser($id);
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
                if (!$this->table->changePassword($user, $data)) {
                    $this->flashMessenger()->addErrorMessage('Mật khẩu cũ chưa đúng, vui lòng kiểm tra lại');
                    return $this->redirect()->toRoute('user', 
                                ['action'=>'change-password','id'=>$id]);
                } 
                else {
                    $this->flashMessenger()->addSuccessMessage('Mật khẩu đã thay đổi');
                }
                
                // Redirect to "view" page
                return $this->redirect()->toRoute('user', 
                        ['action'=>'listUser']);                
            }      
                    
        } 
        
        $view =  new ViewModel([
            'form' => $form,
            'user' => $user
        ]);
        $view->setTemplate('user/change-password.phtml'); 
        return $view;
    }
}
