<?
session_start();

include("../../includes/db.inc.php");
include("../../includes/common.php");

include("EODobj.php");

$EODobj = new EODobj();

$result = $EODobj->getStsNo();
echo $result;
?>