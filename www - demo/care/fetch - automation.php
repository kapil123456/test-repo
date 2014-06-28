<?php
require_once("lib/config.php");

$mrn="";
if(isset($_REQUEST['p_mrn']))
{
$mrn=$_REQUEST['p_mrn'];
}
$qry="";

    //inserting a record in fetch table
	$qry_savefetchreq="
    INSERT INTO tblfetch(f_pmrn,f_reqstatus,f_locked,f_error,f_user,f_date) VALUES ('".$mrn."','0','0','0','".$_SESSION['username']."','".date("Y-m-d H:i:s")."')";
    $fetch=$con->insertRecords($qry_savefetchreq,  __FILE__,  __LINE__);
	

    
	//Retriving P_ID from patienttable
    $pidqry="SELECT p_id from tblpatients where p_mrn='".$mrn."'";
    $p_pid=$con->getSingleRow($pidqry,  __FILE__,  __LINE__);


	$tmptblqry="SELECT p_lname,p_fname,p_mname,p_dob,p_mrn,p_id FROM tblpatients where p_id='".$p_pid['p_id']."'";
	$ftmp=$con->getSingleRow($tmptblqry,  __FILE__,  __LINE__);
	
	//Automating updating the fetch request as OK
			if($fetch)
		{
			$fupdateqry="update tblfetch set f_reqstatus='OK' where f_pmrn='".$ftmp['p_mrn']."'";
	$fupstatus=$con->updateRecords($fupdateqry, __FILE__,  __LINE__);	
		}
	
	//end of Automation
	
	//inserting the record in temptable
	$qry_svfetchparm="
	INSERT INTO tmptable (t_lname, t_fname, t_mname, t_mrn, t_dob, t_pid) VALUES ('".$ftmp['p_lname']."','".$ftmp['p_fname']."','".$ftmp['p_mname']."','".$ftmp['p_mrn']."','".$ftmp['p_dob']."','".$ftmp['p_id']."')";
	$fetchparm=$con->insertRecords($qry_svfetchparm,  __FILE__,  __LINE__);
	
	//updating the tblpatients to FETCHED
	$pstatusqry="update tblpatients set p_type='FETCHED' where p_id='".$p_pid['p_id']."'";
	$tstatus=$con->updateRecords($pstatusqry, __FILE__,  __LINE__);

	if($fetch && $fetchparm && $tstatus)
		{
				echo "<div><br/>Fetch requested successfully.</div><br/>";
		}
	
//$_SESSION['er']=$msg;

//exit();	
?>