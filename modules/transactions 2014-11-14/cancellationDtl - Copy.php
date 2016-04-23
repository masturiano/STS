<?
session_start();

include("../../includes/db.inc.php");
include("../../includes/common.php");
include("cancellationObj.php");
$cancelObj = new cancelObj();

switch($_POST['act']) {
	
	case "cancelStsDtl":
			if($cancelObj->cancelStsDtl($_POST)){
				echo "dialogAlert('Cancellation Succeeded!');\n";
			}else{
				echo "dialogAlert('There was an error during the cancellation!');\n";
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
		var chckBox = $('#stsNo_'+id);
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
				$('#stsNo_'+i).attr('checked','true');
				$('#cmbEnhancer_'+i).removeClass("txtInv");
				$('#cmbEnhancer_'+i).addClass('txtVis2');
				$('#switcher_'+i).val("1");
			}
		} else {
			for(i=0;i<=chldCnt;i++){
				$('#stsNo_'+i).attr('checked',false);
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
    if ($_GET['action']=='viewToCancel') {
		$arrInvoices = $cancelObj->getAprrovedSTSDtl($_GET['refNo']);
	?>
	<form id="formInv" name="formInv">
   		<input type="hidden" name="hdnRefno" value="<?=$_GET['refNo']?>" id="hdnRefno" />
        <table width="100%" border="0" cellspacing="0" cellpadding="0" id='contractList' class='display'>
          <thead> 
          <tr style='font: Verdana; font-size:9px; height:25px;'>
            <th width="3%" height="25"><input type="checkbox" name="chAll2" onClick="checkAllDtl2();"  id="chAll2"></th>
            <th width="10%"><strong>STS Ref No.</strong></th>
            <th width="10%"><strong>STS No.</strong></th>
            <th><strong>STS Amount</strong></th>
            <th align="center"><strong>Supplier</strong></th>
            <th align="center"><strong>Store</strong></th>
             <th align="center"><strong>App Date</strong></th>
            <th align="center"><strong>No of Aps.</strong></th>
            <th align="center"><strong>Uploaded Amt.</strong></th>
            <th align="center"><strong>On Queue Amt.</strong></th>
          </tr>
           </thead> 
            <?
			$u = 0;
            foreach($arrInvoices as $val) {
			?>
            <tr  style='font: Verdana; font-size:9px; height:25px;' class='gradeK'  align='center'>
              <td class=""><input type="checkbox" <?=$checked?>  name="stsNo_<?=$u;?>" value="<?=$val['stsNo'];?>" id="stsNo_<?=$u;?>" onClick="ToggleBranch2('<?=$u;?>');" /></td>
              <td height="5" align="center"><?=$val['stsRefno'];?></td>
              <td height="5" align="center"><?=$val['stsNo'];?></td>
              <input type="hidden" name="switcher_<?=$u;?>" id="switcher_<?=$u;?>" value="0"/>
              <td align="right"><?=number_format($val['stsAmt'],2);?></td>
              <td align="left"><?=$val['ASNUM'].'-'.$val['ASNAME'];?></td>
              <td align="left"><?=$val['strCode'].'-'.$val['brnDesc'];?></td>
               <td align="right"><?=date('m/d/Y',strtotime($val['applyDate']));?></td>
               <td align="right"><?=$val['nbrApplication'];?></td>
              <td align="right"><?=number_format($val['uploadedAmt'],2);?></td>
              <td align="right"><?=number_format($val['onqueue'],2);?></td>
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

?>   

</body>
</html>
