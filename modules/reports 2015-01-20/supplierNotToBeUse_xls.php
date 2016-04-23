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
	
	$Deptc   = $workbook->addFormat(array('Size' => 10,
										  'fgColor' => 'white',
										  'Pattern' => 1,
										  'border' =>1,
										  'Align' => 'center'));
	$Deptc->setFontFamily('Calibri'); 
	$Deptc2   = $workbook->addFormat(array('Size' => 10,
										  'border' =>1,
										  'Pattern' => 1,
										  'Align' => 'center'));
	$Deptc2->setFgColor(12); 
	$Deptc2->setFontFamily('Calibri');
	
	$filename = "Supplier NTBU.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("Supplier Not to Be Use");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(3,0));
	
	/*
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
	*/
	
	$worksheet->write(0,0,"Supplier Not To Be Use From ".date('m/d/Y',strtotime($_GET['txtDateFrom']))." to ".date('m/d/Y',strtotime($_GET['txtDateTo'])),$headerFormat);
	for($i=1;$i<8;$i++) {
		$worksheet->write(0, $i, "",$headerFormat);	
	}
	$worksheet->setColumn(0,0,30);
	$worksheet->setColumn(0,1,19);
	$worksheet->setColumn(0,2,11);
	$worksheet->setColumn(0,3,15);
	$worksheet->setColumn(0,4,16);
	$worksheet->setColumn(0,5,16);
	$worksheet->setColumn(0,6,13);
	$worksheet->setColumn(0,7,20);
	
	$worksheet->write(2,0,"SUPPLIER NAME",$headerFormat);
	$worksheet->write(2,1,"SUPPLIER NO.",$headerFormat);
	$worksheet->write(2,2,"STS NO.",$headerFormat);
	$worksheet->write(2,3,"STS SEQUENCE",$headerFormat);
	$worksheet->write(2,4,"STS REFNO",$headerFormat);
	$worksheet->write(2,5,"DEPARTMENT",$headerFormat);
	$worksheet->write(2,6,"STS AMOUNT",$headerFormat);
	$worksheet->write(2,7,"STS APPLY DATE",$headerFormat);
	
		$ctrRow = 3;
		$totFund = $totUpload = $totQueue = 0;
		$ctr = 3 ;
		
		$arrCancelled = $reportsObj->supplierNotToBeUse($_GET['txtDateFrom'],$_GET['txtDateTo']);
		
			foreach ($arrCancelled as $val) {
					
				$totExpAmt = $flag = 0;
				$row = ($col==0) ? $detail2:$detail;
				$row2 = ($col==0) ? $Dept2:$Dept;
				$row3 = ($col==0) ? $Deptc2:$Deptc;
				$col = ($col==0) ? 1:0;
				$ctr++;	
				$worksheet->write($ctr,0,$val['ASNAME'],$row);
				$worksheet->write($ctr,1,$val['suppCode'],$row);
				$worksheet->write($ctr,2,$val['stsNo'],$row);
				$worksheet->write($ctr,3,$val['stsSeq'],$row);
				$worksheet->write($ctr,4,$val['stsRefno'],$row);
				$worksheet->write($ctr,5,$val['deptDesc'],$row);
				$worksheet->write($ctr,6,number_format($val['stsApplyAmt'],2),$row2);
				$worksheet->write($ctr,7,date('m/d/Y',strtotime($val['stsApplyDate'])),$row3);
				
				$totSts += $val['stsApplyAmt'];
			}
			$ctr++;	
			$worksheet->setRow($ctr,16);
			$worksheet->write($ctr,5,"Grand Total",$headerFormat);
			$worksheet->write($ctr,6,number_format($totSts,2),$headerFormat);
				
$workbook->close();
?>