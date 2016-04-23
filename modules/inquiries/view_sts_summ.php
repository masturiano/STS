<?php
session_start();

include("../../includes/db.inc.php");
include("../../includes/common.php");
include("inquiriesObj.php");
$inquiriesObj = new inquiriesObj();
if ($_SESSION['sts-username'] == "") {
	echo "<SCRIPT>window.parent.location.href='../../index.php';</SCRIPT>";
}

switch($_GET['action']) {
	case "print":
			echo "window.open('sts_detail_excel.php?refNo={$_GET['refNo']}');";
		exit();
	break;

}

$arrSTSInfo = $inquiriesObj->getSTSHdrDtl($_GET['refNo']);
$arrUp = $inquiriesObj->getUploaded($_GET['refNo']);
$arrQ = $inquiriesObj->getOnqueue($_GET['refNo']);
$totUp = $totQ = $qDocAmt = $uDocAmt= 0;

?>

  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
    	<td><span class="link2" onclick="PrintDetails()"><img src="../../images/printer.png"> Print</span></td>
    </tr>
    <tr>
    	<td>&nbsp;</td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="10%" height="20px" class="style24">STS Ref. No: </td>
          <td width="38%"><?=$arrSTSInfo['stsRefno']?></td>
          <td width="17%" height="20px" class="style24">Supplier: </td>
          <td width="50px"><?=$arrSTSInfo['suppName']?></td>
        </tr>
        <tr>
          <td height="20px" class="style24">Amount: </td>
          <td><?=number_format($arrSTSInfo['stsAmt'],2)?></td>
         <td height="20px" class="style24">Entry Date:</td>
          <td><?=date('m/d/Y',strtotime($arrSTSInfo['dateEntered']))?></td>
        </tr>
        <tr>
          <td height="20px" class="style24">Remarks: </td>
          <td><?=$arrSTSInfo['stsRemarks']?></td>
          <td height="20px" class="style24">Payment Mode: </td>
          <td><?=$arrSTSInfo['payMode']?></td>
        </tr>
         <tr>
          <td colspan="4" height="20px"></td>
        </tr>          
      </table></td>
    </tr>
    <tr>
      <td>
	      <table width="100%" cellpadding="0" cellspacing="1" border="1">
			<tr>
				<td align="center" class="style25 style24" width="50%"><strong>Uploaded</strong></td>
				<td align="center" class="style25 style24" width="50%"><strong>Queued</strong></td>
				
			</tr>
      		<tr>
      			<td width="25%" valign="top"  class="style25" >
      				<table  width="100%">
      					<tr>
                        	<td width="40%" align="left"><strong>Branch</strong></td>
                            <td width="20%" align="left"><strong>STS No</strong></td>
      						<td width="20%" align="right"><strong>Amount</strong></td>
      					</tr>
      				<?php
      				 foreach ($arrUp as $valU){
      				 	$totUp += (float)$valU['stsApplyAmt'];
      				?>
      					<tr>
                        	<td align="left"><?=$valU['brnShortDesc']?></td>
                            <td align="left"><?=$valU['stsNo']."-".$valU['stsSeq']?></td>
      						<td align="right">
							<? if((float)$valU['stsApplyAmt']<0){
								$uDocAmt = $valU['stsApplyAmt']*-1;
							}else{
								$uDocAmt = $valU['stsApplyAmt'];
							}
							echo number_format($uDocAmt,2)?></td>
      					</tr>
      				<?php }?>
   				</table>      			</td>
      			<td width="35%" valign="top"  class="style25">
      				<table  width="100%">
                    	<tr>
      						<td width="40%" align="left"><strong>Branch</strong></td>
                            <td width="20%" align="left"><strong>STS No</strong></td>
      						<td width="20%" align="right"><strong>Amount</strong></td>
      					</tr>
      				<?php
	      				 foreach ($arrQ as $valQ){
    	  				 	$totQ += (float)$valQ['stsApplyAmt'];
      				 	?>

      					<tr>
                        	<td align="left"><?=$valQ['brnShortDesc']?></td>
                             <td align="left"><?=$valQ['stsNo']."-".$valQ['stsSeq']?></td>
      						<td align="right">
							<? if((float)$valQ['stsApplyAmt'] < 0){
								$qDocAmt = $valQ['stsApplyAmt'] * -1;
							}else{
								$qDocAmt = $valQ['stsApplyAmt'];
							}
							echo number_format($qDocAmt,2)?></td>
      					</tr>
      				<?php
                    	}?>
      				</table>				</td>
      			
                <td>                </td>
      		</tr>
      		<tr>
      			<td colspan="3">
      				<table width="100%" cellpadding="0" cellspacing="1">
      					<tr>
      						<td width="50%" align="right" class="style25 style24"><span style="text-align: right;">
								<? if((float)$totUp < 0){$totUp = $totUp * -1;}
								echo number_format($totUp,2)?>&nbsp;</span>
                            </td>
      						<td width="50%" align="right" class="style25 style24">
                            	<? if($totQ < 0){$totQ = $totQ * -1;}
								echo number_format($totQ,2)?>&nbsp;
                            </td>
      						
      				</table>      			</td>
      		</tr>
   	  </table>      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td></td>
    </tr>
  </table>
</div>
