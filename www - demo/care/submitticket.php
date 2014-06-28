<?php

require_once('lib/config.php');
$_PHP_SELF=$_SERVER['PHP_SELF'];

if(!isset($_SESSION['username']))
{
  header("location: login.php");
  exit();
}

if(isset($_SESSION['allow_tickets']) && $_SESSION['allow_tickets']==0)
{
  header("location: index.php");
  exit();
}

if(isset($_REQUEST['msg']) && $_REQUEST['msg']==1)
{

  echo "<script type='text/javascript'>alert('Ticket Submitted Sent.');</script>";
 
 unset($_SESSION['msg']);
} 
else if(isset($_SESSION['msg']) && $_SESSION['msg']==0)
{
$msg=$_SESSION['msg'];
echo "<script type='text/javascript'>alert('Failed to submit ticket : SMPT Server Failed $msg');</script>";
 unset($_SESSION['msg']);
}



?>

<!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Care Connext</title>

<link rel="stylesheet" type="text/css" href="style/jquery-ui-1.10.3.custom.min.css">
<link href="style/medical-services-style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="style/jquery-ui-1.10.3.custom.css">
<link rel="stylesheet" type="text/css" href="style/jquery-ui.css">

  <script src="scripts/jquery-1.9.1.js"></script>
  <script src="scripts/jquery-ui.js"></script>
  <script>
  
 document.getElementById("fname").disabled=true;
 document.getElementById("email").disabled=true;
 document.getElementById("tsub").disabled=true;
 document.getElementById("tmsg").disabled=true;
  </script>

</head>
<body>

	<div id="Container">
    	<div id="Header">
        	<div id="Logo"><a href="index.php"><img src="images/careconnext-original.png" style="width:55%; height:auto;" alt="medical services logo" border="0" /></a></div>
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
                	<div class="Search-Hdng">Support</div>
                    <div id="Search-inner"  style="height:300px">
					
                    	

                  </div> <!--Search-Inner ends-->
                </div> <!--Search-M ends-->
            </div> <!--Col-1 ends-->
           
            <div id="Col-2" style="height:auto;">
            	<div id="Bread-Crum"><a href="index.php">Main Menu</a> &gt; <span class="Brd-crms-span">Submit Ticket</span> &gt; <a href="javascript:history.back()" >Go Back</a>    </div> 
       	    <!--Bread-Crum ends-->
                
			<p style="text-indent: 1em;"> Please Call XXX-XXX-XXXX User Code:
			   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b> No Support Contract Enabled<b><button type="button" text= "centre"> Enable Now  </button>
			</p>
				
				
                <div id="table-main">
                    <div class="table-h"></div>
                    <div class="table-h-">Ticket Form | <?php
if(isset($_GET['msg'])){
echo $_GET['msg'];
}
?></div>
                    <div class="table-h--"></div>                                
            	</div>
                
                <div id="Results-M">
                	<div class="table-bod">

         <form name="myForm" method="post" action="email.php">        
<table id="tblResults" width="961" border="0" cellspacing="0" cellpadding="5">
 
  <tr >
    <td width="235" class="border">Full Name</td>
    <td width="193" class="border">
      <input type="text" id="fname" name="fname"  accesskey="1" tabindex="1" style="width:250px;border-radius:7px; border:2px solid #dadada;">
    
    </td>
  </tr>
  <tr >
    <td width="235" class="border">Email</td>
    <td width="193" class="border">
      <input type="text" id="email" name="email"  accesskey="1" tabindex="2" style="width:250px;border-radius:7px; border:2px solid #dadada;">
    
    </td>
  </tr>
 <tr >
    <td width="235" class="border">Subject</td>
    <td width="193" class="border">
      <input type="text" id="tsub" name="tsub"  accesskey="1" tabindex="3" style="width:250px;border-radius:7px; border:2px solid #dadada;">
    
    </td>
  </tr>
 <tr >
    <td width="235" class="border">Message</td>
    <td width="193" class="border">
      <textarea rows="10" cols="75" maxlength="300" id="tmsg" name="tmsg" accesskey="1" tabindex="4" style="border-radius:7px; border:2px solid #dadada;"></textarea>
      
    
    </td>
  </tr>

   <tr >
    <td width="150" class="border"></td>
    <td width="150" class="border">
  
      <input type="submit" name="btnsubmit" id="btnsubmit" value="Submit" accesskey="1" tabindex="8" > | 
       <input type="reset" name="btnreset" id="btnreset" accesskey="1" tabindex="9">
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
                 <div id="Footer">
        	Powered by: CareConnext Technologies™
        </div> <!--Footer ends-->
        </div> <!--Content ends-->
        
   
    </div> <!--Container ends-->

</body>
</html>
