<?
session_start();
include("../../includes/db.inc.php");
include("../../includes/common.php");
include("maintenanceObj.php");
$maintObj = new maintenanceObj;


$now = date('Y-m-d H:i:s');
if ($_SESSION['sts-username'] == "") {
	//header("location: index.php");
	echo "<SCRIPT>window.parent.location.href='../../index.php';</SCRIPT>";
}

switch($_GET['action']) {	
	case 'changePassword':
		$count = $maintObj->checkNewFrOld($_GET['txtOldPass']);
		if($count>0){
			if($maintObj->changePass($_GET['txtNewPass'])){
				echo "dialogAlert('Password succesfully changed!');";
			}else{
				echo "dialogAlert('An Error Occured');";		
			}
		}else{
			echo "dialogAlert('Old Password is Invalid');";	
		}
	exit();
	break;	
}
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
	$("button").button();
	
	$("#change").click( function (){	
		if(validateInputs()&&checkIfMatched()){
			$.ajax({
				url: 'changePassword.php',
				type: "GET",
				data: $("#formChange").serialize()+'&action=changePassword',
				success: function(msg){
					clearFields();
					eval(msg);
				}				
			});		
		}
	});
});
function clearFields(){
	$('#txtOldPass,#txtNewPass,#txtConfirmPass').val('');	
}
function validateInputs(){
	var val = true;
	if (validateString($("#txtOldPass"))== false)
		val = false;
	if (validateString($("#txtNewPass"))== false)
		val = false;
	if (validateString($("#txtConfirmPass"))== false)
		val = false;	
	return val;
}

function checkIfMatched(){
	var newPass = $('#txtNewPass').val();
	var confirmPass = $('#txtConfirmPass').val();
	if (newPass == confirmPass){
		$('#txtNewPass,#txtConfirmPass').removeClass("ui-state-error");
		return true;
	}
	else{
		dialogAlert('Password confirmation did not match');
		$('#txtNewPass,#txtConfirmPass').addClass("ui-state-error");
		return false;	
	}
}

function validateString(ObjName){
	if(ObjName.val().length == 0){
		ObjName.addClass("ui-state-error");
		dialogAlert('Required Fields');
		return false;
	}
	else{
		ObjName.removeClass("ui-state-error");
		return true;
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
</script>
<style type="text/css">
<!--
	fieldset{
		border-color: #111; 
	}
	.link {
		cursor: pointer; 
		font-size:11px; 
		color:#B8EC79;
		font-weight: bold;
	}	
-->
</style>
</head>

<body>
<center>
<h1 class="ui-widget-header ui-corner-all " style="padding-left:5px; text-align:left; width:50%">Change Password</h1>

<div class="ui-widget-content ui-corner-all" style=" width:50%;" align="center">

	<table width="60%" border="0" cellspacing="3" cellpadding="2">
    	<form name="formChange" id="formChange">
    	<tr>
        	<td width="30%"><strong>Old Password: </strong></td>
           	<td width="20%"><input type="password" name="txtOldPass" id="txtOldPass"/></td>
    	</tr>
        <tr>
        	<td><strong>New Password: </strong></td>
            <td><input type="password" name="txtNewPass" id="txtNewPass"/></td>
        </tr>
        <tr>
        	<td><strong>Confirm Password: </strong></td>
            <td><input type="password" name="txtConfirmPass" id="txtConfirmPass"/></td>
        </tr>
        </form>
        <tr>
        	<td colspan="2" align="center"><button id="change">Change</button></td>
        </tr>
        
	</table>
</div>
</center>
 <div style="visibility:hidden;  overflow:hidden;">
    	<div id='dialogAlert' title='IT-PMS'>
            <p id='dialogMsg'></p>
    	</div>
    </div>
</body>
</html>