<?php 

require_once('lib/config.php');



if(isset($_REQUEST['gid']) && $_REQUEST['gid']!='')
{
	$er='';

	$chkIfAdmin="Select groupname from groups where id='".$_REQUEST['gid']."'";
	$flag=$con->getSingleRow($chkIfAdmin,  __FILE__,  __LINE__);
		//pr($flag);	
	$chkIfUserInGroup="Select uid from users where groupid='".$flag['groupname']."'";
	
	
	$ifug=$con->getMultiRow($chkIfUserInGroup,  __FILE__,  __LINE__);	
		
		  if(empty($ifug))
			{
			    if($flag['uid']!='App Admin' && $flag['uid']!='app admin')
				{
					$delPermissionsQry="DELETE FROM permissions WHERE gid='".$_REQUEST['gid']."'";
					$delPerm=$con->deleteRecords($delPermissionsQry);
						//echo $delPerm;
					
					$delGroupQry="DELETE FROM groups WHERE id='".$_REQUEST['gid']."'";
					$delGrp=$con->deleteRecords($delGroupQry);
				}
				else
				{
				$er='1';
				}
			}
			else
			{
			 $er='1';
			}
			if(isset($er) && $er=='1')
			{
				$_SESSION['ergrp']=$er;
				header("location: usrmgt.php");
				exit();
			}
			else
			{
				header("location: usrmgt.php");
				exit();
			}
}

if(isset($_REQUEST['udid']) && $_REQUEST['udid']!='')
{
	$err='';	
	
	$chkIfUser="Select uid from users where uid='".$_REQUEST['udid']."'";
	$flagu=$con->getSingleRow($chkIfUser,  __FILE__,  __LINE__);
		
			if($flagu['uid']!='Administrator' && $flagu['uid']!='administrator')
			{

				$delUserQry="DELETE FROM users WHERE uid='".$_REQUEST['udid']."'";
				$delPerm=$con->deleteRecords($delUserQry);
				
			}
			else
			{
			 $err='1';
			}
if(isset($err) && $err=='1')
{
$_SESSION['usrerr']=$err;
header("location: usermgr.php");
exit();
}
else
{
header("location: usermgr.php");
exit();
}


}


?>

