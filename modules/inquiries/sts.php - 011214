<?
include("../../includes/db.inc.php");
include("../../includes/common.php");
include("inquiriesObj.php");
$inqObj = new inquiriesObj();

$arrComp = array('1001'=>'CLARK','1002'=>'SUBIC');
switch($_GET['action']){	
	
	case 'searchStsRefNo':
		$check = $inqObj->checkStsRefNo($_GET['stsRefNo']); 
		echo "$('#trigger').val('0');";
		if ($check==0){
			echo "$('#details').fadeOut('slow');
				  $('#inquiry').dialog({
						height: 235
				  });";
			echo "$('#errorMsg').html('STS Ref Number Not Fund');\n";
			echo "$('#txtRefNo').addClass('error');";
			echo "$('#errorMsg').addClass('ui-widget');\n";
			echo "$('#errorMsg').addClass('ui-state-error');\n";
			echo "$('#errorMsg').addClass('ui-corner-all');\n";	
			
		}
		else{	
			$arrSTS = $inqObj->getSTSHdr($_GET['stsRefNo']);
			$upAmt = $inqObj->getAmt($_GET['stsRefNo'],'U','R');
			$qAmt = $inqObj->getAmt($_GET['stsRefNo'],'Q','R');
			echo "$('#errorMsg').html('');
					$('#errorMsg').removeClass('ui-widget');
					$('#errorMsg').removeClass('ui-state-error');
					$('#errorMsg').removeClass('ui-corner-all');
					$('#txtRefNo').removeClass('error');
					$('#txtSTSNo').removeClass('error');
					$('#txtContractNo').removeClass('error');";
			echo "$('#details').show('slow');
					$('#inquiry').dialog({
						height: 500
				  	});";			

			echo "$('#txtStsRefNo').html('{$arrSTS['stsRefno']}');\n";
			echo "$('#txtStsNo').html('".$arrSTS['stsStartNo']." - ".$arrSTS['stsEndNo']."');\n";
			if(date('m.d.Y',strtotime($arrSTS['dateApproved']))=='01.01.1970'){
				$dateApproved = "-";
			}else{
				$dateApproved = date('m.d.Y',strtotime($arrSTS['dateApproved']));	
			}
			echo "$('#txtStsDate').html('".$dateApproved."');\n";
			echo "$('#txtAppStart').html('".date('m.d.Y',strtotime($arrSTS['applyDate']))."');\n";
			if($arrSTS['stsType']==5){
			echo "$('#txtAppEnd').html('".date('m.d.Y',strtotime($arrSTS['endDateApp5']))."');\n";
			}
			else{
			echo "$('#txtAppEnd').html('".date('m.d.Y',strtotime($arrSTS['endDate']))."');\n";
			}
			echo "$('#appDate').html('{$arrSTS['nbrApplication']}');\n";
			echo "$('#txtSuppName').html('".addslashes($arrSTS['suppName'])."');\n";
			echo "$('#txtSuppCode').html('".addslashes($arrSTS['suppCode'])."');\n";
			echo "$('#txtComp').html('');\n";
			echo "$('#txtStsAmt').html('". number_format($arrSTS['stsAmt'],2)."');\n";
			echo "$('#txtDept').html('".addslashes($arrSTS['hierarchyDesc'])."');\n";
			echo "$('#remarks').html('".addslashes($arrSTS['stsRemarks'])."');\n";
			echo "$('#txtPayMode').html('{$arrSTS['payMode']}');\n";
			echo "$('#txtCreatedBy').html('".addslashes($arrSTS['fullName'])."');\n";
			echo "$('#txtUpAmt').html('". number_format($upAmt,2)."');\n";
			echo "$('#txtQueAmt').html('". number_format($qAmt,2)."');\n";
			echo "$('#txtContractNo').html('".$arrSTS['contractNo']."');\n";
			echo "$('#txtDateCreated').html('".date('m.d.Y',strtotime($arrSTS['dateEntered']))."');\n";
			if($arrSTS['stsStat']=='R'){
				$stat = "Approved";	
			}elseif($arrSTS['stsStat']=='O'){
				$stat = "Open";	
			}else{
				$stat = "Cancelled";	
			}
			echo "$('#txtStatus').html('".$stat."');\n";
			if(date('m.d.Y',strtotime($arrSTS['cancelDate'])) =='01.01.1970'){
				$cancelled = '-';	
			}else{
				$cancelled = date('m.d.Y',strtotime($arrSTS['cancelDate']));
			}
			echo "$('#txtDateCancelled').html('".$cancelled."');\n";
			echo "$('#txtApprovedBy').html('".$arrSTS['approvedBy']."');\n";
		}
	
	exit();
	break;
	
	case 'searchSTS':
		$check = $inqObj->checkStsNo($_GET['stsNo']); 
		if ($check==0){
			echo "$('#details').fadeOut('slow');
				  $('#inquiry').dialog({
						height: 235
				  });";
			echo "$('#errorMsg').html('STS Number Not Fund');\n";
			echo "$('#txtSTSNo').addClass('error');";
			echo "$('#errorMsg').addClass('ui-widget');\n";
			echo "$('#errorMsg').addClass('ui-state-error');\n";
			echo "$('#errorMsg').addClass('ui-corner-all');\n";	
			echo "$('#trigger').val('0');";
		}
		else{	
			$arrSTS = $inqObj->getSTSDet($_GET['stsNo']);
			$upAmt = $inqObj->getAmt($_GET['stsNo'],'U','S');
			$qAmt = $inqObj->getAmt($_GET['stsNo'],'Q','S');
			echo "$('#errorMsg').html('');
					$('#errorMsg').removeClass('ui-widget');
					$('#errorMsg').removeClass('ui-state-error');
					$('#errorMsg').removeClass('ui-corner-all');
					$('#txtRefNo').removeClass('error');
					$('#txtSTSNo').removeClass('error');
					$('#txtContractNo').removeClass('error');";
					
			echo "$('#details').show('slow');
					$('#inquiry').dialog({
						height: 500
				  	});";			
			echo "$('#trigger').val('1');";
			echo "$('#txtStsRefNo').html('{$arrSTS['stsRefno']}');\n";
			echo "$('#txtStsNo').html('{$arrSTS['stsNo']}');\n";
			echo "$('#txtStsDate').html('".date('m.d.Y',strtotime($arrSTS['dateApproved']))."');\n";
			echo "$('#txtAppStart').html('".date('m.d.Y',strtotime($arrSTS['applyDate']))."');\n";
			if($arrSTS['stsType']==5){
			echo "$('#txtAppEnd').html('".date('m.d.Y',strtotime($arrSTS['endDateApp5']))."');\n";
			}
			else{
			echo "$('#txtAppEnd').html('".date('m.d.Y',strtotime($arrSTS['endDate']))."');\n";
			}
			echo "$('#appDate').html('{$arrSTS['nbrApplication']}');\n";
			echo "$('#txtSuppName').html('".addslashes($arrSTS['suppName'])."');\n";
			echo "$('#txtSuppCode').html('".addslashes($arrSTS['suppCode'])."');\n";
			echo "$('#txtComp').html('".$arrSTS['strCode']." - ".$arrSTS['brnShortDesc']."');\n";
			echo "$('#txtStsAmt').html('". number_format($arrSTS['stsAmt'],2)."');\n";
			echo "$('#txtDept').html('".addslashes($arrSTS['hierarchyDesc'])."');\n";
			echo "$('#remarks').html('".addslashes($arrSTS['stsRemarks'])."');\n";
			echo "$('#txtPayMode').html('{$arrSTS['payMode']}');\n";
			echo "$('#txtCreatedBy').html('".addslashes($arrSTS['fullName'])."');\n";
			echo "$('#txtUpAmt').html('". number_format($upAmt,2)."');\n";
			echo "$('#txtQueAmt').html('". number_format($qAmt,2)."');\n";
			echo "$('#txtContractNo').html('{$arrSTS['contractNo']}');\n";
			echo "$('#txtDateCreated').html('".date('m.d.Y',strtotime($arrSTS['dateEntered']))."');\n";
			if($arrSTS['dtlStatus']=='R'){
				$stat = "Approved";	
			}elseif($arrSTS['dtlStatus']=='O'){
				$stat = "Open";	
			}else{
				$stat = "Cancelled";	
			}
			
			echo "$('#txtStatus').html('".$stat."');\n";
			if(date('m.d.Y',strtotime($arrSTS['cancelDate'])) =='01.01.1970'){
				$cancelled = '-';	
			}else{
				$cancelled = date('m.d.Y',strtotime($arrSTS['cancelDate']));
			}
			if($arrSTS['dtlStatus']=='C'){
				$cancelled = date('m.d.Y',strtotime($inqObj->getCancelledDate($arrSTS['stsNo'])));
			}
			echo "$('#txtDateCancelled').html('".$cancelled."');\n";
			echo "$('#txtApprovedBy').html('".$arrSTS['approvedBy']."');\n";
		}
	
	exit();
	break;
	
	case 'searchContractNo':
		echo "$('#trigger').val('0');";
		$check = $inqObj->checkContractNo($_GET['contractNo']); 
		if ($check==0){
			echo "$('#details').fadeOut('slow');
				  $('#inquiry').dialog({
						height: 235
				  });";
			echo "$('#errorMsg').html('Contract Number Not Fund');\n";
			echo "$('#txtContractNo').addClass('error');";
			echo "$('#errorMsg').addClass('ui-widget');\n";
			echo "$('#errorMsg').addClass('ui-state-error');\n";
			echo "$('#errorMsg').addClass('ui-corner-all');\n";	
		}
		else{	
			$arrSTS = $inqObj->getSTSHdrContract($_GET['contractNo']);
			$upAmt = $inqObj->getContractAmt($_GET['contractNo'],'U');
			$qAmt = $inqObj->getContractAmt($_GET['contractNo'],'Q');
			echo "$('#errorMsg').html('');
					$('#errorMsg').removeClass('ui-widget');
					$('#errorMsg').removeClass('ui-state-error');
					$('#errorMsg').removeClass('ui-corner-all');
					$('#txtRefNo').removeClass('error');
					$('#txtSTSNo').removeClass('error');
					$('#txtContractNo').removeClass('error');";
					
			echo "$('#details').show('slow');
					$('#inquiry').dialog({
						height: 500
				  	});";			

			echo "$('#txtStsRefNo').html('{$arrSTS['stsRefno']}');\n";
			echo "$('#txtStsNo').html('{$arrSTS['stsNo']}');\n";
			echo "$('#txtStsDate').html('".date('m.d.Y',strtotime($arrSTS['dateApproved']))."');\n";
			echo "$('#txtAppStart').html('".date('m.d.Y',strtotime($arrSTS['applyDate']))."');\n";
			if($arrSTS['stsType']==5){
			echo "$('#txtAppEnd').html('".date('m.d.Y',strtotime($arrSTS['endDateApp5']))."');\n";
			}
			else{
			echo "$('#txtAppEnd').html('".date('m.d.Y',strtotime($arrSTS['endDate']))."');\n";
			}
			echo "$('#appDate').html('{$arrSTS['nbrApplication']}');\n";
			echo "$('#txtSuppName').html('".addslashes($arrSTS['suppName'])."');\n";
			echo "$('#txtSuppCode').html('".addslashes($arrSTS['suppCode'])."');\n";
			echo "$('#txtComp').html('{$arrSTS['brnShortDesc']}');\n";
			echo "$('#txtStsAmt').html('". number_format($arrSTS['stsAmt'],2)."');\n";
			echo "$('#txtDept').html('".addslashes($arrSTS['hierarchyDesc'])."');\n";
			echo "$('#remarks').html('".addslashes($arrSTS['stsRemarks'])."');\n";
			echo "$('#txtPayMode').html('{$arrSTS['payMode']}');\n";
			echo "$('#txtCreatedBy').html('".addslashes($arrSTS['fullName'])."');\n";
			echo "$('#txtUpAmt').html('". number_format($upAmt,2)."');\n";
			echo "$('#txtQueAmt').html('". number_format($qAmt,2)."');\n";
			echo "$('#txtContractNo').html('{$arrSTS['contractNo']}');\n";
			echo "$('#txtDateCreated').html('".date('m.d.Y',strtotime($arrSTS['dateEntered']))."');\n";
			if($arrSTS['stsStat']=='R'){
				$stat = "Approved";	
			}elseif($arrSTS['stsStat']=='O'){
				$stat = "Open";	
			}else{
				$stat = "Cancelled";	
			}
			echo "$('#txtStatus').html('".$stat."');\n";
			if(date('m.d.Y',strtotime($arrSTS['cancelDate'])) =='01.01.1970'){
				$cancelled = '-';	
			}else{
				$cancelled = date('m.d.Y',strtotime($arrSTS['cancelDate']));
			}
			echo "$('#txtDateCancelled').html('".$cancelled."');\n";
			echo "$('#txtApprovedBy').html('".$arrSTS['approvedBy']."');\n";
		}
	exit();
	break;
	
	case "PrintRefs":
		if($_GET['contractNo']==""){
			echo "dialogAlert('Invalid Contract Number');";
		}
		else{
			echo "window.open('sts_refs_excel.php?stsNo={$_GET['stsNo']}&contractNo={$_GET['contractNo']}');";
		}
		exit();
	break;
	
	case 'PrintContract':
		if($_GET['refNo']==""){
				echo "dialogAlert('Invalid Reference Number');";
		}else{		
			echo "window.open('../transactions/report/enhancerAgreement_pdf.php?refNo={$_GET['refNo']}');";
		}
	exit();
	break;
	
	case 'PrintSTS':
		if($_GET['refNo']==""){
				echo "dialogAlert('Invalid Reference Number');";
		}else{		
			echo "window.open('../transactions/report/stsContent_pdf.php?refNo={$_GET['refNo']}');";
		}
	exit();
	break;
	
	case 'PrintDetails':
		if($_GET['stsNo']==""){
				echo "dialogAlert('Invalid STS Number');";
		}else{		
			echo "window.open('stsInquiry_pdf.php?stsNo={$_GET['stsNo']}');";
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
<link type="text/css" href="../../includes/jquery/css/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<link type="text/css" href="../../includes/jquery/development-bundle/demos/demos.css" rel="stylesheet" />
<script type="text/javascript" src="../../includes/jquery/js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="../../includes/jquery/js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript">
		
		function stsInquiry() {
			$('#details').hide();
			$('table.details td').addClass('line');
			$("#inquiry").dialog("destroy");
			$("#inquiry").dialog({
			title: "STS INQUIRY",
			height: 235,
			width: 700,
			modal: true,
			closeOnEscape: false,
			position: {
				my: 'center',
				at: 'top'
			},
			buttons:{
				'Print Details': function() {
					var stsNo = $('#txtStsNo').html();
					if($("#trigger").val()==1){
						$.ajax({
								url: "sts.php",
								type: "GET",
								traditional: true,
								data: 'action=PrintDetails&stsNo='+stsNo,
								beforeSend: function() {
									ProcessData('Generating Contract','Open');
								},
								success: function(msg){
									ProcessData('','Close');
									eval(msg);
								}				
						 });	
					}else{
						alert("Invalid");	
					}
				},
				'Print Contract': function() {
					var stsRefNo = $('#txtStsRefNo').html();
					$.ajax({
							url: "sts.php",
							type: "GET",
							traditional: true,
							data: 'action=PrintContract&refNo='+stsRefNo,
							beforeSend: function() {
								ProcessData('Generating Contract','Open');
							},
							success: function(msg){
								ProcessData('','Close');
								eval(msg);
							}				
		   			 });		
				},
				'Print STS': function() {
					var stsRefNo = $('#txtStsRefNo').html();
					$.ajax({
							url: "sts.php",
							type: "GET",
							traditional: true,
							data: 'action=PrintSTS&refNo='+stsRefNo,
							beforeSend: function() {
								ProcessData('Generating STS Report....','Open');
							},
							success: function(msg){
								ProcessData('','Close');
								eval(msg);
							}				
		   			 });		
				},
				'Print Participants Under this Contract': function() {
					var stsNo = $('#txtStsRefNo').html();
					var contractNo = $('#txtContractNo').html();
					$.ajax({
							url: "sts.php",
							type: "GET",
							traditional: true,
							data: 'action=PrintRefs&stsNo='+stsNo+'&contractNo='+contractNo,
							beforeSend: function() {
								ProcessData('Generating Report','Open');
							},
							success: function(msg){
								ProcessData('','Close');
								eval(msg);
							}				
		   			 });	
				},	
			},
			beforeClose: function(event, ui) {
				return false;
			},
		
		});									
	}
	
	function searchSTSRef(){
		clearFields();
		var stsRefNo = $('#txtRefNo').val();
		clearBox();
		$.ajax({
			url: "sts.php",
			type: "GET",
			traditional: true,
			data: 'action=searchStsRefNo&stsRefNo='+stsRefNo,
			beforeSend: function() {
				ProcessData('Searching STS Reference Number...','Open');
			},
			success: function(msg){
				ProcessData('','Close');
				eval(msg);
			}				
		});		
	}
	
	function searchSTSNo(){
		clearFields();
		var stsNo = $('#txtSTSNo').val();
		clearBox();
		$.ajax({
			url: "sts.php",
			type: "GET",
			traditional: true,
			data: 'action=searchSTS&stsNo='+stsNo,
			beforeSend: function() {
				ProcessData('Searching STS Number...','Open');
			},
			success: function(msg){
				ProcessData('','Close');
				eval(msg);
			}				
		});		
	}
	function searchContractNo(){
		clearFields();
		var contractNo = $('#txtConNo').val();	
		clearBox();
		$.ajax({
			url: "sts.php",
			type: "GET",
			traditional: true,
			data: "action=searchContractNo&contractNo="+contractNo,
			beforeSend: function(){
				ProcessData("Searching Contract Number...","Open");
			},
			success: function(msg){
				ProcessData("","Close");	
				eval(msg);
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
	  if ( (key == null) || (key == 0) || (key == 8) || (key == 13) || (key == 27) )
		return true;
	  // Check to see if it's a number
	  keyChar =  String.fromCharCode(key);
	 if ( (/\d/.test(keyChar)) || (/\./.test(keyChar)) ) {
		 window.status = "";
		 return true;
		} 
	  else {
		window.status = "Field accepts numbers only.";
		return false;
	   }
	}	
	function clearBox(){
		$('#txtRefNo,#txtSTSNo,#txtConNo').val('');
	}
	function clearFields(){
		$('.txtLabel').empty();
	}
	function ProcessData(msg,act) {
		if (act=='Open') {
			$("#dialogProcess").dialog("destroy");
			$("#Process").html(msg)
				$("#dialogProcess").dialog({
				title: 'PG STS',
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
<!--
.textBox {
	border: solid #999999; 
	border-width: 1px; 
	width:90px; 
	height:18px;
	font-size: 11px;
}
.error {
	background: #f8dbdb;
	border-color: #e77776;
}
#formFundsInq select{
	border: 1px solid #999999; 
	width:192px; 
	height:22px;
	font-size: 11px;
}
#formFundsInq select.error {
	background: #f8dbdb;
	border-color: #e77776;
}
#formFundsInq input.error {
	background: #f8dbdb;
	border-color: #e77776;
}

.selectBox {
	border: 1px solid #999999; 
	width:110px; 
	height:22px;
	font-size: 11px;
}
.txtLabel {
	font: Verdana;
	font-size:11px;
	height:25px;
}
.txtLabelH {
	font: Verdana;
	font-size:11px;
	height:25px;
	font-weight:bold;
}
.headerContent2 {
	height: 85px;
	width: 667px;
	border: solid #666666 1px;
	padding: 3px;
	background-color:#CCCCCC;
}
.headerContent1 {
	height: 258px;
	width: 667px;
	border: solid #666666 1px;
	padding: 3px;
	background-color:#CCCCCC;
}

.headerContentPart {
	height: 33px;
	border: solid #666666 1px;
	padding: 3px;
	background-color:#CCCCCC;
}
.errMsg {
	height:20px;
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
.line { 
	border-bottom: solid 1px; 
	border-bottom-color: #C3C3C3;
}

-->
</style>         

</head>

<body  onload="stsInquiry();">

<div id='inquiry' title="STS Inquiry">
    <form id="formSTSInq" name="formSTSInq">
    
    <div class="headerContent2 ui-corner-all">
    <fieldset>
    <legend>Search</legend>
     <table width = "100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="6"><div id="errorMsg" class="errMsg" name="errorMsg"></div></td>
        </tr>
      <input type="hidden" id="trigger" name="trigger" />
        <tr align="center">
            <td height="25"  class="txtLabelH">Reference Number</td>
            <td><input type="text" name="txtRefNo" id="txtRefNo" class="textBox  text ui-widget-content ui-corner-all" onkeypress="return valNumInputDec(event)" onkeydown="if(event.keyCode==13) searchSTSRef();"/></td>
             <td height="25"  class="txtLabelH">Contract No</td>
            <td><input type="text" name="txtConNo" id="txtConNo" class="textBox  text ui-widget-content ui-corner-all" onkeypress="return valNumInputDec(event)" onkeydown="if(event.keyCode==13) searchContractNo();"/></td>
            <td height="25"  class="txtLabelH">STS Number</td>
            <td><input type="text" name="txtSTSNo" id="txtSTSNo" class="textBox  text ui-widget-content ui-corner-all" onkeypress="return valNumInputDec(event)" onkeydown="if(event.keyCode==13) searchSTSNo();"/></td>
        </tr>
       </table>
    </form>
	</fieldset>
	</div>
    </br>
<div id="details">
<div class="headerContent1 ui-corner-all">
    <fieldset><legend>Details</legend>
        <table width="100%" border="0"  cellspacing="0" cellpadding="0" class="details">
            <tr>
            	<td height="25" class="txtLabelH">STS Ref. No.</td>
                <td class="txtLabel" id="txtStsRefNo"></td>
            	<td height="25" class="txtLabelH" width="17%">STS No.</td>
                <td class="txtLabel" id="txtStsNo" width="17%"></td>
                <td height="25" class="txtLabelH">STS Date</td>
                <td  class="txtLabel" id="txtStsDate" ></td>
            </tr>
            <tr>
            	<td height="25" class="txtLabelH">App Start Date</td>
                <td class="txtLabel" id="txtAppStart"></td>
            	<td height="25" class="txtLabelH" width="17%">App End Date</td>
                <td class="txtLabel" id="txtAppEnd" width="17%"></td>
            	<td height="25" class="txtLabelH">No. of Application</td>
                <td class="txtLabel" id="appDate"></td>
            </tr>
             <tr>
            	<td height="25" class="txtLabelH">Creation Date</td>
                <td class="txtLabel" id="txtDateCreated"></td>
            	<td height="25" class="txtLabelH" width="17%">Status</td>
                <td class="txtLabel" id="txtStatus" width="17%"></td>
            	<td height="25" class="txtLabelH">Cancelled Date</td>
                <td class="txtLabel" id="txtDateCancelled"></td>
            </tr>
            <tr>
                <td height="25" class="txtLabelH">Supplier</td>
                <td colspan="3" class="txtLabel" id="txtSuppName"></td>
                <td height="25" class="txtLabelH">Supplier's Code</td>
                <td class="txtLabel" id="txtSuppCode"></td>
            </tr>
            <tr>
                <td height="25" class="txtLabelH">Branch</td>
                <td class="txtLabel" id="txtComp"></td>
            	<td height="25" class="txtLabelH" width="17%">Contract No.</td>
                <td class="txtLabel" id="txtContractNo" width="17%"></td>
            	<td height="25" class="txtLabelH">Department</td>
                <td class="txtLabel" id="txtDept"></td>
            </tr>
            <tr>
            	<td height="25" class="txtLabelH" width="17%">STS Amount</td>
                <td class="txtLabel" id="txtStsAmt" width="17%"></td>
            	<td height="25" class="txtLabelH">Uploaded Amount</td>
                <td class="txtLabel" id="txtUpAmt"></td>
                <td height="25" class="txtLabelH">Onqueue Amount</td>
                <td class="txtLabel" id="txtQueAmt"></td>
            </tr>
             <tr>
            	<td height="25" class="txtLabelH">Remarks</td>
                <td colspan="3" class="txtRemarks" id="remarks"></td>
                 <td height="25" class="txtLabelH">Approved By</td>
                <td class="txtLabel" id="txtApprovedBy"></td>
            </tr>
   			<tr>
            	<td height="25" class="txtLabelH">Mode of Payment</td>
                <td class="txtLabel" id="txtPayMode"></td>
                <td colspan="2"></td>
                <td height="25" class="txtLabelH">Created By</td>
                <td class="txtLabel" id="txtCreatedBy"></td>
            </tr>
        </table>    
    </fieldset>
	<div id='dialogProcess' style="overflow:hidden;"><br />
    	<div style="text-align:center"><img src="../../images/progress2.gif" /></div>
      	<div id='Process' style="text-align:center"></div>
  	</div>
</div>
</div>
 <div id='dialogAlert' title='STS'>
        <p id='dialogMsg'></p>
 </div>
</div>
</body>
</html>
