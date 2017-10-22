<?php

namespace Database\Controller;

use Zend\Mvc\Controller\AbstractActionController;


class AdapterController extends AbstractActionController{
    
    public function adapterDB(){
		return new \Zend\Db\Adapter\Adapter(array(
				'driver'	=> 'Pdo_Mysql',
				'database'	=> 'db_nhahang',
				'username'	=> 'root',
				'password'	=> '',
				'hostname'	=> 'localhost',		
				'charset'	=> 'utf8',
		));
    }
    
    public function indexAction()
    {
    	//echo '<meta charset="utf-8">';
    	$db	= $this->adapterDB();
    	
    	//$sql	= 'SELECT * FROM `food_type`';
    	$sql	= 'SELECT * FROM `food_type` WHERE id BETWEEN 2 AND 4';
    	$statement 	= $db->query($sql);
    	$result		= $statement->execute();
    	
    	foreach($result as $row){
    		echo '<pre>';
    		print_r($row);
    		echo '</pre>';
    	}
    	return false;
        
	}

	public function demo2Action(){
		$db	= $this->adapterDB();
		$sql		= 'SELECT * FROM `food_type` WHERE `name` LIKE :ten AND `id` = :maloai';
		$statement	= $db->query($sql);	
		$sql_result		= $statement->execute([
			'ten' => '%canh%', 
			'maloai' => 6
		]);
		foreach($sql_result as $row){
    		echo '<pre>';
    		print_r($row);
    		echo '</pre>';
		}
		return false;
	}
	
	public function demo3Action(){
		$db	= $this->adapterDB();
		$sql = 'SELECT * FROM `food_type` WHERE id BETWEEN ? AND ?';
		
		$sql_result = $db->createStatement($sql, array(1, 5))->execute();
		
		foreach($sql_result as $row){
    		echo '<pre>';
    		print_r($row);
    		echo '</pre>';
		}
		//https://framework.zend.com/manual/2.1/en/modules/zend.db.adapter.html#creating-an-adapter-quickstart
		//echo $sql_result= $db->getLastGeneratedValue();
    	return false;
	}
}