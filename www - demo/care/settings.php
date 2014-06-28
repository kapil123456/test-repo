<?php
require_once('lib/config.php');


if(!isset($_SESSION['username']))
{
  header("location: login.php");
  exit();
}
if(isset($_SESSION['allow_conf']) && $_SESSION['allow_conf']==0)
{
  header("location: index.php");
  exit();
}
function makedirectory($dirpath)
{
$f=0;
$structure="./".$dirpath;
if (!file_exists($structure)) {
    mkdir($structure, 0777, true);
	$f=1;
}

return $f;
}
//pr($_POST);
$iqry="";
$qry="";
$data="";
$structure="";


if(isset($_SESSION['username']))
{

  $us=$_SESSION['username'];
  $atype='Settings and Configurations';
  $pt='';
  $details="Opened ".$atype;
  $ip=getIP();
  $date=date("Y-m-d H:i:s");
  $dv="settings.php";
  $k= useraudit($con,$date,$ip,$us,$atype,$pt,$details,$dv);

if(isset($_POST['Location-Import'],$_POST['Location-Export'],$_POST['Location-Batch']))
{
if(!is_dir($_POST['Location-Import']))
{
 $x=makedirectory($_POST['Location-Import']);
}
if(!is_dir($_POST['Location-Export']))
{
 $y=makedirectory($_POST['Location-Export']);
}
if(!is_dir($_POST['Location-Batch']))
{
 $z=makedirectory($_POST['Location-Batch']);
}

 $iqry="UPDATE tblglobalsettings SET input_folder_path='".$_POST['Location-Import']."',storage_folder_path='".$_POST['Location-Export']."',batch_folder_path='".$_POST['Location-Batch']."'";
$r=$con->updateRecords($iqry);
}
$qry="SELECT * FROM tblglobalsettings";
$data=$con->getMultiRow($qry,  __FILE__,  __LINE__);

}
else
{
  header("location: login.php");
  exit();
}


?>






<!DOCTYPE >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Settings Care Connect</title>
<link href="style/medical-services-style.css" rel="stylesheet" type="text/css" />
</head>

<body >
	<div id="Container">
    	<div id="Header">
        	<div id="Logo"><a href="index.php"><img src="images/medical-services-logo.jpg" alt="medical services logo" border="0" /></a></div>
            <div id="Head-right">
            	<div id="Hr-row-1">Welcome!   &nbsp;&nbsp;&nbsp; <span class="Hr-row-1-span">to Care Connect</span>
                    </div>
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
            
                <!--Hr-row-2 ends-->
          </div>
            <!--Head-right ends-->
        </div> <!--Header ends-->
        <div id="Content" >
        <div id="Col-1">
            	<div id="Search-M">
                	<div class="Search-Hdng">Settings</div>
                    <div id="Search-inner">
					<ul>
                    <li><a href="#" alt="Configure Seurity Group">Data Path</a></li>
                    <li><a href="emailconf.php?_cmd=l" alt="Configure User">Email Configuration</a></li>
                      <li><a href="pdc.php?_cmd=l" alt="Configure Domain">Domain Configuration</a></li>
                    </ul>
                    	

                  </div> <!--Search-Inner ends-->
                </div> <!--Search-M ends-->
            </div> <!--Col-1 ends-->
          <div id="Col-2">
                <div id="Bread-Crum"><a href="index.php">Main Menu</a> &gt; <span class="Brd-crms-span">Data Path Setting</span>                </div> 
                <div style="width:100%; min-height:350px; margin:auto; padding:20px;">
                
                	
	 <div id="login-area">
                      <div id="login-in">
                        <h1>Settings</h1>
                        <form id="form1" name="form1" method="post" >
                            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                  <tr>
                    <td width="18%">Location for import:</td>
                    <td width="82%" valign="middle"><input name="Location-Import" type="text" class="field" id="Location-Batch" value="<?php echo $data[0]['input_folder_path'];?>" /> </td>
                  </tr>
                  
                  <tr>
                    <td>Location for file storage:</td>
                    <td  valign="middle"><input type="text" name="Location-Export" id="Location-Batch" class="field" value="<?php echo $data[0]['storage_folder_path'];?>"  /></td>
                  </tr>
                   <tr>
                    <td>Location for batch file storage:</td>
                    <td  valign="middle"><input type="text" name="Location-Batch" id="Location-Batch" class="field" value="<?php echo $data[0]['batch_folder_path'];?>" /></td>
                  </tr>
                  <tr>
                    <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0"  style="margin-top:10px;">
                      <tr>
                        <td width="64%"></td>
                       
                      </tr>
                    </table></td>
                    </tr>
                  
                  <tr>
                    <td height="73" colspan="2" align="center" style=" outline:none;" headers="50"><input name="Settings-btn" type="submit" value="Save" class="Reports-btn"  /></td>
                    </tr>
                </table>
                </form>
                      </div>
                    </div>
                    
    
    
                      
                    <div class="clr"></div>
                </div>
                </div>
        </div> <!--Content ends-->
            <div class="clr"></div>
        <div id="Footer">
        	Powered by: CareConnect ™ Platform
        </div> <!--Footer ends-->

        
    </div> <!--Container ends-->
</body>
</html>

