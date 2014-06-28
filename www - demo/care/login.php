<?php
require_once('lib/config.php');

?>
<!DOCTYPE >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login Care Connect</title>
<link href="style/medical-services-style.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<div id="Container">
    	<div id="Header">
        	<div id="Logo"><a href="index.htm"><img src="images/careconnext-original.png" style="width:55%; height:auto;" alt="medical services logo" border="0" /></a></div>
            <div id="Head-right">
            	<div id="Hr-row-1">Welcome!   &nbsp;&nbsp;&nbsp; <span class="Hr-row-1-span">to Care Connect</span></div>
                <!--Hr-row-2 ends-->
          </div>
            <!--Head-right ends-->
        </div> <!--Header ends-->
        <div id="Content" >
                <div style="width:100%; min-height:350px; margin:auto; padding:20px;">
                	
	 <div id="login-area">
                      <div id="login-in">
                        <h1>Login to your account</h1>
                        <form id="form1" name="form1" method="post" action="ldap.php">
                            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                  <tr>
                    <td width="18%">Email</td>
                    <td width="82%" valign="middle"><input name="username" type="text" class="field" id="username" />  <?php if(isset($_REQUEST['err'])){echo $_REQUEST['err'];}?></td>
                  </tr>
                  
                  <tr>
                    <td>password</td>
                    <td  valign="middle"><input type="password" name="password" id="password" class="field" /></td>
                  </tr>
                     <tr>
                    <td>Domain:</td>
                    <td  valign="middle"><Select id="listoptions" name="listoptions" class="Filling-option" ><option><?php echo $domain;?></option></Select></td>
                  </tr>
                  <tr>
                    <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0"  style="margin-top:10px;">
                    <!--  <tr>
                        <td width="64%"><input name="" type="checkbox" value=""  style="margin:0; padding:0; "/>&nbsp;&nbsp;Remember me on this computer</td>
                        <td width="36%" align="right"><a href="#" class="forgot">Forgot password?</a></td>
                      </tr>-->
                    </table></td>
                    </tr>
                  
                  <tr>
                    <td height="73" colspan="2" align="center" style=" outline:none;" headers="50"><input name="button" type="submit" value="Login" class="Reports-btn"  /></td>
                    </tr>
                </table>
                </form>
                      </div>
                    </div>
                    
    
    
    <!--<table width="510" border="0" align="center">
		<tr>
			<td colspan="2">Login Form:</td>
		</tr>
		
        <tr>
			<td>Username:</td>
			<td><input type="text" name="username" id="username" /></td>
		</tr>
		<tr>
			<td>Password</td>
			<td><input type="password" name="password" id="password" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" name="button" class="Logout-btn" id="button" value="Login" /> | <input type="reset" name="button" id="button" class="Logout-btn" value="Reset" /></td>
		</tr>
	</table>-->

                    
                    <div class="clr"></div>
                </div>
        </div> <!--Content ends-->
            <div class="clr"></div>
        <div id="Footer">
        	Powered by: CareConnext Technologies™ Platform
        </div> <!--Footer ends-->
        
    </div> <!--Container ends-->
</body>
</html>
