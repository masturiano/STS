<?
session_start();
include("../../includes/db.inc.php");
include("../../includes/common.php");
include("efdObj.php");
$efdObj = new efdObj();
if ($_SESSION['sts-username'] == "") {
	echo "<SCRIPT>window.parent.location.href='../../index.php';</SCRIPT>";
}
switch($_GET['action']){	
	
	case 'PrintSum':
		echo "window.open('green_cross_xls.php?{$_SERVER['QUERY_STRING']}');";
	exit();
	break;
}
$arrMode = array('R'=>"APPROVED",'O'=>"UNAPPROVED");
$arrType = array("DET"=>"DETAILED","SUM"=>"SUMMARIZED");
$arrTran = array("0"=>'ALL','1'=>"REGULAR STS",'2'=>"LISTING FEE",'4'=>"SHELF ENHANCER",'5'=>"DISPLAY ALLOWANCE",'6'=>"PUSH GIRL",'7'=>"SAMPLING DEMO");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>

<link type="text/css" href="../../includes/jquery/css/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<link type="text/css" href="../../includes/jquery/development-bundle/demos/demos.css" rel="stylesheet" />
<script type="text/javascript" src="../../includes/jquery/js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="../../includes/jquery/js/jquery-ui-1.8.16.custom.min.js"></script>


<script type="text/javascript">
	    $(function(){
		    $('#txtDateYear').datepicker
            ({
		        changeMonth: false,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'yy',
                onClose: function(dateText, inst) 
                { 
                    var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                    var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                    $(this).datepicker('setDate', new Date(year, 1));
                }	
		    });
                    
		    $("#printInq, #printInqXls").button({
			    icons: {
				    primary: 'ui-icon-print',
			    }
		    });
   
		    $("#printInqXlsSum").click( function (){
			    var dateYear = $('#txtDateYear').val();
                if(dateYear == '')
                {
                    $('#txtDateYear').addClass('ui-state-error');    
                }
                else
                {
                    $.ajax({
                        url: 'green_cross.php',
                        type: "GET",
                        data: $("#formInq").serialize()+'&action=PrintSum',
                        success: function(Data){
                            eval(Data);
                        }                
                    });            
                }  
		    });
	    });

    function valDateStartEnd(valStart,valEnd,id1,id2) {
	    var parseStart = Date.parse(valStart);
	    var parseEnd = Date.parse(valEnd);
	    if (valStart !='' && valEnd !='') {
		    if(parseStart > parseEnd) {
			    $('#'+id1).addClass('ui-state-error');
			    $('#'+id2).addClass('ui-state-error');
			    dialogAlert("Date 'TO' Must Be Greater than Date 'FROM'");		
			    return false;
		    } else {
			    $('#'+id1).removeClass('ui-state-error');
			    $('#'+id2).removeClass('ui-state-error');	
			    return true;
		    }
	    }else {
		    $('#'+id1).addClass('ui-state-error');
		    $('#'+id2).addClass('ui-state-error');
		    dialogAlert("Please Select Month Range");			
		    return false;
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
    function validateString(ObjName){
		    if(ObjName.val().length == 0){
			    ObjName.addClass("ui-state-error");
			    return false;
		    }
		    else{
			    ObjName.removeClass("ui-state-error");
			    return true;
		    }
	    }
    function validateInputs() {
		    var val = true;
		    if (validateCmb($("#cmbTran"))== false){
			    dialogAlert("Required Field!");		
			    val = false;	
		    }
		    return val;
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
	width:250px; 
	height:22px;
	font-size: 11px;
}
.hd {
	font-size: 11px;
	font-family: Verdana;
	font-weight: bold;
}
.ui-datepicker-calendar 
{
    display: none;
}

.btn {
  background: #3498db;
  background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
  background-image: -moz-linear-gradient(top, #3498db, #2980b9);
  background-image: -ms-linear-gradient(top, #3498db, #2980b9);
  background-image: -o-linear-gradient(top, #3498db, #2980b9);
  background-image: linear-gradient(to bottom, #3498db, #2980b9);
  -webkit-border-radius: 5;
  -moz-border-radius: 5;
  border-radius: 5px;
  -webkit-box-shadow: 0px 1px 3px #666666;
  -moz-box-shadow: 0px 1px 3px #666666;
  box-shadow: 0px 1px 3px #666666;
  font-family: Arial;
  color: #ffffff;
  font-size: 12px;
  padding: 5px 20px 5px 20px;
  border: solid #1f628d 1px;
  text-decoration: none;
}

.btn:hover {
  background: #3cb0fd;
  text-decoration: none;
}
</style>
</head>

<body>


<h2 class="ui-widget-header ui-corner-all" style="padding:5px;">GREEN CROSS</h2>
<div class="ui-widget-content" style="padding:5px;">
<? $arrDist = array("ALL"=>"ALL","ACC"=>"ACC","ACE"=>"ACE","CHA"=>"CHA","ECO"=>"ECO","EDI"=>"EDI"); ?>
<center>
    <form name="formInq" id="formInq">
	<table width="90%" border="0" cellspacing="3" cellpadding="2">
    	<tr>
            <td class="hd" width="15%"><strong> Year </strong></td>
        	<td class="hd" width="1%"><strong> : </strong></td>
           	<td><input type="text" name="txtDateYear" id="txtDateYear" readonly="readonly" class="textBox" style="width: 100px;"/></td>
        </tr>
        <tr>
  			<td class="hd" ><strong> Store </strong></td>
            <td class="hd"><strong> : </strong></td>
           	<td><? $efdObj->DropDownMenu($efdObj->makeArr($efdObj->getBranches(),'strCode','brnDesc',''),'cmbStore','','class="selectBox"'); ?></td>
        </tr> 
	</table>
    </form>
    <table>
        <tr>
            <td align="center" colspan="3" style="padding-top: 10px;">
            <button id="printInqXlsSum" class="btn">PRINT IN EXCEL SUMMARY</button></td>
       </tr> 
    </table>
</center>
    <div id='dialogAlert' title='STS'>
            <p id='dialogMsg'></p>
    	</div>
</div>
</body>
</html>