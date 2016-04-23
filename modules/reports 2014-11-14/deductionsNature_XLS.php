<?
################### INCLUDE FILE #################
	session_start();
	ini_set('include_path','D:\wamp\php\PEAR');
	ini_set("memory_limit","1g");
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
	$headerFormat->setNumFormat('#,##0.00');
	
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
	
	$Deptc3   = $workbook->addFormat(array('Size' => 10,
										  'border' =>1,
										  'Pattern' => 1,
										  'Align' => 'right'));
	$Deptc3->setFgColor(12); 
	$Deptc3->setFontFamily('Calibri');
	$Deptc3->setNumFormat('#,##0.00');	
	
	$filename = "deductions_nature.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("transaction_details");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(4,0));

	$worksheet->write(0,0,"Nature of Deductions From ".date('m/d/Y',strtotime($_GET['txtDateFrom']))." to ".date('m/d/Y',strtotime($_GET['txtDateTo'])),$headerFormat);
	for($i=1;$i<=9;$i++) {
		$worksheet->write(0, $i, "",$headerFormat);	
	}
	$worksheet->setColumn(0,0,40);
	$worksheet->setColumn(0,1,15);
	$worksheet->setColumn(0,2,15);
	$worksheet->setColumn(0,3,15);
	$worksheet->setColumn(0,4,5);
	$worksheet->setColumn(0,5,15);
	$worksheet->setColumn(0,6,25);
	$worksheet->setColumn(0,7,25);
	$worksheet->setColumn(0,8,50);
	$worksheet->setColumn(0,9,25);
	
		$ctrRow = 3;
		$totFund = $totUpload = $totQueue = 0;
		
		$supDet = $reportsObj->deductionsSupplier($_GET['cmbSupp']);
		$worksheet->write(1,0,"VENDOR#: ".$supDet['suppCode'],$row2);
		$worksheet->write(2,0,"VENDOR NAME: ".$supDet['suppName'],$row2);
		
		$worksheet->write(3,0,"Store",$headerFormat);
		$worksheet->write(3,1,"Invoice #",$headerFormat);
		$worksheet->write(3,2,"Amount",$headerFormat);
		$worksheet->write(3,3,"Start Date",$headerFormat);
		$worksheet->write(3,4,"Seq #",$headerFormat);
		$worksheet->write(3,5,"Contract #",$headerFormat);
		$worksheet->write(3,6,"Created By",$headerFormat);
		$worksheet->write(3,7,"Approve By",$headerFormat);
		$worksheet->write(3,8,"Description/Remarks",$headerFormat);
		$worksheet->write(3,9,"Check Details",$headerFormat);
		
		$ctr = 3 ;
		
		$row = ($col==0) ? $detail2:$detail;
		$row2 = ($col==0) ? $Dept2:$Dept;
		$row3 = ($col==0) ? $Deptc2:$Deptc;
		$row4 = $Deptc3;
		$col = ($col==0) ? 1:0;
		
		$arrDet = $reportsObj->deductionsNature($_GET['txtDateFrom'],$_GET['txtDateTo'],$_GET['cmbSupp']);
		$ctr++;

		foreach ($arrDet as $valDet) {
			$ctr++;
			$worksheet->write($ctr,0,$valDet['STRNAM'],$row);
			$worksheet->write($ctr,1,$valDet['invoice'],$row);
			$worksheet->write($ctr,2,$valDet['stsApplyAmt'],$row4);
			$worksheet->write($ctr,3,date('m/d/Y',strtotime($valDet['applyDate'])),$row3);
			$worksheet->write($ctr,4,$valDet['nbrApplication'],$row);
			$worksheet->write($ctr,5,$valDet['contractNo'],$row);
			$worksheet->write($ctr,6,$valDet['createdBy'],$row);
			$worksheet->write($ctr,7,$valDet['approvedBy'],$row);
			$worksheet->write($ctr,8,$valDet['stsRemarks'],$row3);
			$worksheet->write($ctr,9,$valDet['checkNumber'].' - '.$valDet['lastUpdateDate'],$row3);
			
			$totAmt += $valDet['stsApplyAmt'];
		}
		$ctr++;
		$worksheet->write($ctr,1,"Total: ",$headerFormat);
		$worksheet->write($ctr,2,$totAmt,$headerFormat);
					
$workbook->close();
?>
