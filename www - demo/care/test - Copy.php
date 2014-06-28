<?php
require_once("lib/config.php");


$rid="";
if(isset($_REQUEST['pid']))
{
$rid=$_REQUEST['pid'];
}
$filesArr = array();
$fArr = array();
 $delete =array();
$arr_index= 0;
$source=INPUT_FOLDER_PATH;
$destination = 'storage/';
$srcFolder=$source;
$qry="";
$fetchset=1;


if(count(glob($srcFolder.'*.pdf'))>0 ){

foreach(glob($srcFolder.'*.pdf') as $filename){
     
	
    $v=str_replace('_', '_', basename($filename,'.pdf'));
	$c=explode("_",$v);
	
	$filesArr[$arr_index] = array();
	$fArr[]=$v;
	
	$total = count($c);
	for($s=0;$s < $total-1;$s++)
	{
		
	     $sections = explode(',',$c[$s]);
		 $filesArr[$arr_index][$sections[0]] = $sections[1];
		 //pr($sections);
		// $filesArr[$arr_index]['type'] = $sections[3];
	     
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
	//pr($sections);
	$arr_index++;
	
	
 }
   
	//echo count($filesArr);
	if(isset($filesArr))
	{
	for($j=0;$j<count($filesArr);$j++)
	{
if(file_exists($destination.$fArr[$j].".pdf"))
{
if($filesArr[$j]['mrn']==$rid)
{
 $fetchset=0;
}
if (copy($source.$fArr[$j].".pdf", $destination.$fArr[$j].".pdf")) 
		{
		$delete[] = $source.$fArr[$j].".pdf";
		}
}
else{
 $iqry="SELECT p_id from tblpatients where p_fname='".$filesArr[$j]['fname']."' and p_lname='".$filesArr[$j]['lname']."' and p_mrn='".$filesArr[$j]['mrn']."'";
 $flag=$con->getSingleRow($iqry,  __FILE__,  __LINE__);
//pr($flag);
$fqry="INSERT INTO tblfile(f_id,f_filename,p_id) value(null,'".$fArr[$j].".pdf','".$flag['p_id']."')";
$z=$con->insertRecords($fqry,  __FILE__,  __LINE__);

 $qry="INSERT INTO tbltemp(pid,pfname,plname,pmrn,pdate,ptype,psum,pisreport,ppath,id) value(null,'".$filesArr[$j]['fname']."','".$filesArr[$j]['lname']."','".$filesArr[$j]['mrn']."','".$filesArr[$j]['date']."','".$filesArr[$j]['type']."','".$filesArr[$j]['summary']."','".$filesArr[$j]['isreport']."','".$v.".pdf','".$flag['p_id']."')";
	
$y=$con->insertRecords($qry,  __FILE__,  __LINE__);

//get sum of count from tbl Visits
$vcqry="SELECT sum(v_fcount) as v_fcount from tblvisits where v_mrn='".$_REQUEST['pid']."'";
 $fcount=$con->getSingleRow($vcqry,  __FILE__,  __LINE__);

//get count from tbl files
$vfcqry="SELECT count(f_filename) as f_count from tblfile where p_id='".$flag['p_id']."'";
 $vfcount=$con->getSingleRow($vfcqry,  __FILE__,  __LINE__);
 
 //compare both counts for equality and set Status to Immediate
 if($fcount['v_fcount']==$vfcount['f_count'] && $vfcount['f_count']!=0 && $fcount['v_fcount']!=0 && !empty($fcount['v_fcount']) && !empty($vfcount['f_count']))
 {
 $pqry="update tblpatients set p_type='Immediate' where p_id='".$flag['p_id']."'";
$t=$con->updateRecords($pqry, __FILE__,  __LINE__);

}
	if (copy($source.$fArr[$j].".pdf", $destination.$fArr[$j].".pdf")) 
		{
		$delete[] = $source.$fArr[$j].".pdf";
		}
		
}
	}
	
	
foreach ($delete as $file) {
  unlink($file);
}
$msg="All files processed!";

if($fetchset==0)
{
$rid="none";
}

}
}
else
{
if(!empty($rid) and $rid!="none")
{
/*$msg= "File request sent to process server!";
$file='d:\\file.csv';
if(file_exists($file))
{
unlink($file);
}
    $fl=0;
	$kqry="SELECT * FROM tblpatients where p_id='".$rid."' INTO OUTFILE '$file' FIELDS TERMINATED BY ','";
	
	$fl=$con->getSingleRow($kqry,  __FILE__,  __LINE__);*/
	
	
	$verifyfetchrecord="SELECT * FROM tblfetch where f_pmrn='".$rid."'";
	
	$frec=$con->getSingleRow($verifyfetchrecord,  __FILE__,  __LINE__);
	
	
	if(empty($frec))
	{
$qry_savefetchreq="
INSERT INTO tblfetch(f_pmrn,f_reqstatus,f_locked,f_error,f_user,f_date) VALUES ('".$rid."','0','0','0','".$_SESSION['username']."','".date("Y-m-d H:i:s")."')";

$fetch=$con->insertRecords($qry_savefetchreq,  __FILE__,  __LINE__);

 $pidqry="SELECT p_id from tblpatients where p_mrn='".$rid."'";
 $p_pid=$con->getSingleRow($pidqry,  __FILE__,  __LINE__);

$tmptblqry="SELECT p_lname,p_fname,p_mname,p_dob,p_mrn,p_id FROM tblpatients where p_id='".$p_pid['p_id']."'";
	
$ftmp=$con->getSingleRow($tmptblqry,  __FILE__,  __LINE__);



$qry_svfetchparm="
INSERT INTO demo.tmptable (t_lname, t_fname, t_mname, t_mrn, t_dob, t_pid) VALUES ('".$ftmp['p_lname']."','".$ftmp['p_fname']."','".$ftmp['p_mname']."','".$ftmp['p_mrn']."','".$ftmp['p_dob']."','".$ftmp['p_id']."')";

$fetchparm=$con->insertRecords($qry_svfetchparm,  __FILE__,  __LINE__);

 $pstatusqry="update tblpatients set p_type='InProcess' where p_id='".$p_pid['p_id']."'";
$tstatus=$con->updateRecords($pstatusqry, __FILE__,  __LINE__);
if($fetch && $fetchparm && $tstatus)
{
$msg="File Fetch in progress!";
}

}
else
{
$msg="Fetch Request Already In Progress!";
}

}
else
{
$msg= "No Files Retrived!";
}



}	

$_SESSION['er']=$msg;
echo "<script> location.replace('listing.php'); </script>";
exit();


?>
