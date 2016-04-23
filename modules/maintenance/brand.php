<?
session_start();
include("../../includes/db.inc.php");
include("../../includes/common.php");
include("maintenanceObj.php");
$maintObj = new maintenanceObj;


$now = date('Y-m-d H:i:s');
if ($_SESSION['sts-username'] == "") {
	echo "<SCRIPT>window.parent.location.href='../../index.php';</SCRIPT>";
}
switch($_GET['action']){	
	case 'Load':
		$page = $_GET['page']; // get the requested page 
		$limit = $_GET['rows']; // get how many rows we want to have into the grid 
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort 
		$sord = $_GET['sord']; // get the direction 
		if(!$sidx) $sidx =1; 
		
		$totUser = $maintObj->countBrand();
		$count = $totUser['count']; 
		if( (int)$count>0 ) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		} 
		
		if ($page > $total_pages) 
			$page=$total_pages; 
		$start = ($limit*$page) - ($limit); // do not put $limit*($page - 1) 
		
		$response->page = $page; 
		$response->total = $total_pages; 
		$response->records = $count; 
		
		if($_GET['_search']=='true'){
			$arrSTS= $maintObj->searchBrandType($sidx,$sord,$start,$limit,$_GET['searchField'],$_GET['searchString']);
		}else{
			$arrSTS = $maintObj->getPaginatedBrandType($sidx,$sord,$start,$limit);
		}
		$i = 0;
		foreach($arrSTS as $val){
			$response->rows[$i]['id']=$val['stsBrand']; 
			$response->rows[$i]['cell']=array($val['stsBrand'],$val['stsBrandDesc'],$val['stat']);
			$i++;
		}
		echo json_encode($response);
	exit();
	break;
	
	case 'AddBrand':
		if($maintObj->checkIfBrandExists($_GET['txtBrand']) == 0){
			if($maintObj->addBrand($_GET)){
				echo "$('#dialogAdd').dialog('close');\n";
				echo "dialogAlert('Brand succesfully added!');";
			}else{
				echo "dialogAlert('There was an error adding a new Brand!');";	
			}
		}else{
			echo "dialogAlert('Brand already exists!');";
		}
	exit();
	break;
	
	case 'getInfo':
			$arrInfo = $maintObj->brandInfo($_GET['id']);
			echo "$('#txtBrand').val('{$arrInfo['stsBrandDesc']}');\n";

	exit();
	break;
	
	case 'updateBrand':
		if($maintObj->checkIfBrandExistsWId($_GET['txtBrand'],$_GET['hdnBrandId']) == 0){
			if ($maintObj->updateBrandInfo($_GET)){
				echo '$("#dialogAdd").dialog("close");';
				echo "dialogAlert('Brand successfully updated.');";	
			} else {
				echo "dialogAlert('Error updating Brand.');";
			}	
		}else{
			echo "dialogAlert('Brand already exists!');";
		}
	exit();
	break;
	
	case 'deleteBrand':
		if($maintObj->deleteBrand($_GET['id'])){
			echo "dialogAlert('Brand succesfully deleted.')";
		}else{
			echo "dialogAlert('There was an error during the deletion of Brand!')";	
		}
	exit();
	break;
	
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>User Maintenance</title>
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
	
	$("#brandTable").jqGrid({ 
		url:'brand.php?action=Load', 
		datatype: "json", 
		colNames:['ID','Description','Status'], 
		colModel:[ 
			{name:'stsBrand', index:'stsBrand', width:20, align:"right"}, 
			{name:'stsBrandDesc', index:'stsBrandDesc', width:100, align:"left"}, 
			{name:'stat', index:'stat', width:40, align:"center"}
		], 
		rowNum:10, 
		rowList:[10,20,30], 
		pager: '#brandPager', 
		sortname: 'stsBrand', 
		viewrecords: true, 
		sortorder: "desc", 
		caption:"Lists", 
		height:"auto",
		width:"450",
		onSelectRow: function(id){ 
			var id = jQuery("#brandTable").jqGrid('getGridParam','selrow'); 
			var ret = jQuery("#brandTable").jqGrid('getRowData',id);
			var stsBrand = ret.stsBrand;
			$("#hdnBrandId").val(stsBrand);
			$("#editBrand").addClass("link");
			$("#editBrand").attr("onclick","editBrandInfo();");
			$("#deleteBrand").addClass("link");
			$("#deleteBrand").attr("onclick","deleteBrand();");
	   }
        
	}).navGrid("#brandPager",{edit:false,add:false,del:false}); 
	
	
	$("#AddBrand").click( function(){ 
		clearAllText();
		$("#dialogAdd").dialog("destroy");
		$("#dialogAdd").dialog({
			title: "NEW ENHANCER",
			height: 210,
			width: 380,
			modal: true,
			closeOnEscape: false,
			close: function() {
				reloadGrid();
			},
			buttons: {
				'Save': function(){
					if (validateInputs()) {
						$.ajax({
							url: "brand.php",
							type: "GET",
							traditional: true,
							data: $("#formBrand").serialize()+'&action=AddBrand',
							success: function(msg){
								eval(msg);
								reloadGrid();
							}	
						});
					}
				}
			}
		});				
	});
});
function reloadGrid() {
	$("#brandTable").trigger("reloadGrid"); 
	$("#editBrand").removeClass("link");
	$("#editBrand").addClass("disable");
	$("#deleteBrand").removeClass("link");
	$("#deleteBrand").addClass("disable");
}
function clearAllText(){
	$("#txtBrand").val("");	
}
function validateString(ObjName){
	if(ObjName.val().length == 0){
		ObjName.addClass("ui-state-error");
		dialogAlert("Required Field!");
		return false;
	}
	else{
		ObjName.removeClass("ui-state-error");
		return true;
	}
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
function validateInputs() {
	var val = true;
	if (validateString($("#txtBrand"))== false)
		val = false;
	
	return val;
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
function editBrandInfo(){
	clearAllText();
	var id = $("#hdnBrandId").val();
	$.ajax({
		url: "brand.php",
		type: "GET",
		traditional: true,
		data: 'action=getInfo&id='+id,
		success: function(msg){
			eval(msg);
		}				
	});	
	$("#dialogAdd").dialog("destroy");
	$("#dialogAdd").dialog({
		title: "Edit User",
		height: 210,
		width: 380,
		modal: true,
		closeOnEscape: false,
		close: function() {
			reloadGrid();
		},
		buttons: {
			'Save': function(){
				if (validateInputs()) {
					$.ajax({
						url: "brand.php",
						type: "GET",
						traditional: true,
						data: $("#formBrand").serialize()+'&action=updateBrand',
						success: function(msg){
							eval(msg);
							reloadGrid();
						}	
					});
				}
			}
		}
	});
}
function deleteBrand(){
	var id = $("#hdnBrandId").val();	
	$("#dialogMsg").html('Are you sure you want to delete?');
	$("#dialogAlert").dialog("destroy");
	$("#dialogAlert").dialog({
		modal: true,
		buttons: {
			'Yes': function(){
				$.ajax({
					url: "brand.php",
					type: "GET",
					traditional: true,
					data: 'action=deleteBrand&id='+id,
					success: function(msg){
						eval(msg);
						reloadGrid();
					}
				});
			},
			'No': function (){
				$(this).dialog('close');
				reloadGrid();
			}
		}
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
	width:541px; 
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
	height: 50px;
	border: solid 1px #222;
	padding: 2px;
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
</style>
</head>

<body>

<h2 class=" ui-widget-header">BRAND MAINTENANCE</h2>
<br />


<center>
<span id="AddBrand" style="cursor: pointer; font-size:11px;" class="link" title="New User">
	<img src="../../images/file_add.png" title="Add Brand" /> New
</span>
&nbsp;&nbsp;&nbsp;&nbsp;
<span class="style23, disable" id="editBrand" title="Edit User" onclick="">
	<img src="../../images/file_edit.png" title="Edit Brand" /> Edit
</span>
&nbsp;&nbsp;&nbsp;&nbsp;
<span class="style23, disable" id="deleteBrand" title="Delete User" onclick="">
	<img src="../../images/file_delete.png" title="Delete Brand" /> Delete
</span>

<div align="right" style="float: right;">

</div>
<br /><br />
<table id="brandTable"></table> </center>
<div id="brandPager"></div> <br /> 

	<div style=" visibility:hidden;  overflow:hidden;">
        <div id='dialogAdd' title='Add'>
        	<div class="headerContent ui-corner-all">
            <form id="formBrand">
            <input type="hidden" name="hdnBrandId" id="hdnBrandId" value="" />    	
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="60%" height="25" class="hd">Brand Description: </td>
                  <td width="%"><input type="text" name="txtBrand" maxlength="20"  id="txtBrand" class="textBox" /></td>
                </tr>
           </table>  
            </form> 
            </div>
        </div>
       
        <div id='dialogProcess' style=" overflow:hidden;"><br />
            <div style="text-align:center"><img src="../../images/progress2.gif" /></div>
            <div id='Process' style="text-align:center"></div>
        </div> 
        <div id='dialogAlert' title='STS'>
            <p id='dialogMsg'></p>
    	</div>
        <div id="Access">
        	<span id="AccessData"></span>
     	</div>
	</div>
</body>
</html>