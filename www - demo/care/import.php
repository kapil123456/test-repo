<?php
require_once("lib/config.php");

$filesArr = array();
$fArr = array();
 $delete =array();
$arr_index= 0;
$source=INPUT_FOLDER_PATH;
$destination = 'storage/';
$srcFolder=$source;
$qry="";
$fetchset=1;

if(count(glob($srcFolder.'*.pdf'))>0 )
{

    
	foreach(glob($srcFolder.'*.pdf') as $filename)
	{
	
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
			//if(file_exists($destination.$fArr[$j].".pdf"))
	//		echo "";
		//					{
						if (copy($source.$fArr[$j].".pdf", $destination.$fArr[$j].".pdf")) 
						{
				
						     $delete[] = $source.$fArr[$j].".pdf";
							 $iqry="SELECT p_id from tblpatients where p_fname='".$filesArr[$j]['fname']."' and p_lname='".$filesArr[$j]['lname']."' and p_mrn='".$filesArr[$j]['mrn']."'";
							 $flag=$con->getSingleRow($iqry,  __FILE__,  __LINE__);
							 $fqry="INSERT INTO tblfile(f_id,f_filename,p_id) value(null,'".$fArr[$j].".pdf','".$flag['p_id']."')";
							 $z=$con->insertRecords($fqry,  __FILE__,  __LINE__);
							 $qry="INSERT INTO tbltemp(pid,pfname,plname,pmrn,pdate,ptype,psum,pisreport,ppath,id) value(null,'".$filesArr[$j]['fname']."','".$filesArr[$j]['lname']."','".$filesArr[$j]['mrn']."','".$filesArr[$j]['date']."','".$filesArr[$j]['type']."','".$filesArr[$j]['summary']."','".$filesArr[$j]['isreport']."','".$fArr[$j].".pdf','".$flag['p_id']."')";
							 $y=$con->insertRecords($qry,  __FILE__,  __LINE__);
						}
						
        //        }
		}
		
		
		foreach ($delete as $file) 
		{
			unlink($file);
		}
	}
}	


exit();
