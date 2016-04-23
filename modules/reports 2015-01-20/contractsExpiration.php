<?
session_start();
include("../../includes/db.inc.php");
include("../../includes/common.php");
include("reportsObj.php");
$reportsObj = new reportsObj();
if ($_SESSION['sts-username'] == "") {
	echo "<SCRIPT>window.parent.location.href='../../index.php';</SCRIPT>";
}
switch($_GET['action']){	
	case 'PrintSUM':
		echo "window.open('cancelled_sts_summ_excel.php?{$_SERVER['QUERY_STRING']}');";
	exit();
	break;
	
	case 'PrintDet':
		echo "window.open('expired_sts_det_excel.php?{$_SERVER['QUERY_STRING']}');";
	exit();
	break;
}
$arrType = array("DET"=>"DETAILED","SUM"=>"SUMMARIZED");
$arrTran = array("0"=>'ALL','1'=>"REGULAR STS",'2'=>"LISTING FEE",'4'=>"SHELF ENHANCER", "5"=>"DISPLAY ALLOWANCE");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Unreleased Transactions</title>

<link type="text/css" href="../../includes/jquery/css/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<link type="text/css" href="../../includes/jquery/development-bundle/demos/demos.css" rel="stylesheet" />
<script type="text/javascript" src="../../includes/jquery/js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="../../includes/jquery/js/jquery-ui-1.8.16.custom.min.js"></script>

<script type="text/javascript">
	$(function(){
		$('#dtMonthYr').datepicker( {
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			dateFormat: 'MM yy',
			onClose: function(dateText, inst) { 
				var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
				var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
				$(this).datepicker('setDate', new Date(year, month, 1));
			}
		});	
		$("#printInq").button({
			icons: {
				primary: 'ui-icon-print',
			}
		});
		$("#printInq").click( function (){
			/*var dateStart = $('#txtDateFrom').val();
			var dateEnd = $('#txtDateTo').val();
			*/
			if (validateInputs()){
				$.ajax({
					url: 'contractsExpiration.php',
					type: "GET",
					data: $("#formInq").serialize()+'&action=PrintDet',
					success: function(Data){
						eval(Data);
					}				
				});														
			}
		});
	});

function valDateStartEnd(valStart,valEnd,id1,id2) {
	var parseStart = Date.parse(valStart);
	var parseEnd = Date.parse(valEnd);
	if (valStart !='' && valEnd !='') {
		if(parseStart > parseEnd) {
			$('#'+id1).addClass('ui-state-error');
			$('#'+id2).addClass('ui-state-error');
			dialogAlert("Date 'TO' Must Be Greater than Date 'FROM'");		
			return false;
		} else {
			$('#'+id1).removeClass('ui-state-error');
			$('#'+id2).removeClass('ui-state-error');	
			return true;
		}
	}else {
		$('#'+id1).addClass('ui-state-error');
		$('#'+id2).addClass('ui-state-error');
		dialogAlert("Please Select Date Range");			
		return false;
	}
}
function dialogAlert(msg){
	$("#dialogAlert").dialog("destroy");
	$("#dialogMsg").html(msg);
	$("#dialogAlert").dialog({
		modal: true,
		buttons: {
			Ok: function() {
				$(this).dialog('close');
			}
		}
	});	
}
function validateCmb(ObjName){
	if(ObjName.val()== 0){
		ObjName.addClass("ui-state-error");
		return false;
	}else{
		ObjName.removeClass("ui-state-error");
		return true;
	}
}
function validateString(ObjName){
	if(ObjName.val().length == 0){
		ObjName.addClass("ui-state-error");
		return false;
	}else{
		ObjName.removeClass("ui-state-error");
		return true;
	}
}
function validateInputs() {
	var val = true;
	if (validateString($("#dtMonthYr"))== false)
		val = false;
	/*if (validateCmb($("#cmbProdGrp"))== false)
		val = false;*/
	return val;
}
</script>
<style type="text/css">
.ui-datepicker-calendar {
	display: none;
}
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

<h2 class="ui-widget-header ui-corner-all" style="padding:5px;">Expired Contracts Report</h2>
<div class="ui-widget-content" style="padding:5px;">
	<table width="100%" border="0" cellspacing="3" cellpadding="2">
    	<form name="formInq" id="formInq">
       
    	<tr>
        	 <td class="hd" ><strong>Transaction Type: </strong></td>
           	<td><? $reportsObj->DropDownMenu($arrTran,'cmbTran','','class="selectBox"'); ?></td>
    
          	<td class="hd" width="20%">Month / Year:</td>
            <td class="hd" width="20%"><input type="text" name="dtMonthYr" id="dtMonthYr" readonly="readonly"/></td>
        
        </form>
        
        	<td colspan="2" align="left"><button id="printInq">Print Report</button></td>
        </tr>
	</table>
    
    <div id='dialogAlert' title='STS'>
    	<p id='dialogMsg'></p>
    </div>
</div>
</body>
</html>