<?php
require_once('lib/config.php');
$_PHP_SELF=$_SERVER['PHP_SELF'];
echo "No data";

/* Erasing data from tables tmptable, tbltemp, tblfile, tblfetch*/

/* $tmptab="DELETE FROM tmptable";
$tmptabdat = $con->getMultiRow($tmptab, __FILE__, __LINE__);

$ttmp="DELETE FROM tbltemp";
$ttmpdat = $con->getMultiRow($ttmp, __FILE__, __LINE__);

$fet="DELETE FROM tblfetch";
$fetchdat = $con->getMultiRow($fet, __FILE__, __LINE__);

$fil="DELETE FROM tblfile";
$filedat = $con->getMultiRow($fil, __FILE__, __LINE__);

$audit="DELETE FROM tbllog";
$auditdat = $con->getMultiRow($audit,__FILE__,__LINE__);
 */
/* $eraseproc="
DELIMITER $$
DROP PROCEDURE IF EXISTS ERASE;
$$
CREATE PROCEDURE ERASE()
BEGIN
DELETE FROM tmptable;
DELETE FROM tbltemp;
DELETE FROM tblfile;
DELETE FROM tblfetch;
DELETE FROM tbllog;
END $$" ; */

$callerase="CALL ERASE()";
$erasecon=$con->getMultiRow($callerase,__FILE__,__LINE__);

/* if($tmptabdat && $ttmpdat && $fetchdat && $filedat && $auditdat)
		{
				 echo "<div><br/>No data shown.</div><br/>";
		} */

?>
