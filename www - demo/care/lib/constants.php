<?php
	define('ADMIN_SITE_TITLE','Data Center Manager Console -- Admin');
	define('SITE_TITLE', "Data Center Manager Console");
	
	define('DB_PRE','');
	define('DCM_NAME','Intel&copy; Data Center Manager');
	//============================= Environment Check ==================
	$envurl = '';
	if(isset($_SERVER["HTTP_HOST"]))
		$envurl = "http://".$_SERVER["HTTP_HOST"]."/";
	
	$env = preg_match("/localhost:80/",$envurl);
	
		error_reporting(E_ALL);
		define('SITE_AT','dev');
		define('DEBUG','TRUE');
		
		if(isset($_SERVER['SERVER_NAME']))
			define('SITE_URL','http://'.$_SERVER['SERVER_NAME'].'/care/');
		else
			define('SITE_URL','http://localhost:80/care/');
		define('DB_SERVER','localhost');
		define('DB_NAME','public demo');
		define('DB_SERVER_USERNAME','root');
		define('DB_SERVER_PASSWORD','root');
		define('ALLOWED_REFERRER', 'localhost');
	
?>