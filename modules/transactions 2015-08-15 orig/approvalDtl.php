<?
session_start();

include("../../includes/db.inc.php");
include("../../includes/common.php");
include("approvalObj.php");
$approvalObj = new approvalObj();

switch($_GET['act']) {
	
	case "postInvoice":
			$arr = $approvalObj->approveSTS($_GET);
			$res = explode("|",$arr);
			
			//echo "_alert('ALERT','Total of records posted: ".$res[2]." <br\> Invoice No: ".$res[3]." <br/> Total of records with error: ".$res[1]." <br\> Invoice No: ".$res[0]." ');";
			echo "dialogAlert('Approved STS #s ".$res[1]." <br/>Some reference with no details (errors): ".$res[0]."');\n";
	exit();
	break;
}
?>
<html>
<head>
<title>Untitled Document</title>
<script type="text/javascript">
	function ToggleBranch2(id) {
		var chckBox = $('#refNo_'+id);
		if (chckBox.attr('checked')) {
			$('#switcher_'+id).val("1");
		} else {
			$('#switcher_'+id).val("0");
		}
	}
	function checkAllDtl2() {
		var chldCnt = $('#hdCtr2').val();
		if ($('#chAll2').attr('checked')) {
			for(i=0;i<=chldCnt;i++){
				$('#refNo_'+i).attr('checked','true');
				$('#cmbEnhancer_'+i).removeClass("txtInv");
				$('#cmbEnhancer_'+i).addClass('txtVis2');
				$('#switcher_'+i).val("1");
			}
		} else {
			for(i=0;i<=chldCnt;i++){
				$('#refNo_'+i).attr('checked',false);
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
	border: solid #999999; 
	border-width: 1px; 
	width:100px; 
	height:18px;
	font-size: 11px;
}
.textBoxLong {
	border: solid #999999; 
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
    if ($_GET['action']=='viewToPrint') {
		$arrInvoices = $approvalObj->getStsForApproval($_GET['suppCode']);
	?>
	<form id="formInv" name="formInv">
   		<input type="hidden" name="hdnSuppCode" value="<?=$_GET['suppCode']?>" id="hdnSuppCode" />
        <table width="100%" border="0" cellspacing="0" cellpadding="0" id='contractList' class='display'>
          <thead> 
          <tr>
            <th width="6%" height="25"><input type="checkbox" name="chAll2" onClick="checkAllDtl2();"  id="chAll2"></th>
            <th width="15%"><strong>Ref. No.</strong></th>
          
            <th><strong>Supplier</strong></th>
            <th align="center"><strong>Total Amount</strong></th>
            <th align="center"><strong>Entered By</strong></th>
            <th align="center"><strong>Date Entered</strong></th>
            <th align="center"><strong>Application Date</strong></th>
          </tr>
           </thead> 
            <?
			$u = 0;
            foreach($arrInvoices as $val) {
			?>
            <tr  style='font: Verdana; font-size:11px; height:25px;' class='gradeA'  align='center'>
              <td class=""><input type="checkbox" <?=$checked?>  name="refNo_<?=$u;?>" value="<?=$val['stsRefno'];?>" id="refNo_<?=$u;?>" onClick="ToggleBranch2('<?=$u;?>');" /></td>
              <td height="25" align="center"><?=$val['stsRefno'];?></td>
              <input type="hidden" name="hdnSuppCode_<?=$u;?>" id="hdnSuppCode_<?=$u;?>" value="<?=$val['suppCode'];?>"/>
              <input type="hidden" name="switcher_<?=$u;?>" id="switcher_<?=$u;?>" value="0"/>
              <td align="left"><?=$val['suppCode'].'-'.$val['suppName'];?></td>
              <td align="right"><?=number_format($val['stsAmt'],2);?></td>
              <td align="right"><?=$val['fullName'];?></td>
              <td align="right"><?=date('M-d-Y',strtotime($val['dateEntered']));?></td>
              <td align="right"><?=date('M-d-Y',strtotime($val['applyDate']));?></td>
            </tr>
            <?
				$u++;
            }
			?>
          </table>  
      <input type="hidden" name="hdCtr2" id="hdCtr2" value="<?=($u-1);?>" />
  
      </form>
      <? } else {?>
<? }
/*function findInvoiceNos($refNo,$strCode,$suppCode){
	$approvalObj = new approvalObj();
	$invNos = $mySqlObj->getInvoiceNosCom($refNo,$strCode,$suppCode);
	foreach($invNos as $valInv){
		$inv .= "[ ".$valInv['invNo']." ]";
	}
	return $inv;
}*/
?>   

</body>
</html>
