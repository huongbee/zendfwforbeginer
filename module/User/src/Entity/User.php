<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * This class represents a registered user.
 * @ORM\Entity()
 * @ORM\Table(name="users")
 */
class User
{
    // User status constants.
    const STATUS_ACTIVE       = 1; // Active user.
    const STATUS_RETIRED      = 2; // Retired user.
    
    /**
    * @ORM\Id
    * @ORM\GeneratedValue
    * @ORM\Column(name="id")   
    */
    protected $id;
    /** 
    * @ORM\Column(name="username")  
    */
    protected $username;

    /** 
    * @ORM\Column(name="password")  
    */
    protected $password;
    /** 
    * @ORM\Column(name="fullname")  
    */

    protected $fullname;
    /** 
    * @ORM\Column(name="birthdate")  
    */
    protected $birthdate;
    /** 
    * @ORM\Column(name="gender")  
    */
    protected $gender;
    /** 
    * @ORM\Column(name="address")  
    */
    protected $address;
    /** 
    * @ORM\Column(name="email")  
    */
    protected $email;

    /** 
    * @ORM\Column(name="phone")  
    */
    protected $phone;

    /** 
    * @ORM\Column(name="role")  
    */
    protected $role;

    /** 
     * @ORM\Column(name="status")  
     */
    protected $status;
    
    /**
    * @ORM\Column(name="pwd_reset_token")  
    */
    protected $passwordResetToken;
    
    /**
    * @ORM\Column(name="pwd_reset_token_creation_date")  
    */
    protected $passwordResetTokenCreationDate;

    /**
     * Returns user ID.
     * @return integer
     */
    public function getId() {
        return $this->id;
    }


     /**
     * Sets user ID. 
     * @param int $id    
     */
    public function setId($id) {
        $this->id = $id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($pw) {
        $this->password = $pw;
    }
    public function getFullName(){ 
        return $this->fullname;
    }       

    
    public function setFullName($fullName) {
        $this->fullname = $fullName;
    }

    public function getBirthday(){ 
        return $this->birthdate;
    }       
  
    public function setBirthday($birthday) {
        $this->birthdate = $birthday;
    }

    public function getGender(){ 
        return $this->gender;
    }       

    
    public function setGender($gender) {
        $this->gender = $gender;
    }

    public function getAddress(){ 
        return $this->address;
    }       

    
    public function setAddress($address) {
        $this->address = $address;
    }

    public function getEmail(){
        return $this->email;
    }

    public function setEmail($email){
        $this->email = $email;
    }

    
    public function getPhone(){
        return $this->phone;
    }

    public function setPhone($phone){
        $this->phone = $phone;
    }

    public function getRole(){
        return $this->role;
    }

    public function setRole($role){
        $this->role = $role;
    }

    /**
     * Returns status.
     * @return int     
     */
     public function getStatus() 
     {
         return $this->status;
     }

     /**
     * Returns possible statuses as array.
     * @return array
     */
    public static function getStatusList() 
    {
        return [
            self::STATUS_ACTIVE => 1,
            self::STATUS_RETIRED => 0 //Đã nghỉ hưu/ chưa active
        ];
    }
    
    /**
     * Returns user status as string.
     * @return string
     */
     public function getStatusAsString()
     {
         $list = self::getStatusList();
         if (isset($list[$this->status]))
             return $list[$this->status];
         
         return 0;
     }    

    /**
     * Sets status.
     * @param int $status     
     */
     public function setStatus($status) 
     {
         $this->status = $status;
     }   


     /**
     * Returns password reset token.
     * @return string
     */
    public function getResetPasswordToken()
    {
        return $this->passwordResetToken;
    }
    
    /**
     * Sets password reset token.
     * @param string $token
     */
     public function setPasswordResetToken($token) 
     {
         $this->passwordResetToken = $token;
     }


     /**
     * Returns password reset token's creation date.
     * @return string
     */
    public function getPasswordResetTokenCreationDate()
    {
        return $this->passwordResetTokenCreationDate;
    }

    /**
     * Sets password reset token's creation date.
     * @param string $date
     */
     public function setPasswordResetTokenCreationDate($date) 
     {
         $this->passwordResetTokenCreationDate = $date;
     }
    
}



