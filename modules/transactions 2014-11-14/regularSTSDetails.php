<?
session_start();

include("../../includes/db.inc.php");
include("../../includes/common.php");

include("transObj.php");
$transObj = new transObj();

if ($_SESSION['sts-username'] == "") {
	echo "<SCRIPT>window.parent.location.href='../../index.php';</SCRIPT>";
}

switch($_GET['action']) {
	
	case "AddSTSDtl":
		$total = 0;
		for($i=0;$i<=(float)$_GET['hdCtr'];$i++) {
			if($_GET["txt_$i"]!="") {
				$total += (float)$_GET["txt_$i"];
			}
		}
		$dtlAmt = $_GET['hdDtl_TotAmt'];		
		if(sprintf('%01.2f', $total) == sprintf('%01.2f', $_GET["hdDtl_Amt"])){
			if($transObj->AddSTSDtl($_GET)) {
				echo '$("#divSTSDtls").dialog("close");';
				echo "dialogAlert('STS Detail successfully added.');\n";
			} else {
				echo "dialogAlert('Error adding STS Detail.');";
			}
		} else {
			echo "dialogAlert('STS Header Amount is not equal to STS Details Total Amount.','dialogAlertFunds');";
		}
		exit();
	break;
	case "Delete":
			if($transObj->DeleteSTSDtl($_GET['refNo'],$_GET['compCode'])) {
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
		if (chckBox.attr('checked')) {
			$('#txt_'+id).attr('readonly',false);
			$('#txt_'+id).addClass('enabletxt');
			$('#cmb_'+id).attr('disabled',false);
			$('#cmb_'+id).addClass('enabletxt');
			$('#imgCopy_'+id).removeClass("txtInv");
			$('#imgCopy_'+id).addClass('txtVis');
			$('#imgPaste_'+id).removeClass("txtInv");
			$('#imgPaste_'+id).addClass('txtVis');
			$('#imgCopy_'+id).attr("onClick","CopyTxt('txt_"+id+"','hdClipTxt')");
			$('#imgPaste_'+id).attr("onClick","PasteTxt('hdClipTxt','txt_"+id+"')");
			ComputeDetails();
		} else {
			$('#txt_'+id).attr('readonly','true');
			$('#txt_'+id).removeClass('enabletxt');
			$('#cmb_'+id).attr('disabled','true');
			$('#cmb_'+id).removeClass('enabletxt');
			$('#txt_'+id).val('');
			$('#imgCopy_'+id).removeClass("txtVis");
			$('#imgCopy_'+id).addClass('txtInv');
			$('#imgPaste_'+id).removeClass("txtVis");
			$('#imgPaste_'+id).addClass('txtInv');
			$('#imgCopy_'+id).attr("onClick","");
			$('#imgPaste_'+id).attr("onClick","");
			ComputeDetails();
		}
		
	}
	function checkAllDtl() {
		$('#dvTotDtdl').html("");
		var chldCnt = $('#hdCtr').val();
		if ($('#chAll').attr('checked')) {
			for(i=0;i<=chldCnt;i++){
				$('#ch_'+i).attr('checked','true');
				$('#txt_'+i).attr('readonly',false);
				$('#txt_'+i).addClass('enabletxt');
				$('#imgCopy_'+i).removeClass("txtInv");
				$('#imgCopy_'+i).addClass('txtVis');
				$('#imgPaste_'+i).removeClass("txtInv");
				$('#imgPaste_'+i).addClass('txtVis');
				$('#imgCopy_'+i).attr("onClick","CopyTxt('txt_"+i+"','hdClipTxt')");
				$('#imgPaste_'+i).attr("onClick","PasteTxt('hdClipTxt','txt_"+i+"')");
			}
		} else {
			for(i=0;i<=chldCnt;i++){
				$('#ch_'+i).attr('checked',false);
				$('#txt_'+i).val('');
				$('#txt_'+i).attr('readonly','true');
				$('#txt_'+i).removeClass('enabletxt');
				$('#imgCopy_'+i).removeClass("txtVis");
				$('#imgCopy_'+i).addClass('txtInv');
				$('#imgPaste_'+i).removeClass("txtVis");
				$('#imgPaste_'+i).addClass('txtInv');
				$('#imgCopy_'+i).attr("onClick","");
				$('#imgPaste_'+i).attr("onClick","");
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
		
		$stsRefNo = $_GET['stsRefNo'];
		$arrSTS = $transObj->getSTSInfo($stsRefNo,$_GET['compCode']);
		$arrBranches =  $transObj->getFilteredBranches($_GET['compCode']);
		$DtlAmt = $arrSTS['stsAmt'];
		
	?>
	<form id="formSTSDtl" name="formSTSDtl">
	<div class="headerContentDtl ui-corner-all">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="25" colspan="2"><span  class="link" onClick="Allocate();">Allocate Equally</span></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="6%" height="25"><input type="checkbox" name="chAll" onClick="checkAllDtl();"  id="chAll"></td>
              <td width="57%"><strong>Branch
				<input type="hidden" name="hdDtl_Amt" value="<?=$arrSTS['stsAmt']?>" id="hdDtl_Amt" />
				<input type="hidden" name="hdDtl_refNo" value="<?=$_GET['stsRefNo']?>" id="hdDtl_refNo" />
                <input type="hidden" name="hdCompCode" value="<?=$_GET['compCode']?>" id="hdCompCode" />
				<input type="hidden" name="hdDtl_TotAmt" value="<?=(float)$DtlAmt?>" id="hdDtl_TotAmt" />
                <input type="hidden" name="hdClipTxt" value="" id="hdClipTxt" />
            </strong></td>
            <td width="37%"><strong>Amount</strong></td>
          </tr>
            <tr>
            	<td colspan="3" height="5"></td>
            </tr>
            <?
			$i = 0;
            foreach($arrBranches as $val) {
			?>
            <tr>
              <td class=""><input type="checkbox" name="ch_<?=$i;?>" onClick="ToggleBranch('<?=$i;?>');" value="<?=$val['brnCode'];?>" id="ch_<?=$i;?>"></td>
              <td height="25"><?=$val['brnDesc'];?></td>
              <td><input name="txt_<?=$i;?>" readonly type="text" onKeyPress="return valNumInputDec(event)" onChange="ComputeDetails();" class="textBox2" id="txt_<?=$i;?>">&nbsp;<img src="../../images/copy.png" id='imgCopy_<?=$i;?>' width="16" class="txtInv" height="16" >&nbsp;<img src="../../images/paste_plain.png" id='imgPaste_<?=$i;?>' style="cursor:pointer;" class="txtInv"  width="16" height="16" onClick="PasteTxt('hdClipTxt','txt_<?=$i;?>');"></td>
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
