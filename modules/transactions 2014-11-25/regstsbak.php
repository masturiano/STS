<?
session_start();
include("../../includes/db.inc.php");
include("../../includes/common.php");
include("transObj.php");
$transObj = new transObj();


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
		
		$totJob = $transObj->countRegSTS();
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
			$arrSTS= $transObj->searchRegSTS($sidx,$sord,$start,$limit,$_GET['searchField'],$_GET['searchString']);
		}else{
			if(isset($_GET['compCode'])){
				$arrSTS = $transObj->getPaginatedRegSTSComp($sidx,$sord,$start,$limit,$_GET['compCode']);
			}else{
				$arrSTS = $transObj->getPaginatedRegSTS($sidx,$sord,$start,$limit);
			}
		}
		$i = 0;
		foreach($arrSTS as $val){
			$response->rows[$i]['id']=$val['stsRefNo']."-".$val['stsComp']; 
			$response->rows[$i]['cell']=array($val['stsComp'],$val['stsRefNo'],$val['compShortName'],$val['suppName'],$val['dept'],$val['stsRemarks'],$val['stsDateEntered'], $val['stsTag'],$val['stsDate'],$val['stsStat']);
			$i++;
		}
		echo json_encode($response);
	exit();
	break;
	
	case 'searchSupplier':
		$arrResult = array();
		$arrSupp = $transObj->findSupplier($_GET['term']);
		foreach($arrSupp as $val){
			$arrResult[] = array("id"=>$val['suppCode'], "label"=>$val['suppCode']." - ".str_replace("-",'-',$val['suppName']), "value" => strip_tags($val['suppName']));	
		}
		echo json_encode($arrResult);
	exit();
	break;
	
	case 'cmbClass':
		$transObj->DropDownMenu($transObj->makeArr($transObj->findClass($_GET['dept']),'stsTransTypeClass','stsTransTypeName',''),'cmbClass','','class="selectBox" onchange="loadSubClass(this.value);"');
	exit();
	break;
	
	case 'cmbSubClass':
		$transObj->DropDownMenu($transObj->makeArr($transObj->findSubClass($_GET['dept'],$_GET['class']),'stsTransTypeSClass','stsTransTypeName',''),'cmbSubClass','','class="selectBox" ');
	exit();
	break;
	
	case 'cmbStore':
		$transObj->DropDownMenu($transObj->makeArr($transObj->findStore($_GET['compCode']),'brnCode','brnDesc',''),'cmbStore','','class="selectBox" ');
	exit();
	break;
	case 'saveHdr':
		if($transObj->saveHeader($_GET)){
			$lastQuery = $transObj->getLastSTSInserted();
			echo "$('#buttonHolder').html(\"<button id='updateHdr' name='updateHdr' onClick='updateHdr();'>Update Header</button>\");";
			echo "$('#txtRefNo').html('{$lastQuery['stsRefNo']}');";
			echo "$('#hdnRefNo').val('{$lastQuery['stsRefNo']}');";
			echo "$('#regSTSDetail').removeAttr('disabled');";
			echo "$('#regSTSDetail').attr('onClick','AddSTSDtl();');";
			echo "dialogAlert('Successfully Saved!');";
		}else{
			echo "dialogAlert('There was an error in adding STS');";
		}
	exit();
	break;
	
	case 'getInfo':
		$val = $transObj->getRegSTSInfoAssoc($_GET['refNo'],$_GET['compCode']);
		$supp = $transObj->getSuppName($val['suppCode']);
		if($val['stsPaymentMode'] =='D'){
			echo "$('#txtTerms').attr('disabled','true');";
			echo "$('#txtTerms').addClass('ui-state-disabled');";
		}else{
			echo "$('#txtTerms').removeAttr('disabled');";
			echo "$('#txtTerms').removeClass('ui-state-disabled');";
		}	
		echo "$('#txtRefNo').html('{$val['stsRefNo']}');\n";
		echo "$('#txtSTSAmount').val('{$val['stsAmt']}');\n";
		echo "$('#txtRemarks').val('{$val['stsRemarks']}');\n";
		echo "$('#hdnSuppCode').val('{$val['suppCode']}');\n";
		echo "$('#txtSupp').val('{$supp['suppName']}');\n";
		echo "$('#cmbCompCode').val('{$val['stsComp']}');\n";
		echo "$('#cmbPayType').val('{$val['stsPaymentMode']}');\n";
		echo "$('#txtTerms').val('{$val['stsTerms']}');\n";
		echo "$('#txtNoApplications').val('{$val['nbrApplication']}');\n";
		echo "$('#txtApDate').val('{$val['applyDate']}');\n";
		echo "$('#cmbDept').val('{$val['stsDept']}');\n";
		echo "$('#hdnDept').val('{$val['stsDept']}');\n";
		echo "$('#hdnClass').val('{$val['stsCls']}');\n";
		echo "$('#hdnSubClass').val('{$val['stsSubCls']}');\n";
	exit();
	break;
	
	case 'LoadSTSDetails':
		$arr = $transObj->getRegSTSDetails($_GET['refNo'],$_GET['compCode']);
		if($arr[0]['brnDesc']==''){
			echo "<script type='text/javascript'>$('#regSTSDetail').removeAttr('disabled');</script>\n";
			echo "<script type='text/javascript'>$('#regSTSDetail').attr('onClick','AddSTSDtl();');</script>\n";
			echo "<script type='text/javascript'>$('#regSTSDelDetail').removeAttr('onClick','DeleteSTSDtl();');</script>\n";
			echo "<script type='text/javascript'>$('#regSTSDelDetail').attr('disabled','true');</script>\n";
		}else{
			echo "<script type='text/javascript'>$('#regSTSDetail').attr('disabled','true');</script>\n";
			echo "<script type='text/javascript'>$('#regSTSDelDetail').attr('onClick','DeleteSTSDtl();');</script>\n";
			echo "<script type='text/javascript'>$('#regSTSDetail').removeAttr('onClick','AddSTSDtl();');</script>\n";
			echo "<script type='text/javascript'>$('#regSTSDelDetail').removeAttr('disabled');</script>\n";
		}
		echo "
		<table cellpadding='0' cellspacing='0'  border='0' class='display' id='STSDetailsList'>
			<thead> 
			  <tr>
		        <th>Branch</th>
		        <th>STS Amount</th>
		        <th>STS Number</th>
		        <th>STS Status</th>
			  </tr>
			</thead>
			";	  
			foreach($arr as $val){ 
			  echo "<tr style='font: Verdana; font-size:11px; height:25px;' class='gradeK'  align='center'>
				<td align=\"left\">".$val['brnDesc']."</td>
				<td >".number_format($val['stsStrAmt'],2)."</td>
				<td>".$val['stsNo']."</td>
				<td>".$val['dtlStatus']."</td>
				";
			echo "</tr>";
			  }
			echo "</tbody>
				  </table>";
	exit();
	break;
	
	case 'UpdateHdr':
		if($transObj->updateHeader($_GET)){
			echo "dialogAlert('STS Header successfully Updated!');";
		}else{
			echo "dialogAlert('There was an error in Updating the STS header');";
		}
	exit();
	break;
	
	case 'DeleteSTS':
		if($transObj->DeleteSTS($_GET['refNo'],$_GET['compCode'])) {
				echo "$('#dialogAlert').dialog('close');";
				echo "dialogAlert('STS successfully deleted.');\n";
			} else {
				echo "dialogAlert('Error deleting STS.');";
			}	
	exit();
	break;
	
	case 'ReleaseSTS':
		$count = $transObj->hasSTSDetail($_GET['refNo'],$_GET['compCode']);
		if((int)$count>0){
			if($transObj->releaseSTS($_GET['refNo'],$_GET['compCode'])){
				echo "$('#dialogAlert').dialog('close');\n";
				echo "dialogAlert('STS successfully released...');\n";
			}else{
				echo "$('#dialogAlert').dialog('close');\n";
				echo "dialogAlert('Error releasing...');\n";
			}
		}else{
			echo "$('#dialogAlert').dialog('close');\n";
			echo "dialogAlert('Please add participants first');\n";
		}
	exit();
	break;
}
$arrMode = array("",'D'=>"INVOICE DEDUCTION",'C'=>"CHECK/COLLECTION");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Regular STS</title>
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
	$('#regSTSDetail,#regSTSDelDetail').attr('disabled','true');
	
	$('#txtApDate').datepicker({
		dateFormat : 'yy-mm-dd'
	});
	$("#stsTable").jqGrid({ 
		url:'regularSTS.php?action=Load', 
		datatype: "json", 
		colNames:['','Ref. No','Company','Supplier', 'Department', 'Remarks','Date Entered','STS Tag','STS Date','Status'], 
		colModel:[ 
			{name:'stsComp', index:'stsComp', width:.25, align:"center"}, 
			{name:'stsRefNo', index:'stsRefNo', width:60, align:"right"}, 
			{name:'compShortName', index:'compShortName', width:80, align:"center"}, 
			{name:'suppName', index:'suppName', width:170, align:"left"}, 
			{name:'dept', index:'dept', width:120}, 
			{name:'stsRemarks', index:'stsRemarks', width:230, align:"left"}, 
			{name:'stsDateEntered', index:'stsDateEntered', width:120, align:"left"}, 
			{name:'stsTag', index:'stsTag', width:60, align:"center"}, 
			{name:'stsDate', index:'stsDate', width:80, sortable:false, align:"center"},
			{name:'stsStat', index:'stsStat', width:60, align:"center"} 
		], 
		rowNum:15, 
		rowList:[15,30,45], 
		pager: '#stsPager', 
		sortname: 'stsRefNo', 
		viewrecords: true, 
		sortorder: "desc", 
		caption:"Regular STS", 
		height:"500",
		width:"1020",
		onSelectRow: function(id){ 
			var id = jQuery("#stsTable").jqGrid('getGridParam','selrow'); 
			var ret = jQuery("#stsTable").jqGrid('getRowData',id);
			stat = ret.stsStat;
			refNo = ret.stsRefNo;
			compCode = ret.stsComp;
			$("#hdnRefNo").val(refNo);
			$("#hdnCompCode").val(compCode);
			if(stat == 'OPEN'){
				$("#editSTS").addClass("link");
				$("#editSTS").attr("onclick","EditSTS();");
				$("#deleteSTS").addClass("link");
				$("#deleteSTS").attr("onclick","DeleteSTS();");
				$("#releaseSTS").addClass("link");
				$("#releaseSTS").attr("onclick","ReleaseSTS();");
			}else{
				$("#editSTS").removeClass("link");
				$("#editSTS").addClass("disable");
				$("#deleteSTS").removeClass("link");
				$("#deleteSTS").addClass("disable");
				$("#releaseSTS").removeClass("link");
				$("#releaseSTS").addClass("disable");
			}
	   }
        
	}).navGrid("#stsPager",{edit:false,add:false,del:false}); 
	
	$("#txtSupp").autocomplete({
		source: "regularSTS.php?action=searchSupplier",
		minLength: 1,
		select: function(event, ui) {	
			var content = ui.item.id;
			$("#hdnSuppCode").val(content);
		}
	});		     
	$("#AddSTS").click( function(){ 
		clearAllText();
		$('#regSTSDetail,#regSTSDelDetail').attr('disabled','true');
		$("#buttonHolder").html("<button id='saveHdr' name='saveHdr' onClick='saveHdr();'>Save Header</button>");
		$("#dialogAdd").dialog("destroy");
			$("#dialogAdd").dialog({
				title: "NEW STS",
				height: 600,
				width: 900,
				modal: true,
				closeOnEscape: false,
				position: {
					my: 'center',
					at: 'top'
				},
				close: function() {
					reloadGrid();
				}
			});				
	});
});
function saveHdr(){ 
	if(validateInputs()){
		$.ajax({
			url: "regularSTS.php",
			type: "GET",
			traditional: true,
			beforeSend: function() {
				ProcessData('Saving STS Details...','Open');
			},
			data: $("#formSTS").serialize()+'&action=saveHdr',
			success: function(msg){
				ProcessData('','Close');
				eval(msg);
			}				
		});
	}else{
		dialogAlert('Please fill up all the required field(s)');	
	}
}
function reloadGrid() {
	$("#stsTable").trigger("reloadGrid"); 
	$("#editSTS").removeClass("link");
	$("#editSTS").addClass("disable");
	$("#deleteSTS").removeClass("link");
	$("#deleteSTS").addClass("disable");
	$("#releaseSTS").removeClass("link");
	$("#releaseSTS").addClass("disable");
}
function searchGridCompany(compCode){
	$("#stsTable").jqGrid('setGridParam',{url:"regularSTS.php?action=Load&compCode="+compCode,page:1}).trigger("reloadGrid");
}

function LoadData(page,divData,gridID,params){
	$.ajax({
		url: page,
		type: "GET",
		data: params,
			success: function(Data){
			$("#"+divData).html(Data);
			$('#'+gridID).dataTable({
				"bJQueryUI" : "true",
				"sPaginationType": "full_numbers"
			});
		}				
	});			
}
function EditSTS(){
	var refNo = $('#hdnRefNo').val();
	var compCode = $('#hdnCompCode').val();
	$("#buttonHolder").html("<button id='updateHdr' name='updateHdr' onClick='updateHdr();'>Update Header</button>");
	clearAllText();
	$.ajax({
		url: "regularSTS.php",
		type: "GET",
		traditional: true,
		data: 'action=getInfo&refNo='+refNo+'&compCode='+compCode,
		success: function(msg){
			eval(msg);
			var dept = $("#cmbDept").val();
			var class = $("#hdnClass").val();
			var subClass = $("#hdnSubClass").val();
			setTimeout("loadClass("+dept+");",300);
			setTimeout("$('#cmbClass').val("+class+");",700);
			setTimeout("loadSubClass("+class+");",1100);
			setTimeout("$('#cmbSubClass').val("+subClass+");",1500);
		}				
	});	
	//LoadData('regularSTS.php','Data','STSDetailsList','action=LoadSTSDetails&refNo='+refNo+'&compCode='+compCode);
	$("#dialogAdd").dialog("destroy");
	$("#dialogAdd").dialog({
		title: "Edit Regular STS",
		height: 600,
		width: 900,
		modal: true,
		closeOnEscape: false,
		position: {
			my: 'center',
			at: 'top'
		},
		close: function() {
			reloadGrid();
		},
	});
}
function AddSTSDtl() {
	var stsRefNo = $('#hdnRefNo').val();
	var compCode = $('#cmbCompCode').val();
	$.ajax({
		url: 'regularSTSDetails.php',
		type: "GET",
		data: "act=Details&stsRefNo="+stsRefNo+'&compCode='+compCode,
		success: function(Data){
			$('#divSTSDtls').html(Data);
		}				
	});	
	$("#divSTSDtls").dialog("destroy");
	$("#divSTSDtls").dialog({
		title: "STS Details",
		height: 420,
		width: 440,
		modal: true,
		closeOnEscape: false,
		buttons: {
			'Save': function() {
				$.ajax({
					url: 'regularSTSDetails.php',
					type: "GET",
					data: $("#formSTSDtl").serialize()+'&action=AddSTSDtl',
					beforeSend: function() {
						ProcessData('Saving STS Details...','Open');
					},
					success: function(Data){
						//LoadData('regularSTS.php','Data','STSDetailsList','action=LoadSTSDetails&refNo='+refNo+'&compCode='+compCode);
						ProcessData('','Close');
						eval(Data);
					}				
				});										
			}
		}
	});	
}	
function DeleteSTSDtl() {
	var stsRefNo = $('#hdnRefNo').val();
	var compCode = $('#cmbCompCode').val();
	$("#dialogMsg").html('Are you sure you want to delete details?');
		$("#dialogAlert").dialog("destroy");
		$("#dialogAlert").dialog({
			modal: true,
			buttons: {
				'YES': function() {
					$.ajax({
						url: 'regularSTSDetails.php',
						type: "GET",
						data: 'action=Delete&refNo='+stsRefNo+'&compCode='+compCode,
						beforeSend: function() {
							ProcessData('Deleting STS Details...','Open');
						},
						success: function(Data){
							//LoadData('regularSTS.php','Data','STSDetailsList','action=LoadSTSDetails&refNo='+refNo+'&compCode='+compCode);
							ProcessData('','Close');
							eval(Data);
						}				
					});												
				},
				'NO': function() {
					$(this).dialog('close');
				}
			},
		});
}
function DeleteSTS(){
	var stsRefNo = $('#hdnRefNo').val();
	var compCode = $('#cmbCompCode').val();
	$("#dialogMsg").html('Are you sure you want to delete?');
		$("#dialogAlert").dialog("destroy");
		$("#dialogAlert").dialog({
			modal: true,
			buttons: {
				'YES': function() {
					$.ajax({
						url: 'regularSTS.php',
						type: "GET",
						data: 'action=DeleteSTS&refNo='+stsRefNo+'&compCode='+compCode,
						beforeSend: function() {
							ProcessData('Deleting STS...','Open');
						},
						success: function(Data){
							reloadGrid();
							ProcessData('','Close');
							eval(Data);
						}				
					});												
				},
				'NO': function() {
					$(this).dialog('close');
				}
			},
		});
}
function ReleaseSTS(){
	var stsRefNo = $('#hdnRefNo').val();
	var compCode = $('#hdnCompCode').val();
	$("#dialogMsg").html('Are you sure you want to release sts reference number ' +stsRefNo+'?');
		$("#dialogAlert").dialog("destroy");
		$("#dialogAlert").dialog({
			modal: true,
			buttons: {
				'YES': function() {
					$.ajax({
						url: 'regularSTS.php',
						type: "GET",
						data: 'action=ReleaseSTS&refNo='+stsRefNo+'&compCode='+compCode,
						beforeSend: function() {
							ProcessData('Releasing STS...','Open');
						},
						success: function(Data){
							reloadGrid();
							ProcessData('','Close');
							eval(Data);
						}				
					});												
				},
				'NO': function() {
					$(this).dialog('close');
				}
			},
		});
}
function updateHdr(){
	var compCode = $('#cmbCompCode').val();
	var stsRefNo = $('#hdnRefNo').val();
	if(validateInputs()){
		$.ajax({
			url: "regularSTS.php",
			type: "GET",
			traditional: true,
			data: $("#formSTS").serialize()+'&action=UpdateHdr&refNo='+stsRefNo+'&compCode='+compCode,
			success: function(msg){
				eval(msg);
			}				
		});
	}else{
		dialogAlert('Please fill up all the required field(s)');	
	}
}
function validateInputs() {
	var val = true;
	if (validateCmb($("#cmbCompCode"))== false)
		val = false;
	if (validateCmb($("#cmbPayType"))== false)
		val = false;
	if (validateCmb($("#cmbDept"))== false)
		val = false;
	if (validateCmb($("#cmbClass"))== false)
		val = false;
	if (validateCmb($("#cmbSubClass"))== false)
		val = false;
	if (validateString($("#txtSTSAmount"))== false)
		val = false;
	if (validateString($("#txtSupp"))== false)
		val = false;
	if (validateString($("#txtRemarks"))== false)
		val = false;
	if (validateString($("#txtNoApplications"))== false)
		val = false;
	if (validateString($("#txtApDate"))== false)
		val = false;
	if (validateString($("#hdnSuppCode"))== false)
		val = false;
	if($('#cmbPayType').val()=='C'){
		if (validateString($("#txtTerms"))== false)
			val = false;
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
function loadStore(compCode){
	$.ajax({
		url: "regularSTS.php",
		type: "GET",
		traditional: true,
		data: 'action=cmbStore&compCode='+compCode,
		beforeSend: function() {
			ProcessData('Loading...','Open');
		},
		success: function(msg){
			ProcessData('Loading...','Close');
			$('#tdStore').html(msg);
		}				
	});
}
function loadClass(dept){
	$.ajax({
		url: "regularSTS.php",
		type: "GET",
		traditional: true,
		data: 'action=cmbClass&dept='+dept,
		beforeSend: function() {
			ProcessData('Loading...','Open');
		},
		success: function(msg){
			ProcessData('Loading...','Close');
			$('#tdClass').html(msg);
		}				
	});
}
function loadSubClass(class){
	$.ajax({
		url: "regularSTS.php",
		type: "GET",
		traditional: true,
		data: 'action=cmbSubClass&dept='+$('#cmbDept').val()+'&class='+class,
		beforeSend: function() {
			ProcessData('Loading','Open');
		},
		success: function(msg){
			ProcessData('Loading...','Close');
			$('#tdSubClass').html(msg);
		}				
	});
}
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
function ProcessData(msg,act) {
		if (act=='Open') {
			$("#dialogProcess").dialog("destroy");
			$("#Process").html(msg)
				$("#dialogProcess").dialog({
				title: 'STS Online',
				height: 80,
				width: 320,
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

function disableFields(switcher){
	if(switcher =='D'){
		$('#txtSTSAmount, #txtSupp, #cmbDept, #cmbClass, #cmbSubClass, #cmbCompCode, #txtRemarks, #cmbPayType, #txtTerms, #txtNoApplications, #txtApDate').attr('disabled','true');
	}else{
		$('#txtSTSAmount, #txtSupp, #cmbDept, #cmbClass, #cmbSubClass, #cmbCompCode, #txtRemarks, #cmbPayType, #txtTerms, #txtNoApplications, #txtApDate').removeAttr('disabled');
	}
}

function disablePayType(val){
	if(val=='D'){
		$("#txtTerms").attr('disabled','true');
		$("#txtTerms").addClass('ui-state-disabled');
	}else{
		$("#txtTerms").removeAttr('disabled');
		$("#txtTerms").removeClass('ui-state-disabled');	
	}
}
function clearAllText(){
	var select1 = "<select class='selectBox' id='cmbDummy2'><option></option></select>";
	$(":text").val("");
	$("#txtRefNo").html("");
	$("#cmbCompCode").val("0");
	$("#cmbPayType").val("0");
	$("#cmbDept").val("0");
	$("#tdClass").html(select1);
	$("#tdSubClass").html(select1);
	$("#Data").html("");
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
.headerContent {
	height: 190px;
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
	color:#0066CC;
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
	background-color: #EEEEEE;
}
.style24 {color: #000000}


-->
</style>
</head>

<body>

<br />
<br />
<span id="AddSTS" style="cursor: pointer; font-size:11px;" class="link" title="Add STS">
	<img src="../../images/file_add.png" title="Add STS" /> New
</span>
&nbsp;&nbsp;&nbsp;&nbsp;
<span class="style23, disable" id="editSTS" title="Edit STS" onclick="">
	<img src="../../images/file_edit.png" title="Edit STS" /> Edit
</span>
&nbsp;&nbsp;&nbsp;&nbsp;
<span class="style23, disable" id="deleteSTS" title="Delete STS" onclick="">
	<img src="../../images/file_delete.png" title="Delete STS" /> Delete
</span>
&nbsp;&nbsp;&nbsp;&nbsp;
<span class="style23, disable" id="releaseSTS" title="Release" onclick="">
	<img src="../../images/tag_green.png" title="Release" /> Release
</span>
&nbsp;&nbsp;&nbsp;&nbsp;
<span class="style23, disable" id="cancelSTS" title="Cancel" onclick="">
	<img src="../../images/layout_delete.png" title="Cancel" /> Cancel
</span>

<div align="right" style="float: right;">
<? $transObj->DropDownMenu($transObj->makeArr($transObj->getAllCompany(),'compCode','compShortName',''),'cmbCompCodeMain','','class="selectBox" onchange="searchGridCompany(this.value);"'); ?>
</div>
<br /><br />
<center><table id="stsTable"></table> </center>
<div id="stsPager"></div> <br /> 

	<div style=" visibility:hidden;  overflow:hidden;">
        <div id='dialogAdd' title='Add'>
     		
        	<div class="headerContent ui-corner-all">
            <input type="hidden" name="hdnRefNo" id="hdnRefNo" />	
            <input type="hidden" name="hdnDept" id="hdnDept" />
            <input type="hidden" name="hdnClass" id="hdnClass" />	
            <input type="hidden" name="hdnSubClass" id="hdnSubClass" />
            <input type="hidden" name="hdnCompCode" id="hdnCompCode" />
            <br />
            <form id="formSTS">    	
            <input type="hidden" id="hdnSuppCode" name="hdnSuppCode" />
            <table width="80%" border="0" cellspacing="2" cellpadding="0" align="center">
                <tr>
                	<td  class="hd">Ref. No.: </td>
                    <td><label><span id="txtRefNo" name="txtRefNo"></span></label></td>
                	<td  class="hd">Company: </td>
                    <td><? $transObj->DropDownMenu($transObj->makeArr($transObj->getAllCompany(),'compCode','compShortName',''),'cmbCompCode','','class="selectBox"'); ?></td>
                </tr>
                <tr>
                  	<td  class="hd">Amount: </td>
                    <td><input type="text" name="txtSTSAmount" id="txtSTSAmount" class="textBox" onKeyPress='return valNumInputDec(event);'/></td>
                    <td class="hd">Remarks: </td>
                    <td><input type="text" name="txtRemarks" id="txtRemarks" class="textBox"/></td>
                </tr>
                <tr>
                    <td class="hd">Supplier: </td>
                    <td><input type="text" name="txtSupp" id="txtSupp" class="textBox"/></td>
                    <td class="hd">Payment Mode: </td> 
                    <td><? $transObj->DropDownMenu($arrMode,'cmbPayType','','class="selectBox" onchange="disablePayType(this.value); "'); ?></td>
                </tr>
                <tr >
                	<td class="hd">Department: </td>
                    <td><? $transObj->DropDownMenu($transObj->makeArr($transObj->getAllDept(),'stsTransTypeDept','stsTransTypeName',''),'cmbDept','','class="selectBox"  onchange="loadClass(this.value);"'); ?></td>
                    <td class="hd">Terms: </td>
                    <td><input type="text" name="txtTerms" id="txtTerms" class="textBox" onKeyPress='return valNumInputDec(event);'/></td>
                </tr>
                <tr>
                    <td class="hd">Class: </td>
                    <td id="tdClass"><select class="selectBox" id="cmbDummy2"><option></option></select></td>
                    <td class="hd">Number of Applications: </td>
                    <td><input type="text" name="txtNoApplications" id="txtNoApplications" class="textBox" onKeyPress='return valNumInputDec(event);'/></td>
                </tr>
                <tr>
                    <td class="hd">Sub-Class: </td>
                    <td id="tdSubClass"><select class="selectBox" id="cmbDummy2"><option></option></select></td>
                    <td class="hd">Application Date: </td>
                    <td><input type="text" name="txtApDate" id="txtApDate" class="textBox" readonly="readonly" /></td>
                </tr>
            </table>
            </form> 
           
            <div align="center" id="buttonHolder" name="buttonHolder">
            	<button id="saveHdr" name="saveHdr">Save</button>
            </div>
             <br />
            </div><br />
            <button id="regSTSDetail" name="regSTSDetail" style="visibility:hidden;">Add Detail</button>
            <button id="regSTSDelDetail" name="regSTSDelDetail" style="visibility:hidden;">Delete Detail</button>
            <div id="Data">           
			</div>  
        </div>
        <div id='dialogProcess' style=" overflow:hidden;"><br />
            <div style="text-align:center"><img src="../../images/progress2.gif"/></div>
            <div id='Process' style="text-align:center"></div>
        </div> 
        <div id='dialogAlert' title='STS'>
            <p id='dialogMsg'></p>
    	</div>
        <div id="divSTSDtls" ></div>
	</div>
  
</body>
</html>