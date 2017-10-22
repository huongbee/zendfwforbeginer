<?php
namespace Database\Repository;

use Doctrine\ORM\EntityRepository;
use Database\Entity\Users;

// This is the custom repository class for Post entity.
class UserRepository extends EntityRepository
{
  //Finds all published posts having any tag.
    public function findAllUsers()
    {
        $entityManager = $this->getEntityManager();
            
        $queryBuilder = $entityManager->createQueryBuilder();
        
        $queryBuilder->select('u')
            ->from(Users::class, 'u')
            ->orderBy('u.id', 'DESC');
        
        $posts = $queryBuilder->getQuery();//->getResult();
        
        return $posts;
    }

 
}