<?
################### INCLUDE FILE #################
	session_start();
	ini_set('include_path','C:\wamp\php\PEAR');
	include("../../includes/db.inc.php");
	include("../../includes/common.php");
	include("reportsObj.php");
	require_once 'Spreadsheet/Excel/Writer.php';
	
	$reportsObj = new reportsObj();
	$workbook = new Spreadsheet_Excel_Writer();
	$headerFormat = $workbook->addFormat(array('Size' => 11,
                                      'Color' => 'black',
                                      'bold'=> 1,
									  'border' => 1,
									  'Align' => 'merge'));
	$headerFormat2 = $workbook->addFormat(array('Size' => 11,
                                      'Color' => 'black',
                                      'bold'=> 1,
									  'border' => 1,
									  'Align' => 'left'));
	$headerFormat->setFontFamily('Calibri'); 
	$headerBorder    = $workbook->addFormat(array('Size' => 10,
                                      'Color' => 'black',
                                      'bold'=> 1,
									  'border' => 1,
									  'Align' => 'merge'));
									  
	$headerBorder->setFontFamily('Calibri'); 
	$workbook->setCustomColor(13,155,205,255);
	$TotalBorder    = $workbook->addFormat(array('Align' => 'right','bold'=> 1,'border'=>1,'fgColor' => 'white'));
	$TotalBorder->setFontFamily('Calibri'); 
	$TotalBorder->setTop(5); 
	$detailrBorder   = $workbook->addFormat(array('border' =>1,'Align' => 'right'));
	$detailrBorder->setFontFamily('Calibri'); 
	$detailrBorderAlignRight2   = $workbook->addFormat(array('Align' => 'left'));
	$detailrBorderAlignRight2->setFontFamily('Calibri');
	$workbook->setCustomColor(12,183,219,255);
	$detail   = $workbook->addFormat(array('Size' => 10,
										  'fgColor' => 'white',
										  'Pattern' => 1,
										  'border' =>1,
										  'Align' => 'left'));
	$detail->setFontFamily('Calibri'); 
	
	//first row format
	$Deptc   = $workbook->addFormat(array('Size' => 10,
										  'fgColor' => 'white',
										  'Pattern' => 1,
										  'border' =>1,
										  'Align' => 'center'));
	$Deptc->setFontFamily('Calibri'); 
	
	$Deptc1   = $workbook->addFormat(array('Size' => 10,
										  'border' =>1,
										  'Pattern' => 1,
										  'Align' => 'center'));
	$Deptc1->setFgColor(12); 
	$Deptc1->setFontFamily('Calibri');
	//end first row format
	
	//2nd row format
	$Deptt   = $workbook->addFormat(array('Size' => 10,
										  'fgColor' => 'white',
										  'Pattern' => 1,
										  'border' =>1,
										  'Align' => 'center'));
	$Deptt->setFontFamily('Calibri'); 
	
	$Deptt2   = $workbook->addFormat(array('Size' => 10,
										  'border' =>1,
										  'Pattern' => 1,
										  'Align' => 'center'));
	$Deptt2->setFgColor(11); 
	$Deptt2->setFontFamily('Calibri');
	//end 2nd row format

	$detail2   = $workbook->addFormat(array('Size' => 10,
										  'border' =>1,
										  'Pattern' => 1,
										  'Align' => 'left'));
	$detail2->setFgColor(12); 
	$detail2->setFontFamily('Calibri'); 
	$Dept   = $workbook->addFormat(array('Size' => 10,
										  'fgColor' => 'white',
										  'Pattern' => 1,
										  'border' =>1,
										  'Align' => 'right'));
	$Dept->setFontFamily('Calibri'); 
	$Dept2   = $workbook->addFormat(array('Size' => 10,
										  'border' =>1,
										  'Pattern' => 1,
										  'Align' => 'right'));
	$Dept2->setFgColor(12); 
	$Dept2->setFontFamily('Calibri');
	
	$blank   = $workbook->addFormat(array('Size' => 10,
										  'border' =>1,
										  'Pattern' => 1,
										  'Align' => 'center'));
	$blank->setFgColor(11); 
	$blank->setFontFamily('Calibri'); 
	

	
	$filename = "cancelled_sts(detailed).xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("Cancelled STS");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(3,0));
	
	if($_GET['cmbTran'] == '1'){
		$type = 'Regular STS';
	}elseif($_GET['cmbTran'] == '2'){
		$type = 'Listing Fee';	
	}elseif($_GET['cmbTran'] == '4'){
		$type = 'Shelf Enhancer';	
	}elseif($_GET['cmbTran'] == '4'){
		$type = 'Display Allowance';	
	}else{
		$type = "All STS Type";
	}
	
	$worksheet->write(0,0,"Cancelled $type (Detailed) From ".date('m/d/Y',strtotime($_GET['txtDateFrom']))." to ".date('m/d/Y',strtotime($_GET['txtDateTo'])),$headerFormat);
	for($i=1;$i<18;$i++) {
		$worksheet->write(0, $i, "",$headerFormat);	
	}
	$worksheet->setColumn(0,0,15);
	$worksheet->setColumn(0,1,35);
	$worksheet->setColumn(0,2,35);
	$worksheet->setColumn(0,3,20);
	$worksheet->setColumn(0,4,20);
	$worksheet->setColumn(0,5,20);
	$worksheet->setColumn(0,6,20);
	$worksheet->setColumn(0,7,20);
	$worksheet->setColumn(0,8,25);
	$worksheet->setColumn(0,9,35);
	$worksheet->setColumn(0,10,20);
	$worksheet->setColumn(0,11,35);
	$worksheet->setColumn(0,12,25);
	$worksheet->setColumn(0,13,35);
	$worksheet->setColumn(0,14,20);
	$worksheet->setColumn(0,15,35);
	$worksheet->setColumn(0,16,20);
	$worksheet->setColumn(0,17,25);
	
	switch(isset($_GET['cmbPMode'])){
	case $_GET['cmbPMode']=='0';
		$pMode = 'ALL';
	break;
	case $_GET['cmbPMode']=='D';
		$pMode = 'Invoice Deduction';
	break;
	case $_GET['cmbPMode']=='C';
		$pMode = 'Collection';
	break;
	}
	
	$worksheet->write(1,0,"MODE OF PAYMENT:".$pMode,$headerFormat);
	$worksheet->write(1,1, "",$headerFormat);
	
	$worksheet->write(2,0,"REF NO.",$headerFormat);
	$worksheet->write(2,1,"REMARKS",$headerFormat);
	$worksheet->write(2,2,"SUPPLIER",$headerFormat);
	$worksheet->write(2,3,"STS AMT.",$headerFormat);
	$worksheet->write(2,4,"VAT AMT.",$headerFormat);
	$worksheet->write(2,5,"AMT. UPLOADED",$headerFormat);
	$worksheet->write(2,6,"AMT. ON QUEUE",$headerFormat);
	$worksheet->write(2,7,"STS NO. USED",$headerFormat);
	$worksheet->write(2,8,"CREATION DATE",$headerFormat);
	$worksheet->write(2,9,"CANCELLED DATE",$headerFormat);
	$worksheet->write(2,10,"CANCELLED BY",$headerFormat);
	$worksheet->write(2,11,"REASON",$headerFormat);
	$worksheet->write(2,12,"EFFECTIVITY DATE",$headerFormat);
	$worksheet->write(2,13,"HIERARCHY",$headerFormat);
	$worksheet->write(2,14,"MODE OF PAYMENT",$headerFormat);
	$worksheet->write(2,15,"BRANCH",$headerFormat);
	$worksheet->write(2,16,"COMPANY",$headerFormat);
	$worksheet->write(2,17,"REPLACEMENT STS",$headerFormat);
		
		$ctrRow = 3;
		$totFund = $totUpload = $totQueue = 0;
		$ctr = 2 ;
		$totExpAmt = $flag = 0;

		$row1 = ($col==0) ? $Deptc1:$Deptc;
		$row2 = ($col==0) ? $detail2:$detail2;
		$col = ($col==0) ? 1:0;
		
		$arrCancelledD = $reportsObj->cancelledSTSDetail($_GET['txtDateFrom'],$_GET['txtDateTo'],$_GET['cmbTran'],$_GET['cmbPMode'],$_GET['cmbCancelType']);
		foreach ($arrCancelledD as $valD) {
			
			//$totExpAmt = $flag = 0;
			//$row = ($col==0) ? $detail2:$detail;
			//$row2 = ($col==0) ? $Dept2:$Dept;
			//$col = ($col==0) ? 1:0;
			
			$ctr++;	
			
			$worksheet->write($ctr,0,$valD['stsRefno'],$row1);
			$worksheet->write($ctr,1,$valD['stsRemarks'],$row2);
			$worksheet->write($ctr,2,$valD['suppName'],$row2);
			$worksheet->write($ctr,3,number_format($valD['amtStsStr'],2),$row1);
			if($valD['stsPaymentMode']=="C"){
			$worksheet->write($ctr,4,number_format($valD['stsVatAmount'],2),$row1);
			}else{
			$worksheet->write($ctr,4,number_format("0"),$row1);
			}
			$worksheet->write($ctr,5,number_format($valD['uploadedAmt'],2),$row1);
			$worksheet->write($ctr,6,number_format($valD['queueAmt'],2),$row1);
			$worksheet->write($ctr,7,"STS".$valD['stsNo']."-".$valD['stsSeq'],$row2);
			$worksheet->write($ctr,8,date('m/d/Y',strtotime($valD['dateEntered'])),$row1);
			$worksheet->write($ctr,9,date('m/d/Y',strtotime($valD['cancelDate'])),$row1);
			$worksheet->write($ctr,10,$valD['fullName'],$row2);
			$worksheet->write($ctr,11,$valD['cancelDesc'],$row2);
			$worksheet->write($ctr,12,date('m/d/Y',strtotime($valD['effectivityDate'])),$row1);
			$worksheet->write($ctr,13,$valD['hierarchyDesc'],$row2);
			$worksheet->write($ctr,14,$valD['payMode'],$row2);
			$worksheet->write($ctr,15,$valD['strCode']." - ".$valD['brnDesc'],$row2);
			$worksheet->write($ctr,16,$valD['companyCode'],$row2);
			$worksheet->write($ctr,17,$valD['replacementSts'],$row2);
			
			//$worksheet->write($ctr,11,"Store Total",$headerFormat);
			//$worksheet->write($ctr,12,number_format($totStsD,2),$headerFormat);
			//$worksheet->write($ctr,13,number_format($totUploadD,2),$headerFormat);
			//$worksheet->write($ctr,14,number_format($totQueueD,2),$headerFormat);
			
			$totStsD += $valD['amtStsStr'];
			$totStsVatD += $valD['stsVatAmount'];
			$totUploadD += $valD['uploadedAmt'];
			$totQueueD += $valD['queueAmt'];
			
			}
		
		$arrCancelledH = $reportsObj->cancelledSTSDetailHeader($_GET['txtDateFrom'],$_GET['txtDateTo'],$_GET['cmbTran'],$_GET['cmbPMode'],$_GET['cmbCancelType']);
		
		if($arrCancelledH){
			$ctr++;
			for($i=0;$i<1;$i++) {
				$worksheet->write($ctr,1,"CANCELLED, APPLIED INVOICES",$headerFormat);	
			}
		}
			
		foreach ($arrCancelledH as $valH) {
			
			//$totExpAmt = $flag = 0;
			//$row = ($col==0) ? $detail2:$detail;
			//$row2 = ($col==0) ? $Dept2:$Dept;
			//$col = ($col==0) ? 1:0;
			
			$ctr++;	
			
			$worksheet->write($ctr,0,$valH['stsRefno'],$row1);
			$worksheet->write($ctr,1,$valH['stsRemarks'],$row2);
			$worksheet->write($ctr,2,$valH['suppName'],$row2);
			$worksheet->write($ctr,3,number_format($valH['stsApplyAmt'],2),$row1);
			if($valH['stsPaymentMode']=="C"){
			$worksheet->write($ctr,4,number_format($valH['stsVatAmt'],2),$row1);
			}else{
			$worksheet->write($ctr,4,number_format("0"),$row1);
			}
			$worksheet->write($ctr,5,number_format($valH['stsApplyAmt'],2),$row1);
			$worksheet->write($ctr,6,number_format("0"),$row1);
			$worksheet->write($ctr,7,"STS".$valH['stsNo']."-".$valH['stsSeq'],$row2);
			$worksheet->write($ctr,8,date('m/d/Y',strtotime($valH['dateEntered'])),$row1);
			$worksheet->write($ctr,9,date('m/d/Y',strtotime($valH['cancelDate'])),$row1);
			$worksheet->write($ctr,10,$valH['fullName'],$row2);
			$worksheet->write($ctr,11,"",$row2);
			$worksheet->write($ctr,12,date('m/d/Y',strtotime($valH['effectivityDate'])),$row1);
			$worksheet->write($ctr,13,$valH['hierarchyDesc'],$row2);
			$worksheet->write($ctr,14,$valH['payMode'],$row2);
			$worksheet->write($ctr,15,$valH['brnDesc'],$row2);
			$worksheet->write($ctr,16,$valH['companyCode'],$row2);
			$worksheet->write($ctr,17,$valH['replacementSts'],$row2);
			
			//$worksheet->write($ctr,11,"Store Total",$headerFormat);
			//$worksheet->write($ctr,12,number_format($totStsD,2),$headerFormat);
			//$worksheet->write($ctr,13,number_format($totUploadD,2),$headerFormat);
			//$worksheet->write($ctr,14,number_format($totQueueD,2),$headerFormat);
			
			$totStsH += $valH['stsApplyAmt'];
			$totStsVatH += $valH['stsVatAmt'];
			$totUploadH += $valH['stsApplyAmt'];
			$totQueueH += $valH['queueAmt'];
			
			}

		$ctr++;	
		
		$totSts += $totStsD + $totStsH;
		$totStsVat += $totStsVatD + $totStsVatH;
		$totUpload += $totUploadD + $totUploadH;
		$totQueue += $totQueueD + $totQueueH;

		$worksheet->setRow($ctr,16);
		$worksheet->write($ctr,2,"Grand Total",$headerFormat);
		$worksheet->write($ctr,3,number_format($totSts,2),$headerFormat);
		$worksheet->write($ctr,4,number_format($totStsVat,2),$headerFormat);
		$worksheet->write($ctr,5,number_format($totUpload,2),$headerFormat);
		$worksheet->write($ctr,6,number_format($totQueue,2),$headerFormat);

			
$workbook->close();
?>
