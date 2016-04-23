<?
session_start();
include("../../includes/db.inc.php");
include("../../includes/common.php");
include("adminObj.php");
$adminObj = new adminObj();



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
		
		$totUser = $adminObj->countUsers();
		
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
			$arrSTS= $adminObj->searchUsers($sidx,$sord,$start,$limit,$_GET['searchField'],$_GET['searchString']);
		}else{
			$arrSTS = $adminObj->getPaginatedUsers($sidx,$sord,$start,$limit);
		}
		$i = 0;
		foreach($arrSTS as $val){
			$response->rows[$i]['id']=$val['userId']."-".$val['userId']; 
			$response->rows[$i]['cell']=array($val['userId'],$val['fullName'],$val['userName'],$val['prodName'],'active');
			$i++;
		}
		echo json_encode($response);
	exit();
	break;
	
	case 'addUser':
		if($adminObj->checkIfUnameExists($_GET['txtUserName']) == 0){
			if($adminObj->addUser($_GET)){
				echo "$('#dialogAdd').dialog('close');\n";
				echo "dialogAlert('User succesfully added!');";
			}else{
				echo "dialogAlert('There was an error adding a new user!');";	
			}
		}else{
			echo "dialogAlert('Username already exists!');";
		}
	exit();
	break;
	
	case 'getInfo':
			$arrInfo = $adminObj->userInfo($_GET['userId']);
			echo "$('#txtUserName').val('{$arrInfo['userName']}');\n";
			echo "$('#txtUserName').attr('readOnly',true);\n";
			echo "$('#txtFullName').val('{$arrInfo['fullName']}');\n";
			echo "$('#cmbDepartment').val('{$arrInfo['grpCode']}');\n";
			echo "$('#cmbStr').val('{$arrInfo['strCode']}');\n";
	exit();
	break;
	
	case 'updateUser':
		if ($adminObj->UpdateUserInfo($_GET)){
				echo '$("#dialogAdd").dialog("close");';
				echo "$('#txtUserName').removeAttr('readOnly');";
				echo "dialogAlert('User successfully updated.');";	
			} else {
				echo "dialogAlert('Error updating User Access.');";
			}	
	exit();
	break;
	
	case 'deleteUser':
		if($adminObj->deleteUser($_GET['userId'])){
			echo "dialogAlert('User succesfully deleted.')";
		}else{
			echo "dialogAlert('There was an error during the deletion of user!')";	
		}
	exit();
	break;
	
	case 'resetPassword':
		if($adminObj->resetPassword($_GET['userId'])){
			echo "dialogAlert('Password has been reset!')";
		}else{
			echo "dialogAlert('There was an error during the password reset.')";
		}
	exit();
	break;
	
	case "ModuleAccess";
			if ($adminObj->ModuleAccess($_GET['pages'],(int)$_GET['userId']))	{
				echo '$("#Access").dialog("close");';
				echo "dialogAlert('User Module Access successfully updated.');";
			} else {
				echo "dialogAlert('Error updating User Module Access.');";
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
	
	$("#userTable").jqGrid({ 
		url:'users.php?action=Load', 
		datatype: "json", 
		colNames:['ID','Name','Username','Department', 'Status'], 
		colModel:[ 
			{name:'userId', index:'userId', width:40, align:"right"}, 
			{name:'fullName', index:'fullName', width:180, align:"left"}, 
			{name:'userName', index:'userName', width:80, align:"center"}, 
			{name:'userName', index:'userName', width:130, align:"left"}, 
			{name:'userName', index:'userName', width:40, align:"center"}
		], 
		rowNum:20, 
		rowList:[20,40,60], 
		pager: '#userPager', 
		sortname: 'userId', 
		viewrecords: true, 
		sortorder: "desc", 
		caption:"Lists", 
		height:"auto",
		width:"980",
		onSelectRow: function(id){ 
			var id = jQuery("#userTable").jqGrid('getGridParam','selrow'); 
			var ret = jQuery("#userTable").jqGrid('getRowData',id);
			var userId = ret.userId;
			$("#hdnUserId").val(userId);
			$("#editUser").addClass("link");
			$("#editUser").attr("onclick","editUserInfo();");
			$("#deleteUser").addClass("link");
			$("#deleteUser").attr("onclick","DeleteUser();");
			$("#resetPass").addClass("link");
			$("#resetPass").attr("onclick","ResetPassword();");
			$("#moduleAccess").addClass("link");
			$("#moduleAccess").attr("onclick","ModuleAccess();");
	   }
        
	}).navGrid("#userPager",{edit:false,add:false,del:false}); 
	
	
	$("#AddUser").click( function(){ 
		clearAllText();
		$("#dialogAdd").dialog("destroy");
		$("#dialogAdd").dialog({
			title: "NEW USER",
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
							url: "users.php",
							type: "GET",
							traditional: true,
							data: $("#formUser").serialize()+'&action=addUser',
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
	$("#userTable").trigger("reloadGrid"); 
	$("#editUser").removeClass("link");
	$("#editUser").addClass("disable");
	$("#deleteUser").removeClass("link");
	$("#deleteUser").addClass("disable");
	$("#moduleAccess").removeClass("link");
	$("#moduleAccess").addClass("disable");
	$("#resetPass").removeClass("link");
	$("#resetPass").addClass("disable");
}
function clearAllText(){
	$("#txtUserName, #txtFullName").val("");	
	$("#cmbDepartment,#cmbStr").val("0");
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
	if (validateString($("#txtUserName"))== false)
		val = false;
	if (validateString($("#txtFullName"))== false)
		val = false;
	if (!validateFixedString($("#cmbDepartment"),0)) { 
		val =  false;
	} else {
		$("#cmbDepartment").removeClass("ui-state-error");
	}
	if (!validateFixedString($("#cmbStr"),0)) { 
		val =  false;
	} else {
		$("#cmbStr").removeClass("ui-state-error");
	}
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
function editUserInfo(){
	clearAllText();
	var userId = $("#hdnUserId").val();
	$.ajax({
		url: "users.php",
		type: "GET",
		traditional: true,
		data: 'action=getInfo&userId='+userId,
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
						url: "users.php",
						type: "GET",
						traditional: true,
						data: $("#formUser").serialize()+'&action=updateUser',
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
function DeleteUser(){
	var userId = $("#hdnUserId").val();	
	$("#dialogMsg").html('Are you sure you want to delete?');
	$("#dialogAlert").dialog("destroy");
	$("#dialogAlert").dialog({
		modal: true,
		buttons: {
			'Yes': function(){
				$.ajax({
					url: "users.php",
					type: "GET",
					traditional: true,
					data: 'action=deleteUser&userId='+userId,
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
function ResetPassword(){
	var userId = $("#hdnUserId").val();	
	$("#dialogMsg").html("Are you sure you want to reset the users password?");
	$("#dialogAlert").dialog("destroy");
	$("#dialogAlert").dialog({
		modal: true,
		buttons: {
			'Yes': function(){
				$.ajax({
					url: "users.php",
					type: "GET",
					traditional: true,
					data: 'action=resetPassword&userId='+userId,
					success: function(msg){
						eval(msg);	
						reloadGrid();
					}
				});
			},
			'No': function(){
				$(this).dialog('close');
				reloadGrid();
			}
		}
	});
}
function ModuleAccess(){
	var userId=$('#hdnUserId').val();
	$.ajax({
		url: 'module_access.php',
		type: "GET",
		data: "userId="+userId,
		success: function(Data){
			$('#AccessData').html(Data);
		}				
	});				
	$("#Access").dialog("destroy");
	$("#Access").dialog({
		title: "Module Access",
		height: 365,
		width: 480,
		modal: true,
		closeOnEscape: false,
		buttons: {
			'Save': function() {
				var pages = getPages();
				$.ajax({
					url: 'users.php',
					type: "GET",
					data: "action=ModuleAccess&pages="+pages+"&userId="+userId,
					success: function(Data){
						eval(Data);
						reloadGrid();
					}				
				});													
			}
		},
		close: function() {
			$('#AccessData').html('');
		}				
	});				
}
function getPages() {
	var modCnt = $("#hdmod").val();
	var subCnt;
	var subpage;
	var index;
	var pages = new Array();
	for(i=1;i<=modCnt;i++){
		subCnt = $("#hdsub_"+i).val();
		for(x=0;x<=subCnt-1;x++){
			subpage = $("#checkBox"+i+"_"+x).val();
			if ($("#checkBox"+i+"_"+x).attr("checked")) {
				index = pages.length;
				pages[index] = subpage;
			}
		}
	}	
	pages.sort();	
	return pages;
}	
function checkAll(Obj,chkBox){
	var chldCnt = $("#"+Obj).val();
	for(i=0;i<=chldCnt-1;i++){
		$("#"+chkBox+"_"+i).attr("checked",true);
	}
}
function unCheckAll(Obj,chkBox){
	var chldCnt = $("#"+Obj).val();
	for(i=0;i<=chldCnt-1;i++){
		$("#"+chkBox+"_"+i).attr("checked",false);
	}
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
	height: 110px;
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

<h2 class=" ui-widget-header">USER'S MAINTENANCE</h2>
<br />
<span id="AddUser" style="cursor: pointer; font-size:11px;" class="link" title="New User">
	<img src="../../images/file_add.png" title="Add User" /> New
</span>
&nbsp;&nbsp;&nbsp;&nbsp;
<span class="style23, disable" id="editUser" title="Edit User" onclick="">
	<img src="../../images/file_edit.png" title="Edit User" /> Edit
</span>
&nbsp;&nbsp;&nbsp;&nbsp;
<span class="style23, disable" id="deleteUser" title="Delete User" onclick="">
	<img src="../../images/file_delete.png" title="Delete User" /> Delete
</span>
&nbsp;&nbsp;&nbsp;&nbsp;
<span class="style23, disable" id="resetPass" title="Reset Password" onclick="">
	<img src="../../images/file_delete.png" title="Reset Password" /> Reset Password
</span>
&nbsp;&nbsp;&nbsp;&nbsp;
<span class="style23, disable" id="moduleAccess" title="Module Access" onclick="">
	<img src="../../images/tag_green.png" title="Module Access" /> Module Access
</span>
<div align="right" style="float: right;">

</div>
<br /><br />

<center><table id="userTable"></table> </center>
<div id="userPager"></div> <br /> 

	<div style=" visibility:hidden;  overflow:hidden;">
        <div id='dialogAdd' title='Add'>
        	<div class="headerContent ui-corner-all">
            <form id="formUser">
            <input type="hidden" name="hdnUserId" id="hdnUserId" value="" />    	
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="60%" height="25" class="hd">User Name: </td>
                  <td width="%"><input type="text" name="txtUserName" maxlength="20"  id="txtUserName" class="textBox" /></td>
                </tr>
                <tr>
                  <td width="60%" height="25" class="hd">Full Name: </td>
                  <td width="%"><input type="text" name="txtFullName" maxlength="100"  id="txtFullName" class="textBox" /></td>
                </tr>
                <tr>
                  <td width="60%" height="25" class="hd">Store Assigned: </td>
                  <td width="%"><? $adminObj->DropDownMenu($adminObj->makeArr($adminObj->getBranches(),'strCode','brnDesc',''),'cmbStr','','class="selectBox"'); ?></td>
                </tr>
                  <td class="hd">Product Group: </td>
                  <td><? $adminObj->DropDownMenu($adminObj->makeArr($adminObj->getGroups(),'grpCode','grpDesc',''),'cmbDepartment','','class="selectBox"'); ?></td>
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