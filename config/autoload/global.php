<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
 use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySqlDriver;
 use Zend\Session\Storage\SessionArrayStorage;
 use Zend\Session\Validator\RemoteAddr;
 use Zend\Session\Validator\HttpUserAgent;
return array(
   	'db' => array(
      	'driver'   => 'Pdo',
      	'dsn'      => 'mysql:dbname=db_nhahang;host=localhost',
      	'username' => 'root',
		'password' => '',
		'driver_options' => array(
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
		)
	),
   	'service_manager' => array(
      	'factories' => array(
        	'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
      	),
	),
	'doctrine' => [
		'connection' => [
			'orm_default' => [
				'driverClass' => PDOMySqlDriver::class,
				'params' => [
					'host'     => '127.0.0.1',                    
					'user'     => 'root',
					'password' => '',
					'dbname'   => 'db_nhahang',
					'charset' => 'utf8', 
					'driverOptions' => array(1002=>'SET NAMES utf8')
				]
			],            
		],        
	],
	
	// Session configuration.
    'session_config' => [
        'cookie_lifetime'     => 60*60*1, // Session cookie will expire in 1 hour.
        'gc_maxlifetime'      => 60*60*24*30, // How long to store session data on server (for 1 month).        
    ],
    // Session manager configuration.
    'session_manager' => [
        // Session validators (used for security).
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class,
        ]
	],
	// Session storage configuration.
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],
);