<?php 
require_once("lib/config.php");
$files_array=0;
$qryFile="";
$dfile="";
if(isset($_POST['file']) && $_POST['file']!='')
{
	$files_array =$_POST['file'];
    $qy="Select p_name from tblpatients where p_id=1";//.$_POST['file'];
	$df=$con->getSingleRow($qy, __FILE__,  __LINE__);
	$us=$_SESSION['username'];
    $atype='Patient files retrived';
    $pt=$df['p_name'];
    $details=$atype." by : ".$us;
    $ip=getIP();
    $date=date("Y-m-d H:i:s");
    $k= useraudit($con,$date,$ip,$us,$atype,$pt,$details);
}
//$files_array ='1';
if(count($files_array)>0)
{
	$qryFile="SELECT p.p_id,p.p_name,f.f_filename FROM tblfile f,tblpatients p WHERE f.p_id=p.p_id and f.p_id in ($files_array) ORDER BY  p_id,f_id ASC ";
	$dfile=$con->getMultiRow($qryFile,  __FILE__,  __LINE__);
}
//pr($dfile);
?>
<div id="Container" style="width:740px;"> 	
  <!--Header ends-->
  <div id="Content" style=" width:100%">
    <div id="Col-2" style="width:100%; height:auto;">
      <!--Bread-Crum ends-->
      <div id="table-main" style="width:100%">
        <div class="table-h"></div>
        <div class="table-h-" style="width:700px">Results</div>
        <div class="table-h--"></div>
      </div>
      <div id="Results-M" style="width:718px">
        <div class="table-bod" style="width:716px">

<table width="718" border="0" cellspacing="0" cellpadding="5">
            <?php 
 if(count($dfile)!=0)
 {
 $pname='';

 for($i=0; $i<=count($dfile)-1;$i++)
 {
 
 if($pname!=$dfile[$i]['p_name'])
 {
    $pname=$dfile[$i]['p_name'];
        
 ?>
            <tr class="t-head-dl" >
             <td ><input type="checkbox" id="checkAll" value=""/></td>
              <td><?php echo $pname;?></td>
            </tr>
            <?php } 

?>
            <tr class="<?php echo ($i%2!=0 ? "t-line-white" : "t-line-grey");?>" >
            <td ><input type="checkbox" id="<?php echo $dfile[$i]['f_filename'];?>" name="selFiles" value="<?php echo $dfile[$i]['f_filename'];?>" class="chkboxes"/></td>
              <td width="100%">&nbsp;<a href="download.php?download_file=<?php echo $dfile[$i]['f_filename'];   ?>" style="width:100%;height:100%;display:block;"> <?php echo $dfile[$i]['f_filename'];   ?></a></td>
            </tr>
            <?php 

  }
  }
  
  else
  {
  echo "<tr>
  <td>No record selected!</td>
  </tr>";
  }
  ?>
  <tr>
    <td></td>
    <td><input type="button" value="View" class="Logout-btn" onClick="getF(checkboxValues())" <?php ($_SESSION['allow_view']==0 ? 'disabled="disabled"': "");  ($_SESSION['allow_save']==0 ? "onclick='shower()'":"");?>/>|<input name="" type="button" value="Print" class="Logout-btn"  onclick="PrintAll(checkboxValues())" <?php ($_SESSION['allow_view']==0 ? 'disabled="disabled"': ""); ($_SESSION['allow_save']==0 ? "onclick='shower()'":"");?>/></td>
  </tr>
          </table>

        </div>
      </div>
    </div>
    <!--Results-M ends-->
  </div>
  <!--Col-2 ends-->
  <div class="clr"></div>
</div>
<!--Content ends-->
<!--Footer ends-->
<!--Container ends-->

<script type="text/javascript">


 //--------------Retrive Checkbox values--------------------------------
 function checkboxValues() {         
     var allVals = [];
     //var myurl = [];   
	if( $('input[name=selFiles]:checked').length == 0 )
    {
        alert("You must check atleast one checkbox");
        return false;
    }
    else
	{
      //  return true;
	// alert("checkbox");
	 $('input[name=selFiles]:checked').each(function() {
       
	   allVals.push($(this).val());
	  
	 });
	 }
	 return allVals; // process the array as you wish in the function so it returns what you need serverside
 }
 
 
  
//Select/Deselect check boxes

  $('#checkAll').click(function(event) {   
    if(this.checked) {
      // Iterate each checkbox
      $(':checkbox').each(function() {
        this.checked = true;                        
      });
    }
    else {
      // Iterate each checkbox
      $(':checkbox').each(function() {
        this.checked = false;
      });
    }
  });
//Function to print multiple files
function PrintAll(arr) {
   //alert(arr);
    var pages = arr;
  
   var oWindow='';
    for (var i = 0; i < pages.length; i++) {

		//if (navigator.appName == 'Microsoft Internet Explorer') 
			
      // Do something after 5 seconds
	  oWindow = window.open("storage/"+pages[i], "_blank");
	  setTimeout(function() {
	  oWindow.print();
	  oWindow.close();
	  }, 30000);
 		
    }

}
</script>