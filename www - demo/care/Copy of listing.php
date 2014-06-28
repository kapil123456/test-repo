<?php
require_once('lib/config.php');
$_PHP_SELF=$_SERVER['PHP_SELF'];
$message='';
$rec_limit = 10;
if(isset($_SESSION['username']))
{
if(isset($_REQUEST['_cmd'])){
  $us=$_SESSION['username'];
  $atype='Custom search screen accessed';
  $pt='';
  $details=$atype." by : ".$us;
  $ip=getIP();
  $date=date("Y-m-d H:i:s");
  $k= useraudit($con,$date,$ip,$us,$atype,$pt,$details);
  unset($_REQUEST['_cmd']);
  echo "<script> location.replace('listing.php'); </script>";
 
 
}

/*if(isset($_REQUEST['mg']) && $_REQUEST['mg']!="" && !isset($_POST['submit']))
{*/
if(isset($_SESSION['er']) && $_SESSION['er']!="" && !isset($_POST['submit']))
{

 $message=$_SESSION['er'];

 echo "<script type='text/javascript'>alert('$message');</script>";
unset($_SESSION['er']);

}




 
$rec_count=0;
$pfc=0;
$qy="SELECT count(p_id) as count FROM tblpatients";

$rc=$con->getSingleRow($qy,  __FILE__,  __LINE__);

$rec_count=$rc['count'];


$qpy="SELECT p_id as pid FROM tblpatients";

$pc=$con->getMultiRow($qpy,  __FILE__,  __LINE__);

//pr($pc);
/*if(!empty($pc))
{
	for($m=0;$m<=count($pc)-1;$m++)
	{
		$sqlfilecheck="select count(f_id) as fcount from tblfile where p_id='".$pc[$m]['pid']."'";
		$fc=$con->getSingleRow($sqlfilecheck,  __FILE__,  __LINE__);
		$pfc=$fc['fcount'];
		
		if(empty($pfc))
		{
			//echo $pfc;
			$issql="Update tblpatients set p_type='Needs Retrieval' where p_id='".$pc[$m]['pid']."'";
			$upr=$con->updateRecords($issql);
		
		}
	}
}*/

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
//$array=array("file1.pdf","file2.pdf","file3.pdf");

if(isset($_POST) && isset($_POST['submit']) && $_POST['submit'])
{


	
	
	
	$arry=array('txt_P_Name'=>'p_name','hospitalList'=>'p_phospital','departmentList'=>'p_department','typeList'=>'p_type',
			'datefrom'=>'p_admitdate','dateto'=>'p_admitdate','datelist'=>'p_admitdate');
	
    
	$searchfilter='';
	$keys=array_keys($arry);
	//count($keys);
	for($n=0;$n<count($keys);$n++)
	{
	
		if(isset($_POST[$keys[$n]]) && $_POST[$keys[$n]]!='-1' && $_POST[$keys[$n]]!='')
		{
			if($keys[$n]!='datefrom' && $keys[$n]!='dateto' && $keys[$n]!='datelist')
			{ 
				$searchfilter.=' and '. $arry[$keys[$n]]."='".$_POST[$keys[$n]]."'";
		 	}
		
			if($keys[$n]=='datelist'  )
			{
				if( $_POST[$keys[$n]]!='range')
				{
					$searchfilter.=' and '. $arry[$keys[$n]].">='".date('Y-m-d',strtotime($_POST[$keys[$n]]))."'";	
				}
				else
				{
					 if($keys[$n]=='datefrom')
				 	 {
							 $searchfilter.=' and '. $arry[$keys[$n]].">='".date('Y-m-d',strtotime($_POST[$keys[$n]]))."'";	
					 }
					
					 if($keys[$n]=='dateto')
					 {
							 $searchfilter.=' and '. $arry[$keys[$n]]."<='".date('Y-m-d',strtotime($_POST[$keys[$n]]))."'";	
					 }
		 
		
				}
			 }
		
		
		
		
		}
	}
	$searchfilter=ltrim($searchfilter,'and ');
    
  $qry="SELECT * FROM tblpatients ".($searchfilter!='' ? 
	" where ".$searchfilter:'')." GROUP BY p_mrn  ORDER BY p_id ASC LIMIT ".$offset.",".$rec_limit;
 
}	
else
{	$qry="SELECT * FROM tblpatients GROUP BY p_mrn 
	ORDER BY p_id ASC LIMIT ".$offset.",".$rec_limit;
}

$data=$con->getMultiRow($qry,  __FILE__,  __LINE__);


}
else
{
  header("location: login.php");
}
?>

<!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Care Connect</title>

<link rel="stylesheet" type="text/css" href="style/jquery-ui-1.10.3.custom.min.css">
<link href="style/medical-services-style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="style/jquery-ui-1.10.3.custom.css">
<link rel="stylesheet" type="text/css" href="style/jquery-ui.css">

  <script src="scripts/jquery-1.9.1.js"></script>
  <script src="scripts/jquery-ui.js"></script>
  
<script>
  function checkForm()
  {
    if(form.dp_list.value==-1)
      {
        alert("Please choose a reason!");
		form.dp_list.focus();
		return false;
      }
  }



 $(function() {
    $( "#tabs" ).tabs();
 	
 
    
    $( "#dialog, #dialog1" ).dialog({
      autoOpen: false,
	  modal: true,
	  opacity: .8,
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });
//-----------2nd Dialog-----------

	
  });
//------------------------------
function getF(arr) 
{
 // alert(arr);
  $.ajax({
  	type:"post",
    url: 'ipopup.php',
	data: {myStr:arr},
    success: function(data) {
	// document.getElementById('dialog1').innerHTML=data;
	  $("#dialog1").html(data);
	  $("#dialog1").dialog({model:true, width:800, height:710}).dialog('open');
    }
  });
 }
 
//---------------TEXT TYPE SEARCH------------------------------
function myFunction(val)
{

	var textboxvalue = val;
  	var optn=document.forms["form"]["pfilter"].value;
	var dplist=document.forms["form"]["dp_list"].value;
	if(dplist!=-1)
	{
	 $.ajax({
		type:"post",
		url: 'search.php',
		data: {txt_P_Name:textboxvalue,pfilter:optn},
		success: function(data) 
		{
		$("#tblResults").html(data);
		$("#tblResults").replaceWith($(this).text());
		}
	  });
	 }
	 else
	 {
	 alert("Please choose a reason!");
		form.dp_list.focus();
		return false;
	 }
	 
}
    
	
//------------FILES DIALOGUE
function getFiles(obj) {

  
  $.ajax({
  	type:"post",
    url: 'popup.php',
	data: {file:$(obj).attr('value')},
    success: function(data) {

      $("#dialog").html(data);
	  $("#dialog").dialog({model:true, width:800}).dialog('open');
    }
  });

 }


function showhidedaterange(o)
{
if($(o).val()=='range')
{
 $('tr[rel="daterange"]').show();
}
else
{
$('tr[rel="daterange"]').hide();
}
}

 $(function() {
    $( "#datefrom" ).datepicker({
      showOn: "button",
      buttonImage: "images/Calen.png",
      buttonImageOnly: true
    });
  });
  
   $(function() {
    $( "#dateto" ).datepicker({
      showOn: "button",
      buttonImage: "images/Calen.png",
      buttonImageOnly: true
    });
  });
function shower()
{
alert("You don't have sufficint previllages!");
}
</script><!-- SCRIPT ENDS-->

</head>


<body>
	<div id="Container">
    	<div id="Header">
        	<div id="Logo"><a href="index.htm">  <img src="images/medical-services-logo.jpg" alt="medical services logo" border="0" /></a></div>
            <div id="Head-right">
            	<div id="Hr-row-1">Welcome!   &nbsp;&nbsp;&nbsp; <span class="Hr-row-1-span"><?php echo $_SESSION['username']; ?></span></div>
                <div id="Hr-row-2">
                	<!--<div id="Hr-row2-c1">
                    	<input name="" type="button" value="Reports" class="Reports-btn" />
                    </div>-->
                    <div id="Hr-row2-c2">
                    <a href="logout.php" class="button" >Logout</a>
                    </div>
                          <?php
					if(isset($_SESSION['allow_tickets']) && $_SESSION['allow_tickets']!=0)
					{?> 
                  	<div id="Hr-row2-c2" style=" width:auto">
                	<a href="submitticket.php" class="button" style=" width:auto" >Submit Ticket</a>
                    </div>
					<?php }?>
                </div> <!--Hr-row-2 ends-->
            </div> <!--Head-right ends-->
        </div> <!--Header ends-->
        <div id="Content">
        	<div id="Col-1">
            	<div id="Search-M">
                	<div class="Search-Hdng">Search <?php if(isset($_POST['txt_P_Name'])){?> <a style=" float:right; font-size:10px; margin-right:10px; color:#FFF; text-decoration:underline" href="index.php">Clear Search</a><?php }?></div>
                    <div id="Search-inner">
                    <form name="form" method="post"  onsubmit="return(checkForm(this));">
     <table width="226" border="0" cellpadding="0" cellspacing="0" style="color:#FFFFFF">
  <tr height="22">
    <td style="font-size:14px">Patient Identification</td>
  </tr>
  <tr height="35">
    <td>
    	<select  class="Filling-option">
        	     <option>Patient Name</option>
            <option>Patient ID (External ID)</option>
            <option>Patient ID (Internal ID)</option>
            <option>Alternate Patient ID – PID</option>
            <option>Patient Name</option>
            <option>Mother’s Maiden Name</option>
            <option>Date/Time of Birth</option>
            <option>Sex</option>
            <option>Patient Alias</option>
            <option>Race</option>
            <option>Patient Address</option>
            <option>Country Code</option>
            <option>Phone Number – Home</option>
            <option>Phone Number – Business</option>
            <option>Primary Language</option>
            <option>Marital Status</option>
            <option>Religion</option>
            <option>Patient Account Number</option>
            <option>SSN Number – Patient</option>
  
        </select>    </td>
  </tr>
  <tr height="38">
    <td><input id="txt_P_Name" name="txt_P_Name" type="text" class="Filling-field" value="<?php echo (isset($_POST['txt_P_Name'])? $_POST['txt_P_Name']: "");?>" onKeyUp="myFunction(this.value)"/></td>
  </tr>
  <tr height="24" style="font-size:14px">
    <td>Reason for Access</td>
  </tr>
  <tr height="35">
    <td>
    	<select id="dp_list" name="dp_list"  class="Filling-option">
        	<option value="-1">
            	Select One
            </option>
        	<option value="Research">
            	Research
            </option>
        	<option value="Export">
            	Export
            </option>                                    
        </select>    </td>
  </tr>
  <tr height="32" style="color:#000000; font-size:16px; font-weight:bold">
    <td>Filter Option</td>
  </tr>
  <tr height="22" style="font-size:14px">
    <td>Hospitals</td>
  </tr>
  <tr height="35">
    <td>
    	<select  name="hospitalList" class="Filling-option">
        	<option value="-1">
            	Select One
            </option>
            <option>
            	Lakewood Ranch
            </option>
            <option>
            	Manatee Memorial
            </option>
        </select>    </td>
  </tr>
  <tr height="24" style="font-size:14px">
    <td>Departments</td>
  </tr>
  <tr height="35">
    <td>
    	<select  name="departmentList" class="Filling-option">
        	<option value="-1">
            	Select One
            </option>
             <option>
            	Obstetrics
            </option>
            <option>
            	Radiology
            </option>
        </select>    </td>
  </tr>
  <tr height="24" style="font-size:14px">
    <td>Document Type</td>
  </tr>
  <tr height="35">
    <td>
    	<select name="typeList" class="Filling-option">
        <option value="-1">
            	Select One
            </option>
            <option>
            	Images (Fetal Strips)
            </option>
            <option>
            	Reports
            </option>
        </select>    </td>
  </tr>
  <tr height="24" style="font-size:14px">
    <td>Date to Filter</td>
  </tr>
  <tr height="35">
    <td>
    	<select  name="datelist" class="Filling-option" onChange="showhidedaterange(this)">
        	<option value="-1">
            	All
            </option>
         	<option value="-3 Months">
            	3 Months
            </option>
            <option value="-6 Months">
            	6 Months
            </option>
            <option value="-1 Year">
            	1 Year
            </option>
            <option value="-2 Year">
            	2 Year
            </option>
              <option value="range">
            	Range
            </option>
        </select>    </td>
  </tr>
  
  <tr>
    <td>    </td>
  </tr>
  <tr rel="daterange" style="display:none">
    <td>
    	<table width="226" border="0" cellpadding="0" cellspacing="0">
  <tr style="color:#000000; font-size:14px" height="24">
    <td width="113">From</td>
    <td width="113">To</td>
  </tr>
</table>   
 </td>
  </tr>
  <tr  rel="daterange" style="display:none">
    <td>    </td>
  </tr>
  <tr  rel="daterange" style="display:none">
    <td>
    	<table width="226" border="0" cellpadding="0" cellspacing="0">
              <tr style="color:#000000; font-size:14px" height="24">
                <td><input id="datefrom" name="datefrom" type="text" class="Filter-fields" /></td>
                <td></td>
                <td><input id="dateto" name="dateto" type="text" class="Filter-fields" /></td>
                <td></td>
              </tr>
		</table>    
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
    	<table width="226" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><input name="submit" type="submit" value="Submit" class="Logout-btn" /></td>
    <td><input name="input" type="reset" value="Reset" class="Logout-btn" /></td>
  </tr>
</table>    
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
                    </div> <!--Search-Inner ends-->
                </div> <!--Search-M ends-->
            </div> <!--Col-1 ends-->
            <div id="Col-2">
            	<div id="Bread-Crum"><a href="index.php">Main Menu</a> &gt; <span class="Brd-crms-span">Reports</span>                </div> 
       	    <!--Bread-Crum ends-->
                
                <div id="table-main">
                    <div class="table-h"></div>
                    <div class="table-h-">Results</div>
                    <div class="table-h--"></div>                                
            	</div>
                
                <div id="Results-M">
                	<div class="table-bod">
<table id="tblResults" width="961" border="0" cellspacing="0" cellpadding="5">
  <tr class="t-head">
   <td width="28" class="border"><input id="selectall"   name="selectall" type="checkbox" value="" style="display:none"/></td>
    <td width="235" class="border">Patient Name</td>
    <td width="193" class="border">Department</td>
    <td width="115" class="border">MRN</td>
    <td width="143" class="border">Admit Date</td>
    <td width="108" class="border">Discharge Date</td>
     <td width="106" class="border">Type </td>
    <td width="106" class="border">Documents</td>
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
    <td> <?php if( $data[$i]['p_type'] != 'Needs Retrieval'){?> <a id="opener<?php echo $data[$i]['p_id'];?>" href="#" value="<?php echo $data[$i]['p_id'];?>" onClick="getFiles(this);">View Files</a> <?php } else {?> <a href="test.php?pid=<?php echo $data[$i]['p_mrn'];?>">Fetch File </a><?php }?></td>
 
  </tr>
  <?php } ?>
</table>
           
                                         <div id="dialog" title="Download Files"></div>
                                        
										<div class="clear"></div>                
                </div>
			</div>
            	<div id="Rsults-btn-row">
                	<div id="View-btn-div"></div>
                    <div id="Prv-nxt-Main">
   <table width="230" border="0" cellpadding="0" cellspacing="0">
  <tr>
  <td>
  <?php if( $page > 0 && $left_rec > $rec_limit)
{
   $last = $page - 2;
   echo "<td><a href=\"$_PHP_SELF?page=$last\" class=\"button\" ><<</a> $page</td>";
   echo "<td><a href=\"$_PHP_SELF?page=$page\" class=\"button\" >>></a></td>";
}
else if( $page == 0 && $left_rec > $rec_limit)
{
   echo "<td width='50%'>&nbsp;</td><td><a href=\"$_PHP_SELF?page=$page\" class=\"button\" >Next</a></td>";
}
else if( $page > 0 && $left_rec < $rec_limit )
{
   $last = $page - 2;
   echo "<td><a href=\"$_PHP_SELF?page=$last\" class=\"button\" >Last</a></td><td></td>";
}

?>
  </tr>
</table>

                    </div> <!--Prv-nxt-Main ends-->
                </div><!--Rsults-btn-row ends-->
                </div> <!--Results-M ends-->
                
            </div> <!--Col-2 ends-->
            <div class="clr"></div>
        </div> <!--Content ends-->
        <div id="Footer">
        	Powered by: Websplines
        </div> <!--Footer ends-->
        
    </div> <!--Container ends-->
     <div id="dialog1" title="Files"></div>
</body>
</html>
