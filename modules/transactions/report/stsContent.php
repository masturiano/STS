<?
session_start();
include("../../../includes/db.inc.php");
include("../../../includes/common.php");
include("../transObj.php");
$transObj = new transObj();

$arrSTS = $transObj->getSTSPrint($_GET['refNo']);
$arrStsPar = $transObj->getRegStsParticipants($_GET['refNo']);
//$arrUserCreated = $transObj->getUsersCreated($_GET['refNo']);
//$arrUserApproved = $transObj->getUsersAprroved($_GET['refNo']);
//$arrSTSPar = $transObj->getSTSParDet($_GET['refNo']);

if(trim($arrSTS['approvedBy']) != ''){
	$count = $transObj->checkIfPrinted($_GET['refNo']);
	if( (int)$count > 0){
		$transObj->tagPrinted($_GET['refNo']);
	}else{
		$transObj->tagRePrinted($_GET['refNo']);
	}
}

?>
<style type="text/css">
.hd {
	font-weight: bold;
}
</style>

<div style="border: thin;">
<br />

<div align="center">
	<img src="../../../images/parco.png" width="200"><br/>
    <span style="font-size:20px;"><strong></strong></span>
    <br/>
   	<strong>SUPPLIER TRANSACTION SLIP <? if($arrSTS['approvedBy']=='') echo " - DRAFT"?></strong>
</div>
<br />
<table border="0" cellspacing="3" cellpadding="0" align="center" >
	<tr>
    	<td class="hd" style="width: 30mm;">DATE: </td>
   		<td style="width: 70mm;"><? if($arrSTS['dateApproved']!='') echo date('M d, Y',strtotime($arrSTS['dateApproved']))?></td>
        <td  class="hd" style="width: 25mm;">	
        </td>
        <td >
        	<table  border="0" cellspacing="0" cellpadding="0" >
            	<tr>
                	<td class="hd" style="width: 17mm;" align="right">REF NO: </td>
                    <td><?=$arrSTS['stsRefno']?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
    	<td  class="hd" >SUPPLIER: </td>
        <td><?=$arrSTS['suppName']?></td>
        <td  class="hd"></td>
        <td>
        	<table  border="0" cellspacing="0" cellpadding="0">
            	<tr>
                	<td align="right" class="hd" style="width: 17mm;">GROUP: </td>
                    <td><?=$arrSTS['grpDesc']?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
    	<td  class="hd">TRANDEPT: </td>
        <td><? 
				echo $arrSTS['Dept'];
			?>
        </td>
        <td  class="hd">TRANCLASS: </td>
        <td><?
			if( $arrSTS['stsType'] =='1')
        		echo $arrSTS['Class'];
			?>
        </td>
    </tr>
    <tr>
    	<td  class="hd">SUBCLASS: </td>
        <td><?
			if( $arrSTS['stsType'] =='1')
				echo $arrSTS['SClass'];
			?>
        </td>
        <td  class="hd">Amount</td>
        <td><?=$arrSTS['stsAmt']?></td>
    </tr>
    <tr>
        <td  class="hd">EFFECTIVITY: </td>
        <td>
		<? if($arrSTS['nbrApplication']=='1'){echo date('M d, Y',strtotime($arrSTS['applyDate']));
		}else{
			echo date('M d, Y',strtotime($arrSTS['applyDate']))." to ".date('M d, Y',strtotime($arrSTS['endDate']));
		}?>
        </td>
        <td  class="hd">MODE OF PAYMENT: </td>
        <td><?=$arrSTS['paymode']?></td>
    </tr>
    <tr>
    	<td  class="hd" valign="top">DETAILS: </td>
        <td colspan="3"style="width: 90mm;"><?=$arrSTS['stsRemarks']?></td>
    </tr>
   <tr>
   		<td colspan="6">
   		<table align="center" border="0" cellspacing="0" cellpadding="0">
        	<tr>
            	<td style="width: 30mm;" align="center"><strong>STS No</strong></td>
                <td style="width: 30mm;" align="center"><strong>Store</strong></td>
                <td style="width: 30mm;" align="center"><strong>Amt.</strong></td>
                <td style="width: 30mm;" align="center"><strong>Vat Amt.</strong></td>
                 <td style="width: 30mm;" align="center"><strong>Total Amt.</strong></td>
            </tr>
            <?
				foreach($arrStsPar as $valPar){
			?>
            		<tr>
                    	<td><?=$valPar['stsNo']?></td>
                        <td><?=$valPar['brnShortDesc']?></td>
                        <td align="right"><?=number_format($valPar['stsAmt'],2)?></td>
                        <td align="right"><?=number_format($valPar['stsVatAmt'],2)?></td>
                        <td align="right"><?=number_format($valPar['stsAmt'] + $valPar['stsVatAmt'],2)?></td>
                    </tr>
			<? $totAmt += $valPar['stsAmt'];
				} ?>
                	<tr>
                    	<td></td>
                        <td><strong>Total</strong></td>
                        <td align="right"><?=number_format($totAmt,2)?></td>
                    </tr>
        </table>
        </td>
   </tr>
</table>

<br />

<table border="0" cellspacing="0" cellpadding="0" align="center" >
	<tr>
    	<td style="width: 63mm;" class="hd" >Supplier Conforme: </td>
        <td style="width: 63mm;" class="hd" >Prepared By: </td>
        <td style="width: 63mm;" class="hd" >Approved By: </td>
    </tr>
    <tr>
    	<td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
    	<td>_________________</td>
        <td style="text-decoration: underline;"><?=$arrSTS['enteredBy']?></td>
        <td style="text-decoration: underline;"><?=$arrSTS['approvedBy']?></td>
    </tr>
</table>
<br />
</div>

