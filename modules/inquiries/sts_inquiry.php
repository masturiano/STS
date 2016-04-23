<?
session_start();
include("../../includes/db.inc.php");
include("../../includes/common.php");
include("inquiriesObj.php");
$inquiriesObj = new inquiriesObj();


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
		
		$totJob = $inquiriesObj->countRegSTS();
		$count = $totJob['count']; 
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
			$arrSTS= $inquiriesObj->searchDispSTS($sidx,$sord,$start,$limit,$_GET['searchField'],$_GET['searchString']);
		}else{	
			$arrSTS = $inquiriesObj->getPaginatedSTS($sidx,$sord,$start,$limit);
		}
		$i = 0;
		foreach($arrSTS as $val){
			$response->rows[$i]['id']=$val['stsRefNo']; 
			$response->rows[$i]['cell']=array($val['stsRefNo'],$val['suppName'],$val['stsRemarks'], date('m-d-Y',strtotime($val['dateEntered'])), $val['dateApproved'],$val['stsStat']);
			$i++;
		}
		echo json_encode($response);
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
	$("#stsTable").jqGrid({ 
		url:'sts_inquiry.php?action=Load', 
		datatype: "json", 
		colNames:['Ref. No','Supplier', 'Remarks','Date Entered','STS Date','Status'], 
		colModel:[ 
			{name:'stsRefNo', index:'stsRefNo', width:40, align:"right"}, 
			{name:'suppName', index:'suppName', width:170, align:"left"}, 
			{name:'stsRemarks', index:'stsRemarks', width:190, align:"left"}, 
			{name:'stsDateEntered', index:'stsDateEntered', width:80, align:"center"}, 
			{name:'stsDate', index:'stsDate', width:52, sortable:false, align:"center"},
			{name:'stsStat', index:'stsStat', width:60, align:"center"} 
		], 
		rowNum:20, 
		rowList:[20,40,60], 
		pager: '#stsPager', 
		sortname: 'stsRefNo', 
		viewrecords: true, 
		sortorder: "desc", 
		caption:"Lists", 
		height:"auto",
		width:"800",
		onSelectRow: function(id){ 
			var id = jQuery("#stsTable").jqGrid('getGridParam','selrow'); 
			var ret = jQuery("#stsTable").jqGrid('getRowData',id);
			refNo = ret.stsRefNo;
			$("#hdnRefNo").val(refNo);
			$("#printDetails").addClass("link");
			$("#printDetails").attr("onclick","printDetails(refNo);");
	   }
	}).navGrid("#stsPager",{edit:false,add:false,del:false}); 
});
function reloadGrid() {
	$("#stsTable").trigger("reloadGrid"); 
}
function printDetails(refNo){
	$.ajax({
		url: 'view_sts_summ.php?refNo='+refNo,
		type: "GET",
		success: function(Data){
			$("#stsData").html(Data);
			}				
	   });	
		$("#dvSts").dialog("destroy");
		$("#dvSts").dialog({
			title: "STS Summary",
			height: 540,
			width: 800,
			modal: true,
			closeOnEscape: false,
			close: function() {
				reloadGrid();
			}
		});
}
function PrintDetails(){
	$.ajax({
		url: 'view_sts_summ.php?&action=print&refNo='+refNo,
		type: "GET",
		success: function(Data){
			eval(Data);
		}				
	});	
}
</script>
<style type="text/css">
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
</head>

<body>
<h2 class="ui-widget-header ui-corner-all" style="padding:5px;">STS INQUIRY</h2>

<center>
	&nbsp;&nbsp;&nbsp;&nbsp;
    <span class="style23, disable" id="printDetails" title="View Details" onclick="">
        <img src="../../images/print.png" title="Print Contract" /> View Details
    </span>
	<table id="stsTable"></table>
</center>
<div id="stsPager"></div> <br /> 
<input type="hidden" id="hdnRefNo" name="hdnRefNo" />
<div id="dvSts">
	<div id="stsData">
    </div>
</div>
</body>
</html>