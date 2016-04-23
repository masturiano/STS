<?
session_start();

include("../../includes/db.inc.php");
include("../../includes/common.php");

include("daObj.php");
$daObj = new daObj();


switch($_GET['act']) {
	
	case "printContract":
		for($i=0;$i<=(int)$_GET['hdCtr2'];$i++) {
			if($_GET["switcher_$i"]=="1"){
				if ($daObj->PrintContract((int)$_GET['hdnRefNo'])){
					if($daObj->checkPayment($_GET['hdnRefNo'])=='C'){
						echo "window.open('report/dispAllowanceContract_pdf.php?refNo={$_GET['hdnRefNo']}&strCode=".$_GET['hdnStrCode_'.$i]."');";
					}else{
						echo "window.open('report/invoiceDeductionContract_pdf.php?refNo={$_GET['hdnRefNo']}&strCode=".$_GET['hdnStrCode_'.$i]."');";
					}
					
				} else {
					echo "dialogAlert('Error printing Contract.');";
				}
			}
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
		$arrContracts = $daObj->getEnhancerDetails2($_GET['refNo']);
	?>
	<form id="formInv" name="formInv">
   		<input type="hidden" name="hdnRefNo" value="<?=$_GET['refNo']?>" id="hdnRefNo" />
        <table width="100%" border="0" cellspacing="0" cellpadding="0" id='contractList' class='display'>
          <thead> 
          <tr>
                <th width="3%" height="25"><input type="checkbox" name="chAll2" onClick="checkAllDtl2();"  id="chAll2"></th>
                <th>Store</th>
                <th>Display Type</th>
                <th>Brand Name</th>
                <th>Location</th>
                <th>DAC #</th>
          </tr>
           </thead> 
            <?
			$u = 0;
            foreach($arrContracts as $val) {
			?>
            <tr  style='font: Verdana; font-size:11px; height:25px;' class='gradeA'  align='center'>
              <td class=""><input type="checkbox" <?=$checked?>  name="refNo_<?=$u;?>" value="<?=$val['refNo'];?>" id="refNo_<?=$u;?>" onClick="ToggleBranch2('<?=$u;?>');" /></td>
              	<input type="hidden" name="hdnStrCode_<?=$u;?>" id="hdnStrCode_<?=$u;?>" value="<?=$val['strCode']?>"/>
                <input type="hidden" name="switcher_<?=$u;?>" id="switcher_<?=$u;?>" value="0"/>
                <td align="left"><?=$val['brnDesc']?></td>
                <td><?=$val['displayType']?></td>
                <td><?=$val['brand']?></td>
                <td><?=$val['location']?></td>
                <td><?=$val['stsNo']?></td>
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
