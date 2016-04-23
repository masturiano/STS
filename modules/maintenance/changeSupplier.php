<?
session_start();
include("../../includes/db.inc.php");
include("../../includes/common.php");
include("supplierObj.php");
$supplierObj = new supplierObj();


?>

<? if($_GET['action']=='view'){
	
		$arr = $supplierObj->getHeaderInformation($_GET['refNo']);
?>	
<center>
	
	<table width="80%" border="0" cellspacing="0" cellpadding="0">
    	<tr>
        	<td class='hd'>STS REF NO: </td>
            <td class='hd2'><? echo $arr['stsRefno'];?></td>
            
            <td class='hd'>STS TOTAL AMT: </td>
            <td class='hd2'><? echo number_format($arr['stsAmt'],2);?></td>
            
             <td class='hd'>CURRENT SUPPLIER: </td>
            <td class='hd2'><? echo $arr['suppCode'].'-'.$arr['ASNAME'];?></td>
        </tr>
        <tr>
        	<td class='hd'>ENTERED BY: </td>
            <td class='hd2'><? echo $arr['fullName'];?></td>
            
            <td class='hd'>ENTRY DATE: </td>
            <td class='hd2'><? echo date('m/d/Y',strtotime($arr['dateEntered']));?></td>
            
             <td class='hd'>DATE APPROVED: </td>
            <td class='hd2'><? echo date('m/d/Y',strtotime($arr['dateApproved']));?></td>
        </tr>
         <tr>
        	<td class='hd'>APPLICATION DATE: </td>
            <td class='hd2'><? echo date('m/d/Y',strtotime($arr['applyDate']));?></td>
            
            <td class='hd'>NUMBER OF APPLICATION: </td>
            <td class='hd2'><? echo $arr['nbrApplication'];?></td>
            
             <td class='hd'>PAYMENT MODE: </td>
            <td class='hd2'><? echo $arr['payMode'];?></td>
        </tr>	
         <tr>
        	<td class='hd'>REMARKS: </td>
            <td class='hd2'><? echo $arr['stsRemarks'];?></td>
        </tr>	
        
    </table>
    <hr />
    <form name="frmSup" id="frmSup">
    <table width="80%" border="0" cellspacing="0" cellpadding="0">
    	<input type="hidden" id="hdnRefNo" name="hdnRefNo" value="<?=$arr['stsRefno'];?>" />
        <input type="hidden" id="hdnOldSuppCode" name="hdnOldSuppCode" value="<?=$arr['suppCode'];?>" />
    	<tr>
        	<td class='hd'>NEW SUPPLIER:</td>
            <td><? $supplierObj->DropDownMenu($supplierObj->makeArr($supplierObj->findSupplier(),'ASNUM','suppCodeName',''),'cmbSupp','','class="selectBox"'); ?></td>
        </tr>
      	<tr><td colspan="2"> &nbsp;</td></tr>
        <tr>
        	<td class='hd'>REASON:</td>
            <td><input  type="text" id="txtReason" name="txtReason"  class="textBoxLong"/></td>
        </tr>
        </form>
        <tr>
            <td colspan="2"><input type="button" value="Change Supplier" id="btnSup" name="btnSup" onClick="changeSupplier();"/></td>
        </tr>
    </table>
</center>
<? 	exit();
	break;
} 

	if($_GET['action']=='saveChanges'){
			if($supplierObj->changeSupplier($_GET)){
				echo "dialogAlert('Change Supplier Succeeded!');\n";
			}else{
				echo "dialogAlert('An error occurred');";
			}
		exit();
		break;	
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Regular STS</title>

<link type="text/css" href="../../includes/jquery/css/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<link type="text/css" href="../../includes/jquery/development-bundle/demos/demos.css" rel="stylesheet" />
<script type="text/javascript" src="../../includes/jquery/js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="../../includes/jquery/js/jquery-ui-1.8.16.custom.min.js"></script>

<script type="text/javascript">

	function valNumInputDec(event) {
		var key, keyChar;
	  	if (window.event)
			key = window.event.keyCode;
	  	else if (event)
			key = event.which;
	  	else
			return true;
	  	// Check for special characters like backspace
	  	if (key == null || key == 0 || key == 8 || key == 13 || key == 27)
			return true;
	  	// Check to see if it's a number
	  	keyChar =  String.fromCharCode(key);
	  	if ((/\d/.test(keyChar)) || (/\./.test(keyChar))) {
			window.status = "";
			return true;
		} 
		else {
			window.status = "Field accepts numbers only.";
			return false;
	   	}
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
	function validateRefNo() {
		var val = true;
		if (validateString($("#searchRef"))== false){
			dialogAlert("Required Field!");		
			val = false;	
		}
		return val;
	}
	function validateInputs() {
		var val = true;
		if (validateCmb($("#cmbSupp"))== false){
			dialogAlert("Required Field!");		
			val = false;	
		}
		if (validateString($("#txtReason"))== false){
			dialogAlert("Required Field!");		
			val = false;	
		}
		return val;
	}
	function searchReference(){
		if(validateRefNo()){
			$.ajax({
				url: "changeSupplier.php",
				type: "GET",
				traditional: true,
				data: 'action=view&refNo='+$("#searchRef").val(),
				success: function(msg){
					$("#divView").html(msg);
				}				
			});
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
	function changeSupplier(){
		
		$("#dialogMsg").html('Are you sure you want to change the current supplier for this sts?');
			$("#dialogAlert").dialog("destroy");
			$("#dialogAlert").dialog({
				modal: true,
				buttons: {
					'YES': function() {
						if(validateInputs()){
							$.ajax({
								url: 'changeSupplier.php',
								type: "GET",
								data: $("#frmSup").serialize()+'&action=saveChanges',
								success: function(Data){
									$("#cmbSupp").val("0");
									$("#txtReason").val();
									eval(Data);
								}
							});		
						}
					},
					'NO': function() {
						$(this).dialog('close');
					}
				},
			});
	}
</script>

<style type="text/css">
<!-- 
.textBox {
	border: solid 1px #222; 
	border-width: 1px; 
	width:190px; 
	height:18px;
	font-size: 11px;
}
.textBoxLong {
	border: solid 1px #222; 
	border-width: 1px; 
	width:527px; 
	height:18px;
	font-size: 11px;
}
.textBoxMin {
	border: solid 1px #222; 
	border-width: 1px; 
	width:130px; 
	height:18px;
	font-size: 11px;
}
.headerContent {
	height: 220px;
	border: solid 1px #222;
	padding: 1px;
	background-color:#E9E9E9;
}	
.detailContent {
	height: 50px;
	border: solid 1px #222;
	padding: 3px;
	background-color:#E9E9E9;
}	
.selectBox {
	border: 1px solid #222; 
	width:192px; 
	height:22px;
	font-size: 11px;
}
.selectBox2 {
	border: 1px solid #222; 
	width:120px; 
	height:22px;
	font-size: 11px;
}

.selectBoxMin {
	border: 1px solid #222; 
	width:100px; 
	height:22px;
	font-size: 11px;

}
.errMsg {
	padding:5px;
	height:30px;
	text-align:center;
}
.disable {
	color:#CCCCCC;
	font-size:11px; 
	cursor:default;
}
.link {
	cursor: pointer; 
	font-size:11px;
	font-weight: bolder; 
	color:#fff;
}
.link2 {
	cursor: pointer; 
	font-size:11px;
	font-weight: bolder; 
	color:#09F;
}
.hd {
	font-size: 11px;
	font-family: Verdana;
	font-weight: bold;
}
.hd2 {
	font-size:11px;
	font-family: Verdana;
}
.style20 {
	font-size: 14px;
	font-weight: bold;
	font-family: "Trebuchet MS";
	color: #EEEEEE;
}
.style21 {
	font-size: 14px;
	font-family: "Trebuchet MS";
	color: #EEEEEE;
}
.style22 {color: #EEEEEE; background-color:#EEEEEE}
.style23 {font-size:11px; cursor: pointer;}

body {
	background-color: #56789a;
}
.style24 {color: #000000}


-->
.dataTables_wrapper {
	
	min-height: 190px;
	_height: 190px;
	clear: both;
}
</style>
<head>

<body>

<h2 class="ui-widget-header ui-corner-all" style="padding:5px;">CHANGE SUPPLIER MAINTENANCE</h2>

&nbsp;&nbsp;&nbsp;&nbsp;
<span class="style23 disable" >Search STS Reference Number</span> 
<input type="text" name="searchRef" id="searchRef"  style="height:11px; text-align:right;" class="ui-corner-all" onKeyPress='return valNumInputDec(event);' onkeydown="if(event.keyCode==13) searchReference();"/>
<input type="button" name="searchGo" id="searchGo" value="Go" onclick="searchReference();" /> 
<input type="button" value="Refresh" onclick="document.location.reload();" id="refresh" name="refresh"/>

<div class="ui-widget-content" style="padding:5px;" id="divView">


</div>
<div id='dialogAlert' title='STS'>
            <p id='dialogMsg'></p>
    	</div>
</body>
</html>