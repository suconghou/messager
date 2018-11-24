<?php
/*************************************系统配置区*************************************/
define('DEBUG',getenv('debug'));
define('ROOT',__DIR__.DIRECTORY_SEPARATOR);
define('APP_PATH',ROOT.'app'.DIRECTORY_SEPARATOR);
define('VAR_PATH',ROOT.'var'.DIRECTORY_SEPARATOR);
// define('LIB_PATH',APP_PATH.'system'.DIRECTORY_SEPARATOR);
define('LIB_PATH','/data/git/mvc/app/system/');
define('VIEW_PATH',APP_PATH.'view'.DIRECTORY_SEPARATOR);
define('MODEL_PATH',APP_PATH.'model'.DIRECTORY_SEPARATOR);
define('CONTROLLER_PATH',APP_PATH.'controller'.DIRECTORY_SEPARATOR);
require LIB_PATH.'core.php';


$config=
[
	'db'=>
	[
		'dsn'=>'mysql:host=127.0.0.1;port=3306;dbname=messager;charset=utf8',
		'user'=>'work',
		'pass'=>'123456',
	],
	// 可选的配置项
	'mail'=>
	[
		'server'=>'smtp.yeah.net',
		'user'=>'suconghou@yeah.net',
		'pass'=>'11260sch45770',
		'name'=>'21Text Mail',
		'port'=>25,
		'auth'=>true,
	],
	'timezone'=>'prc',
    'gzip'=>false,
    'event'=>[
		'json'=>function($e){
			return json(['code'=>$e->getCode(),'msg'=>$e->getMessage()]);
		}
	]
];




app::start($config);


