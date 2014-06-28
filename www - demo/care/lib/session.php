<?php
$login_user_id= (isset($_SESSION['uid']) ? $_SESSION['uid'] : false);
if($login_user_id === false && (!isset($requireLogin) || $requireLogin != 'N'))
{
	if($page != 'index.php')
		redirectPage('index.php',ERROR_LOGIN_TO_ACC);
}
else
{
	if($page == 'index.php' && isset($_GET['st']) && $_GET['st'] == 'logout')
	{
	}
	elseif($page == 'index.php' && $login_user_id !== false)
		redirectPage('dashboard.php');
}
?>