<?php
namespace Form\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Form\Form\UploadFile;
use Zend\Filter\File\Rename;

use Zend\View\Model\JsonModel;

class FileController extends AbstractActionController{

    // public function indexAction(){
    //     $form = new UploadFile();
    //     $request = $this->getRequest();
    //     if($request->isPost()){
            
    //         $file = $request->getFiles()->toArray();
            
    //         $form->setData($file);
           
    //         if($form->isValid()) {                
    //             $data = $form->getData();  
                
    //             $upload	= new \Zend\File\Transfer\Adapter\Http();
                
    //             $fileInfo	= $upload->getFileInfo();
    //             $fileSize	= $upload->getFileSize();
    //             $fileName	= $upload->getFileName();

    //             $upload->setDestination(FILES_PATH . '/upload');
    //             $upload->receive();

    //             //rename Upload File
    //             //use Zend\Filter\File\Rename;

    //             // $filter = new Rename([
    //             //     "target"    => FILES_PATH . "/upload/".$file['fileupload']['name'],
    //             //     "randomize" => true, 
    //             // ]);
    //             // $filter->filter($file['fileupload']);                
    //             //remove line 29,30
               
                
    //             echo 'uploaded ';
    //         }
    //         else{
    //             print_r($form->getMessages());
    //         }
    // 	}
    //     $view = new ViewModel(array('form'=>$form));
	//     $view->setTemplate('form/file/index.phtml'); 
	//     return $view;
    // }


    //v37
        // public function indexAction(){
        //     $form = new UploadFile();
        //     $request = $this->getRequest();
        //     if($request->isPost()){
                
        //         $file = $request->getFiles()->toArray();
                
        //         $form->setData($file);
                
        //         if($form->isValid()) {                
        //             $data = $form->getData();  
        //             // echo '<pre>';
        //             // print_r($data);
        //             // echo '</pre>';
        //             $upload	= new \Zend\File\Transfer\Adapter\Http();

        //             foreach($data['fileupload'] as $img){

        //                 $filter = new Rename([
        //                     "target"    => FILES_PATH . "/upload/".$img['name'],
        //                     "randomize" => true, 
        //                 ]);
        //                 $filter->filter($img); 
        //             }
        //             echo 'uploaded ';

        //         }
        //         else{
        //             print_r($form->getMessages());
        //         }
        //     }
        //     $view = new ViewModel(array('form'=>$form));
        //     $view->setTemplate('form/file/index.phtml'); 
        //     return $view;
        // }



    //V38   bỏ qua
            //use Zend\View\Model\JsonModel;
        public function indexAction(){
                $form = new UploadFile();
                $request = $this->getRequest();
                if($request->isPost()){
                    
                    $post = array_merge_recursive(
                        $request->getPost()->toArray(),
                        $request->getFiles()->toArray()
                    );
                    
                    $form->setData($post);
                    
                    if($form->isValid()) {                
                        $data = $form->getData();  
                        
                        // Form is valid, save the form!
                        if (!empty($post['isAjax'])) {
                            $upload	= new \Zend\File\Transfer\Adapter\Http();

                            foreach($data['fileupload'] as $img){

                                $filter = new Rename([
                                    "target"    => FILES_PATH . "/upload/".$img['name'],
                                    "randomize" => true, 
                                ]);
                                $filter->filter($img); 
                            }
                            
                        } 
                        return new JsonModel(array(
                            'status'   => true,
                            'redirect' => $this->url('form/sub', //your route name ...
                                                    array('controller'=>'File', 'action' => 'index')
                                                ),
                            'form' => $form,
                        ));
                    }
                    else{
                        
                        if (!empty($post['isAjax'])) {
                            // Send back failure information via JSON
                            return new JsonModel(array(
                                'status'     => false,
                                'form' => $form->getMessages(),
                                'formData'   => $form->getData(),
                            ));
                        }
                        print_r($form->getMessages());
                    }
                }
                $view = new ViewModel(array('form'=>$form));
                $view->setTemplate('form/file/index.phtml'); 
                return $view;
            }


        public function uploadProgressAction()
        {
            $id = $this->params()->fromQuery('id', null);
            $progress = new \Zend\ProgressBar\Upload\SessionProgress();
            return new \Zend\View\Model\JsonModel($progress->getProgress($id));
        }
}
