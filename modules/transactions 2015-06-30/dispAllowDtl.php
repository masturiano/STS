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
	
	case "AddSTSDtl":
		$total = 0;
		for($i=0;$i<=(float)$_POST['hdCtr'];$i++) {
			if($_POST["txt_$i"]!="") {
				$total += (float)$_POST["txt_$i"];
			}
		}
		$dtlAmt = $_POST['hdDtl_TotAmt'];		
		//if(sprintf('%01.2f', $total) == sprintf('%01.2f', $_POST["hdDtl_Amt"])){
			if($allowanceObj->AddSTSDtl($_POST)) {
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
			if($allowanceObj->DeleteSTSDtl($_POST['refNo'])) {
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
			/*$('#imgCopy_'+id).removeClass("txtInv");
			$('#imgCopy_'+id).addClass('txtVis');
			$('#imgPaste_'+id).removeClass("txtInv");
			$('#imgPaste_'+id).addClass('txtVis');
			$('#imgCopy_'+id).attr("onClick","CopyTxt('txt_"+id+"','hdClipTxt')");
			$('#imgPaste_'+id).attr("onClick","PasteTxt('hdClipTxt','txt_"+id+"')");*/
			//$('#cmbEnhancer_'+id).removeClass("txtInv");
			//$('#cmbEnhancer_'+id).addClass('txtVis2');
			//$('#cmbEnhancer_'+id).val(""+enhance);
			ComputeDetails();
		} else {
			$('#txt_'+id).attr('readonly','true');
			$('#txt_'+id).removeClass('enabletxt');
			$('#cmb_'+id).attr('disabled','true');
			$('#cmb_'+id).removeClass('enabletxt');
			$('#txt_'+id).val('');
			$('#txtVatAmt_'+id).val('');
			$('#txtEwtAmt_'+id).val('');
			$('#txtSubAmt_'+id).val('');
			
			/*$('#imgCopy_'+id).removeClass("txtVis");
			$('#imgCopy_'+id).addClass('txtInv');
			$('#imgPaste_'+id).removeClass("txtVis");
			$('#imgPaste_'+id).addClass('txtInv');
			$('#imgCopy_'+id).attr("onClick","");
			$('#imgPaste_'+id).attr("onClick","");*/
			//$('#cmbEnhancer_'+id).removeClass("txtVis2");
			//$('#cmbEnhancer_'+id).addClass('txtInv');
			ComputeDetails();
		}
		
	}
	function checkAllDtl() {
		$('#dvTotDtdl').html("");
		var chldCnt = $('#hdCtr').val();
		//var enhance = $('#hdnEnhanceType').val();
		if ($('#chAll').attr('checked')) {
			for(i=0;i<=chldCnt;i++){
				$('#ch_'+i).attr('checked','true');
				$('#txt_'+i).attr('readonly',false);
				$('#txt_'+i).addClass('enabletxt');
				/*$('#imgCopy_'+i).removeClass("txtInv");
				$('#imgCopy_'+i).addClass('txtVis');
				$('#imgPaste_'+i).removeClass("txtInv");
				$('#imgPaste_'+i).addClass('txtVis');*/
				//$('#cmbEnhancer_'+i).removeClass("txtInv");
				//$('#cmbEnhancer_'+i).addClass('txtVis2');
				//$('#cmbEnhancer_'+i).val(""+enhance);
				/*$('#imgCopy_'+i).attr("onClick","CopyTxt('txt_"+i+"','hdClipTxt')");
				$('#imgPaste_'+i).attr("onClick","PasteTxt('hdClipTxt','txt_"+i+"')");*/
			}
		} else {
			for(i=0;i<=chldCnt;i++){
				$('#ch_'+i).attr('checked',false);
				$('#txt_'+i).val('');
				$('#txt_'+i).attr('readonly','true');
				$('#txt_'+i).removeClass('enabletxt');
				
				$('#txtVatAmt_'+i).val('');
				$('#txtEwtAmt_'+i).val('');
				$('#txtSubAmt_'+i).val('');
				
				/*$('#imgCopy_'+i).removeClass("txtVis");
				$('#imgCopy_'+i).addClass('txtInv');
				$('#imgPaste_'+i).removeClass("txtVis");
				$('#imgPaste_'+i).addClass('txtInv');
				$('#imgCopy_'+i).attr("onClick","");
				$('#imgPaste_'+i).attr("onClick","");*/
				//$('#cmbEnhancer_'+i).removeClass("txtVis2");
				//$('#cmbEnhancer_'+i).addClass('txtInv');
				$('#txtExpDtlSum').val(0);
			}
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
	function addDecimal(amt){
		amt = Math.round(amt * 100) / 100;
		return amt;
	}
	function reCompute(i){
		var vat = parseFloat($("#hdnVat").val());
		var ewt = parseFloat($("#hdnEwt").val());
		
		$('#txtVatAmt_'+i).val(addDecimal(parseFloat($('#txt_'+i).val())*parseFloat(vat)));
		$('#txtEwtAmt_'+i).val(addDecimal($('#txt_'+i).val()*ewt*-1));
		$('#txtSubAmt_'+i).val(addDecimal(parseFloat($('#txt_'+i).val())+($('#txt_'+i).val()*vat)));
	}
	function Allocate() {
		
		var counter = $('#hdCtr').val();
		var amount = $('#hdDtl_Amt').val();
		
		var noPar = percent = ctr = allocPerParticipant = accumulatedFund = accumulatedVat = accumulatedTax = vatTotamt=taxTotamt=0;
		
		var vat = parseFloat($("#hdnVat").val());
		var ewt = parseFloat($("#hdnEwt").val());
		
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
					
					vatamt = Math.round(allocPerParticipant*vat*100)/100;
					taxamt = Math.round(allocPerParticipant*ewt*100)/100;
					
				} else {
					allocPerParticipant = amount - accumulatedFund;
					
					vatamt = (amount*vat)-accumulatedVat;
					vatamt = Math.round(vatamt*100)/100;
					
					taxamt = (amount*ewt)-accumulatedTax;
					taxamt = Math.round(taxamt*100)/100;
					
					allocPerParticipant = Math.round(allocPerParticipant*100)/100;
				}
				//accumulatedFund += allocPerParticipant;	
				
				accumulatedFund += allocPerParticipant;	
				accumulatedVat += vatamt;	
				accumulatedTax += taxamt;	
				$('#txt_'+a).val(allocPerParticipant);
				vatTotamt += vatamt;
				taxTotamt += taxamt;				
				$('#txtVatAmt_'+a).val(vatamt+'');
				$('#txtEwtAmt_'+a).val(taxamt*-1+'');
				
				/*$('#txt_'+a).val(allocPerParticipant);
				$('#txtVatAmt_'+a).val(addDecimal(parseFloat($('#txt_'+a).val())*parseFloat(vat)));
				$('#txtEwtAmt_'+a).val(addDecimal($('#txt_'+a).val()*ewt*-1));*/
				$('#txtSubAmt_'+a).val(addDecimal(allocPerParticipant+vatamt));
		
				$('#dvTotDtdl').html(amount);	
			}
		}	
		
		/*var counter = $('#hdCtr').val();
		var amount = $('#hdDtl_Amt').val();
		
		var noPar = percent = ctr = allocPerParticipant = accumulatedFund = 0;
		var vat = parseFloat($("#hdnVat").val());
		var ewt = parseFloat($("#hdnEwt").val());
		
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
				$('#txtVatAmt_'+a).val(addDecimal(parseFloat($('#txt_'+a).val())*parseFloat(vat)));
				$('#txtEwtAmt_'+a).val(addDecimal($('#txt_'+a).val()*ewt*-1));
				$('#txtSubAmt_'+a).val(addDecimal(parseFloat($('#txt_'+a).val())+($('#txt_'+a).val()*vat)));
		
				$('#dvTotDtdl').html(amount);	
			}
		}	*/
	}	
	
</script>
<style type="text/css">
<!--
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
-->
</style>         
</head>

<body>
	<?
    if ($_POST['act']=='Details') {
		
		$stsRefNo = $_POST['refNo'];
		$arrSTS = $allowanceObj->getSTSInfo($stsRefNo);
		$arrBranches =  $allowanceObj->getFilteredBranches($_POST['compCode'],$_POST['refNo']);
		$DtlAmt = $arrSTS['stsAmt'];
		
		if($_POST['vatTag']=='N'){
			$ewt = 0;	
			$vat = 0;
		}else{
			$ewt = $allowanceObj->getEwt($_POST['idept'],$_POST['iclass'],$_POST['isubClass']);
			$vat = $allowanceObj->getVat();
		}
	?>
	<form id="formSTSDtl" name="formSTSDtl">
	<div class="headerContentDtl ui-corner-all">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
          	<td colspan="2">
            
            	<?
                /* Commented by Mydel 
				$ch = 0;
             	$arrCompanies = $allowanceObj->getDistinctCompanies();
               
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
                End of Comment*/
                ?>
                 &nbsp;<span class="link" onClick="AddSTSDtl('undefined')" style="color:#F60;" ><span style="font-family: Verdana;">All Puregold</span></span>
                 |&nbsp;<span class="link" onClick="AddSTSDtl('ne')" style="color:#F60;" ><span style="font-family: Verdana;">All NE</span></span> 
            </td>
            <td>&nbsp;
            </td>
          </tr>
          <tr>
            <td height="25" colspan="2"><span  class="link" onClick="Allocate();" style="color:#F60;">Allocate Equally</span></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="6%" height="25"><input type="checkbox" name="chAll" onClick="checkAllDtl();"  id="chAll"></td>
              <td width="47%"><strong>Branch
             	<input type="hidden" name="hdnEwt" id="hdnEwt" value="<?=$ewt?>" />
                <input type="hidden" name="hdnVat" id="hdnVat" value="<?=$vat?>" />
				<input type="hidden" name="hdDtl_Amt" value="<?=$arrSTS['stsAmt']?>" id="hdDtl_Amt" />
				<input type="hidden" name="hdnEnhanceType" value="<?=$arrSTS['enhanceType']?>" id="hdnEnhanceType" />
                <input type="hidden" name="hdDtl_refNo" value="<?=$_POST['refNo']?>" id="hdDtl_refNo" />
				<input type="hidden" name="hdDtl_TotAmt" value="<?=(float)$DtlAmt?>" id="hdDtl_TotAmt" />
                <input type="hidden" name="hdClipTxt" value="" id="hdClipTxt" />
            </strong></td>
            <td><strong>Amount</strong></td>
            <td align="center"><strong>VAT Amount</strong></td>
            <td align="center" style="visibility:hidden"><strong>EWT Amount</strong></td>
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
				
				
				$readonly2 = ($val['stsVatAmt'] != '') ? "value=".$val['stsVatAmt']:" readonly";
				$readonly3 = ($val['stsEwtAmt'] != '') ? "value=".$val['stsEwtAmt']:" readonly";
				$readonly4 = ($val['stsAmt'] != '') ? "value=".round($val['stsAmt']+$val['stsVatAmt'],2):" readonly";
				
			?>
            <tr>
              
              <td class=""><input type="checkbox" <?=$checked?>  name="ch_<?=$i;?>" onClick="ToggleBranch('<?=$i;?>');" value="<?=$val['strCode'];?>" id="ch_<?=$i;?>"></td>
              <input type="hidden" name="comp_<?=$i;?>" id="comp_<?=$i;?>" value="<?=$val['compCode'];?>" />
              <td height="25"><?=$val['brnDesc'];?></td>
              <td><input name="txt_<?=$i;?>"  type="text" onKeyPress="return valNumInputDec(event)" onChange="ComputeDetails();" onKeyUp="reCompute('<?=$i?>');" <?=$readonly?> id="txt_<?=$i;?>" style="text-align:right" />&nbsp;</td>
              <td><input name="txtVatAmt_<?=$i;?>"  type="text" onKeyPress="return valNumInputDec(event)" onChange="ComputeDetails();" <?=$readonly2?> id="txtVatAmt_<?=$i;?>" style="text-align:right" size="12"></td>
              <td style="visibility:hidden"><input name="txtEwtAmt_<?=$i;?>"  type="text" onKeyPress="return valNumInputDec(event)" onChange="ComputeDetails();" <?=$readonly3?> id="txtEwtAmt_<?=$i;?>" style="text-align:right" size="12"></td>
              <td><input name="txtSubAmt_<?=$i;?>"  type="text" onKeyPress="return valNumInputDec(event)" onChange="ComputeDetails();" <?=$readonly4?> id="txtSubAmt_<?=$i;?>" style="text-align:right" size="12"></td>
             	
            </tr>
            <?
				$i++;
            }
			?>
            <tr>
              <td class=""></td>
              <td height="25"><strong>TOTAL</strong></td>
              <td><div id="dvTotDtdl" style="font-weight:bold; text-align:center;"></div></td>
            </tr>
          </table>  
      <input type="hidden" name="hdCtr" id="hdCtr" value="<?=($i-1);?>" />
    </div>
      </form>
      <?} else {?>
<?}?>   

</body>
</html>
