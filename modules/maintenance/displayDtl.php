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
		
		$totUser = $maintObj->countDisplayDtl($_GET['dispId']);
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
			$arrSTS= $maintObj->searchDisplayTypeDtl($sidx,$sord,$start,$limit,$_GET['searchField'],$_GET['searchString'],$_GET['dispId']);
		}else{
			$arrSTS = $maintObj->getPaginatedDisplayTypeDtl($sidx,$sord,$start,$limit,$_GET['dispId']);
		}
		$i = 0;
		foreach($arrSTS as $val){
			$response->rows[$i]['id']=$val['dispDtlId']; 
			$response->rows[$i]['cell']=array($val['dispDtlId'],$val['dispDesc'],$val['status']);
			$i++;
		}
		echo json_encode($response);
	exit();
	break;
	
	case 'AddBrandDtl':
		if($maintObj->checkIfSpecDtlExists($_GET['txtBrandDtl']) == 0){
			if($maintObj->addSpecsDtl($_GET)){
				echo "$('#dialogAdd').dialog('close');\n";
				echo "dialogAlert('Detail succesfully added!');";
			}else{
				echo "dialogAlert('There was an error adding a new Detail!');";	
			}
		}else{
			echo "dialogAlert('Detail already exists!');";
		}
	exit();
	break;
	
	case 'updateBrandDtl':
		if($maintObj->checkIfSpecsDtlExistsWId($_GET['txtBrandDtl'],$_GET['masterId'],$_GET['dtlId']) == 0){
			if ($maintObj->updateSpecsDtlInfo($_GET)){
				echo '$("#dialogAdd").dialog("close");';
				echo "dialogAlert('Specs successfully updated.');";	
			} else {
				echo "dialogAlert('Error updating Brand.');";
			}	
		}else{
			echo "dialogAlert('Brand already exists!');";
		}
	exit();
	break;
	
	case 'getDtlInfo':
			$arrInfo = $maintObj->specsDtlInfo($_GET['masterId'],$_GET['dtlId']);
			echo "$('#txtBrandDtl').val('{$arrInfo['dispDesc']}');\n";
	exit();
	break;
	
	case 'deleteBrandDtl':
		if($maintObj->deleteSpecsDtl($_GET['id'],$_GET['dtlId'])){
			echo "dialogAlert('Detail succesfully deleted.')";
		}else{
			echo "dialogAlert('There was an error during the deletion of Detail!')";	
		}
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
	
	$("#dtlTable").jqGrid({ 
		url:'displayDtl.php?action=Load&dispId=<?=$_GET['dispId']?>', 
		datatype: "json", 
		colNames:['ID','Description','Status'], 
		colModel:[ 
			{name:'dispDtlId', index:'dispDtlId', width:20, align:"right"}, 
			{name:'dispDesc', index:'dispDesc', width:100, align:"left"}, 
			{name:'status', index:'status', width:40, align:"center"}
		], 
		rowNum:10, 
		rowList:[10,20,30], 
		pager: '#dtlPager', 
		sortname: 'dispDtlId', 
		viewrecords: true, 
		sortorder: "desc", 
		caption:"Lists", 
		height:"auto",
		width:"450",
		onSelectRow: function(id){ 
			var id = jQuery("#dtlTable").jqGrid('getGridParam','selrow'); 
			var ret = jQuery("#dtlTable").jqGrid('getRowData',id);
			var dtlId = ret.dispDtlId;
			$("#hdnDtlId").val(dtlId);
			$("#editDtl").addClass("link");
			$("#editDtl").attr("onclick","editDtlInfo();");
			$("#deleteDtl").addClass("link");
			$("#deleteDtl").attr("onclick","deleteDtl();");
	   }
        
	}).navGrid("#dtlPager",{edit:false,add:false,del:false}); 
	
	$("#AddBrandDtl").click( function(){ 
		clearAllText();
		$("#dialogAddDtl").dialog("destroy");
		$("#dialogAddDtl").dialog({
			title: "NEW DISPLAY SPECIFICATION DETAIL",
			height: 210,
			width: 380,
			modal: true,
			closeOnEscape: false,
			close: function() {
				reloadGrid2();
			},
			buttons: {
				'Save': function(){
					if (validateInputs2()) {
						$.ajax({
							url: "displayDtl.php",
							type: "GET",
							traditional: true,
							data: $("#formBrandDtl").serialize()+'&action=AddBrandDtl',
							success: function(msg){
								eval(msg);
								reloadGrid2();
							}	
						});
					}
				}
			}
		});				
	});
	
});

function editDtlInfo(){
	clearAllText2();
	var dtlId = $("#hdnDtlId").val();
	var id = $("#hdnMasterId").val();
	$.ajax({
		url: "displayDtl.php",
		type: "GET",
		traditional: true,
		data: 'action=getDtlInfo&masterId='+id+'&dtlId='+dtlId,
		success: function(msg){
			eval(msg);
		}				
	});	
	$("#dialogAddDtl").dialog("destroy");
	$("#dialogAddDtl").dialog({
		title: "Edit Detail",
		height: 210,
		width: 380,
		modal: true,
		closeOnEscape: false,
		close: function() {
			reloadGrid();
		},
		buttons: {
			'Save': function(){
				if (validateInputs2()) {
					$.ajax({
						url: "displayDtl.php",
						type: "GET",
						traditional: true,
						data: $("#formBrandDtl").serialize()+'&action=updateBrandDtl',
						success: function(msg){
							eval(msg);
							reloadGrid2();
						}	
					});
				}
			}
		}
	});	
}

function deleteDtl(){
	var dtlId = $("#hdnDtlId").val();
	var id = $("#hdnMasterId").val();
	$("#dialogMsg").html('Are you sure you want to delete?');
	$("#dialogAlert").dialog("destroy");
	$("#dialogAlert").dialog({
		modal: true,
		buttons: {
			'Yes': function(){
				$.ajax({
					url: "displayDtl.php",
					type: "GET",
					traditional: true,
					data: 'action=deleteBrandDtl&id='+id+'&dtlId='+dtlId,
					success: function(msg){
						eval(msg);
						reloadGrid2();
					}
				});
			},
			'No': function (){
				$(this).dialog('close');
				reloadGrid2();
			}
		}
	});
}
function validateInputs2() {
	var val = true;
	if (validateString($("#txtBrandDtl"))== false)
		val = false;
	
	return val;
}
function clearAllText2(){
	$("#txtBrandDtl").val("");	
}
function reloadGrid2() {
	$("#dtlTable").trigger("reloadGrid"); 
	$("#editDtl").removeClass("link");
	$("#editDtl").addClass("disable");
	$("#deleteDtl").removeClass("link");
	$("#deleteDtl").addClass("disable");
}
</script>
</head>

<body>

<center>
<div class=" ui-widget-header">
    <span id="AddBrandDtl" style="cursor: pointer; font-size:11px;" class="link" title="New">
        <img src="../../images/file_add.png" title="Add Brand" /> New
    </span>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <span class="style23, disable" id="editDtl" title="Edit" onclick="">
        <img src="../../images/file_edit.png" title="Edit Brand" /> Edit
    </span>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <span class="style23, disable" id="deleteDtl" title="Delete" onclick="">
        <img src="../../images/file_delete.png" title="Delete Brand" /> Delete
    </span>
</div>
</center>

<br />

<table id="dtlTable"></table> </center>
<div id="dtlPager"></div> <br /> 
<? $arrMode = array('Y'=>"YES",'N'=>"NO"); ?>
 	<div style=" visibility:hidden;  overflow:hidden;">
        <div id='dialogAddDtl' title='Add'>
        	<br />
        	<div class="headerContent ui-corner-all">
            <form id="formBrandDtl">
                <input type="hidden" name="hdnMasterId" id="hdnMasterId" value="<?=$_GET['dispId']?>" />    	
                <input type="hidden" name="hdnDtlId" id="hdnDtlId"  />
                
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="60%" height="25" class="hd">Display Specs Detail: </td>
                      <td width="%"><input type="text" name="txtBrandDtl" maxlength="20"  id="txtBrandDtl" class="textBox" /></td>
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