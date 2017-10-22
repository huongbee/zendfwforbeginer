<?php

namespace User;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Controller\AbstractActionController;
use User\Controller\AuthController;
use User\Service\AuthManager;

class Module {

	public function getConfig(){

        return include __DIR__ . '/../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {        
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
      
    }

    /**
     * 
    *Phương pháp này được gọi là một khi quá trình khởi động MVC hoàn tất 
    *và cho phép đăng ký các trình nghe sự kiện. 
     */
     public function onBootstrap(MvcEvent $event)
     {
         // Get event manager.
         $eventManager = $event->getApplication()->getEventManager();
         $sharedEventManager = $eventManager->getSharedManager();
         // Register the event listener method. 
         $sharedEventManager->attach(AbstractActionController::class, 
                 MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 100);
     }
    //Phương thức nghe sự kiện cho sự kiện 'Dispatch'. 
    //Chúng ta lắng nghe sự kiện Dispatch để gọi bộ lọc truy cập. 
    //Bộ lọc truy cập cho phép xác định xem khách truy cập hiện tại có được phép 
    //xem trang đó hay không. Nếu người đó không được phép và không được phép xem trang, 
    //chúng tôi sẽ chuyển hướng người dùng đến trang đăng nhập.
    public function onDispatch(MvcEvent $event)
    {
        // Get controller and action to which the HTTP request was dispatched.
        $controller = $event->getTarget();
        $controllerName = $event->getRouteMatch()->getParam('controller', null);
        $actionName = $event->getRouteMatch()->getParam('action', null);
        
        // Convert dash-style action name to camel-case.
        $actionName = str_replace('-', '', lcfirst(ucwords($actionName, '-')));
        
        // Get the instance of AuthManager service.
        $authManager = $event->getApplication()->getServiceManager()->get(AuthManager::class);
        
        // Execute the access filter on every controller except AuthController
        // (to avoid infinite redirect).
        if ($controllerName!=AuthController::class && 
            !$authManager->filterAccess($controllerName, $actionName)) {
            
            // Remember the URL of the page the user tried to access. We will
            // redirect the user to that URL after successful login.
            $uri = $event->getApplication()->getRequest()->getUri();
            // Make the URL relative (remove scheme, user info, host name and port)
            // to avoid redirecting to other domain by a malicious user.
            $uri->setScheme(null)
                ->setHost(null)
                ->setPort(null)
                ->setUserInfo(null);
            $redirectUrl = $uri->toString();
            
            // Redirect the user to the "Login" page.
            return $controller->redirect()->toRoute('login', [], 
                    ['query'=>['redirectUrl'=>$redirectUrl]]);
        }
    }

}


?>