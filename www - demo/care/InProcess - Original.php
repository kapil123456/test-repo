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
 

$fArray=readfiles($ffile,$srcFolder);
$fAr=rfiles($ffile,$srcFolder);
 
 if(!empty($fAr))
{

for($j=0;$j<count($fArray);$j++)
  {
			$iqry="SELECT p_id from tblpatients where p_fname='".$fArray[$j]['fname']."' and p_lname='".$fArray[$j]['lname']."' and p_mrn='".$fArray[$j]['mrn']."'";
			$flag=$con->getSingleRow($iqry,  __FILE__,  __LINE__);
			//pr($flag);
			
			$checkIfFile="Select f_filename from tblfile where f_filename='".$fAr[$j].".pdf'";
			$flagFile=$con->getSingleRow($checkIfFile,  __FILE__,  __LINE__);
			
			if(empty($flagFile))
			{
					$fqry="INSERT INTO tblfile(f_id,f_filename,p_id) value(null,'".$fAr[$j].".pdf','".$flag['p_id']."')";
					
					$z=$con->insertRecords($fqry,  __FILE__,  __LINE__);
					
					$qry="INSERT INTO tbltemp(pid,pfname,plname,pmrn,pdate,ptype,psum,pisreport,ppath,id) value(null,'".$fArray[$j]['fname']."','".$fArray[$j]['lname']."','".$fArray[$j]['mrn']."','".$fArray[$j]['date']."','".$fArray[$j]['type']."','".$fArray[$j]['summary']."','".$fArray[$j]['isreport']."','".$fAr[$j].".pdf','".$flag['p_id']."')";
					
					$y=$con->insertRecords($qry,  __FILE__,  __LINE__);
			
			}

	
	//copy file from input to storage
	if (copy($source.$fAr[$j].".pdf", $destination.$fAr[$j].".pdf")) 
	{
	
	    $delete[] = $source.$fAr[$j].".pdf";
    }
  }
	
foreach ($delete as $file) {
  unlink($file);
}


}
else
{
 
// Display Progress
		if($statustmp['f_reqstatus']==0 &&  $fcount['v_fcount']!=$vfcount['f_count'] || $fcount['v_fcount']==0 )
		{
			echo "<div  >
				   <img id='imgLoad' src='images/loading.gif' alt='Fetching...' />
				  <br/>
				   files fetched =".$tblfcount['tblf_count']."/".$fcount['v_fcount']
				   ."</div><br/>";
		}
		else
		{
			echo $fcount['v_fcount']."=".$vfcount['f_count'];
			//update status to Immediate when $fcount['v_fcount']."=".$vfcount['f_count']
			if($fcount['v_fcount']==$vfcount['f_count'])
			{
				$pqry="update tblpatients set p_type='Immediate' where p_mrn='".$_REQUEST['mrn']."'";
				$t=$con->updateRecords($pqry, __FILE__,  __LINE__);
				echo "<script> location.replace('listing.php'); </script>";
			}
            
            
            
		}



}

}

function readfiles($ffile,$srcFolder)
{
$filesArr = array();
$fArr = array();
$arr_index= 0;
if(count(glob($srcFolder.'*.*'))>0)
{

	foreach(glob($srcFolder.'*.*') as $filename)
	{
       
		
		$v=str_replace('_', '_', basename($filename,'.pdf'));
	//pr(strcmp($v,$ffile));
		
		
		
		$c=explode("_",$v);
	    $cc=explode("_",$ffile);
	//pr($c[0].$cc[0]);
	//pr($c[1].$cc[1]);
//	pr($c[2].$cc[2]);
		if(strcmp($c[0],$cc[0])==0 && strcmp($c[1],$cc[1])==0 && strcmp($c[2],$cc[2])==0)
		{
		
		$filesArr[$arr_index] = array();
		$fArr[]=$v;
	
		$total = count($c);
	//pr($c);
			for($s=0;$s < $total-1;$s++)
			{
				
				 $sections = explode(',',$c[$s]);
				 $filesArr[$arr_index][$sections[0]] = $sections[1];
				
				 
			}
	
		$sections = explode(',',$c[$total-1]);
		$filesArr[$arr_index][$sections[0]] = $sections[1];
		$filesArr[$arr_index]['type'] = $sections[2];
		$filesArr[$arr_index]['summary'] = $sections[count($sections)-	1];
		$filesArr[$arr_index]['isreport'] = 'n';
	 
			if(count($sections)>4)
			{
			  $filesArr[$arr_index]['isreport'] = $sections[3];
			}
//	pr($sections);
$arr_index++;
	}

	 
	
   
   
   }
   
}
return $filesArr;
}

function rfiles($ffile,$srcFolder)
{
$filesArr = array();
$fArr = array();
$arr_index= 0;
if(count(glob($srcFolder.'*.*'))>0)
{

	foreach(glob($srcFolder.'*.*') as $filename)
	{
       
		
		$v=str_replace('_', '_', basename($filename,'.pdf'));
	//pr(strcmp($v,$ffile));
		
		
		
		$c=explode("_",$v);
	    $cc=explode("_",$ffile);
	//pr($c[0].$cc[0]);
	//pr($c[1].$cc[1]);
//	pr($c[2].$cc[2]);
		if(strcmp($c[0],$cc[0])==0 && strcmp($c[1],$cc[1])==0 && strcmp($c[2],$cc[2])==0)
		{
		
		$filesArr[$arr_index] = array();
		$fArr[]=$v;
	
		$total = count($c);
	//pr($c);
			for($s=0;$s < $total-1;$s++)
			{
				
				 $sections = explode(',',$c[$s]);
				 $filesArr[$arr_index][$sections[0]] = $sections[1];
				
				 
			}
	
		$sections = explode(',',$c[$total-1]);
		$filesArr[$arr_index][$sections[0]] = $sections[1];
		$filesArr[$arr_index]['type'] = $sections[2];
		$filesArr[$arr_index]['summary'] = $sections[count($sections)-	1];
		$filesArr[$arr_index]['isreport'] = 'n';
	 
			if(count($sections)>4)
			{
			  $filesArr[$arr_index]['isreport'] = $sections[3];
			}
//	pr($sections);
$arr_index++;
	}

	 
	
   
   
   }
   
}
return $fArr;
}
?>