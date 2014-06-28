<?php
    require_once('lib/config.php');
	
	
	$user="";
	$psw="";
	$error="";
	
	if(isset($_POST['username']) && isset($_POST['password']))
	{
		//domain user to connect to LDAP
    $user = $_POST['username'];
    //user password
    $psw = $_POST['password'];
    
	 $adldap->connect();

	$username=$user;
	$password=$psw;	
    
	If($_POST['listoptions']==$domain)
	{

	 $isvalid=$adldap->user()->authenticate($username, $password);
	//$groups=$adldap->user()->info($username);
	//groups($username,$recursive=NULL,$isGUID=false);
	//$groupList = $adldap->group()->allSecurity($include_desc = false, $search = "*", $sorted = true);
//pr($groups);
//pr($isvalid);

	 $fetchGrps="Select groupname from groups g, users u where g.groupname=u.groupid";
	 $fGrps=$con->getMultiRow($fetchGrps,  __FILE__,  __LINE__);
	 
	 //var_dump($adldap->user()->ingroup($username,"Domain Users",$recursive=NULL,$isGUID=false));
//pr($fGrps);
//	exit(); 
//$adldap->user()->ingroup($username,$fGrps[$l]['groupname'],$recursive=NULL,$isGUID=false)==true)
	 $ingroup=false;
	 for($l=0;$l<count($fGrps);$l++)
	 {
	
		if($fGrps[$l]['groupname']=="LWR Copy Q Admins" || $fGrps[$l]['groupname']=="LWR Copy Q")		
		{
		 $ingroup=true;
		}
	    	
	 
	 }

	if($isvalid==true and $ingroup==true)
	{

		$ur = $adldap->user()->infoCollection($user, array('*'));

		$u=explode('@',$user);
		$_SESSION['u']=$u['0'];
		$_SESSION['p']=$password;
	
		$queryAppUser = "SELECT uid,email,first_name,last_name,password,groupid,activated
        				 FROM users
       					 WHERE uid = '$user' and password='$psw';";
 
		$readuser=$con->getSingleRow($queryAppUser,  __FILE__,  __LINE__);
		
		if($readuser)
		{
		  
	    $grpperm=setPermissions($con,$readuser['groupid']);	
		
	
		enterHome($con,$readuser['uid']);
	    }
		else
		{
		  $chkuser_qry="Select uid From users where uid='".$user."'";
          $flag=$con->getSingleRow($chkuser_qry,  __FILE__,  __LINE__);
	
				if(empty($flag))
				{
				//default email value if no email found.
				if(empty($ur->mail))
				{
				 $em=$user.'@'.$domain;
				}
				else
				{
				 $em=$ur->mail;
				}
			
		 				 $addusr_qry="INSERT INTO users (
								uid,
								email,
								password ,
								activated,
								activation_code,
								activated_at,
								last_login,
								persist_code,
								reset_password_code,
								first_name,
								last_name,
								created_at,
								updated_at,
								groupid
								)
						VALUES ('".$user."','".$em."','".$psw."',0, NULL , NULL , NULL , NULL ,  NULL,'".$ur->displayname."' ,'". $ur->sn."'  ,'".date("Y-m-d H:i:s")."','0000-00-00 00:00:00','Care Connect Users')";

 					$j=$con->insertRecords($addusr_qry,__FILE__,__LINE__);
					
				
	
	$qryUser = "SELECT uid,email,first_name,last_name,password,groupid,activated FROM users WHERE uid = '$user' and 	password='$psw';"; 
		
		$ruser=$con->getSingleRow($qryUser,  __FILE__,  __LINE__);
		
			$grpperm=setPermissions($con,$ruser['groupid']);	
						
							enterHome($con,$ruser['uid']);
						
						
			}
		}
	}
	else
	{
		
		$qryAppUser = "SELECT uid,email,first_name,last_name,password,groupid,activated FROM users WHERE uid = '$user' and 	password='$psw';"; 
		
		$appuser=$con->getSingleRow($qryAppUser,  __FILE__,  __LINE__);
	
		
		if(isset($appuser['groupid']) && $appuser['groupid']=='App Admin')
		{
		$_SESSION['u']=$appuser['uid'];
		$_SESSION['p']=$password;
			
					    $grpperm=setPermissions($con,$appuser['groupid']);	
						
						enterHome($con,$appuser['uid']);
				
		}
		else
		{
  		$error="Invalid Login/Password!";
		}
	}

		
	
    
	}
	
	}
  	if($error!="")
	{
		header("location: login.php?err=".$error);
		exit();
	}
	
	
	function setPermissions($con,$grpid)
	{
		$fg=0;
	
	        $qrypermissions="Select * from permissions where grp_id='".$grpid."'";
			$permres=$con->getSingleRow($qrypermissions,  __FILE__,  __LINE__);
			
				if($permres)
				{
					$_SESSION['groupid']=$permres['grp_id'];
					$_SESSION['allow_view']=$permres['allow_view'];
					$_SESSION['allow_print']=$permres['allow_print'];
					$_SESSION['allow_save']=$permres['allow_save'];
					$_SESSION['allow_audit']=$permres['allow_audit'];
					$_SESSION['allow_tickets']=$permres['allow_tickets'];
					$_SESSION['allow_usrmgt']=$permres['allow_usrmgt'];
					$_SESSION['allow_demo']=$permres['allow_demo'];
					$_SESSION['allow_conf']=$permres['allow_conf'];
					$_SESSION['allow_csv']=$permres['allow_csv'];
					$_SESSION['allow_parse']=$permres['allow_parse'];

				$fg=1;
				}
			
			return $fg;	
	}
	
	                    function enterHome($con,$usr)
						{
						 $_SESSION['username'] = $usr;
						$us=$_SESSION['username'];
						$atype='Log In';
						$pt='';
						$details=$atype." as : ".$us;
						$ip=getIP();
						$date=date("Y-m-d H:i:s");
						$dv="Index.php";
						$k= useraudit($con,$date,$ip,$us,$atype,$pt,$details,$dv);
						header("location: index.php");
						exit();
						
						}
?>