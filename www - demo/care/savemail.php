<?php
require_once('lib/config.php');

if($_POST)
{
$id=$_POST['e_id'];
$smtp=$_POST['emailHost'];
$eid=$_POST['email'];
$psw=$_POST['psw'];
$efrom=$_POST['emailFrom'];
$eto=$_POST['emailTo'];
$esub=$_POST['emailSub'];
$ebody=$_POST['emailBody'];
$euid=$_SESSION['username'];


$getemail_gry="SELECT e_id,e_smtp,e_eid,e_psw,e_from,e_to,e_sub,e_body,e_uid FROM emailconf where e_uid='".$euid."'";
$edata=$con->getMultiRow($getemail_gry,  __FILE__,  __LINE__);
if(empty($edata))
{
$savqry="INSERT INTO emailconf(e_id,e_smtp,e_eid,e_psw,e_from,e_to,e_sub,e_body,e_uid) 
        values(NULL,'".$smtp."','".$eid."','".$psw."','".$efrom."','".$eto."','".$esub."','".$ebody."','".$euid."')";

 $y=$con->insertRecords($savqry,  __FILE__,  __LINE__);
}
else
{
 $updqry="UPDATE emailconf SET
 							e_smtp='".$smtp."',
							e_eid='".$eid."',
							e_psw='".$psw."',
							e_from='".$efrom."',
							e_to='".$eto."',
							e_sub='".$esub."',
							e_body='".$ebody."',
							e_uid='".$euid."'
						where  e_id='".$id."'";
 $j=$con->updateRecords($updqry,  __FILE__,  __LINE__);
 
 }
 header("location: emailconf.php");
 exit();

}


?>
