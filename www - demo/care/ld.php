<?php 
require_once('lib/config.php');
require_once('./adLDAP/src/adLDAP.php');
$adldap = new adLDAP(array('base_dn'=>'DC=webspline,DC=local', 'account_suffix'=>'@webspline.local'));


$adldap->connect();

$username='fraz';
$password='123.xyz';

$res=$adldap->user()->authenticate($username, $password);
//$authUser = $adldap->authenticate($username, $password);
if ($res == true) {
  echo "User authenticated successfully";
}
else {
  echo "User authentication unsuccessful";
}
$groupList = $adldap->group()->allSecurity($include_desc = false, $search = "*", $sorted = true);
pr($groupList);

$user = $adldap->user()->infoCollection($username, array('*'));
echo $user->displayName;
$groupArray = $user->memberOf; 
//print_r($groupArray);

$attributes=array(
  "group_name"=>"Test Group",
  "description"=>"Just Testing",
  "container"=>array("Groups","A Container"),
);
//$result=$adldap->group()->create($attributes);
$r=$adldap->getDomainControllers();
print_r($r);
//$groups = $adldap->group()->addUser('Test Group', 'fraz');
/*
$attributes=array(
"username"=>"ali",
"logon_name"=>"ali@webspline.local",
"firstname"=>"ali",
"surname"=>"hasan",
"company"=>"Webspline",
"department"=>"Development",
"email"=>"ali@webspline.local",
"container"=>array("Users","Users"),
"enabled"=>1,
"password"=>"abc.123"
);
 
$result = $adldap->user()->create($attributes);
var_dump($result);*/

?>