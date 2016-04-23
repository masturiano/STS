<?
session_start();
include("../../includes/db.inc.php");
include("../../includes/common.php");
include("accrualObj.php");
$accrualObj = new accrualObj();
if ($_SESSION['sts-username'] == "") {
	echo "<SCRIPT>window.parent.location.href='../../index.php';</SCRIPT>";
}
switch($_GET['action']){	
	
	case 'PrintXls':
			echo "window.open('accrual_XLS.php?{$_SERVER['QUERY_STRING']}');";
	exit();
	break;
	
	case 'checkAccruMonth':
			$monthDate = $accrualObj->getRecCount($accrualObj->checkAccruMonth($_GET['txtMonth']));

			if($monthDate == 1){
				echo "$('#createTextfile').attr('disabled','disabled');";
				//echo "$('#createTextfile').removeAttr('disabled');";
			}else{
				echo "$('#createTextfile').removeAttr('disabled');";
			}
			
	exit();
	break;
	
	case 'insertTxtFile':
			$accrualObj->insertTxtFile($_GET['txtMonth']);
	exit();
	break;
	
	case 'createTxtFile':
			$accrualObj->createTxtFile($_GET['txtMonth']);
	exit();
	break;
	
	case 'insertAccruHist':
			$accrualObj->insertAccruHist($_GET['txtMonth']);
	exit();
	break;
	
	case 'truncAccruTemp':
			$accrualObj->truncAccruTemp();
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

<link href="../../includes/showLoading/css/showLoading.css" rel="stylesheet" media="screen" /> 
<script type="text/javascript" src="../../includes/showLoading/js/jquery.showLoading.js"></script>


<script type="text/javascript">
	$(function(){		
		
		$('#txtMonth').datepicker
		({
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			dateFormat: 'M-yy',
			onClose: function(dateText, inst) 
			{ 
				var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
				var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
				$(this).datepicker('setDate', new Date(year, month, 1));
			}
		});
		
		$("#printInq, #printInqXls").button({
			icons: {
				primary: 'ui-icon-print',
			}
		});
		
		$("#printInqXls").click( function (){
			var dateMonth = $('#txtMonth').val();

			if (dateMonth == ""){
				dialogAlert("Please select accrual month!");
				$('#txtMonth').addClass('ui-state-error');
			}else{
				$.ajax({
					url: 'accrual.php',
					type: "GET",
					data: $("#formInq").serialize()+'&action=PrintXls',
					success: function(Data){
						eval(Data);
					}				
				});														
			}
			
		});
		
		$("#createTextfile").button({
			icons: {
				primary: 'ui-icon-print',
			}
		});
		
		$("#createTextfile").click( function (){
			var dateMonth = $('#txtMonth').val();

			if (dateMonth == ""){
				dialogAlert("Please select accrual month!");
				$('#txtMonth').addClass('ui-state-error');
			}else{
				$.ajax({
					url: 'accrual.php',
					type: "GET",
					data: $("#formInq").serialize()+'&action=insertTxtFile',
					beforeSend: function() {
						jQuery('#activity_pane').showLoading();
					},
					success: function(Data){
						createTxtFile(dateMonth)
					}				
				});														
			}
			
		});
	});

function checkAccruMonth(){

	var dateMonth = $('#txtMonth').val();
	
	$.ajax({
		url: 'accrual.php',
		type: "GET",
		data: $("#formInq").serialize()+'&action=checkAccruMonth',
		success: function(Data){
			eval(Data);
		}				
	});	
}

function createTxtFile(dateMonth){

	var dateMonth = $('#txtMonth').val();
	
	$.ajax({
		url: 'accrual.php',
		type: "GET",
		data: $("#formInq").serialize()+'&action=createTxtFile',
		success: function(Data){
			 insertAccruHist(dateMonth);
		}				
	});	
}

function insertAccruHist(dateMonth){

	var dateMonth = $('#txtMonth').val();
	
	$.ajax({
		url: 'accrual.php',
		type: "GET",
		data: $("#formInq").serialize()+'&action=insertAccruHist',
		success: function(Data){
			truncAccruTemp(dateMonth);
		}				
	});	
}

function truncAccruTemp(dateMonth){

	var dateMonth = $('#txtMonth').val();
	
	$.ajax({
		url: 'accrual.php',
		type: "GET",
		data: $("#formInq").serialize()+'&action=truncAccruTemp',
		success: function(Data){
			$("#dialogAlert").html("Textfile Created!");
			$("#dialogAlert").dialog({
				modal: true,
				buttons: {
					Ok: function() {
						$(this).dialog('close');
						document.location.reload();
					}
				}
			});
			eval(Data);
		}				
	});	
}

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
	}
	else{
		ObjName.removeClass("ui-state-error");
		return true;
	}
}
function validateString(ObjName){
		if(ObjName.val().length == 0){
			ObjName.addClass("ui-state-error");
			return false;
		}
		else{
			ObjName.removeClass("ui-state-error");
			return true;
		}
	}
function validateInputs() {
		var val = true;
		if (validateCmb($("#cmbTran"))== false){
			dialogAlert("Required Field!");		
			val = false;	
		}
		return val;
	}
</script>

<style type="text/css">

.ui-datepicker-calendar 
{
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


	<h2 class="ui-widget-header ui-corner-all" style="padding:5px;">STS ACCRUAL</h2>
	<div class="ui-widget-content" style="padding:5px;">
	
		<div id="activity_pane" style="height: 100vh;">
		
			<table width="30%" border="0" cellspacing="3" cellpadding="2" align="center">
				<form name="formInq" id="formInq">
				<tr>
					<td align="center" class="hd" > Month </td>
					<td align="center" class="hd" > : </td>
					<td align="center" colspan="2"><input type="text" name="txtMonth" id="txtMonth" readonly="readonly" class="textBox"  onfocus="checkAccruMonth()"/></td>
			   </tr>
			   </form>
			   <tr>
					<td align="center" colspan="3"> <button id="printInqXls">Print in EXCEL</button></td>
					<td align="center" colspan="3"> <button id="createTextfile">Create Textfile</button></td>
			   </tr> 
				
			</table>
		
			<div id='dialogAlert' title='STS'>
				<p id='dialogMsg'></p>
			</div>
		</div>
	
	</div>

</body>
</html>