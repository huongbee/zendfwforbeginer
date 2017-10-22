<?php

namespace Database;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface{

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
    // Model\UsersTable sử dụng ServiceManager để tạo một 
    // UsersTableGateway để chuyển tới UsersTable.
    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\UserTable::class => function($container) {
                    $tableGateway = $container->get(Model\UserTableGateway::class);
                    return new Model\UserTable($tableGateway);
                },
                //một UsersTableGateway được tạo ra bằng cách lấy một Zend\Db\Adapter\Adapter 
                //(cũng từ ServiceManager) 
                //và sử dụng nó để tạo một đối tượng TableGateway.
                Model\UserTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\User());
                    return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig()
    {
        
        return [
            'factories' => [
                Controller\TableGatewayController::class => function($container) {
                    return new Controller\TableGatewayController(
                       $container->get(Model\UserTable::class)
                        
                    );
                }
            ],
        ];
    }

}


?>