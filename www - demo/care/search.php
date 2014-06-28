<?php
require_once('lib/config.php');
//$array=array("file1.pdf","file2.pdf","file3.pdf");
$_PHP_SELF=$_SERVER['PHP_SELF'];
$rec_limit = 10;
$data= array();
$qry='';
$rec_count=0;

$qy="SELECT count(p_id) as count FROM tblpatients ";

$rc=$con->getSingleRow($qy,  __FILE__,  __LINE__);

$rec_count=$rc['count'];
if( isset($_GET{'page'} ) )
{
   $page = $_GET{'page'} + 1;
   $offset = $rec_limit * $page ;
}
else
{
   $page = 0;
   $offset = 0;
}
$left_rec = $rec_count - ($page * $rec_limit);

if(isset($_POST['txt_P_Name']) && isset($_POST['pfilter']))
{
	$qry="SELECT * FROM tblpatients WHERE ".$_POST['pfilter']." like '%".$_POST['txt_P_Name']."%' GROUP BY p_mrn ORDER BY p_lname,p_fname ASC LIMIT ".$offset.",".$rec_limit;
}
else
{
	$qry='';
	
}


if(isset($qry) && $qry!='')
{
$data=$con->getMultiRow($qry,  __FILE__,  __LINE__);
}

?>
 
       
 <table id="tblResult" width="961" border="0" cellspacing="0" cellpadding="5">
  <tr class="t-head">
   <td width="28" class="border"><input id="selectall"   name="selectall" type="checkbox" value="" style="display:none"/></td>
    <td width="235" class="border">Last Name</td>
    <td width="193" class="border">First Name</td>
    <td width="143" class="border">Date of Birth</td>
    <td width="115" class="border">MRN</td>
     <td width="106" class="border">Type </td>
    <td width="106" class="border">Documents</td>
  
      
  </td>
  </tr>
 <?php
 for($i=0;$i<count($data);$i++){
 ?><tr class="<?php echo ($i%2!=0 ? "t-line-white" : "t-line-grey");?>">
    <td><input name="file[]" type="checkbox" value="<?php echo $data[$i]['p_id'];?>"  style="display:none"/></td>
     <td><?php echo $data[$i]['p_lname'];?></td>
    <td><?php echo $data[$i]['p_fname'];?></td>
   <td> <?php echo date("m-d-Y", strtotime( $data[$i]['p_dob']));?></td>
    <td><?php echo $data[$i]['p_mrn'];?></td>
    <td><?php echo $data[$i]['p_type'];?></td>
     <td> <?php if( $data[$i]['p_type'] == 'Immediate'){?> <a id="opener<?php echo $data[$i]['p_id'];?>" href="#" value="<?php echo $data[$i]['p_id'];?>" onClick="getFiles(this);">View Files</a> <?php }
	   if( $data[$i]['p_type'] == 'InProcess' ){?> <a id="opener<?php echo $data[$i]['p_id'];?>" href="#" value="<?php echo $data[$i]['p_mrn']?>" onClick="fetchFiles(this)">Check Progress</a>
	 <?php } if( $data[$i]['p_type'] == 'Needs Retrieval' ) {?> <a href="test.php?pid=<?php echo $data[$i]['p_mrn'];?>">Fetch File </a><?php }?></td>
    <!--<td>
    	<a href="#"><img src="images/edit.png" border="0" /></a>&nbsp;&nbsp;
    	<a href="#"><img src="images/set.png" border="0" /></a>&nbsp;&nbsp;
    	<a href="#"><img src="images/del.png" border="0" /></a>
      
    </td>-->
  </tr>
  <?php } ?>
</table>
           