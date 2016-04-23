<?
################### INCLUDE FILE #################
	session_start();
	ini_set('include_path','D:\wamp\php\PEAR');
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
	$filename = "cancelled_sts(detailed).xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("Cancelled STS");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(3,0));
	
	
	
	$worksheet->write(0,0,"Expiring Contract for the Month of ".date('m-Y',strtotime($_GET['dtMonthYr'])),$headerFormat);
	for($i=1;$i<10;$i++) {
		$worksheet->write(0, $i, "",$headerFormat);	
	}
	$worksheet->setColumn(0,0,10);
	$worksheet->setColumn(0,1,35);
	$worksheet->setColumn(0,2,35);
	$worksheet->setColumn(0,3,35);
	$worksheet->setColumn(0,4,15);
	$worksheet->setColumn(0,5,15);
	$worksheet->setColumn(0,6,15);
	$worksheet->setColumn(0,7,15);
	$worksheet->setColumn(0,8,15);
	$worksheet->setColumn(0,9,15);
	$worksheet->setColumn(0,10,45);
	
	$worksheet->write(2,0,"REF NO.",$headerFormat);
	$worksheet->write(2,1,"REMARKS",$headerFormat);
	$worksheet->write(2,2,"SUPPLIER",$headerFormat);
	$worksheet->write(2,3,"STS AMT.",$headerFormat);
	$worksheet->write(2,4,"AMT. UPLOADED",$headerFormat);
	$worksheet->write(2,5,"AMT. ON QUEUE",$headerFormat);
	$worksheet->write(2,6,"STS NO. USED",$headerFormat);
	$worksheet->write(2,7,"ENTERED BY",$headerFormat);
	
	
		$ctrRow = 3;
		$totFund = $totUpload = $totQueue = 0;
		$ctr = 3 ;
		
		$arrExp = $reportsObj->expiredContractsSumm(date('m/d/Y',strtotime($_GET['dtMonthYr'])),$_GET['cmbTran']);
		
			foreach ($arrExp as $val) {
					
				$totExpAmt = $flag = 0;
				$row = ($col==0) ? $detail2:$detail;
				$row2 = ($col==0) ? $Dept2:$Dept;
				$col = ($col==0) ? 1:0;
				$ctr++;
				$worksheet->write($ctr,0,$val['stsRefno'],$row2);
				$worksheet->write($ctr,1,$val['stsRemarks'],$row);
				$worksheet->write($ctr,2,$val['suppName'],$row);
				$worksheet->write($ctr,3,number_format($val['stsAmt'],2),$row2);
				$worksheet->write($ctr,4,number_format($val['uploadedAmt'],2),$row2);
				$worksheet->write($ctr,5,number_format($val['queueAmt'],2),$row2);
				$worksheet->write($ctr,6,$val['stsStartNo']." - ".$val['stsEndNo'],$row2);
				$worksheet->write($ctr,7,$val['fullName'],$row);
				
				
				$ctr++;
				$worksheet->write($ctr,1,"STS No.",$row2);
				$worksheet->write($ctr,2,"Store Code",$row2);
				$worksheet->write($ctr,3,"Store Amount",$row2);
				$worksheet->write($ctr,4,"Uploaded Amount",$row2);
				$worksheet->write($ctr,5,"Onqueue Amount",$row2);
				$worksheet->write($ctr,6,"Group",$row);
				$worksheet->write($ctr,7,"Hierarchy",$row);
					
				$arrDtl = $reportsObj->expiredContractDtl($val['stsRefno'],$_GET['cmbTran']);
				foreach ($arrDtl as $valD) {
					$totExpAmt = $flag = 0;
					$row = ($col==0) ? $detail2:$detail;
					$row2 = ($col==0) ? $Dept2:$Dept;
					$col = ($col==0) ? 1:0;
					$ctr++;	
					if($valD['stsType']!='5'){
						$sts = 'STS';
					}else{
						$sts = 'DA';
					}
					$worksheet->write($ctr,1,$sts.$valD['stsNo'],$row2);
					$worksheet->write($ctr,2,$valD['strCode'],$row2);
					$worksheet->write($ctr,3,number_format($valD['stsAmt'],2),$row2);
					$worksheet->write($ctr,4,number_format($valD['uploadedAmt'],2),$row2);
					$worksheet->write($ctr,5,number_format($valD['queueAmt'],2),$row2);
					$worksheet->write($ctr,6,$valD['grpDesc'],$row);
					$worksheet->write($ctr,7,$valD['hierarchyDesc'],$row);
					$totStsD += $valD['stsAmt'];
					$totUploadD += $valD['uploadedAmt'];
					$totQueueD += $valD['queueAmt'];
					
					$totSts += $valD['stsAmt'];
					$totUpload += $valD['uploadedAmt'];
					$totQueue += $valD['queueAmt'];
				}
				$ctr++;	
				$worksheet->setRow($ctr,16);
				$worksheet->write($ctr,2,"Store Total",$headerFormat);
				$worksheet->write($ctr,3,number_format($totStsD,2),$headerFormat);
				$worksheet->write($ctr,4,number_format($totUploadD,2),$headerFormat);
				$worksheet->write($ctr,5,number_format($totQueueD,2),$headerFormat);
				unset($totStsD,$totUploadD,$totQueueD);
				
				
			}
			$ctr++;	
			$worksheet->setRow($ctr,16);
			$worksheet->write($ctr,2,"Grand Total",$headerFormat);
			$worksheet->write($ctr,3,number_format($totSts,2),$headerFormat);
			$worksheet->write($ctr,4,number_format($totUpload,2),$headerFormat);
			$worksheet->write($ctr,5,number_format($totQueue,2),$headerFormat);
			
$workbook->close();
?>
