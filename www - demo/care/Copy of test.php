<?php
require_once("lib/config.php");
/*$filename="Fname,ADAMS_Lname,KATIE_MRN,2048450_date,18022010,image,c-section";
$pieces = explode("_", $filename);
pr($pieces);
$c=explode(",",$pieces[3]);*/
//pr($c);
$rid="";
if(isset($_REQUEST['pid']))
{
$rid=$_REQUEST['pid'];
}
$filesArr = array();
$fArr = array();
 $delete =array();
$arr_index= 0;
$source=$srcFolder;
$destination = $destZFolder;
$qry="";


if(count(glob($srcFolder.'*.*'))>0){

foreach(glob($srcFolder.'*.*') as $filename){
     
	
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
	for($j=0;$j<count($filesArr);$j++)
	{

 $iqry="SELECT p_id from tblpatients where p_fname='".$filesArr[$j]['Fname']."' and p_lname='".$filesArr[$j]['Lname']."' and p_mrn='".$filesArr[$j]['MRN']."'";
 $flag=$con->getSingleRow($iqry,  __FILE__,  __LINE__);
//pr($flag);
$fqry="INSERT INTO tblfile(f_id,f_filename,p_id) value(null,'".$fArr[$j].".pdf','".$flag['p_id']."')";
$z=$con->insertRecords($fqry,  __FILE__,  __LINE__);

 $qry="INSERT INTO tbltemp(pid,pfname,plname,pmrn,pdate,ptype,psum,pisreport,ppath,id) value(null,'".$filesArr[$j]['Fname']."','".$filesArr[$j]['Lname']."','".$filesArr[$j]['MRN']."','".$filesArr[$j]['date']."','".$filesArr[$j]['type']."','".$filesArr[$j]['summary']."','".$filesArr[$j]['isreport']."','".$v.".pdf','".$flag['p_id']."')";
	
$y=$con->insertRecords($qry,  __FILE__,  __LINE__);

 $pqry="update tblpatients set p_type='Immidiate' where p_id='".$flag['p_id']."'";
$t=$con->updateRecords($pqry, __FILE__,  __LINE__);

	if (copy($source.$fArr[$j].".pdf", $destination.$fArr[$j].".pdf")) 
		{
		$delete[] = $source.$fArr[$j].".pdf";
		}
		

	}
	
	//pr($delete);
foreach ($delete as $file) {
  unlink($file);
}
$msg="All files processed!";
$rid="none";
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
	
	
	$verifyfetchrecord="SELECT * FROM tblfetch where f_pmrn='".$rid."' and f_reqstatus =1 or f_reqstatus =0  and f_locked=1";
	
	$frec=$con->getSingleRow($verifyfetchrecord,  __FILE__,  __LINE__);
	
	
	if(empty($frec))
	{
$qry_savefetchreq="
INSERT INTO tblfetch(f_pmrn,f_reqstatus,f_locked,f_error,f_user,f_date) VALUES ('".$rid."','0','1','0','".$_SESSION['username']."','".date("Y-m-d H:i:s")."')";

$fetch=$con->insertRecords($qry_savefetchreq,  __FILE__,  __LINE__);

 $pidqry="SELECT p_id from tblpatients where p_mrn='".$rid."'";
 $p_pid=$con->getSingleRow($pidqry,  __FILE__,  __LINE__);

$tmptblqry="SELECT p_lname,p_fname,p_mname,p_dob,p_mrn,p_id FROM tblpatients where p_id='".$p_pid['p_id']."'";
	
$ftmp=$con->getSingleRow($tmptblqry,  __FILE__,  __LINE__);



$qry_svfetchparm="
INSERT INTO demo.tmptable (t_lname, t_fname, t_mname, t_mrn, t_dob, t_pid) VALUES ('".$ftmp['p_lname']."','".$ftmp['p_fname']."','".$ftmp['p_mname']."','".$ftmp['p_mrn']."','".$ftmp['p_dob']."','".$ftmp['p_id']."')";

$fetchparm=$con->insertRecords($qry_svfetchparm,  __FILE__,  __LINE__);


if($fetch && $fetchparm)
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
header("location: listing.php");
exit();


?>
