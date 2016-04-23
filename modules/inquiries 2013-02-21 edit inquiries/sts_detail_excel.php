<?
################### INCLUDE FILE #################
	session_start();
	ini_set('include_path','D:\wamp\php\PEAR');
	include("../../includes/db.inc.php");
	include("../../includes/common.php");
	include("inquiriesObj.php");
	require_once 'Spreadsheet/Excel/Writer.php';
	
	$inquiriesObj = new inquiriesObj();
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
	$headerFormat3 = $workbook->addFormat(array('Size' => 10,
                                      'Color' => 'black',
                                      'bold'=> 1,
									  'border' => 0,
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
	$filename = "sts_detail.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("STS Details");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(9,0));
	
	$worksheet->write(0,0,"STS Details",$headerFormat);
	
	for($i=1;$i<6;$i++) {
		$worksheet->write(0, $i, "",$headerFormat);	
	}
	
	$arrSTSInfo = $inquiriesObj->getSTSHdrDtl($_GET['refNo']);
	$arrUp = $inquiriesObj->getUploaded($_GET['refNo']);
	$arrQ = $inquiriesObj->getOnqueue($_GET['refNo']);
	
	$worksheet->write(2,0,"STS Ref. No: ".$arrSTSInfo['stsRefno'],$headerFormat3);
	$worksheet->write(3,0,"Supplier: ".$arrSTSInfo['suppName'],$headerFormat3);
	$worksheet->write(4,0,"Amount: ".$arrSTSInfo['stsAmt'],$headerFormat3);
	
	$worksheet->write(2,4,"Payment Mode: ".$arrSTSInfo['payMode'],$headerFormat3);
	$worksheet->write(3,4,"Entry Date:: ".date('m/d/Y',strtotime($arrSTSInfo['dateEntered'])),$headerFormat3);
	$worksheet->write(4,4,"Remarks: ".$arrSTSInfo['stsRemarks'],$headerFormat3);
	
	$worksheet->write(7,0,"Uploaded",$headerFormat);
	for($i=1;$i<3;$i++) {
		$worksheet->write(7,$i,"",$headerFormat);	
	}
	$worksheet->write(7,3,"Queued",$headerFormat);
	for($i=4;$i<6;$i++) {
		$worksheet->write(7,$i,"",$headerFormat);	
	}
	
	$worksheet->setColumn(0,0,35);
	$worksheet->setColumn(0,1,10);
	$worksheet->setColumn(0,2,15);
	$worksheet->setColumn(0,3,35);
	$worksheet->setColumn(0,4,15);
	$worksheet->setColumn(0,5,15);
	
	
	$worksheet->write(8,0,"Branch",$headerFormat);
	$worksheet->write(8,1,"STS No.",$headerFormat);
	$worksheet->write(8,2,"Amount",$headerFormat);
	$worksheet->write(8,3,"Branch",$headerFormat);
	$worksheet->write(8,4,"STS No.",$headerFormat);
	$worksheet->write(8,5,"Amount",$headerFormat);
	
		$totFund = $totUpload = $totQueue = 0;
		$ctr = 9;
			foreach ($arrUp as $valU) {
					
				$totExpAmt = $flag = 0;
				$row = ($col==0) ? $detail2:$detail;
				$row2 = ($col==0) ? $Dept2:$Dept;
				$col = ($col==0) ? 1:0;
				
				$worksheet->write($ctr,0,$valU['brnShortDesc'],$row);
				$worksheet->write($ctr,1,$valU['stsNo']."-".$valU['stsSeq'],$row2);
				if($valU['stsApplyAmt']<0){
					$uDocAmt = $valU['stsApplyAmt']*-1;
				}else{
					$uDocAmt = $valU['stsApplyAmt'];
				}
				$worksheet->write($ctr,2,number_format($uDocAmt,2),$row2);
				$ctr++;	
				$totUpload += $valU['stsApplyAmt'];
			}
			if($totUpload<0){
				$totUpload = $totUpload*-1;
			}
			$worksheet->setRow($ctr,16);
			$worksheet->write($ctr,2,number_format($totUpload,2),$headerFormat);
		
		$ctr2 = 9;
			foreach ($arrQ as $valQ) {
				$row = ($col==0) ? $detail2:$detail;
				$row2 = ($col==0) ? $Dept2:$Dept;
				$col = ($col==0) ? 1:0;
				
				$worksheet->write($ctr2,3,$valQ['brnShortDesc'],$row);
				$worksheet->write($ctr2,4,$valQ['stsNo']."-".$valQ['stsSeq'],$row2);
				
				if($valQ['stsApplyAmt']<0){
					$qDocAmt = $valQ['stsApplyAmt']*-1;
				}else{
					$qDocAmt = $valQ['stsApplyAmt'];
				}
				$worksheet->write($ctr2,5,number_format($qDocAmt,2),$row2);
				$ctr2++;	
				$totQueue += $valQ['stsApplyAmt'];
			}
			if($totQueue<0){
				$totQueue = $totQueue*-1;
			}
			$worksheet->setRow($ctr2,16);
			$worksheet->write($ctr2,5,number_format($totQueue,2),$headerFormat);
				
$workbook->close();
?>
