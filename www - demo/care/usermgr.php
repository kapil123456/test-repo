<?php

require_once('lib/config.php');
$_PHP_SELF=$_SERVER['PHP_SELF'];

if(!isset($_SESSION['username']))
{
  header("location: login.php");
  exit();
}
if(isset($_REQUEST['_cmd'])){
  $us=$_SESSION['username'];
  $atype='User Management';
  $pt='';
  $details="Opened ".$atype;
  $ip=getIP();
  $date=date("Y-m-d H:i:s");
  $dv="usermgr.php";
  $k= useraudit($con,$date,$ip,$us,$atype,$pt,$details,$dv);
 }
 
if(isset($_REQUEST['upid']) && $_REQUEST['upid']!='')
{
 $getUserqry="SELECT * FROM users WHERE uid='".$_REQUEST['upid']."'";
 $dataedit=$con->getSingleRow($getUserqry,  __FILE__,  __LINE__);
 

}

if(isset($_SESSION['allow_usrmgt']) && $_SESSION['allow_usrmgt']==0)
{
  header("location: index.php");
  exit();
}

if(isset($_SESSION['usrerr']))
{

  echo "<script type='text/javascript'>alert('Cannot delete admin.');</script>";
 unset($_SESSION['usrerr']);
} 




$getgrp_gry="SELECT groupname FROM GROUPS";
$data=$con->getMultiRow($getgrp_gry,  __FILE__,  __LINE__);
 
 $getUser_gry="SELECT * FROM USERS";
$udata=$con->getMultiRow($getUser_gry,  __FILE__,  __LINE__);

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
  function validateForm()
{
var listGroup=document.forms["myForm"]["listGroup"].value;
if (listGroup==null || listGroup=="-1")
  {
  alert("Group must be selectd");
  //document.getElementById("fname").style.borderColor = "#E34234";
  return false;
  }

var fname=document.forms["myForm"]["fname"].value;
if (fname==null || fname=="")
  {
  alert("First name must be filled out");
  //document.getElementById("fname").style.borderColor = "#E34234";
  return false;
  }
  
  var lname=document.forms["myForm"]["lname"].value;
if (lname==null || lname=="")
  {
  alert("Last name must be filled out");
  //document.getElementById("rpsw").style.borderColor = "#E34234";
  return false;
  }
    var uid=document.forms["myForm"]["uid"].value;
if (uid==null || uid=="")
  {
  alert("User Id must be filled out");
  //document.getElementById("uid").style.borderColor = "#E34234";
  return false;
  }
      var psw=document.forms["myForm"]["psw"].value;
	  var rpsw=document.forms["myForm"]["rpsw"].value;
if (psw==null || psw=="" || psw.length <=6)
  {
  alert("Password must be filled out");
  return false;
  }
  if (rpsw==null || rpsw=="" || psw.length <=6)
  {
  alert("Retype Password must be filled out");
  return false;
  }
if (psw != rpsw) {
            //alert("Passwords Do not match");
            document.getElementById("psw").style.borderColor = "#E34234";
            document.getElementById("rpsw").style.borderColor = "#E34234";
			return false;
        }
		else
		{
		 document.getElementById("psw").style.borderColor = "#dadada";
		document.getElementById("rpsw").style.borderColor = "#dadada";
		}
      
  
var email=document.forms["myForm"]["email"].value;
var atpos=email.indexOf("@");
var dotpos=email.lastIndexOf(".");
if (atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length)
  {
  alert("Not a valid e-mail address");
 // document.getElementById("email").style.borderColor = "#E34234";
  return false;
  }
}
  
  </script>

</head>
<body>

	<div id="Container">
    	<div id="Header">
        	<div id="Logo"><a href="index.php"><img src="images/medical-services-logo.jpg" alt="medical services logo" border="0" /></a></div>
            <div id="Head-right">
            	<div id="Hr-row-1">Welcome!   &nbsp;&nbsp;&nbsp; <span class="Hr-row-1-span"></span></div>
                <div id="Hr-row-2">
              
                    <div id="Hr-row2-c2">
                    	<a href="logout.php" class="button">Logout</a>
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
                	<div class="Search-Hdng">Manage Users</div>
                    <div id="Search-inner">
						 <ul>
                    <li><a href="usrmgt.php?_cmd=l" alt="Configure Seurity Group">Add Group</a></li>
                    <li><a href="#" alt="Configure User">Add User</a></li>
                    </ul>
                    	

                  </div> <!--Search-Inner ends-->
                </div> <!--Search-M ends-->
            </div> <!--Col-1 ends-->
           
            <div id="Col-2" style="height:auto;">
            	<div id="Bread-Crum"><a href="index.php">Main Menu</a> &gt; <a href="usrmgt.php">Group Management</a> &gt;<span class="Brd-crms-span">Create User</span>                </div> 
       	    <!--Bread-Crum ends-->
                
                <div id="table-main">
                    <div class="table-h"></div>
                    <div class="table-h-">User Registration Form | <?php
if(isset($_GET['msg'])){
echo $_GET['msg'];
}
?></div>
                    <div class="table-h--"></div>                                
            	</div>
                
                <div id="Results-M">
                	<div class="table-bod">

         <form name="myForm" method="post" action="saveusr.php" onSubmit="return validateForm();">        
<table id="tblResults" width="961" border="0" cellspacing="0" cellpadding="5">
 <tr >
    <td width="235" class="border">Member of</td>
    <td width="193" class="border">
      <Select id="listGroup" name="lstgroup" tabindex="0">
      <option value="-1">-Select-</option>
	  <?php
	  for($i=0;$i<=count($data)-1;$i++) 
      {
	  ?>
      <option value="<?php echo $data[$i]['groupname'] ;?>"  <?php if(isset($dataedit['groupid']) && $data[$i]['groupname']==$dataedit['groupid']){ echo 'selected';}else{ echo '';} ?>><?php echo $data[$i]['groupname'];?>     </option>
      <?php } ?></Select>
    </td>
  </tr>
  <tr >
    <td width="235" class="border">First Name</td>
    <td width="193" class="border">
      <input type="text" id="fname" name="fname" accesskey="1" tabindex="1" value="<?php  if(isset($dataedit['first_name'])){echo $dataedit['first_name'];} ?>" style="border-radius:7px; border:2px solid #dadada;">
    
    </td>
  </tr>
  <tr >
    <td width="235" class="border">Last Name</td>
    <td width="193" class="border">
      <input type="text" id="lname" name="lname"  value="<?php  if(isset($dataedit['last_name'])){echo $dataedit['last_name'];} ?>" accesskey="1" tabindex="2" style="border-radius:7px; border:2px solid #dadada;">
    
    </td>
  </tr>
<tr >
    <td width="150" class="border">User Id</td>
    <td width="150" class="border">
      <input type="text" id="uid" name="uid" value="<?php  if(isset($dataedit['uid'])){echo $dataedit['uid'];}?>"<?php  if(isset($dataedit['uid'])){echo ' readonly=true';}  ?>"  accesskey="1" tabindex="3" style="border-radius:7px; border:2px solid #dadada;">
    </td>
    <td width="150" class="border"></td>
  </tr>
  <tr >
    <td width="150" class="border">Password</td>
    <td width="150" class="border">
      <input type="password" id="psw" name="psw"  value="<?php  if(isset($dataedit['password'])){echo $dataedit['password'];} ?>" accesskey="1" tabindex="4" style="border-radius:7px; border:2px solid #dadada;">
    </td>
    <td width="150" class="border">
  </tr>
   <tr >
    <td width="150" class="border">Re Type Password</td>
    <td width="150" class="border">
      <input type="password" id="rpsw" name="rpsw"  value="<?php  if(isset($dataedit['password'])){echo $dataedit['password'];} ?>" accesskey="1" tabindex="5" style="border-radius:7px; border:2px solid #dadada;">
    </td>
    <td width="150" class="border">
  </tr>
   <tr >
    <td width="150" class="border">Email</td>
    <td width="150" class="border">
      <input type="text" id="email" name="email"  value="<?php  if(isset($dataedit['email'])){echo $dataedit['email'];} ?>" accesskey="1" tabindex="6" style="border-radius:7px; border:2px solid #dadada;">
    </td>
    <td width="150" class="border">
  </tr>
    <tr >
    <td width="150" class="border">Is Locked</td>
    <td width="150" class="border">
      <input type="checkbox" name="isdisabled" <?php  if(isset($dataedit['allow_view'])&&$dataedit['activated']==1){echo ($dataedit['activated']==1 ? "checked=true":"checked=false");}?>  id="isdisabled" accesskey="1" tabindex="7">
    </td>
    <td width="150" class="border">
  </tr>
   
   <tr >
    <td width="150" class="border"></td>
    <td width="150" class="border">
       <?php if(isset($dataedit['uid']) && $dataedit['uid']!=''){?><input type="submit" name="btnUpdate" id="btnUpdate" value="Update" accesskey="1" tabindex="9"> 
	    |  <input type="button" name="btnclear" id="btnclear" value="cancel" accesskey="1" tabindex="10" onClick="document.location.href='usermgr.php';">
	   <?php } else {?>
      <input type="submit" name="btnsubmit" id="btnsubmit" value="Save User" accesskey="1" tabindex="8" > | 
       <input type="reset" name="btnreset" id="btnreset" accesskey="1" tabindex="9"><?php }?> 
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
                
                    <div id="table-main">
                    <div class="table-h"></div>
                    <div class="table-h-"></div>
                    <div class="table-h--"></div>                                
            	</div>
                
                <div id="Results-M">
              <div class="table-bod" >   	
<table id="tblResults" width="961" border="0" cellspacing="0" cellpadding="5"  >
 <tr class="t-head">
     <td width="235" class="border">Group Name</td>
    <td width="193" class="border">ID</td>
    <td width="115" class="border">Name</td>
    <td width="143" class="border">Email</td>
    <td width="108" class="border">Date</td>
     <td width="106" class="border">Locked</td>
    <td width="106" class="border">Actions</td>
  </tr>

  
   <?php
  
 for($i=0;$i<count($udata);$i++){
 ?><tr class="<?php echo ($i%2!=0 ? "t-line-white" : "t-line-grey");?>">
    <td><?php echo $udata[$i]['groupid']?></td>
    <td><?php echo $udata[$i]['uid']?></td>
    <td><?php echo $udata[$i]['last_name'].$udata[$i]['first_name']?></td>
    <td><?php echo $udata[$i]['email']?></td>
    <td><?php echo date("d-m-Y", strtotime($udata[$i]['created_at']));?></td>
    <td><?php echo ($udata[$i]['activated']!=0 ? "<img src='images/tick.png' border='0' />":"<img src='images/del.png' border='0' />");?></td>
 
  <td>
    	<a href="<?php echo $_PHP_SELF.'?upid='.$udata[$i]['uid'];?>"><img src="images/edit.png" border="0" /></a>&nbsp;
    	<a href="<?php echo 'delgrp.php?udid='.$udata[$i]['uid'];?>" onClick="return confirm('Are you sure you want to Delete?');"><img src="images/del.png" border="0" /></a>
      
    </td>
  </tr>
  <?php } ?>
 </table>
	</div>
    </div>	
                
                </div> <!--Results-M ends-->
                
            </div> <!--Col-2 ends-->
            <div class="clr"></div>
        </div> <!--Content ends-->
        <div id="Footer">
        	Powered by CareConnect™
        </div> <!--Footer ends-->
    </div> <!--Container ends-->
<body>
</body>
</html>
