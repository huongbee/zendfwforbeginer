<?php
namespace Database\Service;

use Database\Entity\Users;
use Zend\Crypt\Password\Bcrypt;
use Zend\Math\Rand;

/**
 * This service is responsible for adding/editing users
 * and changing user password.
 */
class UsersManager{

    private $entityManager;  

    public function __construct($entityManager) 
    {
        $this->entityManager = $entityManager;
    }

    public function checkEmailExists($email) {
        
        $user = $this->entityManager->getRepository(Users::class)
                ->findOneByEmail($email);
        
        return $user !== null;
        // $q = $this->entityManager->createQueryBuilder()
        //     ->select('u')
        //     ->from(Users::class, 'u')
        //     ->where('u.email = :email')
        //     ->orWhere('u.username = :username')
        //     ->setParameter('email', $email)
        //     ->setParameter('username', $username)
        //     ->getQuery();
            
        // return $q->getOneOrNullResult();
    }
    public function checkUsernameExists($username) {
        $user = $this->entityManager->getRepository(Users::class)
                ->findOneByUsername($username);
        
        return $user !== null;
    }

    public function addUser($data) 
    {
        // Do not allow several users with the same email address.
        if($this->checkEmailExists($data['email'])) {
            throw new \Exception("Email " . $data['email']. ' đã có người sử dụng');
        }
        if($this->checkUsernameExists($data['username'])) {
            throw new \Exception(" Username " . $data['username'].' đã có người sử dụng');
        }
        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
        // die;
        // Create new User entity.
        //username, password, fullname, birthdate, 
        //gender, address, email, phone, role
        $user = new Users();
        
        $user->setUsername($data['username']);
        $user->setFullName($data['fullname']);
        $user->setBirthday($data['birthdate']);
        $user->setGender($data['gender']);
        $user->setAddress($data['address']);
        $user->setEmail($data['email']);
        $user->setPhone($data['phone']);
        $user->setRole($data['roles']);
              

        // Encrypt password and store the password in encrypted state.
        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($data['password']);        
        $user->setPassword($passwordHash);
        

        // Add the entity to the entity manager.
        //Để thêm một thực thể mới được tạo vào bảng users, 
        //sử dụng phương thức persist () 
        $this->entityManager->persist($user);
        

        //EntityManager nhớ những thay đổi của bạn trong bộ nhớ
        // nhưng không áp dụng các thay đổi đối với cơ sở dữ liệu tự động
        //Để áp dụng các thay đổi đối với cơ sở dữ liệu -> use the flush() method.
        // Apply changes to database.
        $this->entityManager->flush();
        
        return $user;
    }


    public function updateUser($user, $data) 
    {
        $user->setFullName($data['fullname']);
        $user->setBirthday($data['birthdate']);
        $user->setGender($data['gender']);
        $user->setAddress($data['address']);
        $user->setPhone($data['phone']);
        $user->setRole($data['roles']);
        
        // Apply changes to database.
        $this->entityManager->flush();
    }

    public function removeUser($user) 
    {
      
      $this->entityManager->remove($user);
          
      $this->entityManager->flush();
    }


    public function validatePassword($user, $password) 
    {
        $bcrypt = new Bcrypt();
        $passwordHash = $user->getPassword();
        
        if ($bcrypt->verify($password, $passwordHash)) {
            return true;
        }
        
        return false;
    }
    
    public function changePassword($user, $data)
    {
        $oldPassword = $data['old_password'];
        
        // Check that old password is correct
        if (!$this->validatePassword($user, $oldPassword)) {
            return false;
        }                
        
        $newPassword = $data['new_password'];
        
        // Check password length
        if (strlen($newPassword)<6 || strlen($newPassword)>64) {
            return false;
        }
        
        // Set new password for user        
        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($newPassword);
        $user->setPassword($passwordHash);
        
        // Apply changes
        $this->entityManager->flush();

        return true;
    }
    

}