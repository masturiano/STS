<?
session_start();
include("../../includes/db.inc.php");
include("../../includes/common.php");
include("agingObj.php");
$agingObj = new agingObj();
if ($_SESSION['sts-username'] == "") {
	echo "<SCRIPT>window.parent.location.href='../../index.php';</SCRIPT>";
}

	
switch($_GET['action']) {
	case "DT":
			echo "window.open('aging_sts_detail_pdf.php?{$_SERVER['QUERY_STRING']}');";
		exit();
	break;	
	
	case "SM":
			echo "window.open('aging_sts_summ_pdf.php?{$_SERVER['QUERY_STRING']}');";
		exit();
	break;	
}
	

$arrMode = array('R'=>"APPROVED",'O'=>"UNAPPROVED");
$arrType = array("DET"=>"DETAILED","SUM"=>"SUMMARIZED");
$arrTran = array("0"=>'ALL','1'=>"REGULAR STS",'2'=>"LISTING FEE",'4'=>"SHELF ENHANCER",'5'=>"DISPLAY ALLOWANCE",'6'=>"PUSH GIRL",'7'=>"SAMPLING DEMO");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>

<link type="text/css" href="../../includes/jquery/css/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<link type="text/css" href="../../includes/jquery/development-bundle/demos/demos.css" rel="stylesheet" />
<script type="text/javascript" src="../../includes/jquery/js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="../../includes/jquery/js/jquery-ui-1.8.16.custom.min.js"></script>


<script type="text/javascript">
	$(function(){
		$('#txtDateFrom, #txtDateTo').datepicker({
			dateFormat : 'mm/dd/yy'
		});
		$("#printInq, #printInqXls").button({
			icons: {
				primary: 'ui-icon-print',
			}
		});
	});

function PrintAging() {
		var action = $('#cmbRptType').val();
		var type = $('#cmbTranType').val();
		$.ajax({
			url: 'agingSts.php?action='+action+'&type='+type+'&payMode='+$("#cmbPayMode").val(),
			type: "GET",
			success: function(Data){
				eval(Data);
			}				
		});
	}
</script>

<style type="text/css">
.textBox {
	border: solid 1px #222; 
	border-width: 1px; 
	width:130px; 
	height:18px;
	font-size: 11px;
}
.selectBox {
	border: 1px solid #222; 
	width:132px; 
	height:22px;
	font-size: 11px;
}
.hd {
	font-size: 11px;
	font-family: Verdana;
	font-weight: bold;
}
</style>
</head>

<body>


<h2 class="ui-widget-header ui-corner-all" style="padding:5px;">AGING REPORT</h2>
<div class="ui-widget-content" style="padding:5px;">
	
    	
        <? $arrRptType = array('DT'=>'Detailed','SM'=>'SUMMARIZED'); 
			$arrTranType = array('DA'=>'Display Allowance','ST'=>'STS');
			$payMode = array('D'=>'Invoice Deduction','C'=>'Collection');
		?>
        <table width="80%" border="0" cellspacing="0" cellpadding="0">
            <tr>
            	<form name="formInq" id="formInq">
                  <td height="25" class="hd">Transaction Type: </td>
                  <td><? $agingObj->DropDownMenu($arrTranType,'cmbTranType','','class="selectBox ui-widget-content ui-corner-all"'); ?></td>
                  <td height="25" class="hd">Report Type: </td>
                  <td><? $agingObj->DropDownMenu($arrRptType,'cmbRptType','','class="selectBox ui-widget-content ui-corner-all"'); ?></td>
                   <td height="25" class="hd">Payment Mode: </td>
                  <td><? $agingObj->DropDownMenu($payMode,'cmbPayMode','','class="selectBox ui-widget-content ui-corner-all"'); ?></td>
         		</form>
               <td align="center"><button id="printInq" onclick="PrintAging();">Print</button> </td>
            </tr>
          </table>
        
    
    <div id='dialogAlert' title='STS'>
            <p id='dialogMsg'></p>
    	</div>
</div>
</body>
</html>