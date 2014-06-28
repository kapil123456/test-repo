<?php

require_once('lib/config.php');

if(!isset($_SESSION['username']))
{
  header("location: login.php");
  exit();
}
if(isset($_REQUEST['_cmd'])){
  $us=$_SESSION['username'];
  $atype='Mail Settings';
  $pt='';
  $details="Opened ".$atype;
  $ip=getIP();
  $date=date("Y-m-d H:i:s");
  $dv="emailconf.php";
  $k= useraudit($con,$date,$ip,$us,$atype,$pt,$details,$dv);
  echo "<script> location.replace('emailconf.php'); </script>";
  exit();
}
//echo $_SESSION['username'];
$getemail_gry="SELECT e_id,e_smtp,e_eid,e_psw,e_from,e_to,e_sub,e_body,e_uid FROM emailconf where e_uid='".$_SESSION['username']."'";
$edata=$con->getMultiRow($getemail_gry,  __FILE__,  __LINE__);

//pr($edata);
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


</head>
<body>

	<div id="Container">
    	<div id="Header">
        	<div id="Logo"><a href="index.php"><img src="images/medical-services-logo.jpg" alt="medical services logo" border="0" /></a></div>
            <div id="Head-right">
            	<div id="Hr-row-1">Welcome!   &nbsp;&nbsp;&nbsp; <span class="Hr-row-1-span"></span></div>
                <div id="Hr-row-2">
                <!-- 	<div id="Hr-row2-c1">
                	  <input name="input2" type="button" value="Reports" class="Logout-btn" />
                	</div>
                   <div id="Hr-row2-c2">
                	<a href="settings.php" class="button">Settings</a>
                	</div>-->
                    <div id="Hr-row2-c2">
                    	<a href="logout.php" class="button">Logout</a>
                    </div>
                    <div id="Hr-row2-c2" style=" width:auto">
                	<a href="submitticket.php" class="button" style=" width:auto" >Submit Ticket</a>
                    </div>
                </div> <!--Hr-row-2 ends-->
            </div> <!--Head-right ends-->
        </div> <!--Header ends-->
        <div id="Content">
        	<div id="Col-1">
            	<div id="Search-M">
                	<div class="Search-Hdng">Email Setup</div>
                    <div id="Search-inner">
					<ul>
                    <li><a href="settings.php?_cmd=l" alt="Configure Seurity Group">Data Path</a></li>
                    <li><a href="#" alt="Configure User">Email Configuration</a></li>
                      <li><a href="pdc.php?_cmd=l" alt="Configure Domain">Domain Configuration</a></li>
                    </ul>
                    	

                  </div> <!--Search-Inner ends-->
                </div> <!--Search-M ends-->
            </div> <!--Col-1 ends-->
           
            <div id="Col-2">
            	<div id="Bread-Crum"><a href="index.php">Main Menu</a> &gt; <span class="Brd-crms-span">Email setup</span>                </div> 
       	    <!--Bread-Crum ends-->
                
                <div id="table-main">
                    <div class="table-h"></div>
                    <div class="table-h-">Email Settings Form | <?php
if(isset($_GET['msg'])){
echo $_GET['msg'];
}
?></div>
                    <div class="table-h--"></div>                                
            	</div>
                
                <div id="Results-M">
                	<div class="table-bod">

         <form name="myForm" method="post" action="savemail.php"  >        
<table id="tblResults" width="961" border="0" cellspacing="0" cellpadding="5">
 <tr >
    <td width="235" class="border">SMTP Host</td>
    <td width="193" class="border">
     <input type="text" id="emailHost" name="emailHost" value="<?php  if(isset($edata[0]['e_smtp'])){echo $edata[0]['e_smtp'];} ?>" accesskey="1" tabindex="6" style="border-radius:7px; border:2px solid #dadada;" >
    </td>
  </tr>
   <tr >
    <td width="150" class="border">Email</td>
    <td width="150" class="border">
      <input type="text" id="email" name="email" value="<?php  if(isset($edata[0]['e_eid'])){echo $edata[0]['e_eid'];} ?>" accesskey="1" tabindex="6" style="border-radius:7px; border:2px solid #dadada;" >
    </td>
    <td width="150" class="border"></td>
  </tr>
   <tr >
    <td width="150" class="border">Password</td>
    <td width="150" class="border">
      <input type="password" id="psw" name="psw" value="<?php  if(isset($edata[0]['e_psw'])){echo $edata[0]['e_psw'];} ?>"  accesskey="1" tabindex="4" style="border-radius:7px; border:2px solid #dadada;" >
    </td>
    <td width="150" class="border"></td>
  </tr>
  <tr >
    <td width="235" class="border">Email "From"</td>
    <td width="193" class="border">
      <input type="text" id="emailFrom" name="emailFrom" value="<?php  if(isset($edata[0]['e_from'])){echo $edata[0]['e_from'];} ?>" accesskey="1" tabindex="1"  style="border-radius:7px; border:2px solid #dadada;" >
    
    </td>
  </tr>
  <tr >
    <td width="235" class="border">Email "To"</td>
    <td width="193" class="border">
      <input type="text" id="emailTo" name="emailTo" value="<?php  if(isset($edata[0]['e_to'])){echo $edata[0]['e_to'];} ?>" accesskey="1" tabindex="2" style="border-radius:7px; border:2px solid #dadada;" >
    
    </td>
  </tr>
<tr >
    <td width="150" class="border">Email "Subject"</td>
    <td width="150" class="border">
      <input type="text" id="emailSub" name="emailSub" value="<?php  if(isset($edata[0]['e_sub'])){echo $edata[0]['e_sub'];} ?>" accesskey="1" tabindex="3" style="border-radius:7px; border:2px solid #dadada;" >
    </td>
    <td width="150" class="border"></td>
  </tr>
    <tr >
    <td width="150" class="border">Email "Message"</td>
    <td width="150" class="border">
      <input type="text" id="emailBody" name="emailBody" value="<?php  if(isset($edata[0]['e_body'])){echo $edata[0]['e_body'];} ?>" accesskey="1" tabindex="3" style="border-radius:7px; border:2px solid #dadada;" >
    </td>
    <td width="150" class="border"></td>
  </tr>
   <tr >
    <td width="150" class="border"></td>
    <td width="150" class="border">
    <input type="hidden" id="e_id" name="e_id" value="<?php  if(isset($edata[0]['e_id'])){echo $edata[0]['e_id'];} ?>"  >
      <input type="submit" name="btnsubmit" id="btnsubmit" value="Save/Update" accesskey="1" tabindex="8" > 
    </td>
    <td width="150" class="border">
  </tr>
    <!--<td>
    	<a href="#"><img src="images/edit.png" border="0" /></a>&nbsp;&nbsp;
    	<a href="#"><img src="images/set.png" border="0" /></a>&nbsp;&nbsp;
    	<a href="#"><img src="images/del.png" border="0" /></a>
      
    </td>-->

</table>
         

 
				
										<div class="clear"></div>                
                </div>
			</div>
            	<div id="Rsults-btn-row">
                	<div id="View-btn-div"><!--<input name="View" type="button" value="View" class="Logout-btn" />--></div>
                    <div id="Prv-nxt-Main">
                    	<table width="230" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><!--<input name="" type="button" value="Previous" class="Logout-btn" />--></td>
    <td><!--<input name="" type="button" value="Next" class="Logout-btn" />--></td>
  </tr>
</table>
</form>
                    </div> <!--Prv-nxt-Main ends-->
                </div><!--Rsults-btn-row ends-->
                
                  
                
            </div> <!--Col-2 ends-->
            <div class="clr"></div>
        </div> <!--Content ends-->
        <div id="Footer">
        	Powered by: CareConnect ™
        </div> <!--Footer ends-->
        
    </div> <!--Container ends-->
<body>
</body>
</html>
