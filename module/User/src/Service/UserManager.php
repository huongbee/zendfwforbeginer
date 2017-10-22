<?php
namespace User\Service;

use User\Entity\User;
use Zend\Crypt\Password\Bcrypt;
use Zend\Math\Rand;
use Zend\Mail;
use Zend\Mail\Transport\Smtp as SmtpTransport; 
use Zend\Mail\Transport\SmtpOptions;

/**
 * This service is responsible for adding/editing users
 * and changing user password.
 */
class UserManager{

    private $entityManager;  

    public function __construct($entityManager) 
    {
        $this->entityManager = $entityManager;
    }

    public function checkEmailExists($email) {
        
        $user = $this->entityManager->getRepository(User::class)
                ->findOneByEmail($email);
        
        return $user !== null;
      
    }
    public function checkUsernameExists($username) {
        $user = $this->entityManager->getRepository(User::class)
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
        $user = new User();
        
        $user->setUsername($data['username']);
        $user->setFullName($data['fullname']);
        $user->setBirthday($data['birthdate']);
        $user->setGender($data['gender']);
        $user->setAddress($data['address']);
        $user->setEmail($data['email']);
        $user->setPhone($data['phone']);
        $user->setRole($data['roles']);
        $user->setStatus($data['status']);

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
        $user->setStatus($data['status']);
        
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
        // echo $passwordHash;
        
        // Apply changes
        $this->entityManager->flush();

        return true;
    }
    
    public function generatePasswordResetToken($user)
    {
        
        // Generate a token.
        $token = Rand::getString(32, '0123456789abcdefghijklmnopqrstuvwxyz', true);
        $user->setPasswordResetToken($token);
        
        $currentDate = date('Y-m-d H:i:s');
        $user->setPasswordResetTokenCreationDate($currentDate);  
        
        $this->entityManager->flush();
        

        $mailTransport = new SmtpTransport();
        $options   = new SmtpOptions(array(
            'name'              => 'smtp.gmail.com',
            'host'              => 'smtp.gmail.com',
            'port'              => 587, // Notice port change for TLS is 587
            'connection_class'  => 'plain',
            'connection_config' => array(
                'username' => 'huonghuong08.php@gmail.com',
                'password' => '0123456789999',
                'port'     => '587',
                'ssl'      => 'tls',
            ),
        ));
        $mailTransport->setOptions($options);


        $subject = 'Password Reset';
        $http = isset($_SERVER['HTTPS']) ? "https://" : "http://" ;
        $httpHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
        $passwordResetUrl = $http . $httpHost . '/zend/public/set-password/' . $token;
        
        $body = 'Chọn liên kết dưới đây để đặt lại mật khẩu của bạn:';
        $body .= "\n$passwordResetUrl\n";
        $body .= "Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua thông báo này.\n";
        
        $mail = new Mail\Message();
        $mail->setEncoding("UTF-8");
        $mail->setBody($body);
        $mail->setFrom('huonghuong08.php@gmail.com', 'Zend FW3');
        $mail->addTo($user->getEmail(), $user->getFullname());
        $mail->setSubject($subject);
    
        $mailTransport->send($mail);
    }


    /**
    * Kiểm tra xem mã thông báo đặt lại mật khẩu cho trước có hợp lệ không.
    */
    public function validatePasswordResetToken($passwordResetToken)
    {
        $user = $this->entityManager->getRepository(User::class)
                ->findOneByPasswordResetToken($passwordResetToken);
        
        if($user==null) {
            return false;
        }
        
        $tokenCreationDate = $user->getPasswordResetTokenCreationDate();
        $tokenCreationDate = strtotime($tokenCreationDate);
        
        $currentDate = strtotime('now');
        
        if ($currentDate - $tokenCreationDate > 24*60*60) {
            return false; // expired
        }
        
        return true;
    }

    public function setNewPasswordByToken($passwordResetToken, $newPassword)
    {
        if (!$this->validatePasswordResetToken($passwordResetToken)) {
           return false; 
        }
        
        $user = $this->entityManager->getRepository(User::class)
                ->findOneByPasswordResetToken($passwordResetToken);
        
        if ($user==null) {
            return false;
        }
        
 
        // Set new password for user        
        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($newPassword);    
       
        $user->setPassword($passwordHash);
          
        // Remove password reset token
        $user->setPasswordResetToken(null);
        $user->setPasswordResetTokenCreationDate(null);
        
        $this->entityManager->flush();
        
        return true;
    }
}