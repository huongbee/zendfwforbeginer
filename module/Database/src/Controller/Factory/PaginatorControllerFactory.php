<?php
namespace Database\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Database\Controller\PaginatorController;
use Database\Service\UsersManager;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller.
 */
class PaginatorControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userManager = $container->get(UsersManager::class);
        
        // Instantiate the controller and inject dependencies
        return new PaginatorController($entityManager, $userManager);
    }
}