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
	$headerFormat3 = $workbook->addFormat(array('Size' => 11,
                                      'Color' => 'black',
                                      'bold'=> 1,
									  'border' => 1,
									  'Align' => 'right'));
	$headerFormat3->setFontFamily('Calibri'); 
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
	$filename = "sts_by_supp.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("STS by Supplier");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(3,0));
	if($_GET['cmbStatus']=='O'){
		$status = "(On Queue)";	
	}elseif($_GET['cmbStatus']=='A'){
		$status = "(Applied)";	
	}else{
		$status = "(On Queue AND Applied)";	
	}
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
	$worksheet->write(0,0,"$status $type From ".date('m/d/Y',strtotime($_GET['txtDateFrom']))." to ".date('m/d/Y',strtotime($_GET['txtDateTo']))." By Supplier",$headerFormat);
	for($i=1;$i<=10;$i++) {
		$worksheet->write(0, $i, "",$headerFormat);	
	}
	$worksheet->setColumn(0,0,10);
	$worksheet->setColumn(0,1,10);
	$worksheet->setColumn(0,2,35);
	$worksheet->setColumn(0,3,35);
	$worksheet->setColumn(0,4,15);
	$worksheet->setColumn(0,5,15);
	$worksheet->setColumn(0,6,15);
	$worksheet->setColumn(0,7,15);
	$worksheet->setColumn(0,8,15);
	$worksheet->setColumn(0,9,15);
	$worksheet->setColumn(0,10,20);
	
	$worksheet->write(2,0,"STS REF.",$headerFormat);
	$worksheet->write(2,1,"STS NO.",$headerFormat);
	$worksheet->write(2,2,"BRANCH",$headerFormat);
	$worksheet->write(2,3,"SUPPLIER",$headerFormat);
	$worksheet->write(2,4,"REMARKS",$headerFormat);
	$worksheet->write(2,5,"AMOUNT",$headerFormat);
	$worksheet->write(2,6,"PAYMENT MODE",$headerFormat);
	$worksheet->write(2,7,"APPLY DATE",$headerFormat);
	$worksheet->write(2,8,"NO. OF APPLICATION",$headerFormat);
	$worksheet->write(2,9,"STATUS",$headerFormat);
	$worksheet->write(2,10,"MMS REF",$headerFormat);
	
	
		$ctrRow = 3;
		$totFund = $totUpload = $totQueue = 0;
		$ctr = 3 ;
		$arrTran = $reportsObj->getStsSupp($_GET['txtDateFrom'],$_GET['txtDateTo'],$_GET['cmbStatus'],$_GET['cmbTran']);
		
			foreach ($arrTran as $val) {
				$totAmt = 0;
				$totExpAmt = $flag = 0;
				$row = ($col==0) ? $detail2:$detail;
				$row2 = ($col==0) ? $Dept2:$Dept;
				$col = ($col==0) ? 1:0;
				$arrH = $reportsObj->getReleasedSTSAPSup($_GET['txtDateFrom'],$_GET['txtDateTo'],$_GET['cmbStatus'],$val['suppCode'],$_GET['cmbTran']);
				$ctr++;
				$worksheet->write($ctr,0,$val['suppName'],$headerFormat2);
				foreach($arrH as $valD){
					$ctr++;
					$worksheet->write($ctr,0,$valD['stsRefno'],$row2);
						if($valD['stsType']=='5'){
						$prefix = 'DA';	
					}else{
						$prefix = 'STS';	
					}
					$worksheet->write($ctr,1,$prefix.$valD['stsNo']."-".$valD['stsSeq'],$row);
					$worksheet->write($ctr,2,$valD['brnDesc'],$row);
					$worksheet->write($ctr,3,$valD['suppName'],$row);
					$worksheet->write($ctr,4,$valD['stsRemarks'],$row2);
					$worksheet->write($ctr,5,number_format($valD['stsApplyAmt'],2),$row2);
					$worksheet->write($ctr,6,$valD['payMode'],$row2);
					$worksheet->write($ctr,7,date('m/d/Y',strtotime($valD['stsApplyDate'])),$row2);
					$worksheet->write($ctr,8,$valD['nbrApplication'],$row2);
					$worksheet->write($ctr,9,$valD['applyStatus'],$row);
					if($valD['stsPaymentMode'] =='D'){
						$worksheet->write($ctr,10,$valD['apBatch'],$row);	
					}else{
						$worksheet->write($ctr,10,$valD['arBatch'],$row);	
					}
					$totAmt += $valD['stsApplyAmt'];
					$grandAmt += $valD['stsApplyAmt'];
				}
				$ctr++;
				$worksheet->write($ctr,4,"Branch Total",$headerFormat);
				$worksheet->write($ctr,5,number_format($totAmt,2),$headerFormat);
			}
			$worksheet->setRow($ctr,16);
			$ctr++;
			$worksheet->write($ctr,4,"Grand Total",$headerFormat);
			$worksheet->write($ctr,5,number_format($grandAmt,2),$headerFormat);
				
$workbook->close();
?>
