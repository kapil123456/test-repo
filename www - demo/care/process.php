<?php
require_once('lib/config.php');
//File path to CSV
$target_path = "C:/wamp/www/care/uploads/";

$filePath = $filePath . basename( $_FILES['uploadedfile']['name']); 
$target_path = $target_path . basename( $_FILES['uploadedfile']['name']); 

if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
echo "The file ". basename( $_FILES['uploadedfile']['name']). 
" has been uploaded"; 

} else{
echo "There was an error uploading the file, please try again!";
header('Location: http://localhost:8082/care/uploadplist.php');
exit();
}





//read the CSV file to Stream
$mrnkeys = array();
$row = 1;
if (($handle = fopen("$filePath", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $num = count($data);
    $row++;
 	$p_id=$data[0];
	$p_name=$data[1];
	$p_mmname=$data[2];
	$p_dob=$data[3];
	$p_gender=$data[4];
	$p_palias=$data[5];
	$p_race=$data[6];
	$p_paddress=$data[7];
	$p_ccode=$data[8];
	$p_phome=$data[9];
	$p_pbusiness=$data[10];
	$p_plang=$data[11];
	$p_mstatus=$data[12];
	$p_religon=$data[13];
	$p_acno=$data[14];
	$p_ssnp=$data[15];
	$p_department=$data[16];
	$p_phospital=$data[17];
	$p_mrn=$data[18];
	$p_admitdate=$data[19];
	$p_dischargedate=$data[20];
	$p_type	=$data[21];
	$p_filename=$data[22];
	$p_fname=$data[23];
	$p_lname=$data[24];
//input the red data from CSV in to Database
 $plqry="INSERT INTO tblpatients (p_name,p_mmname,p_dob,p_gender,p_palias,p_race,p_paddress,p_ccode,p_phome	,p_pbusiness,p_plang,p_mstatus,p_religon,p_acno,p_ssnp,p_department,p_phospital,p_mrn,p_admitdate,p_dischargedate,p_type,	p_filename,p_fname,p_lname)
VALUES ('$p_name','$p_mmname','$p_dob','$p_gender','$p_palias','$p_race','$p_paddress','$p_ccode','$p_phome','$p_pbusiness','$p_plang','$p_mstatus','$p_religon','$p_acno','$p_ssnp','$p_department','$p_phospital','$p_mrn','$p_admitdate','$p_dischargedate','Needs Retrieval','$p_filename','$p_fname','$p_lname') ON DUPLICATE p_mrn UPDATE p_name='$data[1]',p_mmname='$data[2]',p_dob='$data[3]',p_gender='$data[4]',p_palias=='$data[5]',p_race=='$data[6]',p_paddress=='$data[7]',p_ccode='$data[7]',p_phome='$data[8]',p_pbusiness='$data[9]',p_plang='$data[10]',p_mstatus='$data[11]',p_religon=='$data[12]',p_acno=='$data[13]',p_ssnp='$data[14]',p_department='$data[15]',p_phospital='$data[16]',p_mrn='$data[17]',p_admitdate='$data[18]',p_dischargedate='$data[19]',p_type='$data[20]',	p_filename='$data[21]',p_fname='$data[22]',p_lname='$data[23]'";

$j=$con->insertRecords($plqry,  __FILE__,  __LINE__);
 $er_msg=$j;
$mrnkeys[]=$data[18]

} 
fclose($handle);
}
//To redirect to index page
//header('Location: http://localhost:8082/care/');
$_SESSION['mg']=$er_msg;
header('Location: http://localhost:8082/care/');
exit();

?>