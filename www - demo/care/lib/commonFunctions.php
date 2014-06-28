<?php
	function pr($arr, $return = FALSE)
	{
		if(SITE_AT == 'dev')
			$text = "<pre style='width:98%; padding:0.5%; margin:0.5%; background-color:#e3e3e3; border:1px solid #666;'>".print_r($arr, true)."</pre>";
			
		if($return)
			return $text;
		else
			echo $text;
	}
	function getIP()
	{
		if(isset($_SERVER))
		{
			if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && eregi("^[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}$",$_SERVER['HTTP_X_FORWARDED_FOR']))
			{
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
			elseif ( isset($_SERVER['HTTP_CLIENT_IP']))
			{
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			}
			else 
			{
				$ip = $_SERVER['REMOTE_ADDR'];
			}
		}
		else
		{
			if (getenv('HTTP_X_FORWARDED_FOR'))
			{
				$ip = getenv('HTTP_X_FORWARDED_FOR');
			}
			elseif ( getenv('HTTP_CLIENT_IP') )
			{
				$ip = getenv('HTTP_CLIENT_IP');
			}
			else 
			{
				$ip = getenv('REMOTE_ADDR');
			}
		}
		return $ip;
	}
	function verifyValues($value)
	{
		global $con;
		if(is_array($value))
			return $value = array_map('verifyValues',$value);
			
		if(is_string($value))
			$value = trim($value);
		
		if(get_magic_quotes_gpc())
			$value = stripslashes($value);
		
		$value = str_replace(array('<script>','</script>','<_script>','</_script>'),'',$value);
		$value = str_replace(array('%3C','%3E','<','>'),'',$value);
		
		$value = strtr( $value, array_flip(get_html_translation_table(HTML_ENTITIES)));
		$value = strip_tags( $value);
		if(isset($con->conLink ))
			$value = mysqli_real_escape_string($con->conLink, $value);
		return $value;
	}
	function encodeData($value)
	{
		$alpha_arr = array('C','O','N','D','M','E','S','L');
		
		$secEncoded = $value;
		$num = rand(0,7);
		
		for($i=1; $i <$num ; $i++)
		{
			$secEncoded = base64_encode($secEncoded);
		}
		
		$secEncoded = $secEncoded . '+' . $alpha_arr[$num];
		
		return $secEncoded = base64_encode($secEncoded);
	}
	function decodedData($value)
	{
		$alpha_arr = array('C','O','N','D','M','E','S','L');
		
		$decoded = base64_decode($value);
		
		list($decoded, $letter) = explode('+',$decoded);
		
		for($i = 0; $i < count($alpha_arr) ; $i++)
		{
			if($alpha_arr[$i] == $letter)
				break;
		}
		
		for($j = 1; $j <$i; $j++)
		{
			$decoded = base64_decode($decoded);
		}
		
		return $decoded;
	}
	function getGridSearchClause($params)
	{
		global $jqGridSearchFilterMap;
		
		$fieldMap		= $params['fieldMap'];
		$searchField	= $fieldMap[$params['searchField']];
		$searchOper		= $jqGridSearchFilterMap[$params['searchOper']];
		$searchString	= $params['searchString'];
		$startWith		= isset($params['startWith']) && $params['startWith']!=''?" ".$params['startWith']." ":" AND ";
		
		if($params['searchOper'] == 'in' || $params['searchOper'] == 'ni')
		{
			$strings = explode(',',$searchString);
			$strings = array_map('trim',$strings);
			$searchString = "'".implode("','",$strings)."'";
		}
		
		$searchOper	= str_replace('__SVAL__',$searchString,$searchOper);
		
		return $searchClause 	= $startWith.$searchField.$searchOper; 
	}
	function redirectPage($pagename,$postmsg='',$returnVariables='')
	{
		echo "Loading....";
		echo ("<form action='". $pagename ."' method='post' name='frmRdirect' id='frmRdirect'>\n");
		echo ("<input name='msg' id='msg' type='hidden' value='$postmsg'>\n");
		if(is_array($returnVariables))
		{
			foreach($returnVariables as $key=>$val)
			{
				echo ("<input name='$key' id='$key' type='hidden' value='$val'>\n");
			}
		}
		echo "</form>\n";
		echo ("<script type='text/javascript'>\n");		
		echo ("document.getElementById('frmRdirect').submit();\n");
		echo ("</script>\n");
		exit;
	}
	function showMessage($params)
	{
		$msgType = $params['type'];
		$msg = $params['msg'];
		
		return "<div class='inlMsg inlMsg".$msgType."'>".$msg."</div>";
	}
//=======================================================================
	function errorHandeler($errNo)
	{
		$path='include/messages.xml';	
		$count=0;	
		if (file_exists($path)) 
		{
			if(is_readable($path))
			{
				$xml = simplexml_load_file($path);
				foreach($xml->MSG as $a)
				{
					echo $a;
					if($xml->MSG[$count]->ID==$errNo)
						return $xml->MSG[$count]->DESC; 
					$count=$count+1;
				}
			}
		}
		return false;
	}
//========================================
function is_valid_ip($ip, $include_priv_res = true)
{
    return $include_priv_res ?
        filter_var($ip, FILTER_VALIDATE_IP) !== false :
        filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false;
}
function ValidateEmail($email)
{

$regex = '/([a-z0-9_.-]+)'. # name

'@'. # at

'([a-z0-9.-]+){2,255}'. # domain & possibly subdomains

'.'. # period

'([a-z]+){2,10}/i'; # domain extension 

if($email == '') { 
	return false;
}
else {
$eregi = preg_replace($regex, '', $email);
}

return empty($eregi) ? true : false;
}

//===================================================
	function checkBoxVal($chkBoxName)
	{
		if(isset($_POST[$chkBoxName]))
			 return $_POST[$chkBoxName];
		else
			return 0;
	} 
//=====================================================================	
	function getVar($var)
	{
		$rVar=$_REQUEST[$var];
		$rVar=str_replace("'","''",trim($rVar));
		$rVar=str_replace('"','""',$rVar);
		return $rVar;
	}
//=================================================================================	
function dateCheck($arrayDate)
{

	for($count=0;$count < count($arrayDate);$count++)
	{
		$arr=split('/',$arrayDate[$count]);
		$arrVal=mktime(0, 0, 0, $arr[0],$arr[1],$arr[2]);
		$arrayDate[$count]=$arrVal;
	}
	asort($arrayDate);
	foreach($arrayDate as $Key=>$val)
	{
		return  $Key;
	}
}
//================================================================================
function mySqlDateFormat($rdate)
{
	list($day, $month,$year) = split('[/.-]', $rdate);
	return $year."/".$month."/".$day;
}
function dateFormat($rdate,$formatType = '')
{
	$date = '';
	if(!is_null($rdate))
	{
		switch($formatType)
		{
			case "1":
				$date = date('j M Y g:i:s a',strtotime($rdate));
			break;
			case "2":
				if( ($rdate - 60) < 0)
					$date = $rdate .' seconds';
				else
				{
					$date = (floor($rdate/60) < 10 ? '0' : '').floor($rdate/60).':'.(($rdate%60) < 10 ? '0' : '').($rdate%60);
				}
				
			break;
			default:
				list($year, $month,$day) = split('[/.-]', $rdate);
				$date = $day."/".$month."/".$year;
			break;
		}
	}
	return $date;
}
//=============================For Listing===========================
function splitText($text,$length)
{
		$shortText='';
		if(strlen($text)>$length)
		{
			$shortText=substr($text,0,$length);
			$shortText=$shortText.'...';
			$shortText="<span title='$text'>$shortText</span>";
			return $shortText;
		}
		else
		return $text;
}
//=======Seting secuirty=======================================================
function setMemberSecuirty($page_name = "")
{
	@session_start();
	$arr = array('pagName'=>$page_name);
	if(!isset($_SESSION['member_id']) || $_SESSION['member_id'] <= 0)
		redirectPage("login.php",ERROR_LOGIN_TO_ACC, $arr);
}


//========================Date Format===============================================
 function dateFormat2($rdate)
{
 if(!is_null($rdate))
 {
  list($year, $month,$day) = split('[-./]', $rdate);
  return $day."-".$month."-".$year;
 }
 return '';
}

//======================== No of days between two dates ===============================================

function dateDiff($dateStart, $dateEnd) 
{
    $start = strtotime($dateStart);
    $end = strtotime($dateEnd);
    $days = $end - $start;
    $days = ceil($days/86400);
    return $days;
}

function getSelectOptions($data, $key, $val,$addSelectOne = 'Y')
{
	$totData = count($data);
	
	$retStr = '';
	
	if($addSelectOne == 'Y')
		$retStr = '<option value="">- Select One -</option>';
		
	for($d = 0;$d<$totData; $d++)
	{
		$retStr .= '<option value="'.$data[$d][$key].'">'.$data[$d][$val].'</option>';
	}
	
	return $retStr;
}

function getCommaSeperatedValues($data)
{
	$totData = count($data);
	$counter = 0;
	$retStr = '';
	if($totData>0)
	{
		foreach($data as $key=>$value)
		{
			foreach($value as $key2=>$value2)
			{
				if($counter==0)
				{
					$retStr = $value2;
					$counter++;
				}
				else
				{
					$retStr .= ",".$value2;
					$counter++;
				}
			}
		}
	}
	return $retStr;
}

function getProtocolTypes($params=array())
{
	global $con;
	
	$qType = isset($params['type']) ? $params['type'] : '';
	$fields = isset($params['fields']) ? $params['fields'] : '*';
	 
	$selQry = "SELECT $fields FROM ".TBL_PROTOCOL_TYPE." ORDER BY order_by"; 
	
	if( isset($_SESSION['getProtocolTypes'.$qType]) && $_SESSION['getProtocolTypes'.$qType] != '')
	{
		return $_SESSION['getProtocolTypes'.$qType];
	}
	else
	{
		$retArr = $con->getMultiRow($selQry);
		$_SESSION['getProtocolTypes'.$qType] = $retArr;
	}	
	return $retArr;
}
function getEntityTypes($params=array())
{
	global $con;
	$qType = isset($params['type']) ? $params['type'] : '';
	 
	$selQry = "SELECT * FROM ".TBL_ENTITY_TYPES." ORDER BY entity_level"; 
	
	if(isset($_SESSION['getEntityTypes'.$qType]) && $_SESSION['getEntityTypes'.$qType] != '')
	{
		return $_SESSION['getEntityTypes'.$qType];
	}
	else
	{
		$entTypes = $con->getMultiRow($selQry);
		$totEnt = count($entTypes);
	
		unset($_SESSION['getEntityTypes'.$qType]);
		$retArr = array();
		for($e = 0; $e < $totEnt ; $e++)
		{
			if($qType == 'longcodekey')
				$retArr[strtolower($entTypes[$e]['entity_type_code'])] = $entTypes[$e];
			elseif($qType == 'shortcodekey')
				$retArr[strtolower($entTypes[$e]['entity_type_short_code'])] = $entTypes[$e];
			elseif($qType == 'shortcodelvl')
				$retArr[strtolower($entTypes[$e]['entity_type_short_code'])] = $entTypes[$e];
			elseif($qType == 'imdchild')
				$retArr[$entTypes[$e]['entity_type_code']] = (isset($entTypes[$e+1]) ? $entTypes[$e+1]['entity_type_code'] : '');
			else
				$retArr[$entTypes[$e]['entity_type_code']] = $entTypes[$e]['entity_type_name'];
		}
		$_SESSION['getEntityTypes'.$qType] = $retArr;
	}	
	return $retArr;
}
function getDevTypes($type='')
{
	if($type == 'keyvalar')
	{
		return array( 	'Server'=>0,
						'PDU'=>1,
						'UPS'=>2,
						'Network Device'=>3,
						'Chassis'=>4,
						'Unmanaged Server'=>5,
						'Unmanaged Network Device'=>6,
						'Unmanaged Storage Device'=>7
					);
	}
	else if($type == 'devT4Adding')
	{
		return array( 	0=>'NODE',
						1=>'NODE',
						2=>'NODE',
						3=>'NODE',
						4=>'ENCLOSURE',
						5=>'NODE',
						6=>'NODE',
						7=>'NODE'
					);
	}
	else
	{
		return array( 	array( 'id'=>0,'value'=>'Server'),
						array( 'id'=>1,'value'=>'PDU'),
						array( 'id'=>2,'value'=>'UPS'),
						array( 'id'=>3,'value'=>'Network Device'),
						array( 'id'=>4,'value'=>'Chassis'),
						array( 'id'=>5,'value'=>'Unmanaged Server'),
						array( 'id'=>6,'value'=>'Unmanaged Network Device'),
						array( 'id'=>7,'value'=>'Unmanaged Storage Device')
					);
	}
}
function getOSTypes()
{
	return array( 	array( 'id'=>'','value'=>'None'),
					array( 'id'=>'Windows','value'=>'Windows'),
					array( 'id'=>'Linux','value'=>'Linux'),
					array( 'id'=>'XEN','value'=>'XEN'),
					array( 'id'=>'ESX','value'=>'ESX')
				);
}

function getParentInfo($params)
{
	global $con;
	
	$parent_id = $params['pid'];
	$parent_for_type = $params['parent_for_type'];
	
	$selQry = "SELECT grp_name, dcm_entity_id, grp_desc, grp_type, grp_entity_code FROM ".TBL_GROUPS." WHERE grp_id = '".$parent_id."' LIMIT 1";
	$parentData = $con->getSingleRow($selQry, __FILE__,__LINE__);
	
	return $parentData;
}

function convert2Dto1Darr($arr,$key)
{
	$retData = array();
	
	$totData = count($arr);
	for($i=0; $i<$totData ;$i++)
	{
		$retData = array_merge($retData,array($arr[$i][$key]));
	}
	return $retData;
}
function convertTo1DProp($arr)
{
	$retData = array();
	
	$totData = count($arr);
	for($i=0; $i<$totData ;$i++)
	{
		$retData = array_merge($retData,array($arr[$i]->name => $arr[$i]->value));
	}
	return $retData;
}

function getEntityParents($params)
{
	global $con;
	$entityId 	 	= (isset($params['entity_id'])? $params['entity_id'] : '');
	$dcm_entity_id 	 	= (isset($params['dcm_entity_id'])? $params['dcm_entity_id'] : '');
	
	if($entityId == '')
	{
		if( $dcm_entity_id != '' )
		{
			$entityIdClause = " AND de.dcm_entity_id='".$dcm_entity_id."'";
		}
		else
		{
			return false;
		}
	}
	else
	{
		$entityIdClause = " AND deh.entity_id='".$entityId."'";
	}
	
	$hirarchy_level = $params['hirarchy_level'];
	$type			= (isset($params['type']) && $params['type']!= '' ? $params['type'] : 'singleParent');
	$encodedString	= (isset($params['encodedString']) && $params['encodedString']!= '' ? $params['encodedString'] : 'Y');
	
	$selQry = "SELECT deh.entity_id, deh.parent_entity_id, deh.hirarchy_level, deh.entity_type, de.assigned_to_type, de.entity_name FROM ".TBL_ENTITY_HIERARCHY." deh, ".TBL_ENTITY." de WHERE deh.entity_id = de.eid ".$entityIdClause." AND deh.hirarchy_level='".$hirarchy_level ."' LIMIT 1";
	
	$parentInfo = $con->getSingleRow($selQry,__LINE__,__FILE__);

	if(isset($parentInfo['entity_id']))
	{
		$entityId = $parentInfo['entity_id'];
		$parentIdArr = array();
		if(isset($parentInfo['parent_entity_id']) )
		{
			$parentId = $parentInfo['parent_entity_id'];
			
			if($type == 'singleParent')
			{
				$parentInfo['hirarchy_level'] = ($parentInfo['hirarchy_level'] == '7' ? $parentInfo['hirarchy_level']-2 : $parentInfo['hirarchy_level']-1);
				$selQry = "SELECT de.entity_id, de.parent_entity_id, de.hirarchy_level, de.entity_type, g.grp_name FROM ".TBL_ENTITY_HIERARCHY." de, ".TBL_GROUPS." g WHERE g.grp_id = de.entity_id AND de.entity_id='".$parentId."' AND hirarchy_level='".$parentInfo['hirarchy_level'] ."' LIMIT 1";
				$parentInfo = $con->getSingleRow($selQry,__LINE__,__FILE__);
				$parentIdArr = $parentInfo;
			}
			elseif($type == 'list2top' || $type == 'list2topwithname')
			{
				$parentInfoArr = array();
				
				if($type == 'list2topwithname')
					$parentInfoArr[] = $parentInfo['entity_name'];
					
				$parentIdArr[] = $parentInfo['entity_id'].'|##|'.$parentInfo['hirarchy_level'];
				$parentInfo['hirarchy_level'] = ($parentInfo['assigned_to_type'] == 'RACK' && $parentInfo['entity_type'] == 'NODE' ? $parentInfo['hirarchy_level']-1: $parentInfo['hirarchy_level']);
				do
				{
					$selQry = "SELECT entity_id, parent_entity_id, hirarchy_level, entity_type FROM ".TBL_ENTITY_HIERARCHY." where entity_id = '".$parentId."' AND hirarchy_level='".($parentInfo['hirarchy_level']-1)."' LIMIT 1";
					$parentInfo = $con->getSingleRow($selQry,__LINE__,__FILE__);
					
					if(isset( $parentInfo['parent_entity_id']))
					{
						$parentId = $parentInfo['parent_entity_id'];
						if( $type == 'list2topwithname' )
						{
							if($parentInfo['entity_type'] == 'ENCLOSURE' || $parentInfo['entity_type'] == 'NODE' )
							{
								$selQry = "SELECT entity_name FROM ".TBL_ENTITY." WHERE eid = '".$parentInfo['entity_id']."' LIMIT 1";
								$parentName = $con->getSingleCell($selQry, __LINE__,__FILE__);
								$parentInfoArr[] =  $parentName;
							}
							else
							{
								$selQry = "SELECT grp_name FROM ".TBL_GROUPS." WHERE grp_id = '".$parentInfo['entity_id']."' LIMIT 1";
								$parentName = $con->getSingleCell($selQry, __LINE__,__FILE__);
								$parentInfoArr[] =  $parentName;
							}
						}
						else
						{
							$parentIdArr[] =  $parentInfo['entity_id'] .'|##|'.$parentInfo['hirarchy_level'];
						}
					}
				}
				while( isset($parentInfo['parent_entity_id']) && $parentInfo['parent_entity_id'] > 0 && $parentInfo['entity_type'] != 'DATACENTER');
				
				if( $type == 'list2topwithname' )
				{
					$parentInfoArr = array_reverse($parentInfoArr);
					$parentIdArr['enclnk'] = encodeData($parentIdArr[0]) ;
					$parentIdArr['names'] = implode(' -> ',$parentInfoArr);
				}
			}
		}
		else
		{
			$parentIdArr[] = '|##|';
		}
	
	}
	else
	{
		$parentIdArr[] = '|##|';
	}
	if($encodedString == 'YS')
		return  encodeData(implode('|~|',$parentIdArr));
	else
		return $parentIdArr;
}

function getChildren($params)
{
	global $con;
	
	$type = (isset($params['type']) ? $params['type'] : 'singleChild');
	$grp_id = $params['gid'];
	$grp_level = $params['glvl'];
	$nodesFound = 'N';
	do
	{
		$selQry = "SELECT DISTINCT entity_id,dcm_entity_id, hirarchy_level, entity_type FROM ".TBL_ENTITY_HIERARCHY." WHERE parent_entity_id IN ('".$grp_id."') AND hirarchy_level > '".$grp_level."'";
		$children = $con->getMultiRow($selQry, __LINE__,__FILE__);
		
		if($type == 'singleChild')
		{
			return $children;
		}
		
		if(count($children) > 0)
		{
			$grp_id = implode("','",convert2Dto1Darr($children,'entity_id'));
			$grp_level = max(convert2Dto1Darr($children,'hirarchy_level'));
			$entityTypes = array_unique(convert2Dto1Darr($children,'entity_type'));

			if($type == 'RACK' && in_array('RACK',$entityTypes))
				return $children;
			if($type == 'ROW' && in_array('ROW',$entityTypes))
				return $children;
			if($type == 'ROOM' && in_array('ROOM',$entityTypes))
				return $children;
				
			if(in_array('NODE',$entityTypes))
			{
				$totChildren = count($children);
				for($child = 0;$child<$totChildren;$child++)
				{
					if($children[$child]['entity_type'] == 'ENCLOSURE')
					{
						$selQry = "SELECT DISTINCT entity_id,dcm_entity_id, hirarchy_level, entity_type FROM ".TBL_ENTITY_HIERARCHY." WHERE dcm_parent_entity_id ='".$children[$child]['dcm_entity_id']."' ";
						$encchildren = $con->getMultiRow($selQry, __LINE__,__FILE__);
						unset($children[$child]);
						if(is_array($encchildren) && count($encchildren) > 0)
							 $children = array_merge( $children,$encchildren);
					}
				}
				if($type == 'NODE')
					return $children;
				else
					return $nodesFound = 'Y';
			}
		}	
	}while(count($children) > 0 && $children[0]['hirarchy_level'] < 8);

	return $nodesFound;
}

function getChildrenMetricData($params)
{
	global $con;
	
	$entity_id = $params['entity_id'];
	$entity_type = $params['entity_type'];
	$hirarchy_level = $params['hirarchy_level'];
	
	$childrenRacks = getChildren(array('type'=>'RACK','gid'=>$entity_id,'glvl'=>$hirarchy_level));
		
	if(is_array($childrenNodes))
	{
		$childrenEntityIds = implode("','",convert2Dto1Darr($childrenNodes,'dcm_entity_id'));
		$childrenRackEntityIds = implode("','",convert2Dto1Darr($childrenRacks,'entity_id'));
		
		echo $selQry = "SELECT SUM(power_total) FROM ".TBL_RACKS_INFO." WHERE rack_id IN ('".$childrenRackEntityIds."')";
	}
}

function getAddEntityProperties($params)
{
	global $intelAPI;

	$isArr = 'N';
	if(is_array($params['data']))
		$isArr = 'Y';
	
	$address = ( $isArr == 'Y' ? $params['data']['address'] : $params->address );
	$protType = ( $isArr == 'Y' ? $params['protType'] : 'IPMI' );
	$connectorName = ( $isArr == 'Y' ? $params['data']['connectorName'] : $params->connectorName );
	$deratedPwr = ($isArr == 'Y' && isset($params['data']['deratedPwr']) ?  $params['data']['deratedPwr'] : 300);
	$nameplatePwr = ( $isArr == 'Y' && isset($params['data']['nameplatePwr']) ?  $params['data']['nameplatePwr'] : 300);
	$entityType = ( ( $isArr == 'Y' && isset($params['data']['deviceType']) && $params['data']['deviceType'] == 'Enclosure' )  || (isset($params->deviceType) && $params->deviceType == 'Enclosure') ? 'ENCLOSURE' : 'NODE');
	
	$username = '';
	if(isset($params['snmp_user']))
		$username = $params['snmp_user'];
	elseif(isset($params['wsu']))
		$username = $params['wsu'];
	elseif(isset($params['ssh_username']))
		$username = $params['ssh_username'];
	elseif(isset($params['iband_username']))
		$username = $params['iband_username'];
	elseif(isset($params['https_username']))
		$username = $params['https_username'];
	
	$password = '';
	if(isset($params['snmp_auth_pass']))
		$password = $params['snmp_auth_pass'];
	elseif(isset($params['wsp']))
		$password = $params['wsp'];
	elseif(isset($params['ssh_pass']))
		$password = $params['ssh_pass'];
	elseif(isset($params['iband_pass']))
		$password = $params['iband_pass'];
	elseif(isset($params['https_pass']))
		$password = $params['https_pass'];
		
	$enc_password = (isset($params['snmp_enc_pass']) ? $params['snmp_enc_pass'] : '');
	
	$com_string = (isset($params['snmp_com_str']) ? $params['snmp_com_str'] : '');
	
	$port = '';
	if(isset($params['ssh_port']))
		$port = $params['ssh_port'];
	elseif(isset($params['https_port']))
		$port = $params['https_port'];
	
	$addEntityParams = array(	
						'entityType'=>$entityType, 
						'forceAddition'=>false,
						'properties'=>array()
					);
	switch($protType)
	{
		case "IPMI":
			$addEntityParams['properties'] =array(
							'BMC_ADDRESS'=> $address,
							'CONNECTOR_NAME'=>$connectorName,
							'DERATED_PWR'=>$deratedPwr,
							'NAMEPLATE_PWR'=>$nameplatePwr
						);
			
		break;
		case "SNMPv1v2c":
			$connectorInfo =  $intelAPI->GetConnectorPropertySet(array('uname'=>$connectorName));
			$totInfo = count($connectorInfo);
			
			for($c=0;$c<$totInfo;$c++)
			{
				if($connectorInfo[$c]->name == 'SNMP_PROTOCOL')
				{
					$optionsConnector = $connectorInfo[$c]->options;
					$totOptionsConnector = count($optionsConnector);
					
					for($oc=0;$oc <$totOptionsConnector;$oc++)
					{
						if($optionsConnector[$oc]->type == 'SELECTION')
						{
							$snmpProtocol = $optionsConnector[$oc]->value;
							if(strpos($snmpProtocol,'V2C') !== FALSE )
								$snmpProtocol = 'V2C';
							elseif(strpos($snmpProtocol,'V2') !== FALSE )
								$snmpProtocol = 'V2';
							elseif(strpos($snmpProtocol,'V1') !== FALSE )
								$snmpProtocol = 'V1';
						}
					}
				}
			}
			
			$addEntityParams['properties'] =array(
							'SNMP_ADDRESS'=>$address,
							'CONNECTOR_NAME'=>$connectorName,
							'DERATED_PWR'=>$deratedPwr,
							'NAMEPLATE_PWR'=>$nameplatePwr,
							'SNMP_COMMUNITY_STRING'=>$com_string,
							'SNMP_PROTOCOL'=>$snmpProtocol);
			
		break;
		case "SNMPv3":
			
			if($username !='')
				$props['SNMP_USER'] = $username;
			if($password !='')
				$props['SNMP_AUTHENTICATION_PASSWORD'] =  $password;
			if($enc_password !='')
				$props['SNMP_ENCRYPTION_PASSWORD'] = $enc_password;
		break;
		/*case "WS_MAN":
			
			if($activeJobs[$tsk]['wsprt'] !='')
				$props['SNMP_USER'] = $activeJobs[$tsk]['wsprt'];
			if($activeJobs[$tsk]['wsu'] !='')
				$props['SNMP_AUTHENTICATION_PASSWORD'] = $activeJobs[$tsk]['wsu'];
			if($activeJobs[$tsk]['wsp'] !='')
				$props['SNMP_ENCRYPTION_PASSWORD'] = $activeJobs[$tsk]['wsp'];
		break;*/
		case "SSH":
			
			$addEntityParams['properties'] = array(
							'SSH_ADDRESS'=>$address,
							'CONNECTOR_NAME'=>$connectorName,
							'SSH_USER'=>$username,
							'SSH_PASSWORD'=>$password,
							'SSH_PORT'=>$port);
		break;
		case "INBAND_PROTOCOL":
			
			if($username !='')
				$props['INBAND_USERNAME'] = $username;
			if($password !='')
				$props['INBAND_PASSWORD'] = $password;
		break;
		case "HTTPS":
			
			if($activeJobs[$tsk]['https_port'] !='')
				$props['HTTPS_PORT'] = $port;
			if($username !='')
				$props['HTTP_BASIC_USER'] = $username;
			if($password !='')
				$props['HTTP_BASIC_PASSWORD'] = $password;
		break;
		default:break;
	}
	return $addEntityParams;
}
function insertEntityInDB($params)
{
	global $con;
	
	$dcm_entity_id = $params['dcm_entity_id'];
	$ipAddress = (isset($params->address) ? $params->address : ( isset($params['address']) ? $params['address'] : '') );
	$properties = $params['props'];
	$callFrom = isset($params['callFrom']) ? $params['callFrom'] : '' ;
	
	if($callFrom == 'cron')
	{
		echo "<br />IP address :".$params->address."   DCM ENTITY ID : ".$dcm_entity_id;
		pr($properties);
	}
	
	if(!isset($entityIds[$properties['ENTITY_TYPE']]))
	{
		$entityIds[$properties['ENTITY_TYPE']] = $con->getSingleCell("SELECT et_id FROM ".TBL_ENTITY_TYPES." WHERE entity_type_code='".$properties['ENTITY_TYPE']."' LIMIT 1",__LINE__,__FILE__);
	}
	
	$q_fields = 'dcm_entity_id, entity_ip_address, added_on, added_by, added_from_ip ,';
	$q_values = "'".$dcm_entity_id."', '".$ipAddress."', NOW(), '-2','disc_cron', ";
	
	if(isset($properties['DESCRIPTION']))
	{
		$q_fields .= '`entity_description`, ';
		$q_values .= "'".addslashes($properties['DESCRIPTION'])."', ";
	}
	if(isset($properties['BMC_ADDRESS']))
	{
		$q_fields .= '`entity_bmc_address`, ';
		$q_values .= "'".addslashes($properties['BMC_ADDRESS'])."', ";
	}
	if(isset($properties['BMC_USER']))
	{
		$q_fields .= '`entity_bmc_user`, ';
		$q_values .= "'".addslashes($properties['BMC_USER'])."', ";
	}
	if(isset($properties['BMC_PASSWORD']))
	{
		$q_fields .= '`entity_bmc_password`, ';
		$q_values .= "'".addslashes($properties['BMC_PASSWORD'])."', ";
	}
	if(isset($properties['BMC_KEY']))
	{
		$q_fields .= '`entity_bmc_key`, ';
		$q_values .= "'".addslashes($properties['BMC_KEY'])."', ";
	}
	if(isset($properties['BMC_MAC_ADDRESS']))
	{
		$q_fields .= '`entity_bmc_mac_address`, ';
		$q_values .= "'".addslashes($properties['BMC_MAC_ADDRESS'])."', ";
	}
	if(isset($properties['IPMI_CIPHER_SUITE']))
	{
		$q_fields .= '`entity_ipmi_cipher_suite`, ';
		$q_values .= "'".addslashes($properties['IPMI_CIPHER_SUITE'])."', ";
	}
	if(isset($properties['SNMP_ADDRESS']))
	{
		$q_fields .= '`entity_snmp_address`, ';
		$q_values .= "'".addslashes($properties['SNMP_ADDRESS'])."', ";
	}
	if(isset($properties['SNMP_PROTOCOL']))
	{
		$q_fields .= '`entity_snmp_protocol`, ';
		$q_values .= "'".addslashes($properties['SNMP_PROTOCOL'])."', ";
	}
	if(isset($properties['SNMP_COMMUNITY_STRING']))
	{
		$q_fields .= '`entity_snmp_community_string`, ';
		$q_values .= "'".addslashes($properties['SNMP_COMMUNITY_STRING'])."', ";
	}
	if(isset($properties['SNMP_USER']))
	{
		$q_fields .= '`entity_snmp_user`, ';
		$q_values .= "'".addslashes($properties['SNMP_USER'])."', ";
	}
	if(isset($properties['SNMP_AUTHENTICATION_PASSWORD']))
	{
		$q_fields .= '`entity_snmp_authentication_password`, ';
		$q_values .= "'".addslashes($properties['SNMP_AUTHENTICATION_PASSWORD'])."', ";
	}
	if(isset($properties['SNMP_ENCRYPTION_PASSWORD']))
	{
		$q_fields .= '`entity_snmp_encryption_password`, ';
		$q_values .= "'".addslashes($properties['SNMP_ENCRYPTION_PASSWORD'])."', ";
	}
	if(isset($properties['SSH_ADDRESS']))
	{
		$q_fields .= '`entity_ssh_address`, ';
		$q_values .= "'".addslashes($properties['SSH_ADDRESS'])."', ";
	}
	if(isset($properties['SSH_USER']))
	{
		$q_fields .= '`entity_ssh_user`, ';
		$q_values .= "'".addslashes($properties['SSH_USER'])."', ";
	}
	if(isset($properties['SSH_PASSWORD']))
	{
		$q_fields .= '`entity_ssh_password`, ';
		$q_values .= "'".addslashes($properties['SSH_PASSWORD'])."', ";
	}
	if(isset($properties['SSH_PORT']))
	{
		$q_fields .= '`entity_ssh_port`, ';
		$q_values .= "'".addslashes($properties['SSH_PORT'])."', ";
	}
	if(isset($properties['HTTPS_ADDRESS']))
	{
		$q_fields .= '`entity_https_address`, ';
		$q_values .= "'".addslashes($properties['HTTPS_ADDRESS'])."', ";
	}
	if(isset($properties['HTTPS_PORT']))
	{
		$q_fields .= '`entity_https_port`, ';
		$q_values .= "'".addslashes($properties['HTTPS_PORT'])."', ";
	}
	if(isset($properties['HTTP_BASIC_USER']))
	{
		$q_fields .= '`entity_http_basic_user`, ';
		$q_values .= "'".addslashes($properties['HTTP_BASIC_USER'])."', ";
	}
	if(isset($properties['HTTP_BASIC_PASSWORD']))
	{
		$q_fields .= '`entity_http_basic_password`, ';
		$q_values .= "'".addslashes($properties['HTTP_BASIC_PASSWORD'])."', ";
	}
	if(isset($properties['UCS_USER']))
	{
		$q_fields .= '`entity_ucs_user`, ';
		$q_values .= "'".addslashes($properties['UCS_USER'])."', ";
	}
	if(isset($properties['UCS_PASSWORD']))
	{
		$q_fields .= '`entity_ucs_password`, ';
		$q_values .= "'".addslashes($properties['UCS_PASSWORD'])."', ";
	}
	if(isset($properties['DISTINGUISHED_NAME']))
	{
		$q_fields .= '`entity_distinguished_name`, ';
		$q_values .= "'".addslashes($properties['DISTINGUISHED_NAME'])."', ";
	}
	if(isset($properties['DCM_SERVER_ADDRESS']))
	{
		$q_fields .= '`entity_dcm_server_address`, ';
		$q_values .= "'".addslashes($properties['DCM_SERVER_ADDRESS'])."', ";
	}
	if(isset($properties['DCM_SERVER_PORT']))
	{
		$q_fields .= '`entity_dcm_server_port`, ';
		$q_values .= "'".addslashes($properties['DCM_SERVER_PORT'])."', ";
	}
	if(isset($properties['EVENT_HANDLER_HOST_ADDRESS']))
	{
		$q_fields .= '`entity_event_handler_host_address`, ';
		$q_values .= "'".addslashes($properties['EVENT_HANDLER_HOST_ADDRESS'])."', ";
	}
	if(isset($properties['EVENT_HANDLER_PORT']))
	{
		$q_fields .= '`entity_event_handler_port`, ';
		$q_values .= "'".addslashes($properties['EVENT_HANDLER_PORT'])."', ";
	}
	if(isset($properties['ENABLE_TLS']))
	{
		$q_fields .= '`entity_enable_tls`, ';
		$q_values .= "'".addslashes($properties['ENABLE_TLS'])."', ";
	}
	if(isset($properties['SERVER_POWER_SAMPLING_PERIOD']))
	{
		$q_fields .= '`entity_server_power_sampling_period`, ';
		$q_values .= "'".addslashes($properties['SERVER_POWER_SAMPLING_PERIOD'])."', ";
	}
	if(isset($properties['SERVER_THERMAL_SAMPLING_PERIOD']))
	{
		$q_fields .= '`entity_server_thermal_sampling_period`, ';
		$q_values .= "'".addslashes($properties['SERVER_THERMAL_SAMPLING_PERIOD'])."', ";
	}
	if(isset($properties['CONNECTOR_NAME']))
	{
		$q_fields .= '`entity_connector_name`, ';
		$q_values .= "'".addslashes($properties['CONNECTOR_NAME'])."', ";
	}
	if(isset($properties['DCM_SERVER_ID']))
	{
		$q_fields .= '`entity_dcm_server_id`, ';
		$q_values .= "'".addslashes($properties['DCM_SERVER_ID'])."', ";
	}
	if(isset($properties['CAPABILITIES']))
	{
		$q_fields .= '`entity_capabilities`, ';
		$q_values .= "'".addslashes($properties['CAPABILITIES'])."', ";
	}
	if(isset($properties['LOCATION']))
	{
		$q_fields .= '`entity_location`, ';
		$q_values .= "'".addslashes($properties['LOCATION'])."', ";
	}
	if(isset($properties['DIMENSION']))
	{
		$q_fields .= '`entity_dimension`, ';
		$q_values .= "'".addslashes($properties['DIMENSION'])."', ";
	}
	if(isset($properties['ENTITY_TYPE']))
	{
		
		$q_fields .= '`entity_type`, ';
		$q_values .= "'".addslashes($properties['ENTITY_TYPE'])."', ";
	}
	if(isset($properties['ENTITY_TYPE']))
	{
		$q_fields .= '`entity_type_id`, ';
		$q_values .= "'".$entityIds[$properties['ENTITY_TYPE']]."', ";
	}
	if(isset($properties['NAMEPLATE_PWR']))
	{
		$q_fields .= '`entity_nameplate_pwr`, ';
		$q_values .= "'".addslashes($properties['NAMEPLATE_PWR'])."', ";
	}
	if(isset($properties['DERATED_PWR']))
	{
		$q_fields .= '`entity_derated_pwr`, ';
		$q_values .= "'".addslashes($properties['DERATED_PWR'])."', ";
	}
	if(isset($properties['NAMEPLATE_PWR_UNMNGD_EQPMNT']))
	{
		$q_fields .= '`entity_nameplate_pwr_unmngd_eqpmnt`, ';
		$q_values .= "'".addslashes($properties['NAMEPLATE_PWR_UNMNGD_EQPMNT'])."', ";
	}
	if(isset($properties['PDU_PWR_LIMIT']))
	{
		$q_fields .= '`entity_pdu_pwr_limit`, ';
		$q_values .= "'".addslashes($properties['PDU_PWR_LIMIT'])."', ";
	}
	if(isset($properties['NODE_STATUS']))
	{
		$q_fields .= '`entity_node_status`, ';
		$q_values .= "'".addslashes($properties['NODE_STATUS'])."', ";
	}
	if(isset($properties['ENCLOSURE_STATUS']))
	{
		$q_fields .= '`entity_enclosure_status`, ';
		$q_values .= "'".addslashes($properties['ENCLOSURE_STATUS'])."', ";
	}
	if(isset($properties['NODE_POLICY_STATUS']))
	{
		$q_fields .= '`entity_node_policy_status`, ';
		$q_values .= "'".addslashes($properties['NODE_POLICY_STATUS'])."', ";
	}
	if(isset($properties['NODE_PWR_LIMIT']))
	{
		$q_fields .= '`entity_node_pwr_limit`, ';
		$q_values .= "'".addslashes($properties['NODE_PWR_LIMIT'])."', ";
	}
	if(isset($properties['BLADE_FORM_FACTOR']))
	{
		$q_fields .= '`entity_blade_form_factor`, ';
		$q_values .= "'".addslashes($properties['BLADE_FORM_FACTOR'])."', ";
	}
	if(isset($properties['CUSTOMIZED_PROPERTY']))
	{
		$q_fields .= '`entity_customized_property`, ';
		$q_values .= "'".addslashes($properties['CUSTOMIZED_PROPERTY'])."', ";
	}
	if(isset($properties['CUSTOMIZED_INFO']))
	{
		$q_fields .= '`entity_customized_info`, ';
		$q_values .= "'".addslashes($properties['CUSTOMIZED_INFO'])."', ";
	}
	if(isset($properties['INBAND_OS_TYPE']))
	{
		$q_fields .= '`entity_inband_os_type`, ';
		$q_values .= "'".addslashes($properties['INBAND_OS_TYPE'])."', ";
	}
	if(isset($properties['INBAND_ADDRESS']))
	{
		$q_fields .= '`entity_inband_address`, ';
		$q_values .= "'".addslashes($properties['INBAND_ADDRESS'])."', ";
	}
	if(isset($properties['INBAND_USERNAME']))
	{
		$q_fields .= '`entity_inband_username`, ';
		$q_values .= "'".addslashes($properties['INBAND_USERNAME'])."', ";
	}
	if(isset($properties['INBAND_PASSWORD']))
	{
		$q_fields .= '`entity_inband_password`, ';
		$q_values .= "'".addslashes($properties['INBAND_PASSWORD'])."', ";
	}
	if(isset($properties['PLATFORM_ID']))
	{
		$q_fields .= '`entity_platform_id`, ';
		$q_values .= "'".addslashes($properties['PLATFORM_ID'])."', ";
	}
	if(isset($properties['DEVICE_TYPE']))
	{
		$q_fields .= '`entity_device_type`, ';
		$q_values .= "'".addslashes($properties['DEVICE_TYPE'])."', ";
	}
	if(isset($properties['DEVICE_MODEL']))
	{
		$q_fields .= '`entity_device_model`, ';
		$q_values .= "'".addslashes($properties['DEVICE_MODEL'])."', ";
	}
	if(isset($properties['AUTHENTICATE_ENTITY']))
	{
		$q_fields .= '`entity_authenticate_entity`, ';
		$q_values .= "'".addslashes($properties['AUTHENTICATE_ENTITY'])."', ";
	}
	if(isset($properties['MIN_POWER_DRAW']))
	{
		$q_fields .= '`entity_min_power_draw`, ';
		$q_values .= "'".addslashes($properties['MIN_POWER_DRAW'])."', ";
	}
	if(isset($properties['MAX_POWER_DRAW']))
	{
		$q_fields .= '`entity_max_power_draw`, ';
		$q_values .= "'".addslashes($properties['MAX_POWER_DRAW'])."', ";
	}
	if(isset($properties['AGGREGATION_MULTIPLIER']))
	{
		$q_fields .= '`entity_aggregation_multiplier`, ';
		$q_values .= "'".addslashes($properties['AGGREGATION_MULTIPLIER'])."', ";
	}
	if(isset($properties['UUID']))
	{
		$q_fields .= '`entity_uuid`, ';
		$q_values .= "'".addslashes($properties['UUID'])."', ";
	}
	if(isset($properties['ENCLOSURE_ADDRESS']))
	{
		$q_fields .= '`entity_enclosure_address`, ';
		$q_values .= "'".addslashes($properties['ENCLOSURE_ADDRESS'])."', ";
	}
	if(isset($properties['ASSET_TAG']))
	{
		$q_fields .= '`entity_asset_tag`, ';
		$q_values .= "'".addslashes($properties['ASSET_TAG'])."', ";
	}
	if(isset($properties['PWR_ESTIMATOR']))
	{
		$q_fields .= '`entity_pwr_estimator`, ';
		$q_values .= "'".addslashes($properties['PWR_ESTIMATOR'])."', ";
	}
	if(isset($properties['GROUP_LIMIT_ON_ENCLOSURE']))
	{
		$q_fields .= '`entity_group_limit_on_enclosure`, ';
		$q_values .= "'".addslashes($properties['GROUP_LIMIT_ON_ENCLOSURE'])."', ";
	}
	if(isset($properties['MGMT_CONSOLE_URL']))
	{
		$q_fields .= '`entity_mgmt_console_url`, ';
		$q_values .= "'".addslashes($properties['MGMT_CONSOLE_URL'])."', ";
	}
	if(isset($properties['MAX_PWR_BUDGET']))
	{
		$q_fields .= '`entity_max_pwr_budget`, ';
		$q_values .= "'".addslashes($properties['MAX_PWR_BUDGET'])."', ";
	}
	if(isset($properties['MIN_PWR_BUDGET']))
	{
		$q_fields .= '`entity_min_pwr_budget`, ';
		$q_values .= "'".addslashes($properties['MIN_PWR_BUDGET'])."', ";
	}
	if(isset($properties['SERVICE_TAG']))
	{
		$q_fields .= '`entity_service_tag`, ';
		$q_values .= "'".addslashes($properties['SERVICE_TAG'])."', ";
	}
	if(isset($properties['PDU_PWR_AS_IT_EQPMNT_PWR']))
	{
		$q_fields .= '`entity_pdu_pwr_as_it_eqpmnt_pwr`, ';
		$q_values .= "'".addslashes($properties['PDU_PWR_AS_IT_EQPMNT_PWR'])."', ";
	}
	if(isset($properties['FIRMWARE_VERSION']))
	{
		$q_fields .= '`entity_firmware_version`, ';
		$q_values .= "'".addslashes($properties['FIRMWARE_VERSION'])."', ";
	}
	if(isset($properties['MGMT_PROCESSOR_TYPE']))
	{
		$q_fields .= '`entity_mgmt_processor_type`, ';
		$q_values .= "'".addslashes($properties['MGMT_PROCESSOR_TYPE'])."', ";
	}
	if(isset($properties['TEMPERATURE_UPPER_LIMIT']))
	{
		$q_fields .= '`entity_temperature_upper_limit`, ';
		$q_values .= "'".addslashes($properties['TEMPERATURE_UPPER_LIMIT'])."', ";
	}
	if(isset($properties['SERIAL_NUMBER']))
	{
		$q_fields .= '`entity_serial_number`, ';
		$q_values .= "'".addslashes($properties['SERIAL_NUMBER'])."', ";
	}
	
	if(isset($properties['NAME']))
	{
		$q_fields .= '`entity_name`, ';
		$q_values .= "'".addslashes($properties['NAME'])."', ";
	}
	else
	{
		if(isset($properties['NAME']))
		{
			$q_fields .= '`entity_name`, ';
			$q_values .= "'".addslashes($properties['NAME'])."', ";
		}
		else
		{
			if(isset($properties['DEVICE_MODEL']))
			{
				$q_fields .= '`entity_name`, ';
				$q_values .= "'".addslashes($properties['DEVICE_MODEL'])." Entity_".$dcm_entity_id."', ";
			}
			else
			{	
				if(isset($properties['DEVICE_TYPE']))
				{
					$q_fields .= '`entity_name`, ';
					$q_values .= "'".addslashes($properties['DEVICE_TYPE'])." Entity_".$dcm_entity_id."', ";
				}
				else
				{	
					$q_fields .= '`entity_name`, ';
					$q_values .= "'Managed Entity_".$dcm_entity_id."', ";
				}
			}
		}
	}
	
	$q_fields = rtrim($q_fields,', ');
	$q_values = rtrim($q_values,", ");
	pr($properties);
	return;
	$insQry = "INSERT INTO ".TBL_ENTITY."( ".$q_fields." ) VALUES ( ".$q_values." )";
	if($callFrom == 'cron' || 1)
	{
		echo "<br>".$insQry;
	}
	$con->insertRecords($insQry, __FILE__,__LINE__);
	
	
	
}



function useraudit($con,$dt,$ip,$user,$atype,$pt,$details)
	{



	$qry="INSERT INTO 
		       tbllog(id,datelog, ipaddress,user, accesstype, patient, details) 
		       VALUES (NULL, '".$dt."','".$ip ."','".$user."','". $atype."','". $pt."','". $details."')";

			$i=$con->insertRecords($qry,  __FILE__,  __LINE__);
			
		return $i;
	}

    function getIPx()
    {
      if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
    }
?>