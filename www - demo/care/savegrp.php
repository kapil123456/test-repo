<?php
require_once('lib/config.php');


    $groupname='';
	$allowview=0;
    $allowprint=0;
    $allowsave=0;
    $allowaudit=0;
    $allowtickets=0;
    $allowadmin=0;
    $allowconf=0;
    $allowdemo=0;
	$er_msg=0;
	$permid="";
	if(isset( $_POST['groupname']))
	{
		$groupname=$_POST['groupname'];
	}
	if(isset( $_POST['allowview']))
	{
		$allowview=1;
	}
	if(isset( $_POST['allowprint']))
	{
		$allowprint=1;
	}
    if(isset( $_POST['allowsave']))
	{
		$allowsave=1;
	}
     if(isset( $_POST['allowaudit']))
	{
		$allowaudit=1;
	}
      if(isset( $_POST['allowtickets']))
	{
		$allowtickets=1;
	}
    if(isset( $_POST['allowadmin']))
	{
		$allowadmin=1;
	}
	 if(isset( $_POST['allowconf']))
	{
		$allowconf=1;
	}
     if(isset( $_POST['allowdemo']))
	{
		$allowdemo=1;
	}
	 if(isset( $_POST['permid']))
	{
		$permid=$_POST['permid'];
	}
	
	
	//pr($_POST);
	if(isset($_POST['btnUpdate']) && $_POST['btnUpdate']=='Update')
	{
	
	 $updatePermissionsQry="UPDATE PERMISSIONS set grp_id='".$groupname."',allow_view='".$allowview."',allow_print='".$allowprint."',allow_save='".$allowsave."',allow_audit='".$allowaudit."',allow_tickets='".$allowtickets."',allow_usrmgt='".$allowadmin."',allow_demo='".$allowdemo."',allow_conf='".$allowconf."' where gid='".$permid."'"; 
	 $urp=$con->updateRecords($updatePermissionsQry);	
	
  
	$updateGroupQry="UPDATE groups set groupname='".$_POST['groupname']."' where id='".$permid."'";
    $urg=$con->updateRecords($updateGroupQry);	
 	
		header("location: usrmgt.php?gid=''");
	
	}
	else{
	
	if(isset($_POST['btnsubmit']) && $_POST['btnsubmit']=='Save')
	{
	
	$qry_chkgrp="SELECT groupname FROM GROUPS WHERE groupname='".$groupname."'";
	$flag=$con->getSingleRow($qry_chkgrp,  __FILE__,  __LINE__);
	
	if(empty($flag))
	{
	 $qrygrp="INSERT INTO GROUPS(id,groupname,created_at,updated_at) 
	 					values(null,'".$groupname."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
	 $i=$con->insertRecords($qrygrp,  __FILE__,  __LINE__);
	
	$qry_grp="SELECT id FROM GROUPS WHERE groupname='".$groupname."'";
	$grpi=$con->getSingleRow($qry_grp,  __FILE__,  __LINE__);
	//pr($grpi);
	
	$qryprm="INSERT INTO PERMISSIONS(pr_id,grp_id,gid,allow_view,allow_print,allow_save,allow_audit,allow_tickets,allow_usrmgt,allow_demo,allow_conf)  VALUES (NULL,'".$groupname."','".$grpi['id']."','".$allowview."','".$allowprint."','".$allowsave."','".$allowaudit."','".$allowtickets."','".$allowadmin."', '".$allowdemo."', '".$allowconf."')";
	 $j=$con->insertRecords($qryprm,  __FILE__,  __LINE__);
	
	$er_msg=$j;
	
		}
		$_SESSION['ergrp']=$er_msg
			header("location: usrmgt.php");
			exit();
	}



}
	
	
	
	


?>


