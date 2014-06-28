<?php
require_once('lib/config.php');
$username="";
$password="";
$error="";
if(isset($_POST['username']) && isset($_POST['password']))
{
$username = $_POST['username'];
$password = $_POST['password'];

 
$ldap_server = "ldap://foo.bar.net";
$auth_user = "user@bar.net";
$auth_pass = "mypassword";

// Set the base dn to search the entire directory.

$base_dn = "DC=bar, DC=net";

// Show only user persons
$filter = "(&(objectClass=user)(objectCategory=person)(cn=*))";

// Enable to show only users
// $filter = "(&(objectClass=user)(cn=$*))";

// Enable to show everything
// $filter = "(cn=*)";

// connect to server

if (!($connect=@ldap_connect($ldap_server))) {
     die("Could not connect to ldap server");
}

// bind to server

if (!($bind=@ldap_bind($connect, $auth_user, $auth_pass))) {
     die("Unable to bind to server");
}

//if (!($bind=@ldap_bind($connect))) {
//     die("Unable to bind to server");
//}

// search active directory

if (!($search=@ldap_search($connect, $base_dn, $filter))) {
     die("Unable to search ldap server");
}

$number_returned = ldap_count_entries($connect,$search);
$info = ldap_get_entries($connect, $search);

echo "The number of entries returned is ". $number_returned."<p>";

for ($i=0; $i<$info["count"]; $i++) {
   echo "Name is: ". $info[$i]["name"][0]."<br>";
   echo "Display name is: ". $info[$i]["displayname"][0]."<br>";
   echo "Email is: ". $info[$i]["mail"][0]."<br>";
   echo "Telephone number is: ". $info[$i]["telephonenumber"][0]."<p>";
}




/* if($result) 
{


 $_SESSION['username'] = 
 $us=$_SESSION['username'];
  $atype='Loged in dashboard accessed';
  $pt='';
  $details=$atype." as : ".$us;
  $ip=getIP();
  $date=date("Y-m-d H:i:s");
  $k= useraudit($con,$date,$ip,$us,$atype,$pt,$details);
 header("location: login.php");

}
else
	{
		$error= "login failed!";
	}*/
}
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
        	<div id="Logo"><a href="index.htm"><img src="images/medical-services-logo.jpg" alt="medical services logo" border="0" /></a></div>
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
                        <form id="form1" name="form1" method="post" >
                            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                  <tr>
                    <td width="18%">Email</td>
                    <td width="82%" valign="middle"><input name="username" type="text" class="field" id="username" /> <?php echo $error;?></td>
                  </tr>
                  
                  <tr>
                    <td>password</td>
                    <td  valign="middle"><input type="password" name="password" id="password" class="field" /></td>
                  </tr>
                  <tr>
                    <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0"  style="margin-top:10px;">
                      <tr>
                        <td width="64%"><input name="" type="checkbox" value=""  style="margin:0; padding:0; "/>&nbsp;&nbsp;Remember me on this computer</td>
                        <td width="36%" align="right"><a href="#" class="forgot">Forgot password?</a></td>
                      </tr>
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
        	Powered by: CareConnect ™ Platform
        </div> <!--Footer ends-->
        
    </div> <!--Container ends-->
</body>
</html>
