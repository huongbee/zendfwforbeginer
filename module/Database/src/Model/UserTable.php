<?php

namespace Database\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Crypt\Password\Bcrypt;

class UserTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    //getTable()
    public function getTableName(){
        return $this->tableGateway->getTable();
    }

    //select()
    public function select01(){
        $table =  $this->tableGateway;
        $rowset = $table->select(function (Select $select) {
            $select->where->like('email', '%@gmail.com');
            $select->order('username ASC')->limit(2);
       });
       return $rowset;
    }


    //getAdapter()
    //use Zend\Db\Sql\Where;
    public function select02(){
        $adapter = $this->tableGateway->getAdapter();
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->columns(array('ten_mon' => 'name', 'don_gia' => 'price'));
        $select->from(array('f' => 'foods'))
                ->join(array('t' => 'food_type'),     // join table with alias
                        'f.id_type = t.id');         // join expression
        $spec = function (Where $where) {
            $where->like('t.name', '%canh%');
        };
        
        $select->where($spec);
        $selectString = $sql->buildSqlString($select);
        $result = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
       
        return $result;
    }




    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    
    public function checkUsernameExists($username) {
        $user = $this->tableGateway->select(['username' => $username])->current();
        return $user !== null;
    }
    public function checkEmailExists($email) {
        $user = $this->tableGateway->select(['email' => $email])->current();
        return $user !== null;
    }
    public function saveUser(User $user){
        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($user->password);  

        $data = [
            'username' => $user->username,
            'password'  => $passwordHash,
            'fullname' => $user->fullname,
            'birthdate'  => $user->birthdate,
            'gender' => $user->gender,
            'address'  => $user->address,
            'email' => $user->email,
            'phone'  => $user->phone,
            'role'  => $user->role,
        ];
        $id = (int) $user->id;
        if ($id <= 0) {
            if($this->checkEmailExists($user->email) ){
                throw new \Exception("Email " . $user->email. ' đã có người sử dụng');
            }
            if($this->checkUsernameExists($user->username)) {
                throw new \Exception(" Username " . $user->username.' đã có người sử dụng');
            }
            
            $this->tableGateway->insert($data);
            return;
        }             
        if (! $this->getUser($id)) {
            throw new RuntimeException(sprintf(
                'Cập nhật không thành công, user %d; không tồn tại',
                $id
            ));
        }
        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function getUser($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Không tìm thấy user với id %d',
                $id
            ));
        }

        return $row;
    }


    public function deleteUser($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
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
        $this->tableGateway->update(['password'=>$passwordHash], ['id' => $user->id]);
        
        return true;
    }

    public function validatePassword($user, $password) 
    {   
        
        $bcrypt = new Bcrypt();
        $passwordHash = $user->password;
        // print_r($password);
        // echo "<br>";
        // echo $passwordHash; die;
        if ($bcrypt->verify($password, $passwordHash)) {
            return true;
        }
        
        return false;
    }




   
}

?>