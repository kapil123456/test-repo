<?php 
require_once("lib/config.php");
$filesArr = array();
$fArr = array();
$delete =array();
$arr_index= 0;


$ffile="Fname,Laurence_Lname,Bosque_MRN,234545_date,08022011,image,c-section ";
$source="\\\\192.168.0.100\\copyqinput\\";
$destination = "D:\\filestore\\";
$srcFolder="\\\\192.168.0.100\\copyqinput\\";
$qry="";
//echo "here before loop : ".count(glob($srcFolder.'*.*'));
if(count(glob($srcFolder.'*.*'))>0)
{
//echo count(glob($srcFolder.'*.*'));
	foreach(glob($srcFolder.'*.*') as $filename)
	{
       
		
		$v=str_replace('_', '_', basename($filename,'.pdf'));
	//	echo $v."<br/>";
		//echo strcmp($v,$ffile);  
		//echo $filename."<br/>";
		if(strcmp($v,$ffile)==0)
		{
		
		
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
	
   }
   
//

	print_r($filesArr);
	for($j=0;$j<count($filesArr);$j++)
	{
	if (copy($source.$fArr[$j].".pdf", $destination.$fArr[$j].".pdf")) {
    $delete[] = $source.$fArr[$j].".pdf";
  }
  }
  foreach ($delete as $file) {
  unlink($file);
}

}

else
{
echo count(glob($srcFolder.'*.*'))."failed!";
}
?>
