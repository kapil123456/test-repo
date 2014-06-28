<?php

require_once("lib/config.php");


$delete =array();
$fArray=array();
$fAr=array();
$ffile="";

//$source="\\\\192.168.0.100\\copyqinput\\";
 $source=INPUT_FOLDER_PATH;
$destination = 'storage/';

$srcFolder=INPUT_FOLDER_PATH;
$qry="";
$val_status="Fetching";
//pr($_REQUEST);

if(isset($_REQUEST['mrn']))
{
	$pqry="SELECT p_id,p_fname,p_lname,p_mrn from tblpatients where p_mrn='".$_REQUEST['mrn']."'";
	$fp=$con->getSingleRow($pqry,  __FILE__,  __LINE__);

	$ffile="fname,".$fp['p_fname']."_lname,".$fp['p_lname']."_mrn,".$fp['p_mrn'];

	$checkFetchStatus="Select * FROM tblfetch where f_pmrn=".$_REQUEST['mrn'];
	$statustmp=$con->getSingleRow($checkFetchStatus,  __FILE__,  __LINE__);

	$vcqry="SELECT sum(v_fcount) as v_fcount from tblvisits where v_mrn='".$_REQUEST['mrn']."'";
	$fcount=$con->getSingleRow($vcqry,  __FILE__,  __LINE__);

	//get count from tbl files
	$vfcqry="SELECT count(ppath) as f_count from tbltemp where pmrn='".$_REQUEST['mrn']."'";
	$vfcount=$con->getSingleRow($vfcqry,  __FILE__,  __LINE__);
 
	//GET COUNT OF RECORDS FROM TABLE FILE 
	$vfcqry1="SELECT count(p_id) as tblf_count from tblfile where p_id='".$fp['p_id']."'";
	$tblfcount=$con->getSingleRow($vfcqry1,  __FILE__,  __LINE__);
 
	// Display Progress
	if($statustmp['f_reqstatus']==0 &&  $fcount['v_fcount']!=$vfcount['f_count'] || $fcount['v_fcount']==0 )
		{
			echo "<div  >
				   <img id='imgLoad' src='images/loading.gif' alt='Fetching...' />
				  <br/>
				   files fetched =".$tblfcount['tblf_count']."/".$fcount['v_fcount']
				   ."</div><br/>";
		}

}
?>