<?php 
require_once("lib/config.php");
$files_array=0;
$qryFile="";
$dfile="";
if(isset($_POST['file']) && $_POST['file']!='')
{
	$files_array =$_POST['file'];
    $qy="Select p_name from tblpatients where p_id= 1";//.$_POST['file'];
	$df=$con->getSingleRow($qy, __FILE__,  __LINE__);
	$us=$_SESSION['username'];
    $atype='Patient files retrived';
    $pt=$df['p_name'];
    $details=$atype." by : ".$us;
    $ip=getIP();
    $date=date("Y-m-d H:i:s");
	$dv="Popup.php";
    $k= useraudit($con,$date,$ip,$us,$atype,$pt,$details,$dv);
}
//$files_array ='1';

if(count($files_array)>0)
{	
	$qryFile="SELECT p.p_id,p.p_name,tmp.ppath,tmp.pdate,tmp.psum,tmp.ptype FROM tblpatients p,tbltemp tmp WHERE p.p_id=tmp.id and tmp.id in ($files_array) ORDER BY  p.p_id,tmp.id ASC ";
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
            <tr class="t-head-dl" style="background:#CCCCCC" >
             <td ></td>
              <td colspan="5"><?php echo $pname;?></td> <!-- changed to display 'pname' and not 'p_name'-->
            </tr>
            <tr class="t-head" >
            <td ><!--<input type="checkbox" id="checkAll" value=""/>--></td>
<td width="20%">Admit Date</td>

<td width="25%">Discharge Date</td>

<td width="35%">Report Name</td>

<td width="10%">Type</td>

<td width="10%"></td>
            </tr>
            <?php } 

?>

<tr class="<?php echo ($i%2!=0 ? "t-line-white" : "t-line-grey");?>" >
<td ><!--<input type="checkbox" id="<?php echo $dfile[$i]['f_filename'];?>" name="selFiles" value="<?php echo $dfile[$i]['f_filename'];?>" class="chkboxes"/>--></td>
<td width="143"><?php echo substr($dfile[$i]['pdate'],4,2)."/".substr($dfile[$i]['pdate'],6,2)."/".substr($dfile[$i]['pdate'],0,4);?></td>
<td width="143"><?php echo substr($dfile[$i]['pdate'],4,2)."/".substr($dfile[$i]['pdate'],6,2)."/".substr($dfile[$i]['pdate'],0,4);?></td>
<td width="235"><?php echo $dfile[$i]['psum'];?></td>
<td width="106"><?php echo $dfile[$i]['ptype'];?></td>
<!--open in new tab and point to the document directly without using onclick-->
<td width="100%">&nbsp;<a target='_blank' href="storage/<?php echo $dfile[$i]['ppath']; ?>">View</a></td> 

<!--<td width="100%">&nbsp;<a href="#" value="<?php echo $dfile[$i]['ppath'];   ?>" onclick="getF(this)" >View</a></td> -->
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
<!--  <tr>
    <td></td>
    <td><input type="button" value="View" class="Logout-btn" onClick="getF(checkboxValues())" <?php ($_SESSION['allow_view']==0 ? 'disabled="disabled"': "");  ($_SESSION['allow_save']==0 ? "onclick='shower()'":"");?>/>|<input name="" type="button" value="Print" class="Logout-btn"  onclick="PrintAll(checkboxValues())" <?php ($_SESSION['allow_view']==0 ? 'disabled="disabled"': ""); ($_SESSION['allow_save']==0 ? "onclick='shower()'":"");?>/></td>
  </tr>-->
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