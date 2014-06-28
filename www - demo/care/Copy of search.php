<?php
require_once('lib/config.php');
//$array=array("file1.pdf","file2.pdf","file3.pdf");
$_PHP_SELF=$_SERVER['PHP_SELF'];
$rec_limit = 10;

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

if(isset($_POST['txt_P_Name']))
{
	$qry="SELECT * FROM tblpatients WHERE p_name like '%".$_POST['txt_P_Name']."%'  ORDER BY p_id ASC LIMIT ".$offset.",".$rec_limit;
}
else
{
	$qry="SELECT * FROM tblpatients ORDER BY p_id ASC LIMIT ".$offset.",".$rec_limit;
}


$data=$con->getMultiRow($qry,  __FILE__,  __LINE__);



?>
 
       
 <table id="tblResult" width="961" border="0" cellspacing="0" cellpadding="5">
  <tr class="t-head">
   <td width="28" class="border"><input id="selectall"   name="selectall" type="checkbox" value="" style="display:none"/></td>
    <td width="235" class="border">Patient Name</td>
    <td width="193" class="border">Department</td>
    <td width="115" class="border">MRN</td>
    <td width="143" class="border">Admit Date</td>
    <td width="108" class="border">Discharge Date</td>
     <td width="106" class="border">Type </td>
    <td width="106" class="border">Actions 
  
      
  </td>
  </tr>
 <?php
 for($i=0;$i<count($data);$i++){
 ?><tr class="<?php echo ($i%2!=0 ? "t-line-white" : "t-line-grey");?>">
    <td><input name="file[]" type="checkbox" value="<?php echo $data[$i]['p_id'];?>"  style="display:none"/></td>
    <td><?php echo $data[$i]['p_name'];?></td>
    <td><?php echo $data[$i]['p_department'];?></td>
    <td><?php echo $data[$i]['p_mrn'];?></td>
    <td><?php echo date("d-m-Y", strtotime( $data[$i]['p_admitdate']));?></td>
    <td><?php echo date("d-m-Y", strtotime( $data[$i]['p_dischargedate']));?></td>
    <td><?php echo $data[$i]['p_type'];?></td>
    <td><a id="opener<?php echo $data[$i]['p_id'];?>" href="#" value="<?php echo $data[$i]['p_id'];?>"  onClick="getFiles(this);">View Files</a>
      </td>
    <!--<td>
    	<a href="#"><img src="images/edit.png" border="0" /></a>&nbsp;&nbsp;
    	<a href="#"><img src="images/set.png" border="0" /></a>&nbsp;&nbsp;
    	<a href="#"><img src="images/del.png" border="0" /></a>
      
    </td>-->
  </tr>
  <?php } ?>
</table>
           