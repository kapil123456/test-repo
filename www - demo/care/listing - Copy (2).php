<?php
require_once('lib/config.php');
$_PHP_SELF=$_SERVER['PHP_SELF'];
$message='';
$rec_limit = 10;
$qry='';
$data=array();
$atype='';
$us='';
if(isset($_SESSION['username']))
{
if(isset($_REQUEST['_cmd'])){
  $us=$_SESSION['username'];
  $atype='Custom search';
  $pt='';
  $details=$atype." by : ".$us;
  $ip=getIP();
  $date=date("Y-m-d H:i:s");
  $dv="Listing.php";
  $k= useraudit($con,$date,$ip,$us,$atype,$pt,$details,$dv);
  echo "<script> location.replace('listing.php'); </script>";
  exit();
}

if(isset($_SESSION['er']) && $_SESSION['er']!="" && !isset($_POST['submit']))
{

 $message=$_SESSION['er'];

 echo "<script type='text/javascript'>alert('$message');</script>";
unset($_SESSION['er']);

}

if(isset($_POST) && isset($_POST['submit']) && $_POST['submit'])
{

		$arry=array('txt_P_LName'=>'p_lname','txt_P_FName'=>'p_fname','txt_P_dob'=>'p_dob','txt_P_mrn'=>'p_mrn');
//	,'hospitalList'=>'p_phospital'
$pt = '';$us='';
if(isset($_POST['txt_P_mrn']) && $_POST['txt_P_mrn']!="")
{
$pt=$_POST['txt_P_mrn'];
}
 if(isset($_POST['txt_P_FName']) && $_POST['txt_P_FName']!="")
{
$pt=$_POST['txt_P_FName'];
}
 if(isset($_POST['txt_P_LName']) && $_POST['txt_P_LName']!="")
{
$pt=$_POST['txt_P_LName'];
}
//echo $pt;
  $us=$_SESSION['username'];
  $atype= 'Search query';
  
  if(isset($_POST['dp_list']) && $_POST['dp_list']!="")
{
$details="Searched for ".$pt.", by : ".$us." ,Reason for Access ".$_POST['dp_list'];
}
  $ip=getIP();
  $date=date("Y-m-d H:i:s");
  $dv="Listing.php";
  $k= useraudit($con,$date,$ip,$us,$atype,$pt,$details,$dv);


	$searchfilter='';
	$keys=array_keys($arry);
	
	for($n=0;$n<count($keys);$n++)
	{
	
		if(isset($_POST[$keys[$n]]) && $_POST[$keys[$n]]!='-1' && $_POST[$keys[$n]]!='')
		{
		
			if($keys[$n]!='txt_P_dob')
			{
				$searchfilter.=' and '. $arry[$keys[$n]]." like '".$_POST[$keys[$n]]."%'";
								
		 	}
		
			if($keys[$n]=='txt_P_dob')
			{
		
				$searchfilter.=' and '. $arry[$keys[$n]]."='".date('Y-m-d',strtotime($_POST[$keys[$n]]))."'";	
			}
		 	
		}
	}
	$searchfilter=ltrim($searchfilter,'and ');
   if($searchfilter!='')
   {
  $qry="SELECT * FROM tblpatients ".($searchfilter!='' ? 
	" where ".$searchfilter:'');
	}
	
	
 
}	
else
{	$qry="";
}
if($qry!="")
{
$data=$con->getMultiRow($qry,  __FILE__,  __LINE__);
}

}
else
{
 // header("location: login.php");
 echo "<script> location.replace('login.php'); </script>";
  exit();
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
 	
 
    
    $( "#dialog, #dialog1,#dialog3" ).dialog({
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
function getF(obj) 
{
 // alert(arr);
  $.ajax({
  	type:"post",
    url: 'ipopup.php',
	data: {myStr:$(obj).attr('value')},
    success: function(data) {
	// document.getElementById('dialog1').innerHTML=data;
	  $("#dialog1").html(data);
	  $("#dialog1").dialog({model:true, width:screen.width,top:0,left:0,height:screen.height}).dialog('open');
    }
  });
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
//------------FILES DIALOGUE
function fetchFiles(obj) {


  $.ajax({
  	type:"post",
    url: 'InProcess.php',
	data: {mrn:$(obj).attr('value')},
    success: function(data) {

      $("#dialog3").html(data);
	  $("#dialog3").dialog({model:true, width:100,height:180}).dialog('open');
    }
  });

 }

function shower()
{
alert("You don't have sufficint previllages!");
}


  
</script><!-- SCRIPT ENDS-->

</head>


<body>
	<div id="Container">
    	<div id="Header">
        	<div id="Logo"><a href="index.php">  <img src="images/medical-services-logo.jpg" alt="medical services logo" border="0" /></a></div>
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
                  	<div id="Hr-row2-c2" style="width:auto">
                	<a href="submitticket.php" class="button" style=" width:auto" >Submit Ticket</a>
                    </div>
					<?php }?>
                </div> <!--Hr-row-2 ends-->
            </div> <!--Head-right ends-->
        </div> <!--Header ends-->
        <div id="Content">
        	<div id="Col-1">
            	<div id="Search-M">
                	<div class="Search-Hdng">Search</div>
                    <div id="Search-inner">
                    <form name="form" method="post"  onsubmit="return(checkForm(this));">
     <table width="226" border="0" cellpadding="0" cellspacing="0" style="color:#FFFFFF">
  <tr height="22">
    <td style="font-size:14px">Patient Identification</td>
  </tr>
  <tr height="35">
    <td>
    <label id="pfilterlname" name="pfilterlname" style="color:#000033;font-size:12px" value="p_lname" >Last Name:</label>
    <!--	<select  id="pfilterlname" name="pfilterlname" class="Filling-option">
				 <option value="p_lname">Last Name</option>  
        </select> -->   </td>
  </tr>
  <tr height="38">
    <td><input id="txt_P_LName" name="txt_P_LName" type="text" class="Filling-field" value="<?php echo (isset($_POST['txt_P_LName'])? $_POST['txt_P_LName']: "");?>" /></td>
  </tr>
  <tr height="35">
    <td>
     <label id="pfilterfname" name="pfilterfname" style="color:#000033;font-size:12px" value="p_fname" >Fist Name:</label>
    	<!--<select  id="pfilterfname" name="pfilterfname" class="Filling-option">
        	     <option value="p_fname">Fist Name</option>
      </select>    -->
    </td>
  </tr>
    <tr height="38">
    <td><input id="txt_P_FName" name="txt_P_FName" type="text" class="Filling-field" value="<?php echo (isset($_POST['txt_P_FName'])? $_POST['txt_P_FName']: "");?>"/></td>
  </tr>
    <tr height="35">
    <td>
     <label id="pfilterdob" name="pfilterdob" style="color:#000033;font-size:12px" value="p_dob" >Date of Birth:</label>
<!--    	<select  id="pfilterdob" name="pfilterdob" class="Filling-option">
                 <option value="p_dob">Date of Birth</option>
        </select>    
-->    </td>
  </tr>
   <tr height="38">
    <td>
    
   <div id='date-dropdown' height='37'>
      
      <select id='month' style='font-size:10px'></select>
      <select id='day'  style='font-size:10px'></select>
      <select id='year' style='font-size:10px'></select>
    </div>
    
    <input id='txt_P_dob' name='txt_P_dob' type='hidden' />    
    
   
  <script>
      /** Init **/
      
      $(function() {        
        initDatePicker();
        initDateDropDown();
      });
      
      
      /** Date Picker **/
      
      function initDatePicker() {
        $('#txt_P_dob').datepicker({
          showOn: 'button',
          buttonImage: 'images/Calen.png',
          buttonImageOnly: true,
          beforeShow: function(input, inst) {            
            setTimeout(function() {
              var $calendar = inst.dpDiv;            
              $calendar.position({
                my: 'left top',              
                at: 'left bottom+5',
                of: $('#date-dropdown')
              });
            }, 1);
          },
          onSelect: function() {
            syncDateDropdownWithDatePicker();
          }
        });        
      }
      
      
      /** Date Dropdown **/
      
      function initDateDropDown() {
        var currentDate = new Date();
        var currentYear = currentDate.getFullYear();
        var currentMonth = currentDate.getMonth();
        var currentDay = currentDate.getDate();
        var numberOfDays = daysInMonth(currentYear, currentMonth);
        
        populateYearDropdown(1900, currentYear+10);
        populateMonthDropdown();
        populateDayDropdown(numberOfDays);
        
        //selectYearDropdown(currentYear);
        //selectMonthDropdown(currentMonth);
       	//selectDayDropdown(currentDay);
        
        bindYearDropdownEvent();
        bindMonthDropdownEvent();
        bindDayDropdownEvent();
      }      
      
      function populateYearDropdown(startYear, endYear) {
        var yearOptions = [];
        yearOptions.push('<option value=""></option>');
        for (var y = startYear; y <= endYear; y++) {          
          yearOptions.push('<option value="' + y + '">' + y + '</option>');
        }
        
        $('#year').html(yearOptions.join());
      }
      
      function populateMonthDropdown() {
        var monthOptions = [];
        monthOptions.push('<option value=""></option>');
        var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        for (var m = 0; m < months.length; m++) {          
          monthOptions.push('<option value="' + m + '">' + months[m] + '</option>');
        }
        
        $('#month').html(monthOptions.join());
      }
      
      function populateDayDropdown(numberOfDays) {
        var dayOptions = [];        
        dayOptions.push('<option value=""></option>');
        for (var d = 1; d <= numberOfDays; d++) {          
          dayOptions.push('<option value="' + d + '">' + d + '</option>');
        }
        $('#day').html(dayOptions.join());
      }
      
      function selectYearDropdown(year) {
        $('#year option[value=' + year + ']').attr('selected', true);
      }
      
      function selectMonthDropdown(month) {
        $('#month option[value=' + month + ']').attr('selected', true);
      }
      
      function selectDayDropdown(day) {
        $('#day option[value=' + day + ']').attr('selected', true);
      }
      
      function refreshDayDropdown() {
        var year = parseInt($('#year').val());
        var month = parseInt($('#month').val());
        var day = parseInt($('#day').val());        
        var numberOfDays = daysInMonth(year, month);
        
        populateDayDropdown(numberOfDays);
        
        if (day <= numberOfDays) {
          selectDayDropdown(day);
        } else {
          selectDayDropdown(numberOfDays);
        }        
      }
      
      /*** Event handlers ***/
      
      function bindYearDropdownEvent() {
        $('#year').on('change', function() {
          refreshDayDropdown();
          syncDatePickerWithDateDropdown();
        });
      }
      
      function bindMonthDropdownEvent() {
        $('#month').on('change', function() {
          //refreshDayDropdown();
          syncDatePickerWithDateDropdown();
        });
      }
      
      function bindDayDropdownEvent() {
        $('#day').on('change', function() {
          syncDatePickerWithDateDropdown();
        });
      }
      
      
      /** Sync Functions **/
      
      function syncDateDropdownWithDatePicker() {
        var datePickerDate = $('#txt_P_dob').datepicker('getDate');
        selectYearDropdown(datePickerDate.getFullYear());
        selectMonthDropdown(datePickerDate.getMonth());
        refreshDayDropdown();
        selectDayDropdown(datePickerDate.getDate());
      }
      
      function syncDatePickerWithDateDropdown() {
        var dropdownYear = parseInt($('#year').val());
        var dropdownMonth = parseInt($('#month').val());
        var dropdownDay = parseInt($('#day').val());
        var dropdownDate = new Date(dropdownYear, dropdownMonth, dropdownDay);
        $('#txt_P_dob').datepicker('setDate', dropdownDate);
      }
      
      
      /** Utility Function **/
      
      function daysInMonth(year, month) {
        return new Date(year, month+1, 0).getDate();
      }
      
    </script>
    
    </td>
  </tr>
      <tr height="35">
    <td>
       <label id="pfiltermrn" name="pfiltermrn" style="color:#000033;font-size:12px" value="p_mrn" >MRN:</label>
<!--    	<select  id="pfiltermrn" name="pfiltermrn" class="Filling-option">
                 <option value="p_mrn">MRN</option>
        </select>   --> 
    </td>
  </tr>
     <tr height="38">
    <td><input id="txt_P_mrn" name="txt_P_mrn" type="text" class="Filling-field" value="<?php echo (isset($_POST['txt_P_mrn'])? $_POST['txt_P_mrn']: "");?>" /></td>
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
  <!--<tr height="32" style="color:#000000; font-size:16px; font-weight:bold">
    <td>Filter Option</td>
  </tr>-->
  <tr height="22" style="font-size:14px">
    <td>Hospitals</td>
  </tr>
  <tr height="35">
    <td>
    	<select  name="hospitalList" class="Filling-option">
        	<!--<option value="-1">
            	Select One
            </option>-->
            <option>
            	Lakewood Ranch
            </option>
       <!--     <option>
            	Manatee Memorial
            </option>-->
        </select>    </td>
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
    <td><input name="submit" type="submit" value="Search" class="Logout-btn" /></td>
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
    <td width="235" class="border">Last Name</td>
    <td width="193" class="border">First Name</td>
    <td width="143" class="border">Date of Birth</td>
    <td width="115" class="border">MRN</td>
     <td width="106" class="border">Type </td>
    <td width="106" class="border">Documents</td>
  </tr>
 <?php
 for($i=0;$i<count($data);$i++){
 ?><tr class="<?php echo ($i%2!=0 ? "t-line-white" : "t-line-grey");?>">
    <td><input name="file[]" type="checkbox" value="<?php echo $data[$i]['p_id'];?>"  style="display:none"/></td>
    <td><?php echo $data[$i]['p_lname'];?></td>
    <td><?php echo $data[$i]['p_fname'];?></td>
    <td><?php echo date("m-d-Y", strtotime( $data[$i]['p_dob']));?></td>
    <td><?php echo $data[$i]['p_mrn'];?></td>
    <td><?php echo $data[$i]['p_type'];?></td>
    <td> <?php if( $data[$i]['p_type'] == 'Immediate'){?> <a id="opener<?php echo $data[$i]['p_id'];?>" href="#" value="<?php echo $data[$i]['p_id'];?>" onClick="getFiles(this);">View Files</a> <?php }
	   if( $data[$i]['p_type'] == 'InProcess' ){?> <a id="opener<?php echo $data[$i]['p_id'];?>" href="#" value="<?php echo $data[$i]['p_mrn']?>" onClick="fetchFiles(this)">Check Progress</a>
	 <?php } if( $data[$i]['p_type'] == 'Needs Retrieval' ) {?> <a href="test.php?pid=<?php echo $data[$i]['p_mrn'];?>">Fetch File </a><?php }?></td>
 
  </tr>
  <?php } ?>
</table>
           
                                         <div id="dialog" title="Download Files"></div>
                                         <div id="dialog3" title="Fetch Progress"></div>
                                        
										<div class="clear"></div>                
                </div>
			</div>
            	<div id="Rsults-btn-row">
                	<div id="View-btn-div"></div>
                    <div id="Prv-nxt-Main">
   <table width="230" border="0" cellpadding="0" cellspacing="0">
  <tr>
  <td></td>
  </tr>
</table>

                    </div> <!--Prv-nxt-Main ends-->
                </div><!--Rsults-btn-row ends-->
                </div> <!--Results-M ends-->
                
            </div> <!--Col-2 ends-->
            <div class="clr"></div>
        </div> <!--Content ends-->
        <div id="Footer">
        	Powered by CareConnect™
        </div> <!--Footer ends-->
        
    </div> <!--Container ends-->
     <div id="dialog1" title="Files"></div>
</body>
</html>
