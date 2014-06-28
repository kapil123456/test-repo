<?php

require_once('lib/config.php');

$_PHP_SELF=$_SERVER['PHP_SELF'];
$dataedit="";

if(!isset($_SESSION['username']))
{
  header("location: login.php");
  exit();
}
if(isset($_REQUEST['gid']) && $_REQUEST['gid']!='')
{
 $editgrpqry="SELECT * FROM PERMISSIONS WHERE grp_id='".$_REQUEST['gid']."'";
 $dataedit=$con->getSingleRow($editgrpqry,  __FILE__,  __LINE__);
//pr($dataedit);
}

if(isset($_REQUEST['_cmd'])){
  $us=$_SESSION['username'];
  $atype='Group Management';
  $pt='';
  $details="Opened ".$atype;
  $ip=getIP();
  $date=date("Y-m-d H:i:s");
  $dv="usrmgt.php";
  $k= useraudit($con,$date,$ip,$us,$atype,$pt,$details,$dv);
  header("location: usrmgt.php");
  exit();
}
if(isset($_SESSION['allow_usrmgt']) && $_SESSION['allow_usrmgt']==0)
{
  header("location: index.php");
  exit();
}

if(isset($_SESSION['ergrp']))
{
  echo "<script type='text/javascript'>alert('Cannot delete group.');</script>";
  unset($_SESSION['ergrp']);
 
}

$username=$_SESSION['u'];
$password=$_SESSION['p'];
$adldap->user()->authenticate($username, $password);

$groupList = $adldap->group()->allSecurity($include_desc = false, $search = "*", $sorted = true);
//pr($groupList);

 $qry="SELECT * FROM PERMISSIONS";
 
 $data=$con->getMultiRow($qry,  __FILE__,  __LINE__);

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
function onlyAlphabets() {

if(document.frmAddGroup.groupname.value=='')
{
alert("Groupname cannot be empty.");
return false;
}


 /* var regex = /^[a-zA-Z]*$/;
  //alert(document.frmAddGroup.groupname.value);
  if (regex.test(document.frmAddGroup.groupname.value)) {
    
	
      //document.getElementById("notification").innerHTML = "Watching.. Everything is Alphabet now";
      return true;
  } else {
 
      document.getElementById("notification").innerHTML = "Alphabets Only";
      return false;
  }*/


}
function resetValidators()
{
document.getElementById("notification").innerHTML = "";
return true;
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
                 <!-- 	<div id="Hr-row2-c1">
                	  <input name="input2" type="button" value="Reports" class="Logout-btn" />
                	</div>
                  <div id="Hr-row2-c2">
                	<a href="settings.php" class="button">Settings</a>
                	</div>-->
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
                    <li><a href="#" alt="Configure Seurity Group">Add Group</a></li>
                    <li><a href="usermgr.php?_cmd=l" alt="Configure User">Add User</a></li>
                    </ul>
                   </div> <!--Search-Inner ends-->
                </div> <!--Search-M ends-->
            </div> <!--Col-1 ends-->
           
            <div id="Col-2"  style="height:auto;"	>
            	<div id="Bread-Crum"><a href="index.php">Main Menu</a> &gt; <span class="Brd-crms-span">Group Management</span>                </div> 
       	    <!--Bread-Crum ends-->
                
                <div id="table-main">
                    <div class="table-h"></div>
                    <div class="table-h-">Security Group Configurations</div>
                    <div class="table-h--"></div>                                
            	</div>
                
                <div id="Results-M">
                	<div class="table-bod">
  <form id="frmAddGroup" name="frmAddGroup" method="post" action="savegrp.php" onSubmit="return onlyAlphabets()" >  
             
<table id="tblResults" width="961" border="0" cellspacing="0" cellpadding="5">
  <tr >
    <td width="235" class="border">Group Name</td>
    <td width="193" class="border">
        <Select id="groupname" name="groupname" tabindex="0">
      <option value="-1">-Select-</option>
       <option value="App Admin">App Admin</option>
	  <?php
	  for($i=0;$i<=count($groupList)-1;$i++) 
      {
	  ?>
      <option value="<?php echo $groupList[$i] ;?>"  <?php if(isset($dataedit['groupid']) && $groupList[$i]==$dataedit['groupid']){ echo 'selected';}else{ echo '';} ?>><?php echo $groupList[$i];?>     </option>
      <?php } ?></Select>
    <span id="notification"></span>
    </td>
    <td width="150" class="border"> <?php
if(isset($_GET['msg'])){
echo $_GET['msg'];
}
?></td>
  </tr>
  <tr >
    <td width="150" class="border">Allow View</td>
    <td width="150" class="border">
     <input type="checkbox" name="allowview" id="allowview" accesskey="1" tabindex="2" <?php  if(isset($dataedit['allow_view'])&&$dataedit['allow_view']==1){echo ($dataedit['allow_view']==1 ? "checked=true":"checked=false");}?>>
    </td>
    <td width="150" class="border"></td>
  </tr>
<tr >
    <td width="150" class="border">Allow Print</td>
    <td width="150" class="border">
     <input type="checkbox" name="allowprint" id="allowprint" accesskey="1" tabindex="2" <?php  if(isset($dataedit['allow_print']) && $dataedit['allow_print']==1){echo ($dataedit['allow_view']==1 ? "checked=true":"checked=false");}?>>
    </td>
    <td width="150" class="border"></td>
  </tr>
  <tr >
    <td width="150" class="border">Allow save to file</td>
    <td width="150" class="border">
      <input type="checkbox" name="allowsave" id="allowsave" accesskey="1" tabindex="3" <?php  if(isset($dataedit['allow_save']) && $dataedit['allow_save']==1){echo ($dataedit['allow_view']==1 ? "checked=true":"checked=false");}?>>
    </td>
    <td width="150" class="border"></td>
  </tr>
   <tr >
    <td width="150" class="border">Allow access to audit logs</td>
    <td width="150" class="border">
      <input type="checkbox" name="allowaudit" id="allowaudit" accesskey="1" tabindex="4" <?php  if(isset($dataedit['allow_audit']) &&$dataedit['allow_audit']==1){echo ($dataedit['allow_view']==1 ? "checked=true":"checked=false");}?>>
    </td>
    <td width="150" class="border"></td>
  </tr>
   <tr >
    <td width="150" class="border">Allow access to submit support tickets</td>
    <td width="150" class="border">
     <input type="checkbox" name="allowtickets" id="allowtickets" accesskey="1" tabindex="5" <?php  if(isset($dataedit['allow_tickets'])&&$dataedit['allow_tickets']==1){echo ($dataedit['allow_view']==1 ? "checked=true":"checked=false");}?>>
    </td>
    <td width="150" class="border"></td>
  </tr>
    <tr >
    <td width="150" class="border">Allow administrator access (Add/Delete Users)</td>
    <td width="150" class="border">
      <input type="checkbox" name="allowadmin" id="allowadmin" accesskey="1" tabindex="6" <?php  if(isset($dataedit['allow_usrmgt'])&&$dataedit['allow_usrmgt']==1){echo ($dataedit['allow_view']==1 ? "checked=true":"checked=false");}?>>
    </td>
    <td width="150" class="border"></td>
  </tr>
      <tr >
    <td width="150" class="border">Allow access to Configuration page</td>
    <td width="150" class="border">
      <input type="checkbox" name="allowconf" id="allowconf" accesskey="1" tabindex="7" <?php  if(isset($dataedit['allow_conf'])&&$dataedit['allow_conf']==1){echo ($dataedit['allow_view']==1 ? "checked=true":"checked=false");}?>>
    </td>
    <td width="150" class="border"></td>
  </tr>
     <tr >
    <td width="150" class="border">Demo access</td>
    <td width="150" class="border">
      <input type="checkbox" name="allowdemo" id="allowdemo" accesskey="1" tabindex="8" <?php  if(isset($dataedit['allow_demo'])&&$dataedit['allow_demo']==1){echo ($dataedit['allow_view']==1 ? "checked=true":"checked=false");}?>>
    </td>
    <td width="150" class="border"></td>
  </tr>
  
   <tr >
    <td width="150" class="border"></td>
    <td width="150" class="border">
    <input type="hidden" id="permid" name="permid" value="<?php if(isset($dataedit['gid'])){ echo  $dataedit['gid'];}?>"/>
      <?php if(isset($dataedit['grp_id']) && $dataedit['grp_id']!=''){?><input type="submit" name="btnUpdate" id="btnUpdate" value="Update" accesskey="1" tabindex="9"> |  <input type="button" name="btnclear" id="btnclear" value="cancel"  accesskey="1" tabindex="10" onClick="document.location.href='usrmgt.php';"><?php } else {?><input type="submit" name="btnsubmit" id="btnsubmit" value="Save" accesskey="1" tabindex="9">| 
       <input type="reset" name="btnreset" id="btnreset" accesskey="1" tabindex="10" onClick="return resetValidators()"><?php }?> 
    </td>
    <td width="150" class="border">
  </tr>
    <!--<td>
    	<a href="#"><img src="images/edit.png" border="0" /></a>&nbsp;&nbsp;
    	<a href="#"><img src="images/set.png" border="0" /></a>&nbsp;&nbsp;
    	<a href="#"><img src="images/del.png" border="0" /></a>
      
    </td>-->

</table>
    </form>   	
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

                    </div> <!--Prv-nxt-Main ends-->
                </div><!--Rsults-btn-row ends-->
           
                 <div id="table-main">
                    <div class="table-h"></div>
                    <div class="table-h-"> </div>
                    <div class="table-h--"></div>                                
            	</div>
                
                <div id="Results-M">
                	<div class="table-bod" >        
            
<table id="tblResults" width="961"  border="0" cellspacing="0" cellpadding="5"  >
 <tr class="t-head">
    <td width="235" class="border">Group Name</td>
     <td width="116" class="border">View</td>
    <td width="100" class="border">Print</td>
    <td width="115" class="border">Save</td>
    <td width="125" class="border">Audit</td>
    <td width="125" class="border">Tickets</td>
     <td width="116" class="border">Admin</td>
    <td width="116" class="border">Demo</td>
     <td width="116" class="border">Config</td>
    <td width="116" class="border">Actions</td>
  </tr>
 	 	 	 	 	 	 	 	 	
  
   <?php
 for($i=0;$i<count($data);$i++){
 ?><tr class="<?php echo ($i%2!=0 ? "t-line-white" : "t-line-grey");?>">
    <td><?php echo $data[$i]['grp_id'];?></td>
    <td><?php echo ($data[$i]['allow_view']!=0 ? "<img src='images/tick.png' border='0' />":"<img src='images/del.png' border='0' />");?></td>
    <td><?php echo ($data[$i]['allow_print']!=0 ? "<img src='images/tick.png' border='0' />":"<img src='images/del.png' border='0' />");?></td>
    <td><?php echo ($data[$i]['allow_save']!=0 ? "<img src='images/tick.png' border='0' />":"<img src='images/del.png' border='0' />");?></td>
    <td><?php echo ($data[$i]['allow_audit']!=0 ? "<img src='images/tick.png' border='0' />":"<img src='images/del.png' border='0' />");?></td>
     <td><?php echo ($data[$i]['allow_tickets']!=0 ? "<img src='images/tick.png' border='0' />":"<img src='images/del.png' border='0' />");?></td>
    <td><?php echo ($data[$i]['allow_usrmgt']!=0 ? "<img src='images/tick.png' border='0' />":"<img src='images/del.png' border='0' />");?></td>
    <td><?php echo ($data[$i]['allow_demo']!=0 ? "<img src='images/tick.png' border='0' />":"<img src='images/del.png' border='0' />");?></td>
    <td><?php echo ($data[$i]['allow_conf']!=0 ? "<img src='images/tick.png' border='0' />":"<img src='images/del.png' border='0' />");?></td>
    
  <td>
    	<a href="<?php echo $_PHP_SELF.'?gid='.$data[$i]['grp_id'];?>" ><img src="images/edit.png" border="0" /></a>&nbsp;
    	<a href="<?php echo 'delgrp.php?gid='.$data[$i]['gid'];?>" onClick="return confirm('Are you sure you want to Delete?');"><img src="images/del.png" border="0" /></a>
      
    </td>
  </tr>
  <?php } ?>
 </table>
	</div>
    </div>	 </div> <!--Results-M ends-->	
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
