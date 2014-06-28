<?php
require_once('lib/config.php');
// Inialize session


// Delete certain session

$us=$_SESSION['username'];
  $atype='User loged out';
  $pt='';
  $details=$atype." as : ".$us;
  $ip=getIP();
  $date=date("Y-m-d H:i:s");
  $k= useraudit($con,$date,$ip,$us,$atype,$pt,$details);
unset($_SESSION['username']);
// Delete all session variables
// session_destroy();

// Jump to login page
header('Location: login.php');
exit();

?>