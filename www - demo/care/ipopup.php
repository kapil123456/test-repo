<?php
require_once('lib/config.php');

//pr($_POST);
$list=0;
$filename=array();
$mn=array();
if(isset($_POST['myStr']) && $_POST['myStr']!='')
{
	$list =$_POST['myStr'];
	$filename=explode(',',$list);
	$filename2=explode('_',$list);
	$mn=explode(',',$filename2[2]);
	
 	
}


?>

<script>
$(function() {
$( "#tabs" ).tabs();
 $('#closeBtn').click(function() {
                    $('#dialog-movie-info').dialog('close');
                });
});
</script>
<style>
  .ui-tabs-nav li.ui-tabs-close-button {
    float: right;
    margin-top: 3px;
}
  </style>

<div id="tabs">
<ul>

<li><a href="#tabs"><?php echo $filename[6];?></a></li>


</ul>
<?php

	$us=$_SESSION['username'];
    $atype=$filename[6].'files viewed';
    $pt=$mn[1];
    $details=$atype." by : ".$us;
    $ip=getIP();
    $date=date("Y-m-d H:i:s");
	$dv="$filename[6]";
    $k= useraudit($con,$date,$ip,$us,$atype,$pt,$details,$dv);
?>
<!--<script>
 $("#loadingicon").show();
   $("#iframepdf").hide(); 

    $('#iframepdf').load('\file.pdf', function() {
      $("#loadingicon").hide(); // this calls after load completes
      $("#iframepdf").show(); 
    })
</script>-->

<div id="tabs-<?php echo $list;?>">
<p>
<!--<object data="input/" type="application/pdf" style="width:718px; height:700px;" frameborder="0">
  <p>Alternative text - include a link <a href="input/">to the PDF!</a></p>
   <param name=”view” value=”fitH” />
</object>-->
<iframe id="iframepdf" src="<?php echo 'storage/'.$list;?>" style="width:100%;height:100%" width="100%" height="100%" marginwidth="0" marginheight="0" hspace="0" vspace="0" frameborder="0" scrolling="no" > </iframe>
</p>
</div>

</div>
