<?
session_start();
include("../../includes/db.inc.php");
include("../../includes/common.php");
include("approvalObj.php");
$approvalObj = new approvalObj();

switch($_GET['action']) {	
	case 'viewToPrint':
		
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
	/*if($("#hdnSuppCode").val()==''){
		alert("Invalid Supplier");	
		return false;
	}
		*/
		$.ajax({
			url: "approvalDtl.php",
			type: "GET",
			traditional: true,
			data: 'action=viewToPrint&suppCode='+$('#hdnSuppCode').val(),
			success: function(msg){ 
				//$('#divDtls').html(msg);
				$("#divDtls").html(msg);
				$('#contractList').dataTable({
					"bJQueryUI" : "true",
					"sPaginationType": "full_numbers"
				});
			}				
		});	
		$("#divDtls2").dialog("destroy");
		$("#divDtls2").dialog({
			title: "UNAPPROVED STS",
			height: 500,
			width: 900,
			modal: true,
			closeOnEscape: false,
			buttons: {
				'APPROVE': function() {
					$.ajax({
						url: 'approvalDtl.php',
						type: "GET",
						data: $("#formInv").serialize()+'&act=postInvoice',
						beforeSend : function(){
							ProcessData('Loading','Open');
						},	
						success: function(Data){
							ProcessData('Loading...','Close');
							eval(Data);
							$.ajax({
								url: "approvalDtl.php",
								type: "GET",
								traditional: true,
								data: 'action=viewToPrint&suppCode='+$('#hdnSuppCode').val(),
								success: function(msg){ 
									//$('#divDtls').html(msg);
									$("#divDtls").html(msg);
									$('#contractList').dataTable({
										"bJQueryUI" : "true",
										"sPaginationType": "full_numbers"
									});
								}				
							});	
						}			
					});		
				}
			}
		});
	});
});
function enableView(val){
	if(val=='0'){
		$('#btnView').attr('disabled','disabled');
	}else{
		$('#btnView').removeAttr('disabled');	
	}
}
function ProcessData(msg,act) {
		if (act=='Open') {
			$("#dialogProcess").dialog("destroy");
			$("#Process").html(msg)
				$("#dialogProcess").dialog({
				title: 'PARCO STS',
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
document.write('<div id="dialog-confirm"><p id="dialog-confirm-p"></p></div>');	
document.write('<div id="dialog-message"><p id="dialog-message-p"></p></div>');	
document.write('<div id="dialog-loading"><p id="dialog-loading-p1"></p><p id="dialog-loading-p2"></p></div>');	
document.write('<div id="dialog-prompt"><p id="dialog-prompt-p1"></p><p id="dialog-prompt-p2"></p><p id="dialog-prompt-p3"></p><p id="dialog-prompt-p4"></p></div>');	
</script>
</script>
</head>

<body>
<h2 class="ui-widget-header ui-corner-all" style="padding:5px;">STS APPROVAL MODULE</h2>
<div class="ui-widget-content" style="padding:5px;">
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
            	<button id="btnView" name="btnView">View For Approval</button>
            </td>
        </tr>
    </table>
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
      
</div>
</body>
</html>