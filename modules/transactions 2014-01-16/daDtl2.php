<?
session_start();

include("../../includes/db.inc.php");
include("../../includes/common.php");

include("daObj.php");
$daObj = new daObj();

if ($_SESSION['sts-username'] == "") {
	echo "<SCRIPT>window.parent.location.href='../../index.php';</SCRIPT>";
}

switch($_POST['action']) {
	
	case "AddSTSDtl":
		$total = 0;
		for($i=0;$i<=(float)$_POST['hdCtr'];$i++) {
			if($_POST["txt_$i"]!="") {
				$total += (float)$_POST["txt_$i"];
			}
		}
		$dtlAmt = $_POST['hdDtl_TotAmt'];		
		//if(sprintf('%01.2f', $total) == sprintf('%01.2f', $_POST["hdDtl_Amt"])){
			if($daObj->AddSTSDtl($_POST)) {
				echo '$("#divSTSDtls").dialog("close");';
				echo "dialogAlert('STS Detail successfully added.');\n";
			} else {
				echo "dialogAlert('Error adding STS Detail.');";
			}
		/*} else {
			echo "dialogAlert('STS Header Amount is not equal to STS Details Total Amount.','dialogAlertFunds');";
		}*/
		exit();
	break;
	case "Delete":
			if($daObj->DeleteSTSDtl($_POST['refNo'])) {
				echo "$('#dialogAlert').dialog('close');";
				echo "dialogAlert('STS Detail successfully deleted.');\n";
			} else {
				echo "dialogAlert('Error deleting STS Detail.');";
			}	
		exit();
	break;

}
?>
<html>
<head>
<title>Untitled Document</title>
<script type="text/javascript">
$(function(){
			
			$('table.details td').addClass('line');
});
 	function CopyTxt(objSource,objClip) {
	 	$('#'+objClip).val($('#'+objSource).val());
	 }
	 function PasteTxt(objClip,objDest) {
	 	$('#'+objDest).val($('#'+objClip).val());
		if (objClip=='hdClipTxt') {
			ComputeDetails();
		}
	 }

	function ToggleBranch(id) {
		var chckBox = $('#ch_'+id);
		//var enhance = $('#hdnEnhanceType').val();
		if (chckBox.attr('checked')) {
			$('#txt_'+id).attr('readonly',false);
			$('#txt_'+id).addClass('enabletxt');
			$('#cmb_'+id).attr('disabled',false);
			$('#cmb_'+id).addClass('enabletxt');
			ComputeDetails();
		} else {
			$('#txt_'+id).attr('readonly','true');
			$('#txt_'+id).removeClass('enabletxt');
			$('#cmb_'+id).attr('disabled','true');
			$('#cmb_'+id).removeClass('enabletxt');
			$('#txt_'+id).val('');
			ComputeDetails();
		}
		
	}
	function ToggleBranch2(id) {
		var chckBox = $('#ch_'+id);
		if (chckBox.attr('checked')) {
			$('#txtSizeSpecs_'+id).removeClass("txtInv");
			$('#txtSizeSpecs_'+id).addClass('txtVis2');
			$('#txtDispSpecs_'+id).removeClass("txtInv");
			$('#txtDispSpecs_'+id).addClass('txtVis2');
			$('#txtUnitAmt_'+id).removeClass("txtInv");
			$('#txtUnitAmt_'+id).addClass('txtVis2');
			$('#txtNoUnits_'+id).removeClass("txtInv");
			$('#txtNoUnits_'+id).addClass('txtVis2');
			$('#txtMonthly_'+id).removeClass("txtInv");
			$('#txtMonthly_'+id).addClass('txtVis2');
			
			$('#txtVatAmt_'+id).removeClass("txtInv");
			$('#txtVatAmt_'+id).addClass('txtVis2');
			$('#txtSubAmt_'+id).removeClass("txtInv");
			$('#txtSubAmt_'+id).addClass('txtVis2');
			
			$('#switcher_'+id).val("1");
		} else {
			$('#txtSizeSpecs_'+id).removeClass("txtVis2");
			$('#txtSizeSpecs_'+id).addClass('txtInv');
			$('#txtDispSpecs_'+id).removeClass("txtVis2");
			$('#txtDispSpecs_'+id).addClass('txtInv');
			$('#txtUnitAmt_'+id).removeClass("txtVis2");
			$('#txtUnitAmt_'+id).addClass('txtInv');
			$('#txtNoUnits_'+id).removeClass("txtVis2");
			$('#txtNoUnits_'+id).addClass('txtInv');
			$('#txtMonthly_'+id).removeClass("txtVis2");
			$('#txtMonthly_'+id).addClass('txtInv');
			
			$('#txtVatAmt_'+id).removeClass("txtVis2");
			$('#txtVatAmt_'+id).addClass('txtInv');
			$('#txtSubAmt_'+id).removeClass("txtVis2");
			$('#txtSubAmt_'+id).addClass('txtInv');
			
			$('#switcher_'+id).val("0");
		}
		
	}
	function checkAllDtl() {
		var chldCnt = $('#hdCtr').val();
		if ($('#chAll').attr('checked')) {
			for(id=0;id<=chldCnt;id++){
				$('#ch_'+id).attr('checked','true');
				$('#txtSizeSpecs_'+id).removeClass("txtInv");
				$('#txtSizeSpecs_'+id).addClass('txtVis2');
				$('#txtDispSpecs_'+id).removeClass("txtInv");
				$('#txtDispSpecs_'+id).addClass('txtVis2');
				$('#txtUnitAmt_'+id).removeClass("txtInv");
				$('#txtUnitAmt_'+id).addClass('txtVis2');
				$('#txtNoUnits_'+id).removeClass("txtInv");
				$('#txtNoUnits_'+id).addClass('txtVis2');
				$('#txtMonthly_'+id).removeClass("txtInv");
				$('#txtMonthly_'+id).addClass('txtVis2');
				$('#txtVatAmt_'+id).removeClass("txtInv");
				$('#txtVatAmt_'+id).addClass('txtVis2');
				$('#txtSubAmt_'+id).removeClass("txtInv");
				$('#txtSubAmt_'+id).addClass('txtVis2');
				$('#switcher_'+id).val("1");
			}
		} else {
			for(id=0;id<=chldCnt;id++){
				$('#ch_'+id).attr('checked',false);
				$('#txtSizeSpecs_'+id).removeClass("txtVis2");
				$('#txtSizeSpecs_'+id).addClass('txtInv');
				$('#txtDispSpecs_'+id).removeClass("txtVis2");
				$('#txtDispSpecs_'+id).addClass('txtInv');
				$('#txtUnitAmt_'+id).removeClass("txtVis2");
				$('#txtUnitAmt_'+id).addClass('txtInv');
				$('#txtNoUnits_'+id).removeClass("txtVis2");
				$('#txtNoUnits_'+id).addClass('txtInv');
				$('#txtMonthly_'+id).removeClass("txtVis2");
				$('#txtMonthly_'+id).addClass('txtInv');
				$('#txtVatAmt_'+id).removeClass("txtVis2");
				$('#txtVatAmt_'+id).addClass('txtInv');
				$('#txtSubAmt_'+id).removeClass("txtVis2");
				$('#txtSubAmt_'+id).addClass('txtInv');
				$('#switcher_'+id).val("0");
			}
		}
	}
	function applySelected() {
		var sizeSpecs = $('#txtSizeSpecs').val();
		var dispSpecs = $('#txtDispSpecs').val();
		var unitAmt = $('#txtUnitAmt').val();
		var noUnit = $('#txtNoUnits').val();
		var monthly = $('#txtMonthly').val();
		var vat = parseFloat($("#hdnVat").val());
		var ewt = parseFloat($("#hdnEwt").val());
		
		var chldCnt = $('#hdCtr').val();		
		var ctr = 0;
		for(i=0;i<=chldCnt;i++){
			if ($('#ch_'+i).attr('checked')) {
				$('#txtSizeSpecs_'+i).val(""+sizeSpecs);
				$('#txtDispSpecs_'+i).val(""+dispSpecs);
				$('#txtUnitAmt_'+i).val(""+unitAmt);
				$('#txtNoUnits_'+i).val(""+noUnit);
				$('#txtMonthly_'+i).val(""+monthly);
				$('#txtVatAmt_'+i).val(addDecimal(parseFloat($('#txtMonthly_'+i).val())*parseFloat(vat)));
				$('#txtEwtAmt_'+i).val(addDecimal($('#txtMonthly_'+i).val()*ewt*-1));
				$('#txtSubAmt_'+i).val(addDecimal(parseFloat($('#txtMonthly_'+i).val())+($('#txtMonthly_'+i).val()*vat)));
				ctr++;
			}
			else{
				$('#txtSizeSpecs_'+i).val("0");
				$('#txtDispSpecs_'+i).val("0");
				$('#txtUnitAmt_'+i).val("0");
				$('#txtNoUnits_'+i).val("0");
				$('#txtMonthly_'+i).val("0");
				$('#txtVatAmt_'+i).val("0");
				$('#txtEwtAmt_'+i).val("0");
				$('#txtSubAmt_'+i).val("0");
				
			}
		}
		if(ctr==0){
			dialogAlert("ERROR: You must select a store first!");	
		}
	}
	function ComputeDetails() {
		var counter = $('#hdCtr').val();
		var amt = 0;
		var id = "";
		for(i=0;i<=counter;i++) {
			if ($('#txt_'+i).val() != '')
				amt = amt + parseFloat($('#txt_'+i).val());
		}
		amt = Math.round(amt*10000)/10000;
		$('#dvTotDtdl').html(amt);
	}	
	
	function Allocate() {
		var counter = $('#hdCtr').val();
		var amount = $('#hdDtl_Amt').val();
		
		var noPar = percent = ctr = allocPerParticipant = accumulatedFund = 0;
		
		for(a=0;a<=counter;a++){
			if ( $('#ch_'+a).attr('checked') ) {
				noPar++;	
			}
		}
		percent = (100/noPar);
		for(a=0;a<=counter;a++) {	
			if ( $('#ch_'+a).attr('checked') ) {	
				var allocPerParticipant = 0;
				ctr++;	
				if (ctr!=noPar) {
					allocPerParticipant = amount * (percent/100);
					allocPerParticipant = Math.round(allocPerParticipant*100)/100;
				} else {
					allocPerParticipant = amount - accumulatedFund;
					allocPerParticipant = Math.round(allocPerParticipant*100)/100;
				}
				accumulatedFund += allocPerParticipant;	
				$('#txt_'+a).val(allocPerParticipant);
				$('#dvTotDtdl').html(amount);	
			}
		}	
	}	
	function addDecimal(amt){
		amt = Math.round(amt * 100) / 100;
		return amt;
	}
	function computeMonthly2(id){
		var unitAmt = $("#txtUnitAmt_"+id).val();
		var noUnit = $("#txtNoUnits_"+id).val();
		var vat = parseFloat($("#hdnVat").val());
		var ewt = parseFloat($("#hdnEwt").val());
		
		$("#txtMonthly_"+id).val(unitAmt * noUnit);
		
		$('#txtVatAmt_'+id).val(addDecimal(parseFloat($('#txtMonthly_'+id).val())*parseFloat(vat)));
		$('#txtEwtAmt_'+id).val(addDecimal($('#txtMonthly_'+id).val()*ewt*-1));
		$('#txtSubAmt_'+id).val(addDecimal(parseFloat($('#txtMonthly_'+id).val())+($('#txtMonthly_'+id).val()*vat)));
	}
</script>
<style type="text/css">
<!--
.txtInv {
	visibility:hidden;
}

.txtVis2 {
	visibility:visible;
}
.textBox2 {
	border: solid #999999; 
	border-width: 1px; 
	width:100px; 
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
.selectBox2 {
	border: 1px solid #222; 
	width:192px; 
	height:22px;
	font-size: 11px;
}
.selectBox3 {
	border: 1px solid #222; 
	width:100px; 
	height:18px;
	font-size: 11px;
}
legend {
	color:#F60;	
}
.hd2 {
	font-size: 9px;
	font-family: Verdana;
	font-weight: bold;
}
.textBoxTemp {
	border: solid 1px #222; 
	border-width: 1px; 
	width:140px; 
	height:14px;
	font-size: 11px;
}
.textBoxTemp2 {
	border: solid 1px #222; 
	border-width: 1px; 
	width:80px; 
	height:14px;
	font-size: 11px;
}
.textBoxTempSmall {
	border: solid 1px #222; 
	border-width: 1px; 
	width:40px; 
	height:14px;
	font-size: 11px;
}
.line { 
	border-bottom: solid 1px; 
	border-bottom-color: #C3C3C3;
}
-->
</style>         
</head>

<body>
	<?
    if ($_POST['act']=='Details') {
		
		if($daObj->daExists($_POST['refNo'])>0){
			$assocHdr = $daObj->getDaHeader($_POST['refNo']);
			echo "<script>$('#txtDispTyp').val('{$assocHdr['displayType']}');</script>\n";
			echo "<script>$('#txtBrand').val('{$assocHdr['brand']}');</script>\n";
			echo "<script>$('#txtLoc').val('{$assocHdr['location']}');</script>\n";
			echo "<script>$('#txtRem').val('{$assocHdr['daRemarks']}');</script>\n";
		}
		$stsRefNo = $_POST['refNo'];
		$arrSTS = $daObj->getSTSInfo($stsRefNo);
		$arrBranches =  $daObj->getFilteredBranches($_POST['compCode'],$_POST['refNo']);
		$DtlAmt = $arrSTS['stsAmt'];
		
		if($_POST['vatTag']=='N'){
			$ewt = 0;	
			$vat = 0;
		}else{
			$ewt = $daObj->getEwt(11,0,0);
			$vat = $daObj->getVat();
		}
		
	?>
	<form id="formSTSDtl" name="formSTSDtl">
	<div class="headerContentDtl ui-corner-all">
    	<fieldset>
        <legend>HEADER</legend>
    	<table width="90%" border="0" cellspacing="0" cellpadding="2" align="center">
        	<tr>
             	<td class="hd">Display Type</td>
                 <td><? $daObj->DropDownMenu($daObj->makeArr($daObj->getDisplayType(),'displayTypeId','displayTypeDesc',''),'txtDispTyp','','class="selectBox2"'); ?></td>
            
                <td class="hd">Brand Name</td>
                <td><input type="text" name="txtBrand" id="txtBrand" class="textBox"/></td>
            </tr>
            <tr>
                <td class="hd">Location</td>
                <td><input type="text" name="txtLoc" id="txtLoc" class="textBox"/></td>
           
            	<td class="hd">Remarks</td>
                <td><input type="text" name="txtRem" id="txtRem" class="textBox"/></td>
            </tr>
        </table>
        </fieldset>
       <fieldset><legend>TEMPLATE</legend>
       <table width="100%" border="0" cellspacing="0" cellpadding="2" align="center">
        <tr>
                <td class="hd2">Size Specs</td>
                <td><? $daObj->DropDownMenu($daObj->makeArr($daObj->getSizeSpecs(),'sizeSpecsId','sizeSpecsDesc',''),'txtSizeSpecs','','class="selectBox3"'); ?></td>
           
                <td class="hd2">Display Specs</td>
                <td><? $daObj->DropDownMenu($daObj->makeArr($daObj->getDisplaySpecs(),'displaySpecsId','displaySpecsDesc',''),'txtDispSpecs','','class="selectBox3"'); ?></td>
          
                <td class="hd2">Display Fee per Unit</td>
                <td><input type="text" name="txtUnitAmt" id="txtUnitAmt" class="textBoxTemp" onKeyPress='return valNumInputDec(event);' style="text-align:right" onChange="computeMonthly();"/></td>
          <tr/>
           <tr> 
                <td class="hd2">No. of Units</td>
                <td><input type="text" name="txtNoUnits" id="txtNoUnits" class="textBoxTemp" onChange="computeMonthly();" onKeyPress='return valNumInputDec(event); ' style="text-align:right"/></td>
                <td class="hd2">Total Display Per Month</td>
                <td><input type="text" name="txtMonthly" id="txtMonthly" class="textBoxTemp" onKeyPress='return valNumInputDec(event);' style="text-align:right"/></td>
                <td colspan="2" align="center">
                	<input type="button" id="btnApplyAll" name="btnApplyAll" value="Apply To All" onClick="applySelected();" style="color:#f20;">
                </td>
            </tr> 
        </table>
        </fieldset>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="details">
          <tr>
          	<td colspan="6">
            	<?
				$ch = 0;
             	$arrCompanies = $daObj->getDistinctCompanies();
			  foreach($arrCompanies as $valComp) {
				  	if($ch==0) {
						$bar = "";
						$ch++;
					} else {
						$bar = " | ";
					}
				?>
                <?=$bar;?><span class="link" style="color:#F60;" onClick="AddSTSDtl(<?=$valComp['compCode'];?>)"><?=$valComp['compShort'];?></span>
                <? }?>
                 |&nbsp;<span class="link" onClick="AddSTSDtl('undefined')" style="color:#F60;" ><span style="font-family: Verdana;">All</span></span> 
            </td>
            <td>&nbsp;
            </td>
          </tr>
         
          <tr>
            <td width="1%" height="25"><input type="checkbox" name="chAll" onClick="checkAllDtl();"  id="chAll"></td>
              <td width="22%"><strong>Branch
              	<input type="hidden" name="hdnEwt" id="hdnEwt" value="<?=$ewt?>" />
                <input type="hidden" name="hdnVat" id="hdnVat" value="<?=$vat?>" />
				<input type="hidden" name="hdDtl_Amt" value="<?=$arrSTS['stsAmt']?>" id="hdDtl_Amt" />
				<input type="hidden" name="hdnEnhanceType" value="<?=$arrSTS['enhanceType']?>" id="hdnEnhanceType" />
                <input type="hidden" name="hdDtl_refNo" value="<?=$_POST['refNo']?>" id="hdDtl_refNo" />
				<input type="hidden" name="hdDtl_TotAmt" value="<?=(float)$DtlAmt?>" id="hdDtl_TotAmt" />
                <input type="hidden" name="hdClipTxt" value="" id="hdClipTxt" />
            </strong></td>
            <td align="center"><strong>Size Specs</strong></td>
            <td align="center"><strong>Display Specs</strong></td>
            <td align="center"><strong>Disp. Fee per Unit</strong></td>
            <td align="center"><strong>No of Units</strong></td>
            <td align="center"><strong>Total per Mo.</strong></td>
             <td align="center"><strong>VAT Amount</strong></td>
            <td align="center"><strong>Gross Amount</strong></td>
          </tr>
            <tr>
            	<td colspan="2" height="5"></td>
            </tr>
            <?
			$i = 0;
            foreach($arrBranches as $val) {
				$checked = ($val['stsAmt'] != '') ? 'checked':'';
           		$readonly = ($val['stsAmt'] != '') ? "class='textBox2 enabletxt' value=".$val['stsAmt']:" readonly class='textBox2'";
				$txtInv =  ($val['stsAmt'] != '') ? "":"txtInv";
				$switcher_val =  ($val['stsAmt'] != '') ? "1":"0";
				
				$readonly2 = ($val['stsVatAmt'] != '') ? "value=".$val['stsVatAmt'] : "readonly";
				$readonly3 = ($val['stsEwtAmt'] != '') ? "value=".$val['stsEwtAmt'] : "readonly";
				$readonly4 = ($val['stsAmt'] != '') ? "value=".round($val['stsAmt']+$val['stsVatAmt'],2):" readonly";
				
			?>
            <tr>
              
              <td class=""><input type="checkbox" <?=$checked?>  name="ch_<?=$i;?>" onClick="ToggleBranch2('<?=$i;?>');" value="<?=$val['strCode'];?>" id="ch_<?=$i;?>"></td>
              <input type="hidden" name="switcher_<?=$i;?>" id="switcher_<?=$i;?>" value='<?=$switcher_val?>'/>
              <input type="hidden" name="comp_<?=$i;?>" id="comp_<?=$i;?>" value="<?=$val['compCode'];?>" />
              <td height="25" class="hd2"><?=$val['brnDesc'];?></td>
              
              <td align="center"><? $daObj->DropDownMenu($daObj->makeArr($daObj->getSizeSpecs(),'sizeSpecsId','sizeSpecsDesc',''),'txtSizeSpecs_'.$i,''.$val['daSize'].'','class="selectBox3 '.$txtInv.'"'); ?></td>
              <td align="center"><? $daObj->DropDownMenu($daObj->makeArr($daObj->getDisplaySpecs(),'displaySpecsId','displaySpecsDesc',''),'txtDispSpecs_'.$i,''. $val['dispSpecs'].'','class="selectBox3 '.$txtInv.'"'); ?></td>	
              
              <td align="center"><input type="text" name="txtUnitAmt_<?=$i;?>" id="txtUnitAmt_<?=$i;?>" class="textBoxTemp2  <?=$txtInv?>" onKeyPress='return valNumInputDec(event);' onChange="computeMonthly2('<?=$i;?>');" style="text-align:right"  value="<?=$val['perUnitAmt'];?>" /></td>
              <td align="center"><input type="text" name="txtNoUnits_<?=$i;?>" id="txtNoUnits_<?=$i;?>" class="textBoxTempSmall <?=$txtInv?>" onChange="computeMonthly2('<?=$i;?>');" onKeyPress='return valNumInputDec(event); ' style="text-align:right;"  value="<?=$val['noUnits'];?>"/></td>
              <td align="center"><input type="text" name="txtMonthly_<?=$i;?>" id="txtMonthly_<?=$i;?>" class="textBoxTemp2 <?=$txtInv?>" onKeyPress='return valNumInputDec(event);' style="text-align:right" value="<?=$val['stsAmt'];?>"/></td>
              
              <td><input name="txtVatAmt_<?=$i;?>"  type="text" onKeyPress="return valNumInputDec(event)" onChange="ComputeDetails();" <?=$readonly2?> id="txtVatAmt_<?=$i;?>" style="text-align:right" size="12" class="<?=$txtInv?>"></td>
              <input name="txtEwtAmt_<?=$i;?>"  type="hidden" onKeyPress="return valNumInputDec(event)" onChange="ComputeDetails();" <?=$readonly3?> id="txtEwtAmt_<?=$i;?>" style="text-align:right" size="12" class="<?=$txtInv?>">
              <td><input name="txtSubAmt_<?=$i;?>"  type="text" onKeyPress="return valNumInputDec(event)" onChange="ComputeDetails();" <?=$readonly4?> id="txtSubAmt_<?=$i;?>" style="text-align:right" size="12" class="<?=$txtInv?>"></td>
            </tr>
            <?
				$i++;
            }
			?>
            
          </table>  
      <input type="hidden" name="hdCtr" id="hdCtr" value="<?=($i-1);?>" />
    </div>
      </form>
      <?} else {?>
<?}?>   

</body>
</html>
