<?
session_start();

include("../../includes/db.inc.php");
include("../../includes/common.php");

include("daObj.php");
$daObj = new daObj();

if ($_SESSION['sts-username'] == "") {
	echo "<SCRIPT>window.parent.location.href='../../index.php';</SCRIPT>";
}

switch($_GET['action']) {
	
	case "addDaDtl":
		$strComp = explode("|",$_GET['cmbStr']);
		$a = $daObj->brandExists($_GET['hdnRefNo2'],$strComp[0]);
		if((int)$a==0){
			if($daObj->addDaDtl($_GET)) {
				echo '$("#divAddEnhancer").dialog("close");';
				echo "dialogAlert('DA Details successfully added.');\n";
			} else {
				echo "dialogAlert('Error adding STS Detail.');";
			}
		}else{
			echo "dialogAlert('Store Already Exists.');";
		}
		exit();
	break;
	case "Delete":
			if($daObj->DeleteSTSDaDtl($_GET['refNo'],$_GET['strCode'])) {
				echo "$('#dialogAlert').dialog('close');";
				echo "dialogAlert('Brand/Enhancer successfully deleted.');\n";
			} else {
				echo "dialogAlert('Error deleting Brand/Enhancer.');";
			}	
		exit();
	break;
	
	case "updateDaDtl":
			if($daObj->updateDaDtl($_GET)) {
				echo '$("#divAddEnhancer").dialog("close");';
				echo "dialogAlert('DA Details successfully updated.');\n";
			} else {
				echo "dialogAlert('Error adding STS Detail.');";
			}
		exit();
	break;

}
?>
<html>
<head>
<title>Untitled Document</title>
<script type="text/javascript">
 
	
</script>


<style type="text/css">
<!--
.selectBox2 {
	border: 1px solid #222; 
	width:192px; 
	height:22px;
	font-size: 11px;
}

.textBox2 {
	border: solid #999999; 
	border-width: 1px; 
	width:100px; 
	height:18px;
	font-size: 11px;
}
.textBoxLong {
	border: 1px solid #222; 
	border-width: 1px; 
	width:100%; 
	height:18px;
	font-size: 11px;
}
#formExpenseDtl input.error {
	background: #f8dbdb;
	border-color: #e77776;
}
#formExpenseDtl select{
	border: 1px solid #999999; 
	width:192px; 
	height:22px;
	font-size: 11px;
}
#formExpenseDtl select.error {
	background: #f8dbdb;
	border-color: #e77776;
}

.headerContentDtl {
	border: solid #666666 1px;
	padding: 3px;
	background-color:#E9E9E9;
}
.textBox1 {	border: solid #999999; 
	border-width: 1px; 
	width:190px; 
	height:18px;
	font-size: 11px;
}
.txtInv {
	visibility:hidden;
}
.txtVis {
	visibility:visible;
	cursor:pointer;
}
.txtVis2 {
	visibility:visible;
}
.enabletxt {
	background: #C7DAFE;
	border-color: #e77776;
}
-->
</style>         
</head>

<body>
	<?
    if ($_GET['act']=='Details') {
		$stsRefNo = $_GET['refNo'];
		if(isset($_GET['strCode'])){
			$arr = $daObj->getStsDaDtl($_GET['refNo'],$_GET['strCode']);
			echo "<script>$('#cmbStr').val('".$arr['strCode']."|".$arr['compCode']."');\n";
			echo "$('#txtDispTyp').val('{$arr['displayType']}');\n";
			echo "$('#txtBrand').val('{$arr['brand']}');\n";
			echo "$('#txtLoc').val('{$arr['location']}');\n";
			echo "$('#txtSizeSpecs').val('{$arr['daSize']}');\n";
			echo "$('#txtDispSpecs').val('{$arr['dispSpecs']}');\n";
			echo "$('#txtNoUnits').val('{$arr['noUnits']}');\n";
			echo "$('#txtRem').val('{$arr['daRemarks']}');\n";
			echo "$('#txtUnitAmt').val('{$arr['perUnitAmt']}');\n";
			echo "$('#txtMonthly').val('{$arr['stsAmt']}');</script>\n";
		}
	?>
	<form id="formEnhancerDtl" name="formEnhancerDtl">
    	<input type="hidden" id="hdnRefNo2" name="hdnRefNo2" value="<?=$stsRefNo?>" />
    	<table width="100%" border="0" cellspacing="0" cellpadding="2">
       		<tr>
            	<td class="hd" width="30%">Store</td>
                <td><? $daObj->DropDownMenu($daObj->makeArr($daObj->getAllBranches(),'strCodeComp','strCodeBranch',''),'cmbStr','','class="selectBox2"'); ?></td>
            </tr>
           	<tr>
             	<td class="hd">Display Type</td>
                 <td><? $daObj->DropDownMenu($daObj->makeArr($daObj->getDisplayType(),'displayTypeId','displayTypeDesc',''),'txtDispTyp','','class="selectBox2"'); ?></td>
            </tr>
            <tr>
                <td class="hd">Brand Name</td>
                <td><input type="text" name="txtBrand" id="txtBrand" class="textBox"/></td>
            </tr>
            <tr>
                <td class="hd">Location</td>
                <td><input type="text" name="txtLoc" id="txtLoc" class="textBox"/></td>
            </tr>
            <tr>
                <td class="hd">Size Specs</td>
                <td><? $daObj->DropDownMenu($daObj->makeArr($daObj->getSizeSpecs(),'sizeSpecsId','sizeSpecsDesc',''),'txtSizeSpecs','','class="selectBox2"'); ?></td>
            </tr>
            <tr>
                <td class="hd">Display Specs</td>
                <td><? $daObj->DropDownMenu($daObj->makeArr($daObj->getDisplaySpecs(),'displaySpecsId','displaySpecsDesc',''),'txtDispSpecs','','class="selectBox2"'); ?></td>
            </tr>
            <tr>
                <td class="hd">Display Fee per Unit</td>
                <td><input type="text" name="txtUnitAmt" id="txtUnitAmt" class="textBox" onKeyPress='return valNumInputDec(event);' style="text-align:right"/></td>
            </tr>
            <tr>
                <td class="hd">No. of Units</td>
                <td><input type="text" name="txtNoUnits" id="txtNoUnits" class="textBox" onChange="computeMonthly();" onKeyPress='return valNumInputDec(event); ' style="text-align:right"/></td>
            </tr>
             <tr>
                <td class="hd">Total Display Per Month</td>
                <td><input type="text" name="txtMonthly" id="txtMonthly" class="textBox" onKeyPress='return valNumInputDec(event);' style="text-align:right"/></td>
            </tr>
            <tr>
                <td class="hd">Remarks</td>
                <td><input type="text" name="txtRem" id="txtRem" class="textBoxLong"/></td>
            </tr> 
        </table>
    
   </form>
<? }?>   

</body>
</html>
