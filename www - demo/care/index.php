<?php

require_once('lib/config.php');
if(!isset($_SESSION['username']))
{
  header("location: login.php");
  exit();
}
if(isset($_REQUEST['mg']))
{
$message=$_REQUEST['mg'];

echo "<script type='text/javascript'>alert('$message');</script>";
 
 }
//echo $_SESSION['allow_usrmgt'];
?>
<!DOCTYPE >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Care Connect</title>
<link href="style/medical-services-style.css" rel="stylesheet" type="text/css" />
<script >

function shower()
{
alert("You don't have sufficient privileges!");
}
</script>




</head>

<body>
	<div id="Container">
    	<div id="Header">
        	<div id="Logo"><a href="index.php" value="Main Menu"><img src="images/careconnext-original.png" style="width:55%; height:auto;" alt="medical services logo" style="height:50%; width:50%" border="0" /></a></div>
            <div id="Head-right">
            	<div id="Hr-row-1">Welcome!  &nbsp;&nbsp;&nbsp; <span class="Hr-row-1-span"><?php echo $_SESSION['username']; ?></span></div>
              <div id="Hr-row-2">
                  
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
                    </div>
                <!--Hr-row-2 ends-->
          </div>
            <!--Head-right ends-->
        </div> <!--Header ends-->
   <!--     <div id="Main-Navigations">
                    <a href="#">Home</a>
                    <a href="#">About Us</a>
                    <a href="#">Services</a>
                    <a href="#">Products</a>
                    <a href="#">Get a Quote</a>
                    <a href="#">Contact Us</a>
                </div>-->
<div id="Content">
	<div style="width:910px; min-height:550px; margin:auto; padding:20px;">

<div class="width-414px">
<div class="Four14-56"><a href="#"><img src="images/g1h.jpg" alt="g1" border="0" /></a></div>
<div class="Four14-73"><?php if( isset($_SESSION['allow_usrmgt']) && $_SESSION['allow_usrmgt'] != '0'){?><a href="usrmgt.php?_cmd=l" onClick="savelog(this.value)" value="Custom Search"><img src="images/g77.jpg" alt="g1" border="0" /></a><?php } else {?><a href="#" value="Custom Search" onClick="shower()"><img src="images/g77d.jpg" alt="a3" border="0" /></a><?php }?></div>     
<div class="Four14-73"><?php if( isset($_SESSION['allow_conf']) && $_SESSION['allow_conf'] != '0'){?><a href="settings.php?_cmd=l" onClick="savelog(this.value)" value="Custom Search"><img src="images/g9.jpg" alt="g2" border="0" /></a><?php } else {?><a href="#" value="Custom Search" onClick="shower()"><img src="images/g9d.jpg" alt="a3" border="0" /></a><?php }?></div>     


</div>



<div class="width-414px">
<div class="Four14-56"><a href="#"><img src="images/g1c.jpg" alt="g1" border="0" /></a></div>
<div class="Four14-73"><a href="uploadplist.php"><img src="images/g5.jpg" alt="g2" border="0" /></a></div>
<div class="Four14-73"><a href="test.php"><img src="images/r3.jpg" alt="g2" border="0" /></a></div>

</div>



<div class="width-414px">
<div class="Four14-56"><a href="#"><img src="images/r1h.jpg" alt="g1" border="0" /></a></div>
<div class="Four14-73"><a href="listing.php?_cmd=l" 
onClick="savelog(this.value)" value="Custom Search">
<img src="images/g4.jpg" alt="g2" border="0" /></a></div>
<div class="Four14-73"><a href=""><img src="images/ge.jpg" alt="ge" border="0" /></a></div>                     
<div class="Four14-73"><a href=""><img src="images/ge.jpg" alt="ge" border="0" /></a></div>          
</div>


<div class="width-414px">
			<div class="Four14-56"><a href="#"><img src="images/a0.jpg" alt="g1" border="0" /></a></div>
			<div class="Four14-73"><?php if( isset($_SESSION['allow_audit']) && $_SESSION['allow_audit'] != '0'){?><a href="paudit.php?_cmd=l" onClick="savelog(this.value)" value="Custom Search"><img src="images/a3.jpg" alt="a3" border="0" /></a><?php } else {?><a href="#" value="Custom Search" onClick="shower()"><img src="images/a3d.jpg" alt="a3d" border="0" /></a><?php }?></div>
     <div class="Four14-73"><?php if( isset($_SESSION['allow_audit']) && $_SESSION['allow_audit'] != '0'){?><a href="suaudit.php?_cmd=l" onClick="savelog(this.value)" value="Custom Search"><img src="images/a2.jpg" alt="a3" border="0" /></a><?php } else {?><a href="#" value="Custom Search" onClick="shower()"><img src="images/a2d.jpg" alt="a2d" border="0" /></a><?php }?></div>       
      <div class="Four14-73"><?php if( isset($_SESSION['allow_audit']) && $_SESSION['allow_audit'] != '0'){?><a href="datacert.php?_cmd=l" onClick="savelog(this.value)" value="Custom Search"><img src="images/a1.jpg" alt="a3" border="0" /></a><?php } else {?><a href="#" value="Custom Search" onClick="shower()"><img src="images/a1d.jpg" alt="a1d" border="0" /></a><?php }?></div>             
     	
</div>
        

		<div class="clr"></div>
	</div>
</div> <!--Content ends-->
            <div class="clr"></div>
        <div id="Footer">
        	Powered by: CareConnext Technologies ™
        </div> <!--Footer ends-->
        
    </div> <!--Container ends-->
</body>
</html>
