<?php

namespace Database\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Predicate;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Ddl;
use Zend\Db\Sql\Ddl\Column;

class SqlController extends AbstractActionController{
    
    public function adapterDB(){
		return new \Zend\Db\Adapter\Adapter(array(
            'driver'	=> 'PDO_MYSQL',
            'database'	=> 'db_nhahang',
            'username'	=> 'root',
            'password'	=> '',
            'hostname'	=> 'localhost',		
            'charset'	=> 'utf8',
		));
    }
    //Zend\Db\Sql\Select
    //1 from()
    public function selectAction(){
        $adapter	= $this->adapterDB();
        //C1
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from('foods');
        //C2
        $sql = new Sql($adapter, 'foods');
        $select = $sql->select();
        $select->where(['id'=> 2]);
        
        $selectString = $sql->buildSqlString($select);
        $result = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
        foreach($result as $row){
    		echo '<pre>';
    		print_r($row);
    		echo '</pre>';
    	}
    	return false;
    }
    


    //2 columns(), where()
    //SELECT "t".* FROM "table" AS "t"
    public function select02Action(){
        $adapter	= $this->adapterDB();
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from(['f' => 'foods']);
        //2. $select->columns(array('name' => 'ten_mon', 'price' => 'don_gia'));
        $select->where(['f.id'=> 3]);
        
        $selectString = $sql->buildSqlString($select);
        $result = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
        foreach($result as $row){
    		echo '<pre>';
    		print_r($row);
    		echo '</pre>';
    	}
    	return false;
    }

    //3 join()
    public function select03Action(){
        $adapter	= $this->adapterDB();
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from(array('f' => 'foods'))
                ->join(array('t' => 'food_type'),     // join table with alias
                        'f.id_type = t.id');         // join expression
        $selectString = $sql->buildSqlString($select);
        $result = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
        foreach($result as $row){
            echo '<pre>';
            print_r($row);
            echo '</pre>';
        }
        return false;
    }

    //4 where()
    //use Zend\Db\Sql\Where
    public function select04Action(){
        $adapter	= $this->adapterDB();
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
        foreach($result as $row){
            echo '<pre>';
            print_r($row);
            echo '</pre>';
        }
        return false;
    }

    
    
    //5 order(),  limit() and offset()
    public function select05Action(){
        $adapter	= $this->adapterDB();
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from('foods');
        $select->order('id ASC');
                //->order('name ASC, age DESC');
        $select->limit(5)->offset(10);
        $selectString = $sql->buildSqlString($select);
        $result = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
        foreach($result as $row){
            echo '<pre>';
            print_r($row);
            echo '</pre>';
        }
        return false;
    }

    // GROUP BY HAVING
    // tổng số món ăn của từng loại
    //use Zend\Db\Sql\Expression;
    public function select07Action()
    {
    	$adapter	= $this->adapterDB();
    	$sqlObj	  	= new Sql($adapter);
    	$selectObj	= $sqlObj->select();
    	$selectObj->from(array('t'	=> 'food_type'))
                    ->columns(array(
                        'ma_loai'		=> 'id',
                        'ten_loai'		=> 'name',
                        'total'			=> new Expression('COUNT(f.id)')
                    ))
                    ->join(
                        array('f' => 'foods'),
                        't.id = f.id_type',
                        array(),
                        $selectObj::JOIN_LEFT)
                    ->group(array('t.id'))
                    //->having('total > 5')
                    ;
    	$sqlString	= $sqlObj->getSqlStringForSqlObject($selectObj);
    	$result		= $adapter->query($sqlString)->execute();
    
    	echo  $sqlString ;
    	foreach ($result as $row){
    		echo '<pre>';
    		print_r($row);
    		echo '</pre>';
    	}
    
    
    	return $this->getResponse();
    }


    //Zend\Db\Sql\Insert

    public function insert01Action(){
        $adapter	= $this->adapterDB();
        $sql = new Sql($adapter);
        $insert = $sql->insert('customers');
        $insert->values(array(
            'name' => 'Ngọc Hương',
            'gender' => 'nữ',
            'email' => 'huognguyenak96@gmail.com',
            'address' => 'Quận 1'
        ));
        $sqlString	= $sql->getSqlStringForSqlObject($insert);
    	$adapter->query($sqlString)->execute();
    
        echo 'inserted';
        return false;
    }

    //Zend\Db\Sql\Update
    public function update01Action(){
        $adapter	= $this->adapterDB();
        $sql = new Sql($adapter);
        $update = $sql->update('customers');
        $update->set(array(
            'name' => 'Ngọc Hương 03',
            'gender' => 'nữ',
            'email' => 'huognguyenak@gmail.com',
            'address' => 'Tân Bình'
        ));
        $update->where('id = 18');
        $sqlString	= $sql->getSqlStringForSqlObject($update);
    	$adapter->query($sqlString)->execute();
    
        echo 'updated';
        return false;
    }

    //delete
    public function delete01Action()
    {
    	$adapter	= $this->adapterDB();
    	$sqlObj	  	= new Sql($adapter);
    	$deleteObj	= $sqlObj->delete('customers');
    	$deleteObj->where(['id'=>19]);
    	 
    
    	$sqlString	= $sqlObj->getSqlStringForSqlObject($deleteObj);
    	$adapter->query($sqlString)->execute();
    
        echo 'deleted';
        return false;
    }

    
    //Zend\Db\Sql\Expression 
    
    // 'SELECT COUNT(id) AS "total" FROM "customers"';
    public function select08Action()
    {
    	$adapter	= $this->adapterDB();
    	$sqlObj	  	= new Sql($adapter);
    
    	$selectObj	= $sqlObj->select();
    	$selectObj->from(array('c'	=> 'customers'))
                ->columns(array(
                        'total'	=> new Expression('COUNT(id)')
                    ));
        $sqlString	= $sqlObj->getSqlStringForSqlObject($selectObj);
    	$result		= $adapter->query($sqlString)->execute();
    	
    	foreach ($result as $row){
    		echo '<pre>';
    		print_r($row);
    		echo '</pre>';
    	}
        return false;
    }

    public function select09Action()
    {
    	$adapter	= $this->adapterDB();
    	$sqlObj	  	= new Sql($adapter);
    
    	$selectObj	= $sqlObj->select();
    	$selectObj->from(array('c'	=> 'customers'))
                ->columns(array(
                        'name_lower'	=> new Expression('LOWER(name)'),
                        'name_upper'	=> new Expression('UPPER(name)'),
                        'id-name'		=> new Expression('CONCAT(id, " - ", name)'),		
                ));
    
    	$sqlString	= $sqlObj->getSqlStringForSqlObject($selectObj);
    	$result		= $adapter->query($sqlString)->execute();
    	
    	foreach ($result as $row){
    		echo '<pre>';
    		print_r($row);
    		echo '</pre>';
    	}
        return false;
    }

    //multiple joins 
    //SELECT `c`.* FROM `customers` AS `c` 
    //LEFT JOIN `bills` AS `b` ON `b`.`id_customer`= `c`.`id` 
    //LEFT JOIN `bill_detail` AS `bd` ON `bd`.`id_bill`= `b`.`id` 
    //WHERE bd.quantity >= 2
    public function select10Action()
    {
    	$adapter	= $this->adapterDB();
    	$sqlObj	  	= new Sql($adapter);
    
    	$selectObj	= $sqlObj->select();
    	$selectObj->from(array('c'	=> 'customers'))
                    ->join(
                        array('b'=>'bills'), 
                        'b.id_customer= c.id',
                        array(),
                        $selectObj::JOIN_LEFT
                    )
                    ->join(
                        array('bd'=>'bill_detail'), 
                        'bd.id_bill= b.id',
                        array(),
                        $selectObj::JOIN_LEFT
                    );
        $selectObj->where('bd.quantity >= 2');
        $sqlString	= $sqlObj->getSqlStringForSqlObject($selectObj);
    	$result		= $adapter->query($sqlString)->execute();
    	echo $sqlString;
    	foreach ($result as $row){
    		echo '<pre>';
    		print_r($row);
    		echo '</pre>';
    	}
        return false;
    }

    // Zend\Db\Sql\Predicate
    //use Zend\Db\Sql\Predicate;
    public function select11Action()
    {
    	$adapter	= $this->adapterDB();
    	$sqlObj	  	= new Sql($adapter);
    	$selectObj	= $sqlObj->select();
    	$selectObj
	    	->from('users')
            ->columns(array('id', 'username'))
            
	    	//->where( new Predicate\Between('id', 31, 33))
 	    	//->where( new Predicate\Expression('id > ? AND id < ?', array(31,33)))
            //->where( new Predicate\Literal('id > 33'))
  	        //->where( new Predicate\In('id', array(31,32,34)))
            //->where( new Predicate\NotIn('id', array(31,33)))
 	    	//->where( new Predicate\IsNull('username'))
 	        //->where( new Predicate\IsNotNull('username'))
	    	//->where( new Predicate\Like('email', '%yahoo.com'))
 	    	//->where( new Predicate\NotLike('email', '%yahoo.com'))
    	;
    
    	echo $sqlString	= $sqlObj->getSqlStringForSqlObject($selectObj);
    	$result		= $adapter->query($sqlString)->execute();
    
    	echo  $sqlString ;
    	foreach ($result as $row){
    		echo '<pre>';
    		print_r($row);
    		echo '</pre>';
    	}
        return false;
    }

    //MIN MAX AVG COUNT SUM GROUP HAVING
    //cho biết đơn giá cao nhất, thấp nhât, đơn giá trung bình, 
    //tổng tiền theo từng loại món ăn
    public function select12Action()
	{
        $adapter	= $this->adapterDB();
    	$sqlObj	  	= new Sql($adapter);
    	$selectObj	= $sqlObj->select();       
		$selectObj
            ->from(array('t' => 'food_type'))
            ->join(array('f' => 'foods'),     // join table with alias
                    'f.id_type = t.id',
                    array(),
                    $selectObj::JOIN_RIGHT
            )
			->columns(array(
					'tenloai' => 'name',
					'min'		=> new Expression('MIN(price)'),
					'max'		=> new Expression('MAX(price)'),
					'avg'		=> new Expression('ROUND(AVG(price))'),
					'total'		=> new Expression('SUM(price)'),
			))
			->group('f.id_type');
		echo $sqlString	= $sqlObj->getSqlStringForSqlObject($selectObj);
    	$result		= $adapter->query($sqlString)->execute();
    	foreach ($result as $row){
    		echo '<pre>';
    		print_r($row);
    		echo '</pre>';
    	}
        return false;
	}
    
    

    // /Zend\Db\Sql\Ddl
    //Creating Tables
    //use Zend\Db\Sql\Ddl;
    //use Zend\Db\Sql\Ddl\Column;
    public function createTable01Action()
	{
        
        $table = new Ddl\CreateTable();	
        $table->setTable('demo');
        $table->addColumn(new Column\Integer('id'));
        $table->addColumn(new Column\Varchar('name', 255));
        $table->addConstraint(new \Zend\Db\Sql\Ddl\Constraint\PrimaryKey('id'));

        $adapter	= $this->adapterDB();
        $sql = new Sql($adapter);
        
        $adapter->query(
            $sql->getSqlStringForSqlObject($table),
            $adapter::QUERY_MODE_EXECUTE
        );
        echo 'executed';
        return false;
    }


    //created another table and alter table to add foreign key
    //1 created another table
    public function createTable02Action()
	{
        
        $table = new Ddl\CreateTable();	
        $table->setTable('type_demo');
        $table->addColumn(new Column\Integer('id'));
        $table->addColumn(new Column\Varchar('name', 255));
        $table->addConstraint(new \Zend\Db\Sql\Ddl\Constraint\PrimaryKey('id'));

        $adapter	= $this->adapterDB();
        $sql = new Sql($adapter);
        
        $adapter->query(
            $sql->getSqlStringForSqlObject($table),
            $adapter::QUERY_MODE_EXECUTE
        );
        echo 'executed';
        return false;
    }

    //alter table to add foreign key
    public function alterTable01Action()
	{
        
        $table = new Ddl\AlterTable('demo');
        $table->changeColumn('name',new Column\Varchar('new_name', 50));
        $table->addColumn(new Column\Integer('id_type_demo'));
        $table->addConstraint(
            new \Zend\Db\Sql\Ddl\Constraint\ForeignKey('contraint_01', 'id_type_demo', 'type_demo', 'id')
        );


        $adapter	= $this->adapterDB();
        $sql = new Sql($adapter);
        $adapter->query(
            $sql->getSqlStringForSqlObject($table),
            $adapter::QUERY_MODE_EXECUTE
        );
        echo 'executed';
        return false;
    }

    //Dropping Tables
    public function dropTable01Action()
	{
        
        //$table = new Ddl\DropTable('demo');
        $table = new Ddl\DropTable('type_demo');
        $adapter	= $this->adapterDB();
        $sql = new Sql($adapter);
        $adapter->query(
            $sql->getSqlStringForSqlObject($table),
            $adapter::QUERY_MODE_EXECUTE
        );
        echo 'executed';
        return false;
    }
}