<?
session_start();
include("../../includes/db.inc.php");
include("../../includes/common.php");
include("cancellationObj.php");
$cancelObj = new cancelObj();

switch($_GET['action']) {	
	case "Load":
		$arr = $cancelObj->getApprovedSTS($suppCode);
		echo "
		<table cellpadding='0' cellspacing='0'  border='0' class='display' id='stsList' width='40%'>
			<thead> 
			  <tr>
				<th width='10%'><strong>Ref. No.</strong></th>
				<th><strong>Supplier</strong></th>
				<th align='center'><strong>Total Amount</strong></th>
				<th align='center'><strong>Entered By</strong></th>
				<th align='center'><strong>Date Entered</strong></th>
				<th align='center'><strong>Approved By</strong></th>
				<th align='center'><strong>Application Date</strong></th>
				<th align='center'><strong>Action</strong></th>
			  </tr>
			</thead>
			";
			  
			foreach($arr as $val){ 
			  echo "<tr style='font: Verdana; font-size:11px; height:25px;' class='gradeK'  align='center'>
				<td>".$val['stsRefno']."</td>
				<td  align='left'>".$val['suppCode'].'-'.$val['suppName']."</td>
				<td  align='right'>".$val['stsAmt']."</td>
				<td  align='left'>".$val['fullName']."</td>
				<td  align='center'>".date('M-d-Y',strtotime($val['dateEntered']))."</td>
				<td  align='left'>".$val['approvedBy']."</td>
				<td  align='center'>".date('M-d-Y',strtotime($val['applyDate']))."</td>
				<td>
					<span class='link' onclick=\"getDetails('{$val['stsRefno']}');\">[ CANCEL ]</span>
				</td>";
			echo "</tr>";
			  }
			echo "</tbody>
				  </table>";
		exit();	
	break;
	
	case "viewToCancel":
		$arr = $cancelObj->getAprrovedSTSDtl($refNo);
		echo "
		<table cellpadding='0' cellspacing='0'  border='0' class='display' id='stsList' width='40%'>
			<thead> 
			  <tr>
				<th width='10%'><strong>STS No.</strong></th>
				<th><strong>STS Amount</strong></th>
				<th align='center'><strong>Supplier</strong></th>
				<th align='center'><strong>Store</strong></th>
				<th align='center'><strong>No. of Application</strong></th>
				<th align='center'><strong>Action</strong></th>
			  </tr>
			</thead>
			";
			  
			foreach($arr as $val){ 
			  echo "<tr style='font: Verdana; font-size:11px; height:25px;' class='gradeK'  align='center'>
				<td>".$val['stsRefno']."</td>
				<td  align='left'>".$val['suppCode'].'-'.$val['suppName']."</td>
				<td  align='right'>".$val['stsAmt']."</td>
				<td  align='left'>".$val['fullName']."</td>
				<td  align='center'>".date('M-d-Y',strtotime($val['dateEntered']))."</td>
				<td  align='left'>".$val['approvedBy']."</td>
				<td  align='center'>".date('M-d-Y',strtotime($val['applyDate']))."</td>
				<td>
					<span class='link' onclick=\"getDetails('{$val['stsRefno']}');\">[ STS Details ]</span>
				</td>";
			echo "</tr>";
			  }
			echo "</tbody>
				  </table>";
		exit();	
	break;
	
	case 'getCancellationDate':
		//if(count($cancelObj->getCancelDates($_GET['refNo']))>0){
			$cancelObj->DropDownMenuD($cancelObj->makeArr($cancelObj->getCancelDates($_GET['refNo']),'stsApplyDate','stsApplyDate',''),'cmbCancelDate','','class="selectBox" ');
		/*}else{
			$arrOption = array('C'=>"CANCEL THE WHOLE STS",'R'=>"REOPEN THE WHOLE STS");
			//echo "\" <font style='color:red;'>STS has not been EOD yet, cancellation date will default to the current date.</font>\"";	
			$cancelObj->DropDownMenu($arrOption,'cmbAction','','class="selectBox"'); 
		}*/
		exit();
	break;
	
	case 'checkIfApplied':
			
			$detailCount =  count($cancelObj->getCancelDates($_GET['refNo']));
			echo "$('#hdnAppliedChecker').val('".$detailCount ."');";
		exit();
	break;
	
	
	case  'cancelStsWHOLE':
	
	 	$userRow = $cancelObj->validateUser($_GET['username2'],$_GET['password2']);
		 if((int)$userRow >0){
			 if($cancelObj->cancelSTSWHOLE($_GET['refNo'],$_GET['txtReason2'],$_GET['cmbCancelDate'],$_GET['stsRefNo2'])){
				 echo "$('#dialogCancelSTS').dialog('close');";
				echo "$('#dialogCancelSTS').dialog('destroy');";
				echo "dialogAlert('Cancellation Process Succeeded!');\n";
				echo "window.open('cancellationXls.php?{$_SERVER['QUERY_STRING']}');";
				
			}else{
				echo "dialogAlert('There was an Error in the Cancellation of STS');";
			}
		 }else{
			echo "dialogAlert('Invalid Username or Password');"; 
		 }	
		 
		
	exit();
	break;
	
	case  'cancelStsREOPEN':
		$userRow = $cancelObj->validateUser($_GET['username2'],$_GET['password2']);
		 if((int)$userRow >0){
			if($cancelObj->cancelSTSREOPEN($_GET['refNo'],$_GET['txtReason2'],$_GET['cmbCancelDate'],$_GET['stsRefNo2'])){
				echo "$('#dialogCancelSTS').dialog('close');";
				echo "$('#dialogCancelSTS').dialog('destroy');";
				echo "dialogAlert('STS Reference number successfully reopened!');\n";
				echo "window.open('cancellationXls.php?{$_SERVER['QUERY_STRING']}');";
				
			}else{
				echo "dialogAlert('There was an error during the reopening of STS');";
			}
		 }else{
			 echo "dialogAlert('Invalid Username or Password');"; 
		 }
	exit();
	break;
	
	
	case 'checkIfRequired':
		$res = $cancelObj->ifStsRequired($_GET['cancelId']);
		echo $res;
	exit();
	break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css" title="currentStyle">
			@import "../../includes/css/demo_page.css" "";
			@import "../../includes/css/demo_table_jui.css";
</style>
<style type="text/css">
<!-- 
.textBox {
	border: solid 1px #222; 
	border-width: 1px; 
	width:250px; 
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
.link {
	cursor: pointer; 
	font-size:11px; 
	color:#0066CC;
	font-weight: bold;
}
</style>
<link type="text/css" href="../../includes/jquery/css/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<link type="text/css" href="../../includes/jquery/development-bundle/demos/demos.css" rel="stylesheet" />
<link type="text/css" href="../../includes/jqGrid/css/ui.jqgrid.css"rel="stylesheet" />
<script type="text/javascript" src="../../includes/jquery/js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="../../includes/jquery/js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="../../includes/jqGrid/js/i18n/grid.locale-en.js"></script>
<script type="text/javascript" src="../../includes/jqGrid/js/jquery.jqGrid.min.js"></script>
<script type="text/javascript" src="../../includes/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
$(function(){
	$('#btnView').button();
	$("#txtSupp").autocomplete({
		source: "regularSTS.php?action=searchSupplier",
		minLength: 1,
		select: function(event, ui) {	
			var content = ui.item.id.split("|");
			$("#hdnSuppCode").val(content[0]);
			$("#txtRep").val(content[1]);
		}
	});	   
	$("#txtSupp").click( function(){ 
		$("#txtSupp").val("");
		$("#hdnSuppCode").val("");
	});
	$('#btnView').click(function(){
		if($("#hdnSuppCode").val()==''){
			alert("Invalid Supplier");	
			return false;
		}
		LoadData('cancellation.php','Data','stsList','action=Load');
	});
});

function ProcessData(msg,act) {
		if (act=='Open') {
			$("#dialogProcess").dialog("destroy");
			$("#Process").html(msg)
				$("#dialogProcess").dialog({
				title: 'STS',
				height: 150,
				modal: true,
				closeOnEscape: false,
				beforeClose: function(event, ui) {
						return false;
					}
			});		
		} else {
			$("#dialogProcess").dialog('close');
			$("#dialogProcess").dialog("destroy");
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
function LoadData(page,divData,gridID,params){
	var suppCode = $("#hdnSuppCode").val();
	$.ajax({
		url: page,
		type: "GET",
		data: params+'&suppCode='+suppCode,
			success: function(Data){
			$("#"+divData).html(Data);
			$('#'+gridID).dataTable({
				"bJQueryUI" : "true",
				"sPaginationType": "full_numbers"
				});
			}				
	   });			
}

function getDetails(refNo){
		$.ajax({
			url: "cancellation.php",
			type: "GET",
			traditional: true,
			data: 'action=checkIfApplied&refNo='+refNo,
			success: function(Data){ 
				eval(Data);
				nextMove(refNo);
			}				
		});	
}
function nextMove(refNo){
	var dtlCheck = $("#hdnAppliedChecker").val();
	if(dtlCheck>0){
		$.ajax({
			url: "cancellationDtl.php",
			type: "GET",
			traditional: true,
			data: 'action=viewToCancel&refNo='+refNo,
			success: function(msg){ 
				$("#divDtls").html(msg);
				$('#contractList').dataTable({
					"bJQueryUI" : "true",
					"bPaginate": false,
					"sScrollY": "280px"
				});
			}				
		});	
		$("#divDtls2").dialog("destroy");
		$("#divDtls2").dialog({
			title: "STS DETAILS FOR CANCELLATION",
			height: 500,
			width: 1000,
			modal: true,
			closeOnEscape: false,
			buttons: {
				'CANCEL STS': function() {
					if(validateDetails()){
						cancelReason(refNo);
					}else{
						dialogAlert("Please select a valid STS number!");	
					}
				}
			}
		});		
	}else{
		$("#dialogCancelSTS2").dialog("destroy");
		$("#spnRefNo").html(refNo);
		$("#dialogCancelSTS2").dialog({
			height: 300,
			width: 630,
			modal: true,
			closeOnEscape: false,
			buttons: {
				'Close': function() {
					$(this).dialog('close');
				},
				'GO': function() {
					
					if(validateCmb($("#txtReason2")) && validateCmb($("#cmbAction")) && validateRef("#stsRefNo2") && validateString($("#username2")) && validateString($("#password2"))){
						$.ajax({
							url: 'cancellation.php',
							type: "GET",
							data: $("#formCancelSTS2").serialize()+'&action=cancelSts'+$("#cmbAction").val()+'&refNo='+refNo,
							beforeSend : function(){
								ProcessData('Loading','Open');
							},	
							success: function(Data){
								eval(Data);
								ProcessData('Loading...','Close');
								/*$("#dialogCancelSTS2").dialog('close');
								$("#dialogCancelSTS2").dialog("destroy");*/
								LoadData('cancellation.php','Data','stsList','action=Load');
							}			
						});	
					}
				}
			}
		});			
	}
}
function validateDetails(){
	var isTrue = false;
	var chldCnt = $('#hdCtr2').val();
	for(i=0;i<=chldCnt;i++){
		if($('#switcher_'+i).val()=="1"){
			isTrue = true;
		}
	}
	return isTrue;
}
function cancelReason(refNo){		
	//$('#txtReason').text('');
	$.ajax({
		url: 'cancellation.php',
		type: "GET",
		data: "action=getCancellationDate&refNo="+refNo,
		success: function(Data){
			eval(Data);
			$("#stopDate").html(Data);
		}				
	});
	$("#dialogCancelSTS").dialog("destroy");
	$("#dialogCancelSTS").dialog({
		height: 300,
		width: 630,
		modal: true,
		closeOnEscape: false,
		buttons: {
			'Close': function() {
				$(this).dialog('close');
			},
			'GO (Cancel STS)': function() {
				var txtReason = $("#txtReason");
				if(validateCmb($("#txtReason")) && validateCmb($("#cmbCancelDate")) && validateRef("stsRefNo") && validateString($("#username1")) && validateString($("#password1"))){
					$.ajax({
						url: 'cancellationDtl.php',
						type: "POST",
						data: $("#formInv").serialize()+"&"+$("#formCancelSTS").serialize()+'&act=cancelStsDtl',
						beforeSend : function(){
							ProcessData('Loading','Open');
						},	
						success: function(Data){
							eval(Data);
							ProcessData('Loading...','Close');
							
							$.ajax({
								url: "cancellationDtl.php",
								type: "GET",
								traditional: true,
								data: 'action=viewToCancel&refNo='+refNo,
								success: function(msg){ 
									$("#divDtls").html(msg);
									$('#contractList').dataTable({
										"bJQueryUI" : "true",
										"sPaginationType": "full_numbers"
									});
								}				
							});	
							LoadData('cancellation.php','Data','stsList','action=Load');
						}			
					});	
				}
			}
		}
	});			
}

function validateRef(idm){
	var val = true;
	
	if( $("#hdnRefTag").val()=='Y' ){
		
		if($(idm).val()==""){
			val = false;	
			dialogAlert("STS Replacement No is required!");
		}else{
			val = true;	
		}
	}
	return val;
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

function checkIfStsRequired(cmbVal){
	$.ajax({
			url: 'cancellation.php',
			type: "GET",
			data: 'action=checkIfRequired&cancelId='+cmbVal,
			beforeSend : function(){
				ProcessData('Loading','Open');
			},	
			success: function(Data){
				ProcessData('Loading...','Close');
				if(Data == 'Y'){
					$("#hdnRefTag").val("Y");
					$("#stsRefNo2").attr("readonly",false);
					$("#stsRefNo2").focus();
				}else{
					$("#hdnRefTag").val("N");
					$("#stsRefNo2").val("");
					$("#stsRefNo2").attr("readonly",true);
				}
			}			
		});	
}
function valNumInputDec(event) {
		//return true;
		var key, keyChar;
		if (window.event)
			key = window.event.keyCode;
		else if (event)
			key = event.which;
		else
			return true;
		// Check for special characters like backspace
		if (key == null || key == 0 || key == 8 || key == 13 || key == 27 || key == 45)
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
document.write('<div id="dialog-confirm"><p id="dialog-confirm-p"></p></div>');	
document.write('<div id="dialog-message"><p id="dialog-message-p"></p></div>');	
document.write('<div id="dialog-loading"><p id="dialog-loading-p1"></p><p id="dialog-loading-p2"></p></div>');	
document.write('<div id="dialog-prompt"><p id="dialog-prompt-p1"></p><p id="dialog-prompt-p2"></p><p id="dialog-prompt-p3"></p><p id="dialog-prompt-p4"></p></div>');	
</script>
</script>
</head>

<body>
<h2 class="ui-widget-header ui-corner-all" style="padding:5px;">STS CANCELLATION</h2>
<div class="ui-widget-content" style="padding:5px;">
<input type="hidden" name="hdnRefTag" id="hdnRefTag" value="N"/>
<input type="hidden" name="hdnAppliedChecker" id="hdnAppliedChecker" value="" />
	<table width="50%" border="0" cellspacing="2" cellpadding="0" align="center">
    	<tr>
            <td class="hd" width="20%">
            	Supplier: 
            </td>
            <td class="hd">
            	<input type="hidden" name="hdnSuppCode" id="hdnSuppCode"  />
            	<input type="text" name="txtSupp" id="txtSupp" class="textBox"/>
            </td>
            <td>
            	<button id="btnView" name="btnView">View STS</button>
            </td>
        </tr>
    </table><br/>
	  <div id="Data" style="width:100%;">
    	
    </div>  
</div>
<div style=" visibility:hidden;  overflow:hidden;">
 	<div id='dialogProcess' style="overflow:hidden;"><br />
        <div style="text-align:center"><img src="../../images/progress2.gif"/></div>
        <div id='Process' style="text-align:center"></div>
	</div>
     <div id="divDtls2">
     <div id="divDtls">
                
     </div>
    </div> 
    <div id='dialogAlert' title='STS'>
    	<p id='dialogMsg'></p>
    </div>
    
     <div id='dialogCancelSTS' title='Cancel STS'>
        <form id="formCancelSTS" name="formCancelSTS">
        <div class="headerContent2 ui-corner-all">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>  
                  <td class="hd">Effectivity Date:</td>
                  <td id="stopDate"></td>
                </tr>
               <tr><td colspan="2">&nbsp;</td></tr>
                <tr>  
                  <td class="hd">Reason:</td>
                  <td colspan="2">
                  			<?= $cancelObj->DropDownMenu($cancelObj->makeArr($cancelObj->getCancelReason(),'cancelId','cancelDesc',''),'txtReason','','class="selectBox" onchange="checkIfStsRequired(this.value);" '); ?>
               		</td>
                </tr>
                 <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                	<td class="hd">STS Ref No Replacement:</td>
                    <td><input type="text" name="stsRefNo" id="stsRefNo" class="textBox" onKeyPress='return valNumInputDec(event);'/></td>
                </tr>
                 <tr><td colspan="2">&nbsp;</td></tr>
                 <tr>
                	<td class="hd">Manager's Username:</td>
                    <td><input type="text" name="username1" id="username1" class="textBox"/></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                	<td class="hd">Manager's Password:</td>
                    <td><input type="password" name="password1" id="password1" class="textBox"/></td>
                </tr>
            </table>
        </div>
        </form>
        </div>
     <div id='dialogCancelSTS2' title='Cancel STS'>
        <form id="formCancelSTS2" name="formCancelSTS2">
        <div class="headerContent2 ui-corner-all">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
            	<tr> 
                	<td colspan="2" align="center"><font style="color:#E00;">NOTE: This STS Reference number <i><strong><span id="spnRefNo"> </span></strong></i> has not been applied yet!</font></td> 
                </tr>
                <tr>  
                  <td class="hd">Cancellation Option:</td>
                  <td>
                  	<? $arrOption = array('WHOLE'=>"CANCEL THE WHOLE STS",'REOPEN'=>"REOPEN THE WHOLE STS");	
						echo $cancelObj->DropDownMenu($arrOption,'cmbAction','','class="selectBox"'); 
					?>
                  </td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>  
                  <td class="hd">Reason:</td>
                  <td colspan="2">
                  		<? $cancelObj->DropDownMenu($cancelObj->makeArr($cancelObj->getCancelReason(),'cancelId','cancelDesc',''),'txtReason2','','class="selectBox" onchange="checkIfStsRequired(this.value);" '); ?>
               		</td>
                </tr>
                 <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                	<td class="hd">STS Ref No Replacement:</td>
                    <td><input type="text" name="stsRefNo2" id="stsRefNo2" class="textBox" onKeyPress='return valNumInputDec(event);'/></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                 <tr>
                	<td class="hd">Manager's Username:</td>
                    <td><input type="text" name="username2" id="username2" class="textBox"/></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                	<td class="hd">Manager's Password:</td>
                    <td><input type="password" name="password2" id="password2" class="textBox"/></td>
                </tr>
            </table>
        </div>
        </form>
        </div>
</div>
</body>
</html>