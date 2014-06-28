<?php
  require_once('lib/config.php');
require_once('PHPMailer-master/class.phpmailer.php');
$searchfilter='';
if(!isset($_SESSION['username']))
{
header("location: login.php");
exit();
}
else
{

$euid=$_SESSION['username'];
$getemail_gry="SELECT e_id,e_smtp,e_eid,e_psw,e_from,e_to,e_sub,e_body,e_uid FROM emailconf where e_uid='".$euid."'";
$edata=$con->getMultiRow($getemail_gry,  __FILE__,  __LINE__);

if(!empty($edata))
{
if(isset($_POST['fname']) && $_POST['fname']!='' && isset($_POST['email']) && $_POST['email']!='' && isset($_POST['tsub']) && $_POST['tsub']!='' && isset($_POST['tmsg']) && $_POST['tmsg']!='')
{
$msend=sendEmail($edata[0]['e_smtp'], $edata[0]['e_eid'],$edata[0]['e_psw'],$_POST['email'],$_POST['fname'],$_POST['tsub'],$_POST['tmsg'],$edata[0]['e_to'],'','');


    header("location: submitticket.php?msg=".$msend);
	exit();
 


}
else
{
if($_GET)
{
$page=$_GET['doc'];
}
//echo $page;
//exit();
$filename='logfile.csv';
$filetoattach = "./logfile.csv";
//var_dump(file_exists("C:\\AppServ\\MySQL\\data\\logfile.csv"));
if(file_exists("C:\\AppServ\\MySQL\\data\\logfile.csv"))
{
unlink("C:\\AppServ\\MySQL\\data\\logfile.csv");
}

    $fl=0;
	//$searchfilter=$_SESSION['dmyQry'];
	
	$kqry="SELECT datelog,user,accesstype  INTO OUTFILE '$filetoattach' FIELDS TERMINATED BY ',' FROM tbllog";
	// WHERE user LIKE '%".$searchfilter."%'";
	
	$fl=$con->getMultiRow($kqry,  __FILE__,  __LINE__);
	

$ml=sendEmail($edata[0]['e_smtp'], $edata[0]['e_eid'],$edata[0]['e_psw'],$edata[0]['e_from'],$edata[0]['e_uid'],$edata[0]['e_sub'],$edata[0]['e_body'],$edata[0]['e_to'],$filetoattach,$filename);

$_SESSION['emsg']=$ml;
   echo "<script> location.replace('$page.php'); </script>";
  exit();
    //header("location: suaudit.php?msg=".$ml);
	//exit();


}

}
}

function sendEmail($smtphost,$smtpuser,$smtppsw,$sender,$sendername,$subject,$msg,$to,$file_to_attach,$filename)
{
$flag=0;
$mail = new PHPMailer();
$mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
$mail->Host  =$smtphost;
$mail->Port  = '465'; // or 587 or 465
$mail->IsHTML(true);
$mail->Username = $smtpuser;
$mail->Password = $smtppsw;
$mail->From      = $sender;
$mail->FromName  = $sendername;
$mail->Subject   = $subject;
$mail->Body      = $msg;
$mail->AddAddress($to);
$mail->AddAttachment( "C:\\AppServ\\MySQL\\data\\logfile.csv", $filename );

if(!$mail->send())
{
$flag=0;
}
else
{
$flag=1;
}

return $flag;
}

?>

