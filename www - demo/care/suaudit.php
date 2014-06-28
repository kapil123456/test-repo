<?php

require_once('lib/config.php');
$_PHP_SELF=$_SERVER['PHP_SELF'];
$rec_limit = 10;
if(!isset($_SESSION['username']))
{
  header("location: login.php");
  exit();
}

if(isset($_SESSION['allow_audit']) && $_SESSION['allow_audit']==0)
{
  header("location: index.php");
  exit();
}


if(isset($_REQUEST['_cmd'])){
  $us=$_SESSION['username'];
  $atype='Simple User Audit ';
  $pt='';
  $details="Viewed Simple User Audit";
  $ip=getIP();
  $date=date("Y-m-d H:i:s");
   $dv="suaudit.php";
  $k= useraudit($con,$date,$ip,$us,$atype,$pt,$details,$dv);
  
  header("location: suaudit.php");
  exit();
}
 $rec_count=0;
$qy="SELECT count(id) as count FROM tbllog ";
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

$searchfilter="";
if(isset($_POST) && isset($_POST['submit']) && $_POST['submit'])
{

$arry=array('txt_P_Name'=>'user');
$keys=array_keys($arry);
 $searchfilter.=' where '. $arry[$keys['0']]." like('%".$_POST[$keys['0']]."%') ";

}


$qry="SELECT * FROM tbllog ".($searchfilter!=''? $searchfilter:'')."order by id DESC LIMIT ".$offset.",".$rec_limit;
$data=$con->getMultiRow($qry,  __FILE__,  __LINE__);
if(isset($_SESSION['emsg']) && $_SESSION['emsg'])
{
 $message=$_SESSION['emsg'];

 echo "<script type='text/javascript'>alert('$message');</script>";
unset($_SESSION['emsg']);
}
?>

<!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Advance Log Care Connect</title>

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
    <td style="font-size:14px">Simple Log</td>
  </tr>
  <tr height="35">
    <td>
    	<select  class="Filling-option">
        	
            <option>User</option>

  
        </select>    </td>
  </tr>
  <tr height="38">
    <td><input id="txt_P_Name" name="txt_P_Name" type="text" class="Filling-field" value="" /></td>
  </tr>
  <tr height="24" style="font-size:14px">
    <td><!--Reason for Access--></td>
  </tr>
  <tr height="35">
    <td>
    	<!--<select name="dp_list"  class="Filling-option">
        	<option value="-1">
            	Select One
            </option>
        	<option value="Research">
            	Research
            </option>
        	<option value="Export">
            	Export
            </option>                                    
        </select> -->   </td>
  </tr>
<tr>
    <td><!--<textarea style="overflow:auto;resize:none" rows="6" cols="25"> </textarea>--></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr height="32" style="color:#000000; font-size:16px; font-weight:bold">
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
    	<table width="226" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><input name="submit" type="submit" value="Submit" class="Logout-btn" /></td>
    <td><input name="input" type="reset" value="Reset" class="Logout-btn" /></td>
  </tr>
</table>    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
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
    <td width="200" class="border">Date</td>
    <td width="100" class="border">User</td>
    <td width="115" class="border">Document viewed</td>
  </tr>
<?php for($i=0;$i<count($data);$i++) {

?>
 <tr class="<?php echo ($i%2!=0 ? "t-line-white" : "t-line-grey");?>">
    <td><input name="file[]" type="checkbox" value=""  style="display:none"/></td>
    <td> <?php echo $data[$i]['datelog'];?></td>
    <td> <?php echo $data[$i]['user']; ?></td>
    <td> <?php echo $data[$i]['Documentviewed'];?></td>
   
    <!--<td>
    	<a href="#"><img src="images/edit.png" border="0" /></a>&nbsp;&nbsp;
    	<a href="#"><img src="images/set.png" border="0" /></a>&nbsp;&nbsp;
    	<a href="#"><img src="images/del.png" border="0" /></a>
      
    </td>-->
  </tr>
  <?php } ?>
</table>

            
				<!--<div style="display:block; float:right; width:500px; margin:40px 30px 30px 0; text-align:right;">
										<div class="pagination">
											<a href="#" title="First Page" class="page-">&lt;&lt; First</a>
                                            <a href="#" title="Previous Page" class="page-">&lt;&lt; Previous</a>
											<a href="#" class="number" title="1">1</a>
											<a href="#" class="number" title="2">2</a>
											<a href="#" class="number current" title="3">3</a>
											<a href="#" class="number" title="4">4</a>
											<a href="#" title="Next Page" class="page-">Next &gt;&gt; </a>
                                            <a href="#" title="Last Page" class="page-">Last &gt;&gt; </a>
										</div> <!-- End .pagination -->
                                 
										<div class="clear"></div>                
                </div>
			</div>
            	<div id="Rsults-btn-row">
                	<div id="View-btn-div"><input name="Email" type="button" value="Email" onClick="document.location.href='email.php?doc=suaudit';" class="Logout-btn" /></div>
                    <div id="Prv-nxt-Main">
                    	<table width="230" border="0" cellpadding="0" cellspacing="0">
  <tr>
  <td>
  <?php if( $page > 0 )
{
   $last = $page - 2;
   echo "<td><a href=\"$_PHP_SELF?page=$last\" class=\"button\" >Last</a> |</td>";
   echo "<td><a href=\"$_PHP_SELF?page=$page\" class=\"button\" >Next</a></td>";
}
else if( $page == 0 )
{
   echo "<td></td><td><a href=\"$_PHP_SELF?page=$page\" class=\"button\" >Next</a></td>";
}
else if( $left_rec < $rec_limit )
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
        	Powered by CareConnect™
        </div> <!--Footer ends-->
        
    </div> <!--Container ends-->
</body>
</html>

