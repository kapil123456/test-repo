<?php
	@session_start();
	$dontIncludeLibs = array();
	$jqGridSearchFilterMap = array('eq'=>" = '__SVAL__'",'lt'=>" < '__SVAL__'",'le'=>" <= '__SVAL__'",'gt'=>" > '__SVAL__'",'ge'=>" >= '__SVAL__'",'ne'=>" != '__SVAL__'",'bw'=>" LIKE '__SVAL__%'",'bn'=>" NOT LIKE '__SVAL__%'",'ew'=>" LIKE '%__SVAL__'",'en'=>" NOT LIKE '%__SVAL__'",'cn'=>" LIKE '%__SVAL__%'",'nc'=>" NOT LIKE '%__SVAL__%'",'in'=>" IN (__SVAL__)",'ni'=>" NOT IN (__SVAL__)");
	
	
	require_once("commonFunctions.php");
	require_once('constants.php');
	require_once('dbTables.php');
	
	/* THIS VARIABLE IS TO BE USED IN THE CONNECTION MANAGER FILE SO PLACE IT BEFORE THE INCLUSION OF connectionMgr.php*/
	$isAjaxRequest = false;
	if(isset($_POST['ajx_request_type']))
		$isAjaxRequest = true;

	require_once("connectionMgr.php");
	require_once('setting_constants.php');


	if(!isset($ldappath) ||  $ldappath == 'Y'){
		require_once('./adLDAP/src/adLDAP.php');
		}
		
	$_POST = array_map('verifyValues',$_POST);
	$_GET = array_map('verifyValues',$_GET);
	//if(!isset($ldappath) ||  $ldappath == 'Y')
	$userIP = getIP();

/*	$getFilePath="Select * from tblglobalsettings";
	$datapaths=$con->getSingleRow($getFilePath,  __FILE__,  __LINE__);


	$srcFolder='./'.$datapaths['input_folder_path'];
	$destZFolder='./'.$datapaths['storage_folder_path'];	*/
	$filePath = "c:/wamp/www/care/uploads/";
	
	/*Domain Configurations dynamic setteings*/
	$getPDC="Select domain from domain";
	$dmn=$con->getSingleRow($getPDC,  __FILE__,  __LINE__);
	$pdc=explode('.',$dmn['domain']);
	$dc=$pdc['0'];
	$dx=$pdc['1'];
	$asfx="@".$dmn['domain'];
	$domain=$dmn['domain'];
		
	if(!isset($ldappath) ||  $ldappath == 'Y'){
    $adldap = new adLDAP(array('base_dn'=>'DC='.$dc.',DC='.$dx , 'account_suffix'=>$asfx));
	}
?>