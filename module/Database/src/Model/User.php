<?php
namespace Database\Model;

use DomainException;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class User //implements InputFilterAwareInterface
{
    public $id;
    public $username;
    public $password;
    public $fullname;
    public $birthdate;
    public $gender;
    public $address;
    public $email;
    public $phone;
    public $role;

    public function exchangeArray(array $data){
        $this->id     = !empty($data['id']) ? $data['id'] : null;
        $this->username = !empty($data['username']) ? $data['username'] : null;
        $this->password  = !empty($data['password']) ? $data['password'] : null;
        $this->fullname     = !empty($data['fullname']) ? $data['fullname'] : null;
        $this->birthdate = !empty($data['birthdate']) ? $data['birthdate'] : null;
        $this->gender  = !empty($data['gender']) ? $data['gender'] : null;
        $this->address  = !empty($data['address']) ? $data['address'] : null;
        $this->email     = !empty($data['email']) ? $data['email'] : null;
        $this->phone = !empty($data['phone']) ? $data['phone'] : null;
        $this->role  = !empty($data['roles']) ? $data['roles'] : null;
    }
    public function getArrayCopy(){
        return get_object_vars($this);
    }

    // public function setInputFilter(InputFilterInterface $inputFilter)
    // {
    //     throw new DomainException(sprintf(
    //         '%s does not allow injection of an alternate input filter',
    //         __CLASS__
    //     ));
    // }

    // public function getInputFilter()
    // {
    //     if ($this->inputFilter) {
    //         return $this->inputFilter;
    //     }

    //     $inputFilter = new InputFilter();

    //     $inputFilter->add([
    //         'name' => 'id',
    //         'required' => true,
    //         'filters' => [
    //             ['name' => ToInt::class],
    //         ],
    //     ]);

    //     $inputFilter->add([
    //         'name' => 'artist',
    //         'required' => true,
    //         'filters' => [
    //             ['name' => StripTags::class],
    //             ['name' => StringTrim::class],
    //         ],
    //         'validators' => [
    //             [
    //                 'name' => StringLength::class,
    //                 'options' => [
    //                     'encoding' => 'UTF-8',
    //                     'min' => 1,
    //                     'max' => 100,
    //                 ],
    //             ],
    //         ],
    //     ]);

    //     $inputFilter->add([
    //         'name' => 'title',
    //         'required' => true,
    //         'filters' => [
    //             ['name' => StripTags::class],
    //             ['name' => StringTrim::class],
    //         ],
    //         'validators' => [
    //             [
    //                 'name' => StringLength::class,
    //                 'options' => [
    //                     'encoding' => 'UTF-8',
    //                     'min' => 1,
    //                     'max' => 100,
    //                 ],
    //             ],
    //         ],
    //     ]);

    //     $this->inputFilter = $inputFilter;
    //     return $this->inputFilter;
    // }

}