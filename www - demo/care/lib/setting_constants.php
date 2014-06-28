<?php
	
	
	$qry1="SELECT input_folder_path FROM tblglobalsettings";
	$ipath=$con->getSingleCell($qry1,  __FILE__,  __LINE__);
	define('INPUT_FOLDER_PATH',$ipath);
	$qry2="SELECT storage_folder_path FROM tblglobalsettings";
	$spath=$con->getSingleCell($qry1,  __FILE__,  __LINE__);
	define('STORAGE_FOLDER_PATH',$spath);
	
	
?>