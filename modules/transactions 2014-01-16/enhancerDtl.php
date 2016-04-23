<?
session_start();

include("../../includes/db.inc.php");
include("../../includes/common.php");

include("allowanceObj.php");
$allowanceObj = new allowanceObj();

if ($_SESSION['sts-username'] == "") {
	echo "<SCRIPT>window.parent.location.href='../../index.php';</SCRIPT>";
}

switch($_POST['action']) {
	
	case "AddEnhancerDtl":
		/*$a = $allowanceObj->brandExists($_POST['hdDtl_refNo2'],$_POST['cmbBrand']);
		if((int)$a==0){*/
			if($allowanceObj->AddEnhancerDtl($_POST)) {
				echo '$("#divAddEnhancer").dialog("close");';
				echo "dialogAlert('Brand/Enhancer successfully added.');\n";
			} else {
				echo "dialogAlert('Error adding STS Detail.');";
			}
		/*}else{
			echo "dialogAlert('Brand Already Exists.');";
		}*/
		exit();
	break;
	case "UpdateEnhancerDtl":
			if($allowanceObj->updateEnhancerDtl($_POST)) {
				echo '$("#divAddEnhancer").dialog("close");';
				echo "dialogAlert('Brand/Enhancer successfully added.');\n";
			} else {
				echo "dialogAlert('Error adding STS Detail.');";
			}
		exit();
	break;
	case "Delete":
			if($allowanceObj->DeleteSTSEnhancerDtl($_POST['refNo'],$_POST['brandCode'],$_POST['category'])) {
				echo "$('#dialogAlert').dialog('close');";
				echo "dialogAlert('Brand/Enhancer successfully deleted.');\n";
			} else {
				echo "dialogAlert('Error deleting Brand/Enhancer.');";
			}	
		exit();
	break;

}
?>
<html>
<head>
<title>Untitled Document</title>
<script type="text/javascript">
 
	function ToggleBranch2(id) {
		var chckBox = $('#ch2_'+id);
		if (chckBox.attr('checked')) {
			$('#cmbEnhancer_'+id).removeClass("txtInv");
			$('#cmbEnhancer_'+id).addClass('txtVis2');
			$('#switcher_'+id).val("1");
		} else {
			$('#cmbEnhancer_'+id).removeClass("txtVis2");
			$('#cmbEnhancer_'+id).addClass('txtInv');
			$('#switcher_'+id).val("0");
		}
		
	}
	function applySelected() {
		var enhance = $('#cmbDefaultEnhancer').val();
		var chldCnt = $('#hdCtr2').val();		
		var ctr = 0;
		for(i=0;i<=chldCnt;i++){
			if ($('#ch2_'+i).attr('checked')) {
				$('#cmbEnhancer_'+i).val(""+enhance);
				ctr++;
			}
			else{
				$('#cmbEnhancer_'+i).val("0");
			}
		}
		if(ctr==0){
			dialogAlert("ERROR: You must select a store first!");	
		}
	}
	function checkAllDtl2() {
		var chldCnt = $('#hdCtr2').val();
		if ($('#chAll2').attr('checked')) {
			for(i=0;i<=chldCnt;i++){
				$('#ch2_'+i).attr('checked','true');
				$('#cmbEnhancer_'+i).removeClass("txtInv");
				$('#cmbEnhancer_'+i).addClass('txtVis2');
				$('#switcher_'+i).val("1");
			}
		} else {
			for(i=0;i<=chldCnt;i++){
				$('#ch2_'+i).attr('checked',false);
				$('#cmbEnhancer_'+i).removeClass("txtVis2");
				$('#cmbEnhancer_'+i).addClass('txtInv');
				$('#switcher_'+i).val("0");
			}
		}
	}
	
</script>
<style type="text/css">
<!--
.textBox2 {
	border: 1px solid #222; 
	border-width: 1px; 
	width:100px; 
	height:18px;
	font-size: 11px;
}
.textBoxLong {
	border: solid #222; 
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
	border: 1px solid #222; 
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
    if ($_POST['act']=='Details') {
		$stsRefNo = $_POST['refNo'];
		$DtlAmt = $arrSTS['stsAmt'];
		if(isset($_POST['brandCode'])){
			$assocHdr = $allowanceObj->getEnhancerHeader($_POST['refNo'],$_POST['brandCode']);
			echo "<script>$('#txtCat').val('{$assocHdr['category']}');</script>\n";
			echo "<script>$('#cmbBrand').val('{$assocHdr['brandCode']}');</script>\n";
			echo "<script>$('#txtBRemarks').val('{$assocHdr['brandRem']}');</script>\n";
			$arrBranches = $allowanceObj->getEnhancerBranchesWithBrand($_POST['refNo'],$_POST['brandCode'],$_POST['category']);
		}else{
			$arrBranches =  $allowanceObj->getEnhancerBranches($_POST['refNo']);
		}
	?>
	<form id="formEnhancerDtl" name="formEnhancerDtl">
    <div>
    	<table width="100%" border="0" cellspacing="2" cellpadding="0">
        	 <tr>
             	<td  class="hd2">Category: </td>
                <td>
                	<? $allowanceObj->DropDownMenu($allowanceObj->makeArr($allowanceObj->getAllCategory(),'stsCategory','stsCatDesc',''),'txtCat','','class="selectBoxMin "'); ?>
                </td>
                <td class="hd2">Brand: </td>
                <td><? $allowanceObj->DropDownMenu($allowanceObj->makeArr($allowanceObj->getAllBrand(),'stsBrand','stsBrandDesc',''),'cmbBrand','','class="selectBoxMin "'); ?></td>
             </tr>
              <tr>
             	<td  class="hd2">Remarks: </td>
                <td colspan="3"><input type="text" name="txtBRemarks" id="txtBRemarks" class="textBoxLong"/></td>
             </tr>
             <tr>
             	<td class="hd2">Default Enhancer: </td>
                <td colspan="2"><? $allowanceObj->DropDownMenu($allowanceObj->makeArr($allowanceObj->getAllEnhancerType(),'enhanceType','enhanceDesc',''),'cmbDefaultEnhancer','','class="selectBoxMin2"'); ?>
                </td>
                <td>
                    <input type="button" id="btnApplyAll" name="btnApplyAll" value="Apply To All" onClick="applySelected();">
                </td>
             </tr>
        </table>
    </div>
    <br />
	<div class="headerContentDtl ui-corner-all">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          
          <tr>
            <td width="6%" height="25"><input type="checkbox" name="chAll2" onClick="checkAllDtl2();"  id="chAll2"></td>
              <td width="47%"><strong>Branch
                <input type="hidden" name="hdDtl_refNo2" value="<?=$_POST['refNo']?>" id="hdDtl_refNo2" />
             
            </strong></td>
            <td width="30%"><strong>Enhancer</strong></td>
          </tr>
            <tr>
            	<td colspan="2" height="5"></td>
            </tr>
            <?
			$u = 0;
            foreach($arrBranches as $val) {
				$checked = ($val['enhanceType'] != '') ? 'checked':'';
				$txtInv =  ($val['enhanceType'] != '') ? "":"txtInv";
				$switcher_val =  ($val['enhanceType'] != '') ? "1":"0";
				
			?>
            <tr>
              
              <td class=""><input type="checkbox" <?=$checked?>  name="ch2_<?=$u;?>" onClick="ToggleBranch2('<?=$u;?>');" value="<?=$val['strCode'];?>" id="ch2_<?=$u;?>"></td>
              <input type="hidden" name="switcher_<?=$u;?>" id="switcher_<?=$u;?>" value='<?=$switcher_val?>'/>
              <input type="hidden" name="comp2_<?=$u;?>" id="comp2_<?=$u;?>" value="<?=$val['compCode'];?>" />
              <td height="25"><?=$val['brnDesc'];?></td>
              <td><? $allowanceObj->DropDownMenu($allowanceObj->makeArr($allowanceObj->getAllEnhancerType(),'enhanceType','enhanceDesc',''),'cmbEnhancer_'.$u,''.$val['enhanceType'].''. $val['enhancetype'].'','class="selectBoxMin '.$txtInv.'"'); ?></td>
            </tr>
            <?
				$u++;
            }
			?>
          </table>  
      <input type="hidden" name="hdCtr2" id="hdCtr2" value="<?=($u-1);?>" />
    </div>
      </form>
      <? } else {?>
<? }?>   

</body>
</html>
