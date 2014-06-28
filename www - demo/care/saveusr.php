<?php
require_once('lib/config.php');
//pr($_POST);

if(isset($_POST['fname']) && $_POST['fname']!="" && isset($_POST['lname']) && $_POST['lname']!="" && isset($_POST['uid']) && $_POST['uid']!="" && isset($_POST['psw']) && $_POST['psw']!="" && isset($_POST['rpsw']) && $_POST['rpsw']!="" && $_POST['psw']==$_POST['rpsw'] && isset($_POST['email']) && $_POST['email']!="" )
{

    $lstgroup='';
    $fname='';
    $lname='';
    $uid='';
    $psw='';
    $email='';
    $isdisabled=0;
	$er_msg='0';
	if(isset($_POST['lstgroup']))
    {
	$lstgroup=$_POST['lstgroup'];
	}
	
	if(isset($_POST['fname']))
    {
	$fname=$_POST['fname'];
	}
	
	if(isset($_POST['lname']))
    {
	$lname=$_POST['lname'];
	}
	
	if(isset($_POST['uid']))
    {
	$uid=$_POST['uid'];
	}
	
	if(isset($_POST['psw']))
    {
	$psw=$_POST['psw'];
	}
	if(isset($_POST['email']))
    {
	$email=$_POST['email'];
	}
	if(isset($_POST['isdisabled']))
    {
	$isdisabled=$_POST['isdisabled'];
	}


if(isset($_POST['btnUpdate']) && $_POST['btnUpdate']=='Update')
{
 $updateusr_qry="UPDATE users SET
								email='".$email."',
								password='".$psw."',
								activated='".$isdisabled."',
								first_name='".$fname."',
								last_name='".$lname."',
								updated_at='".date("Y-m-d H:i:s")."',
								groupid='".$lstgroup."'
				WHERE 
								uid='".$uid."'";
$urg=$con->updateRecords($updateusr_qry);

header("location: usermgr.php?upid=''");
exit();
}
else{

if(isset($_POST['btnsubmit']) && $_POST['btnsubmit']=='Save User')
{
   $chkuser_qry="Select uid From users where uid='".$uid."'";
    $flag=$con->getSingleRow($chkuser_qry,  __FILE__,  __LINE__);
	
	if(empty($flag))
	{

    $addusr_qry="INSERT INTO users (
								uid,
								email,
								password ,
								activated,
								activation_code,
								activated_at,
								last_login,
								persist_code,
								reset_password_code,
								first_name,
								last_name,
								created_at,
								updated_at,
								groupid
								)
						VALUES ('".$uid."','".$email."','".$psw."','".$isdisabled."', NULL , NULL , NULL , NULL ,  NULL,'".$fname."' , '".$lname."'  ,'".date("Y-m-d H:i:s")."','0000-00-00 00:00:00','".$lstgroup."')";

 $j=$con->insertRecords($addusr_qry,  __FILE__,  __LINE__);
 $er_msg=$j;
 
 }
 $_SESSION['usrerr']=$er_msg;
 header("location: usermgr.php");
	exit();
}
}
}
?>
