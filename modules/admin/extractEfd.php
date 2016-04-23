<?
session_start();

include("../../includes/db.inc.php");
include("../../includes/common.php");
include("extractObj.php");

$extractObj = new extractObj();
	
if ($_SESSION['sts-username'] == "") {
	echo "<SCRIPT>window.parent.location.href='../../index.php';</SCRIPT>";
}
	
switch($_GET['action']) {
    case "processAce":
        
            $directory="C:\wamp\www\STS\importfiles\efd_ace_data";
            $arch_directory="C:\wamp\www\STS\importfiles\archive_ace";
            // create a handler to the directory
            //$dirhandler = opendir($directory);
            // read all the files from directory
            $nofiles=0;
            $checkEmpty  = (count(glob($directory.'*')) === 0) ? 'Empty' : 'Not empty';

            if ($checkEmpty == "Empty"){
                echo "0";
                exit();
            }else{
                $extractObj->truncTempAce();
                if ($dirhandler = opendir($directory)) {
                    while ($file = readdir($dirhandler)) {
                        $file_ext = explode('.',$file);
                        $max_val = count($file_ext);
                        $file_ext = $file_ext[($max_val-1)];
                        $ermsg = "";
    
                        if($file_ext == "csv" || $file_ext == "CSV"){
                            
                            $extractObj->bulkInsertAce($file);
                            copy($directory."\\".$file, $arch_directory."\\".$file);
                        }
                    }
                }
                
                if($extractObj->deleteHeaderAce()){
                    if($extractObj->deleteMonthAce($_GET['txtDateFrom'])){
                        if($extractObj->updateBlankFieldAce()){
                            $extractObj->insertTblEfdAce($_GET['txtDateFrom']);          
                        } 
                    }   
                }
                
                $dirEraseCont = "C:\wamp\www\STS\importfiles\efd_ace_data\\";
                foreach(glob($dirEraseCont.'*.csv*') as $v){
                    unlink($v);
                }
                foreach(glob($dirEraseCont.'*.CSV*') as $v){
                    unlink($v);
                }
            }
                        
        exit();
    break;
    
	case "processEco":
		
            $directory="C:\wamp\www\STS\importfiles\efd_eco_data";
            $arch_directory="C:\wamp\www\STS\importfiles\archive_eco";
            // create a handler to the directory
            //$dirhandler = opendir($directory);
            // read all the files from directory
            $nofiles=0;
            $checkEmpty  = (count(glob($directory.'*')) === 0) ? 'Empty' : 'Not empty';

            if ($checkEmpty == "Empty"){
                echo "0";
                exit();
            }else{
                $extractObj->truncTempEco();
                if ($dirhandler = opendir($directory)) {
                    while ($file = readdir($dirhandler)) {
                        $file_ext = explode('.',$file);
                        $max_val = count($file_ext);
                        $file_ext = $file_ext[($max_val-1)];
                        $ermsg = "";
    
                        if($file_ext == "csv" || $file_ext == "CSV"){
                            
                            $extractObj->bulkInsertEco($file);
                            copy($directory."\\".$file, $arch_directory."\\".$file);
                        }
                    }
                }
                
                if($extractObj->deleteHeaderEco()){
                     if($extractObj->deleteMonthEco($_GET['txtDateFrom'])){
                        if($extractObj->updateBlankFieldEco()){
                            $extractObj->insertTblEfdEco($_GET['txtDateFrom']);         
                        }    
                     }   
                }
                
                $dirEraseCont = "C:\wamp\www\STS\importfiles\efd_eco_data\\";
                foreach(glob($dirEraseCont.'*.csv*') as $v){
                    unlink($v);
                }
                foreach(glob($dirEraseCont.'*.CSV*') as $v){
                    unlink($v);
                }
            }
						
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
    $('#txtDateFrom').datepicker
    ({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'M-yy',
        onClose: function(dateText, inst) 
        { 
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
        }    
    });
        
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
    
    function ProcessAce() {
        
        $.ajax({
        url: "extractEfd.php",
        type: "GET",
        traditional: true,
        data: $("#formUser").serialize()+'&action=processAce',
        beforeSend: function() {
            ProcessData('Processing STS Ace','Open');
        },
        success: function(msg){
                ProcessData('','Close');
                eval(msg);
                ProcessEco();
            }                
       });        
    }
    
	function ProcessEco() {
		$.ajax({
		url: "extractEfd.php",
		type: "GET",
		traditional: true,
		data: $("#formUser").serialize()+'&action=processEco',
		beforeSend: function() {
			ProcessData('Processing STS Eco','Open');
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
</style>

</head>



<body onload="loadProcess();" >
<h2 class=" ui-widget-header">STS END OF DAY PROCESSING</h2>
<div style=" visibility:hidden;">
	<div id='dialogEOD'><br />
	<form id="formUser" name="formUser">
        <table align="center" width="80%" border="0">
            <tr>
                <td class="hd" width="60%"><strong> Month </strong></td>
                <td class="hd" width="1%"><strong> : </strong></td>
                   <td><input type="text" name="txtDateFrom" id="txtDateFrom" readonly="readonly" class="textBox" style="width: 100px;"/></td>
            </tr>
            <tr>
                <td class="hd" width="15%" colspan="3"><strong> </strong></td>
            </tr>
        </table> 
        <table width="100%" border="0" cellspacing="0" cellpadding="0">  <tr>
        	<tr>  
            <td width="20%" align="center">
            	<button id="processEOD" name="processEOD" style="width:200px; font-size:16px; padding:20px;" onclick="ProcessAce()">Extract EFD</button></td>
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




