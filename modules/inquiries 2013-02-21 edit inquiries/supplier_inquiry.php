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

<link type="text/css" href="../../includes/jquery/css/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<link type="text/css" href="../../includes/jquery/development-bundle/demos/demos.css" rel="stylesheet" />


	<!-- AUTO COMPLETE -->
	<script src="../../includes/jquery/js/jquery-1.6.2.min.js"></script>
    <script src="../../includes/jquery/development-bundle/ui/jquery.ui.core.js"></script>
    <script src="../../includes/jquery/development-bundle/ui/jquery.ui.widget.js"></script>
    <script src="../../includes/jquery/development-bundle/ui/jquery.ui.button.js"></script>
    <script src="../../includes/jquery/development-bundle/ui/jquery.ui.position.js"></script>
    <script src="../../includes/jquery/development-bundle/ui/jquery.ui.autocomplete.js"></script>
    <!-- ADDITIONAL DIALOAD -->
    <script src="../../includes/jquery/development-bundle/ui/jquery.ui.mouse.js"></script>
	<script src="../../includes/jquery/development-bundle/ui/jquery.ui.draggable.js"></script>
	<script src="../../includes/jquery/development-bundle/ui/jquery.ui.resizable.js"></script>
	<script src="../../includes/jquery/development-bundle/ui/jquery.ui.dialog.js"></script>
    <script src="../../includes/jquery/development-bundle/external/jquery.bgiframe-2.1.2.js"></script>
	<style>
	.ui-button { margin-left: -1px; }
	.ui-button-icon-only .ui-button-text { padding: 0.35em; } 
	.ui-autocomplete-input { margin: 0; padding: 0.28em 0 0.27em 0.195em; }
	</style>
	<script>
	$(function(){
		$("#txtSupp").autocomplete({
			source: "../transactions/regularSTS.php?action=searchSupplier",
			minLength: 1,
			select: function(event, ui) {	
				var content = ui.item.id.split("|");
				$("#hdnSuppCode").val(content[0]);
				$("#txtSupp").val(content[1]);
			}
		});	  
	});

	$(function() {
		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
		$( "#dialog-modal" ).dialog({
			height: 140,
			modal: true
		});
	});
	</script>

<style type="text/css">
/*table radius border*/
table.radius {
	border: 6px inset #8B8378;
	-moz-border-radius: 6px;
}

/*alternate table row colour*/
table tr.alternate:nth-child(even) { background-color:#00F; }
</style>

<style>

		h1 { font-size: 1.2em; margin: .6em 0; }
		div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
		div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
		.ui-dialog .ui-state-error { padding: .3em; }
		.validateTips { border: 1px solid transparent; padding: 0.3em; }
</style>

</head>

<?php
//VAR

$txtSupp = $_POST["txtSupp"];
$hdnSuppCode = $_POST["hdnSuppCode"];
$choose = $_POST["choose"];
$type = $_POST["type"];
$search = $_POST["search"];
$submit = $_POST["submit"];

//COND
$isNumeric="^[0-9]+$";
?>

<body>
<h2 class="ui-widget-header ui-corner-all" style="padding:5px;">SUPPLIERS INQUIRY</h2>

<center>

<fieldset style="width:700px;">
	<legend>
    <b>
    <font color="#FFFFFF">
    Supplier
    </font>
    </b>
    </legend>

<!-- TABLE 1 -->    
<table border="0" align="center">
<form 
	action="" 
	method="post" 	
	onsubmit="return validate();">
            
	<tr>
        <td width="360px">

	<div class="ui-widget">
	<input type="text" name="txtSupp" id="txtSupp" size="60"/>
    <input type="hidden" name="hdnSuppCode" id="hdnSuppCode" size="60"/>
	</div>

        
        </td>

        <td width="350px">
        <font color="#FFFFFF">Reference No.</font>
        <input
        	type="radio"
            name="choose"
            id="choose"
            value="refno" 
            title="Reference Number" checked="checked"/>
        <font color="#FFFFFF">STS No.</font> 
        <input
        	type="radio"
            name="choose"
            id="choose"
            value="stsno"
            title="STS Number"/>
        </td>
        <td width="150px">
        <input 
        	type="text" 
            name="type" 
            id="type" 
            title="Type" />

        </td>
         <td width="50px">
         <input
        	type="submit"
            id="submit"
            name="submit" 
            value="Go" />
        </td>
    </tr>
</form>
</table>
</fieldset>

<center>

<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content">
	<thead>
		<tr class="ui-widget-header ">
			<th width="50px">Ref.No</th>
			<th width="300px">Supplier</th>
			<th width="50px">STS No.</th>
            <th width="300px">Store</th>
			<th width="80px">STS Amt.</th>
			<th width="80px">Prepared By</th>
            <th width="80px">Date Prepared</th>
			<th width="80px">Application Date</th>
			</tr>
	</thead>

<?php
if($txtSupp!="" && $type==""){
$result_query=mssql_query("
SELECT 
tblStsDtl.stsRefno as refno, 
tblStsDtl.stsAmt as stsamt, 
tblStsDtl.stsNo as stsno, 
tblStsHdr.applyDate as appdate, 
tblStsHdr.dateEntered dateent, 
tblUsers.fullName fulname, 
tblStsDtl.strCode + ' - ' + pg_pf.dbo.tblBranches.brnDesc AS store,
tblStsHdr.suppCode + ' - ' + sql_mmpgtlib.dbo.APSUPP.ASNAME AS supplier
FROM tblStsDtl INNER JOIN
tblStsHdr ON tblStsDtl.stsRefno = tblStsHdr.stsRefno INNER JOIN
tblUsers ON tblStsHdr.enteredBy = tblUsers.userId INNER JOIN
pg_pf.dbo.tblBranches ON tblStsDtl.strCode = pg_pf.dbo.tblBranches.strCode INNER JOIN
sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM
WHERE tblStsHdr.suppCode = '$hdnSuppCode'
ORDER BY tblStsDtl.stsRefno");
?>

    <tbody>
    
	<?php
    $b=0;
    while($myrow2=mssql_fetch_array($result_query)){
    $b++;
    print($b%2) ? '<tr style="background-color:#ffffff">' : '<tr style="background-color:#efefef">'
    ?>
			<td width="50px"><?php echo $myrow2["refno"]?></td>
			<td width="300px"><?php echo $myrow2["supplier"]?></td>
            <td width="50px"><?php echo $myrow2["stsno"]?></td>
            <td width="300px"><?php echo $myrow2["store"]?></td>
            <td width="80px"><?php echo $myrow2["stsamt"]?></td>
            <td width="150px"><?php echo $myrow2["fulname"]?></td>
            <td width="80px"><?php echo $myrow2["dateent"]?></td>
            <?php if($myrow2["stsno"]!=""){?>
            <td width="80px"><?php echo $myrow2["appdate"]?></td>
            <?php }?>
             </tr>
	<?php
    }
    ?>
		
	</tbody>
    
<?php
}
?>

<?php
if($hdnSuppCode!="" && $type!=""){
switch($choose){
case $choose=="refno";
	$tblname = "tblStsDtl.stsRefno";
break;
case $choose=="stsno";
	$tblname = "tblStsDtl.stsNo";
break;
}

$result_query=mssql_query("
SELECT 
tblStsDtl.stsRefno as refno, 
tblStsDtl.stsAmt as stsamt, 
tblStsDtl.stsNo as stsno, 
tblStsHdr.applyDate as appdate, 
tblStsHdr.dateEntered dateent, 
tblUsers.fullName fulname, 
tblStsDtl.strCode + ' - ' + pg_pf.dbo.tblBranches.brnDesc AS store,
tblStsHdr.suppCode + ' - ' + sql_mmpgtlib.dbo.APSUPP.ASNAME AS supplier
FROM tblStsDtl INNER JOIN
tblStsHdr ON tblStsDtl.stsRefno = tblStsHdr.stsRefno INNER JOIN
tblUsers ON tblStsHdr.enteredBy = tblUsers.userId INNER JOIN
pg_pf.dbo.tblBranches ON tblStsDtl.strCode = pg_pf.dbo.tblBranches.strCode INNER JOIN
sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM
WHERE tblStsHdr.suppCode = '$hdnSuppCode'
AND $tblname = '$type'
ORDER BY $tblname");
?>
    
	<?php
    $b=0;
    while($myrow2=mssql_fetch_array($result_query)){
    $b++;
    print($b%2) ? '<tr style="background-color:#ffffff">' : '<tr style="background-color:#efefef">'
    ?>
			<td width="50px"><?php echo $myrow2["refno"]?></td>
			<td width="300px"><?php echo $myrow2["supplier"]?></td>
            <td width="50px"><?php echo $myrow2["stsno"]?></td>
            <td width="300px"><?php echo $myrow2["store"]?></td>
            <td width="80px"><?php echo $myrow2["stsamt"]?></td>
            <td width="150px"><?php echo $myrow2["fulname"]?></td>
            <td width="80px"><?php echo $myrow2["dateent"]?></td>
            <?php if($myrow2["stsno"]!=""){?>
            <td width="80px"><?php echo $myrow2["appdate"]?></td>
            <?php }?>
             </tr>
	<?php
    }
    ?>
    
	</tbody>

<?php
}
?>

    <thead>
            <tr class="ui-widget-header ">
                <th colspan="8">
                <center>
                Total number of record(s):
                <font style="text-decoration:blink">
				<?php
				if($txtSupp=="" && $type==""){
					echo "0";
				}
				if($txtSupp!="" && $type==""){
					echo @mssql_num_rows($result_query);
				}
				if($txtSupp!="" && $type!=""){
					echo @mssql_num_rows($result_query);
				}
				?>
                </font>
                </center>
                </th>
			</tr>
    </thead>

</table>

</center>

</body>
</html>

<script language="javascript">
function validate(){

var isNumeric=/^[0-9]+$/;

var txtSupp=document.getElementById("txtSupp").value;
var hdnSuppCode=document.getElementById("hdnSuppCode").value;
var choose=document.getElementById("choose").value;
var type=document.getElementById("type").value;

	if(txtSupp==""){
	alert('Please input supplier!');
	return false;
	}
	if((type!="") && (type!=type.match(isNumeric))){
	alert('Please enter only numbers for Ref.No or STS No.!');
	return false;
	}
}
</script>