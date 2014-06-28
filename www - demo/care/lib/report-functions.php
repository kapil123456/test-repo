<?php

function getReportData_ExportRackCapacityData($arr)
{
	global $con;
	
	$postData = $arr['postData'];
	
	$rckAllsrchField = $postData['rckAllsrchField'];
	$rckAllsrchOper = $postData['rckAllsrchOper'];
	$rckAllsrchString = $postData['rckAllsrchString'];
	
	$fieldMap 		= array('name'=>'grp_name','sttl'=>'space_total','savl'=>'space_available','scont'=>'space_continuous','sutil'=>'space_utilization','pttl'=>'power_total','pavl'=>'power_available','putil'=>'power_utilization');
	
	$searchClause=$searchField	= $searchString	='';
	
	if( $rckAllsrchField != '' &&  $rckAllsrchOper != '' && $rckAllsrchString != '' )
	{
		$searchClause 	= getGridSearchClause(array('fieldMap'=>$fieldMap, 'searchField'=>$rckAllsrchField,'searchOper'=>$rckAllsrchOper, 'searchString'=>$rckAllsrchString ) );
		
	}
	
	$selQry = "SELECT COUNT(ri_id) AS tot FROM  ".TBL_RACKS_INFO." ri, ".TBL_GROUPS." g WHERE g.grp_id=ri.rack_id AND g.grp_entity_code='RACK' AND g.grp_status ='active' ".$searchClause;
	$rckTot =   $con->getSingleCell($selQry, __FILE__, __LINE__);
	
	$fileString = "Rack Capacity Data\n\nFile Generated On : ".dateFormat(date("Y-m-d H:i:s"),'1')."\n\nRack Name,Space - Total (U),Space - Available (U),Space - Continuous (U),Space - Utilization,Power - Total (W),Power - Available (W),Power - Utilization\n\n";
	if($rckTot)
	{
	
		$dataPerIteration = 100;
		$startFrom = 0;
		$iterations = ceil($rckTot/$dataPerIteration);
		
		for($iter=0;$iter<$iterations;$iter++)
		{
			$limitClause = " LIMIT ".$startFrom.", ".$dataPerIteration;
			
			$selQry = "SELECT ri_id as id, g.grp_name as name, rack_id, space_total, space_available, space_continuous, space_utilization, power_total, power_available, power_utilization  FROM  ".TBL_RACKS_INFO." ri, ".TBL_GROUPS." g WHERE g.grp_id=ri.rack_id AND g.grp_entity_code='RACK' AND g.grp_status ='active' ".$searchClause.$limitClause;
			$rckList =   $con->getMultiRow($selQry, __FILE__, __LINE__);
			$totalRacks = count($rckList);
			
			for($r=0;$r<$totalRacks;$r++)
			{
				$fileString .= $rckList[$r]['name'].",".$rckList[$r]['space_total'].",".$rckList[$r]['space_available'].",".$rckList[$r]['space_continuous'].",".$rckList[$r]['space_utilization']." %,".$rckList[$r]['power_total'].",".$rckList[$r]['power_available'].",".$rckList[$r]['power_utilization']." %\n";
			}
			$startFrom = $startFrom + $dataPerIteration;
		}
	}	
	$fileName = "RackCapacityData.csv";
	
	$fileObj = @fopen(BASE_DIR.$fileName,'w');
	@fwrite($fileObj,$fileString);
	@fclose($fileObj);
	
	return $fileName;
}

function getReportData_EntityGraphData($arr)
{
	global $con;
	
	$postData = $arr['postData'];
	
	$requestCallFrom = (isset($postData['tempTrendCFrom']) && $postData['tempTrendCFrom']!='' ? $postData['tempTrendCFrom'] : 'db');
	$requestZmLevel = (isset($postData['tempTrendZm']) && $postData['tempTrendZm']!='' ? $postData['tempTrendZm'] : '1D');
	$dcm_entity_id = (isset($postData['tempTrendEid']) && $postData['tempTrendEid']!='' && is_numeric(decodedData($postData['tempTrendEid'])) ? decodedData($postData['tempTrendEid']) : '-1');
	
	if($dcm_entity_id == '-1' || $requestCallFrom != 'devicegraphs')
	{
		return false;
	}
	
	$startTimeFrameArr = array('1H'=>'-1 hour ','1D'=>'-1 day ','1W'=>'-1 week ','1M'=>'-1 month ','3M'=>'-3 month ','6M'=>'-6 month ','1Y'=>'-1 year ');
	$requestZmLevel = (isset($startTimeFrameArr[$requestZmLevel]) ?  $requestZmLevel : '1D');
	
	$curTime =  strtotime(date('Y-m-d H:i:s'));
	$startTime =  date('c', strtotime($startTimeFrameArr[$requestZmLevel].'-5 minute',$curTime));
	$endTime = date('c', strtotime('+5 minute', $curTime));
	
	if(isset($postData['tempTrendIsGroup']) && $postData['tempTrendIsGroup'] == 'Y')
	{
		$selQry = "SELECT dcm_entity_id, 'LOGICAL_GROUP' AS entity_type, grp_name as name FROM ".TBL_GROUPS." WHERE dcm_entity_id = '".$dcm_entity_id."' LIMIT 1";
	}
	else
	{
		$selQry = "SELECT eh.dcm_entity_id, eh.hirarchy_level, eh.entity_type FROM ".TBL_ENTITY_HIERARCHY." eh WHERE eh.dcm_entity_id = '".$dcm_entity_id."' LIMIT 1";
	}
	$entityInfo = $con->getSingleRow($selQry, __LINE__,__FILE__);
	$entityName = '';
	$totData =0;
	
	if(isset($entityInfo['dcm_entity_id']) && $entityInfo['dcm_entity_id'] == $dcm_entity_id)
	{
		if($entityInfo['entity_type'] =='NODE' || $entityInfo['entity_type'] =='ENCLOSURE' )
		{
			$selQry = "SELECT entity_name as name FROM ".TBL_ENTITY." WHERE dcm_entity_id = '".$dcm_entity_id."' LIMIT 1";
		}
		else
		{
			$selQry = "SELECT grp_name as name FROM ".TBL_GROUPS." WHERE dcm_entity_id = '".$dcm_entity_id."' LIMIT 1";
		}
		$entityName = $con->getSingleCell($selQry, __FILE__, __LINE__); 
		
		$selQry = "SELECT COUNT(graph_str_datetime) FROM ".TBL_GRAPH_VALUES." WHERE 
	entity_type='".$entityInfo['entity_type']."' AND dcm_entity_id='".$entityInfo['dcm_entity_id']."' AND  graph_datetime >= '".$startTime."' AND graph_datetime <= '".$endTime."'";
		
		$totData = $con->getSingleCell($selQry, __FILE__, __LINE__); 
	}
	
	$fileString = "Entity Temperature/Power Data (".ucwords(ltrim($startTimeFrameArr[$requestZmLevel],'-')).")\n\nEntity Name:".$entityName."\n\nFile Generated On : ".dateFormat(date("Y-m-d H:i:s"),'1')."\n\nDate & Time,Max. Inlet Temperature (&deg;C),Min. Inlet Temperature (&deg;C),Avg. Inlet Temperature (&deg;C),IT Equipment Power (W)\n\n";
	
	if($totData)
	{
	
		$dataPerIteration = 100;
		$startFrom = 0;
		$iterations = ceil($totData/$dataPerIteration);
		
		for($iter=0;$iter<$iterations;$iter++)
		{
			$limitClause = " LIMIT ".$startFrom.", ".$dataPerIteration;
			
			$selQry = "SELECT graph_str_datetime, min_inlet_temp,avg_inlet_temp,max_inlet_temp,it_eqpmnt_pwr, dcm_entity_id FROM ".TBL_GRAPH_VALUES." WHERE 
	entity_type='".$entityInfo['entity_type']."' AND dcm_entity_id='".$entityInfo['dcm_entity_id']."' AND  graph_datetime >= '".$startTime."' AND graph_datetime <= '".$endTime."' GROUP BY graph_str_datetime ".$limitClause;
			$dataList =   $con->getMultiRow($selQry, __FILE__, __LINE__);
			$totalDataList = count($dataList);
			
			for($r=0;$r<$totalDataList;$r++)
			{
				$fileString .= $dataList[$r]['graph_str_datetime'].",".$dataList[$r]['max_inlet_temp'].",".$dataList[$r]['min_inlet_temp'].",".$dataList[$r]['avg_inlet_temp'].",".$dataList[$r]['it_eqpmnt_pwr']."\n";
			}
			$startFrom = $startFrom + $dataPerIteration;
		}
	}	
	$fileName = "EntityTempPowData.csv";
	
	$fileObj = @fopen(BASE_DIR.$fileName,'w');
	@fwrite($fileObj,$fileString);
	@fclose($fileObj);
	
	return $fileName;
}
?>