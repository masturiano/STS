<?
session_start();
#### Roshan's Ajax dropdown code with php
#### Copyright reserved to Roshan Bhattarai - nepaliboy007@yahoo.com
#### if you have any problem contact me at http://roshanbh.com.np
#### fell free to visit my blog http://php-ajax-guru.blogspot.com
?>
<? 
include("../../includes/db.inc.php");
include("../../includes/common.php");
include("efdObj.php");
$efdObj = new efdObj();

$subDis=$_REQUEST['subDis'];
$distribution_name = $efdObj->getSubDistributionName($subDis);
?>
<select name="cmbSubGroup" class="selectBox" style="width: 100px;">
    <? foreach($distribution_name as $row) { ?>
    <option value="<?=$row['SUB_DISTRIBUTION_NAME']?>"><?=$row['SUB_DISTRIBUTION_NAME']?></option>
    <? } 
    ?>
</select>
