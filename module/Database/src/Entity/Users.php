<?php
namespace Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * This class represents a single post in a blog.
 * @ORM\Entity(repositoryClass="\Database\Repository\UserRepository")
 * @ORM\Table(name="users")
 */
class Users
{
    //`id`, `username`, `password`, `fullname`, 
    //`birthdate`, `gender`, `address`, `email`, `phone`, `role`
    
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

    public function getId() {
        return $this->id;
    }

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


    
}



