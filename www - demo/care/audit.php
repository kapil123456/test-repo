<?php 

require_once('lib/config.php');

pr($_POST);
if(isset($_SESSION['username']))
{
	/*  

				$qry="INSERT INTO 
		              tbllog(id,datelog, ipaddress,user, accesstype, patient, details) 
		              VALUES (NULL, '".date("Y-m-d H:i:s")."','".getIP() ."','".$_SESSION['username']."','". $atype."','". $pt."','". $details."')";

			$i=$con->insertRecords($qry,  __FILE__,  __LINE__);*/
		
}	
?>
