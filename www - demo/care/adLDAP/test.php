<?php

include ("./src/adLDAP.php");
try {
    $adldap = new adLDAP();//$options
}
catch (adLDAPException $e) {
    echo $e;
    exit();   
}

//var_dump($adldap);

echo ("<pre>\n");

// authenticate a username/password
//if (0) {
	echo "----------";
	$result = $adldap->authenticate("administrator", "abc.123");
	var_dump($result);
//}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>
