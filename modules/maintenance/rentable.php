<?
session_start();
include("../../includes/db.inc.php");
include("../../includes/common.php");
include("maintenanceObj.php");
$maintObj = new maintenanceObj;



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Rentables</title>

<style type="text/css" title="currentStyle">
			@import "../../includes/css/demo_page.css" "";
			@import "../../includes/css/demo_table_jui.css";
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
		$("#btnViewDtl").button({
			icons: {
				primary: 'ui-icon-print',
			}
		});
		$("#btnRefresh,#btnSave").button();
	});
	
	function viewDtl(){
		if (validateInputs2()) {
			$.ajax({
				url: "rentableDtl.php",
				type: "GET",
				traditional: true,
				data: $("#formInq").serialize()+'&action=getBrandDtl',
				success: function(msg){
					//eval(msg);
					//reloadGrid2();
					$("#rentableDtl").html(msg);
				}	
			});
		}
	}
	function validateInputs2() {
		var val = true;
		if (validateFixedString($("#cmbSpecs"),0)== false)
			val = false;
		if (validateFixedString($("#cmbStore"),0)== false)
			val = false;
		
		return val;
	}
	function validateFixedString(ObjName,errvalue){
		if(ObjName.val()== errvalue){
			ObjName.addClass("ui-state-error");
			dialogAlert("Required Field!");
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
	function emptyDiv(){
		$('#rentableDtl').empty();
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

<h2 class=" ui-widget-header">STORE RENTABLES MAINTENANCE</h2>

<div class="ui-widget-content" style="padding:5px;">
	<table width="80%" border="0" cellspacing="0" cellpadding="0">
            <tr>
            	<form name="formInq" id="formInq">
                  <td height="25" class="hd">Rentable Spaces: </td>
                  <td><? $maintObj->DropDownMenu($maintObj->makeArr($maintObj->listRentables(),'displaySpecsId','displaySpecsDesc',''),'cmbSpecs','','class="selectBox" onChange="emptyDiv();"'); ?></td>
                  <td height="25" class="hd">Stores: </td>
                  <td><? $maintObj->DropDownMenu($maintObj->makeArr($maintObj->getBranches(),'strCode','strCodeName',''),'cmbStore','','class="selectBox" onChange="emptyDiv();"'); ?></td>
         		</form>
               <td align="center"><button id="btnViewDtl" onclick="viewDtl();">VIEW</button> </td>
               <td align="center"><button id="btnRefresh" onclick="refreshDtl();">REFRESH</button> </td>
            </tr>
          </table>
        
    <div id="rentableDtl">
    	
    </div>
    <div id='dialogAlert' title='STS'>
            <p id='dialogMsg'></p>
    	</div>
</div>
</body>
</html>