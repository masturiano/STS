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

	
	
	case 'PrintXls':
			if($_GET['cmbType'] != 3){
				echo "window.open('stsSummaryPerSuppPerProdCat_XLS.php?{$_SERVER['QUERY_STRING']}');";
			}else{
				echo "window.open('stsSummaryPerSuppPerProdCatPf_XLS.php?{$_SERVER['QUERY_STRING']}');";
			}
			
	exit();
	break;
	
}

$arrType = array('0'=>" ",'1'=>"STS",'2'=>"DA",'3'=>"PF");

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
		
		$("#printInqXls").button({
			icons: {
				primary: 'ui-icon-print',
			}
		});
		
		$("#txtSup").autocomplete({
			source: "stsSummaryPerSuppPerProdCat.php?action=searchSupplier",
			minLength: 1,
			select: function(event, ui) {	
				var content = ui.item.id;
				$("#hdnSuppCode").val(content);
			}
		});	    

		$("#printInqXls").click( function (){
			var dateStart = $('#txtDateFrom').val();
			var dateEnd = $('#txtDateTo').val();
			var cmbType = $('#cmbType').val();
			if (valDateStartEnd(dateStart,dateEnd,'txtDateFrom','txtDateTo')){
				if (cmbType == 0){
					dialogAlert("Please select type");
					$('#cmbType').addClass('ui-state-error');
				}else{
					$.ajax({
						url: 'stsSummaryPerSuppPerProdCat.php',
						type: "GET",
						data: $("#formInq").serialize()+'&action=PrintXls',
						success: function(Data){
							eval(Data);
						}				
					});	
				}												
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


<h2 class="ui-widget-header ui-corner-all" style="padding:5px;">STS SUMMARY PER SUPP PER PRODUCT CATEGORY</h2>
<div class="ui-widget-content" style="padding:5px;">
	<table width="40%" border="0" cellspacing="3" cellpadding="2" align="center">
    	<form name="formInq" id="formInq">
    	<tr>
        	
    
        	<td class="hd" ><strong>Date From: </strong></td>
           	<td><input type="text" name="txtDateFrom" id="txtDateFrom" readonly="readonly" class="textBox"/></td>
    	
        	<td class="hd">Date To:</td>
            <td><input type="text" name="txtDateTo" id="txtDateTo" readonly="readonly" class="textBox"/></td>
			
			<td class="hd">Type:</td>
			<td><? $reportsObj->DropDownMenu($arrType,'cmbType','','class="selectBox"'); ?></td> 
        
        
       </tr>
       </form>
       <tr>
        	<td colspan="6" align="center"> <button id="printInqXls">Print in EXCEL</button></td>
       </tr> 
        
	</table>
    
    <div id='dialogAlert' title='STS'>
            <p id='dialogMsg'></p>
    	</div>
</div>
</body>
</html>