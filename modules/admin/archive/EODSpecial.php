<?
session_start();

include("../../includes/db.inc.php");
include("../../includes/common.php");
include("EODobjSpecial.php");

$EODobj = new EODobj();
	
if ($_SESSION['sts-username'] == "") {
	echo "<SCRIPT>window.parent.location.href='../../index.php';</SCRIPT>";
}
	
switch($_GET['action']) {
	case "process":
		/*if($EODobj->ExtractTblSTShdr()){
			if($EODobj->ExtractAPAR()){*/
			$arr = $EODobj->getAllChecks();
			foreach($arr as $val){
				$EODobj->uploadToOracle($val['checkNo']);
				/*if($EODobj->uploadToOracle($_GET['cmbCheck'])){
					echo "dialogAlert('STS successfully Uploaded.');\n";
				}else{
					echo "dialogAlert('Upload Failed.');\n";
				}*/
			}
		/*	}else{
				echo "dialogAlert('STS failed In Extracting AP/AR Transaction.');\n";
			}
		}else{
			echo "dialogAlert('STS failed In Extracting Header.');\n";
		}*/
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
$(function(){
	$("#processEOD").button({
			icons: {
				primary: 'ui-icon-check',
			}
	});
});
	function loadProcess() {
		$("#dialogEOD").dialog("destroy");
		$("#dialogEOD").dialog({
		title: "Process STS",
		height: 200,
		width: 300,
		modal: true,
		closeOnEscape: false,
		beforeClose: function(event, ui) {
				return false;
			},
		});									
	}
	function Process() {
		$.ajax({
		url: "EODSpecial.php",
		type: "GET",
		traditional: true,
		data: $("#formUser").serialize()+'&action=process',
		beforeSend: function() {
			ProcessData('Process STS','Open');
		},
		success: function(msg){
				ProcessData('','Close');
				eval(msg);
			}				
	   });		
	}
	
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
	</script>  
 
</head>

<body onload="loadProcess();" >
<h2 class=" ui-widget-header">PRocess AP</h2>
<div style=" visibility:hidden;">
	<div id='dialogEOD'><br />
   
	<form id="formUser" name="formUser">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">  <tr>
        	
            <tr>  
             <td>Check Number:</td>
             <td><? $EODobj->DropDownMenu($EODobj->makeArr($EODobj->getAllChecks(),'checkNo','checkNo',''),'cmbCheck','','class="selectBox"  width="100%"'); ?></td>
            
            </tr>
            <tr>
            	<td width="20%" align="center" colspan="2">&nbsp;</td>
            </tr>
            <tr>
            	<td width="20%" align="center" colspan="2">&nbsp;</td>
            </tr>
             <tr>
            	<td width="20%" align="center" colspan="2">&nbsp;</td>
            </tr>
            <tr>
            <td width="20%" align="center" colspan="2">
            
            	<button id="processEOD" name="processEOD" onclick="Process()">Proccess AP TEXT FILE</button></td>
          </tr>
        </table>  
    </form>
  </div>
  <div id="Access">
  	<span id="AccessData"></span>
  </div>
    <div id='dialogAlert' title='STS Online'>
        <p id='dialogMsg'></p>
    </div>
	<div id='dialogProcess' style=" overflow:hidden;"><br />
    	<div style="text-align:center"><img src="../../images/progress2.gif"/></div>
    	<div id='Process' style="text-align:center"></div>
    </div>   
</div>
</body>
</html>




