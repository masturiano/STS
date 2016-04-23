<?
################### INCLUDE FILE #################
	session_start();
	ini_set('include_path','D:\wamp\php\PEAR');
	ini_set("memory_limit","1g");
	include("../../includes/db.inc.php");
	include("../../includes/common.php");
	include("accrualObj.php");
	require_once 'Spreadsheet/Excel/Writer.php';
	
	$accrual = new accrualObj();
	$workbook = new Spreadsheet_Excel_Writer();
	$headerFormat = $workbook->addFormat(array('Size' => 11,
                                      'Color' => 'black',
                                      'bold'=> 1,
									  'border' => 1,
									  'Align' => 'merge'));
	$headerFormat2 = $workbook->addFormat(array('Size' => 11,
                                      'Color' => 'black',
                                      'bold'=> 1,
									  'border' => 0,
									  'Align' => 'merge'));
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
	
	$monthFilename = date('F',strtotime($_GET['txtMonth']));
	$yearFilename = date('Y',strtotime($_GET['txtMonth']));
	
	$filename = "STS_Accrual_{$monthFilename}_{$yearFilename}.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("transaction_details");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(4,0));

	$worksheet->write(0,0,"Accrual for the Month of ".date('F',strtotime($_GET['txtMonth'])),$headerFormat);
	for($i=1;$i<=3;$i++) 
	{
		$worksheet->write(0, $i, "",$headerFormat);	
	}
	$worksheet->setColumn(0,0,15);
	$worksheet->setColumn(1,1,9);
	$worksheet->setColumn(2,2,10);
	$worksheet->setColumn(3,3,11);
	$worksheet->setColumn(4,4,10);
	$worksheet->setColumn(5,5,10);
	$worksheet->setColumn(6,6,9);
	$worksheet->setColumn(7,7,9);
	$worksheet->setColumn(8,8,9);
	$worksheet->setColumn(9,9,12);
	$worksheet->setColumn(10,10,11);
	$worksheet->setColumn(11,11,17);
	$worksheet->setColumn(12,12,15);
	$worksheet->setColumn(13,13,15);
	$worksheet->setColumn(14,14,20);
	$worksheet->setColumn(15,15,12);
	$worksheet->setColumn(16,16,11);
	$worksheet->setColumn(17,17,14);
	$worksheet->setColumn(18,18,6);
	$worksheet->setColumn(19,19,8);
	$worksheet->setColumn(20,20,30);
	$worksheet->setColumn(21,21,6);
	$worksheet->setColumn(22,22,6);

	
		//$arrCountMonth = $accrual ->getCountMonth($_GET['txtMonth']);
		
		
	
		//$lastYear = substr($_GET['txtDateFrom'],-4) - 1;
		
		//$worksheet->setMerge(2, 2, 2, 5);
		//$worksheet->write(2,2,$lastYear,$headerFormat2);
		
		//$currYear = substr($_GET['txtDateFrom'],-4);

		//$worksheet->setMerge(2, 6, 2, 9);
		//$worksheet->write(2,6,$currYear,$headerFormat2);
		
		$worksheet->write(3,0,"STS#",$headerFormat);
		$worksheet->write(3,1,"STS Seq",$headerFormat);
		$worksheet->write(3,2,"STS Refno",$headerFormat);
		$worksheet->write(3,3,"Comp Code",$headerFormat);
		$worksheet->write(3,4,"Store Code",$headerFormat);
		$worksheet->write(3,5,"Supp Code",$headerFormat);
		$worksheet->write(3,6,"STS Type",$headerFormat);
		$worksheet->write(3,7,"STS Dept",$headerFormat);
		$worksheet->write(3,8,"STS Class",$headerFormat);
		$worksheet->write(3,9,"STS Sub Class",$headerFormat);
		$worksheet->write(3,10,"Group Code",$headerFormat);
		$worksheet->write(3,11,"STS Apply Amount",$headerFormat);
		$worksheet->write(3,12,"STS Apply Date",$headerFormat);
		$worksheet->write(3,13,"STS Acctual Date",$headerFormat);
		$worksheet->write(3,14,"Store Payment Mode",$headerFormat);
		$worksheet->write(3,15,"Upload Date",$headerFormat);
		$worksheet->write(3,16,"Upload Ref",$headerFormat);
		$worksheet->write(3,17,"Upload Ap File",$headerFormat);
		$worksheet->write(3,18,"Status",$headerFormat);
		$worksheet->write(3,19,"Ap Batch",$headerFormat);
		$worksheet->write(3,20,"Remarks",$headerFormat);
		$worksheet->write(3,21,"Major",$headerFormat);
		$worksheet->write(3,22,"Minor",$headerFormat);
		
		$colHeader = 1;
		
		$ctr = 3 ;
		
		$row = ($col==0) ? $detail2:$detail;
		$row2 = ($col==0) ? $Dept2:$Dept;
		$row3 = ($col==0) ? $Deptc2:$Deptc;
		$row4 = $Deptc3;
		$col = ($col==0) ? 1:0;
		
		$arrDet = $accrual ->accrualDtl($_GET['txtMonth']);
		
		$NumToMonth = array('1'=>'Jan','2'=>'Feb','3'=>'Mar','4'=>'Apr','5'=>'May','6'=>'Jun','7'=>'Jul','8'=>'Aug','9'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
		
		foreach ($arrDet as $valDet) {
			$ctr++;
			$worksheet->write($ctr,0,$valDet['stsNo'],$row);
			$worksheet->write($ctr,1,$valDet['stsSeq'],$row);
			$worksheet->write($ctr,2,$valDet['stsRefno'],$row);
			$worksheet->write($ctr,3,$valDet['compCode'],$row);
			$worksheet->write($ctr,4,$valDet['strCode'],$row);
			$worksheet->write($ctr,5,$valDet['suppCode'],$row);
			$worksheet->write($ctr,6,$valDet['stsType'],$row);
			$worksheet->write($ctr,7,$valDet['stsDept'],$row);
			$worksheet->write($ctr,8,$valDet['stsCls'],$row);
			$worksheet->write($ctr,9,$valDet['stsSubCls'],$row);
			$worksheet->write($ctr,10,$valDet['grpCode'],$row);
			$worksheet->write($ctr,11,$valDet['stsApplyAmt'],$Deptc3);
			$worksheet->write($ctr,12,date('Y-m-d',strtotime($valDet['stsApplyDate'])),$row);
			$worksheet->write($ctr,13,date('Y-m-d',strtotime($valDet['stsActualDate'])),$row);
			$pMode = array('D'=>'Deduction','C'=>'Collection');
			$worksheet->write($ctr,14,$pMode[$valDet['stsPaymentMode']],$row);
			$worksheet->write($ctr,15,date('Y-m-d',strtotime($valDet['uploadDate'])),$row);
			$worksheet->write($ctr,16,$valDet['uploadApRef'],$row);
			$worksheet->write($ctr,17,$valDet['uploadApFile'],$row);
			$worksheet->write($ctr,18,$valDet['status'],$row);
			$worksheet->write($ctr,19,$valDet['apBatch'],$row);
			$worksheet->write($ctr,20,$valDet['stsRemarks'],$row);
			$worksheet->write($ctr,21,$valDet['glMajor'],$row);
			$worksheet->write($ctr,22,$valDet['glMinor'],$row);
			
			$totAmt += $valDet['stsApplyAmt'];
		}
		$ctr++;
		$worksheet->write($ctr,10,"Total",$headerFormat);
		$worksheet->write($ctr,11,$totAmt,$headerFormat);
		
					
$workbook->close();
?>
