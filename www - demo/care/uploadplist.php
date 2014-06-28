<?php

require_once('lib/config.php');
if(!isset($_SESSION['username']))
{
  header("location: login.php");
  exit();
}
if(isset($_SESSION['mg']))
{
$message=$_SESSION['mg'];

echo "<script type='text/javascript'>alert('$message');</script>";
 
 }

?>
<!DOCTYPE >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Care Connect</title>
<link href="style/medical-services-style.css" rel="stylesheet" type="text/css" />






</head>

<body>
	<div id="Container">
    	<div id="Header">
        	<div id="Logo"><a href="index.php" value="Dashboard"><img src="images/medical-services-logo.jpg" alt="medical services logo" border="0" /></a></div>
            <div id="Head-right">
            	<div id="Hr-row-1">Welcome!  &nbsp;&nbsp;&nbsp; <span class="Hr-row-1-span"><?php echo $_SESSION['username']; ?></span></div>
                <div id="Hr-row-2">
                <!--	<div id="Hr-row2-c1">
                	  <input name="input2" type="button" value="Reports" class="Logout-btn" />
                	</div>-->
                  <!--  <div id="Hr-row2-c2">
                	<a href="settings.php?_cmd=l" class="button" >Settings</a>
                	</div>-->
                    <div id="Hr-row2-c2">
                    	<a href="logout.php" class="button" >Logout</a>
                                 
                    </div>
                </div> <!--Hr-row-2 ends-->
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
     <div style="width:100%; min-height:350px; margin:auto; padding:20px;">
                	<center><div id="Bread-Crum"><a href="index.php">Main Menu</a> &gt; <span class="Brd-crms-span">Upload Patient List</span>                </div></center> 
    
       <div id="login-area">

<form enctype="multipart/form-data" action="process.php" method="POST">
<p align="justify">
<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
Select File : <input name="uploadedfile" type="file" /><br/>
<br/>
<input type="submit" value="Upload and Process Data" />
</p>
</form>

<!--<div class="fileinputs">
	<input type="file" name="uploadedfile" class="file" />
    <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
	<div class="fakefile">
		<input />
		<img src="search.gif" />
	</div>
</div>-->

       </div>             
             
                    <div class="clr"></div>
                </div>
        </div> <!--Content ends-->
            <div class="clr"></div>
        <div id="Footer">
        	Powered by: CareConnect ™ Platform
        </div> <!--Footer ends-->
        
    </div> <!--Container ends-->
</body>
</html>
