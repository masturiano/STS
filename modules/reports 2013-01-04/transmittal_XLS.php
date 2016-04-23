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
	$Date   = $workbook->addFormat(array('Size' => 10,
										  'fgColor' => 'white',
										  'Pattern' => 1,
										  'border' =>1,
										  'Align' => 'center'));
	$Date->setFontFamily('Calibri'); 
	$Date2   = $workbook->addFormat(array('Size' => 10,
										  'border' =>1,
										  'Pattern' => 1,
										  'Align' => 'center'));
	$Date2->setFgColor(12); 
	$Date2->setFontFamily('Calibri');
	$filename = "transmittal.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("transmittal");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(3,0));
	if($_GET['cmbTran'] == '1'){
		$type = 'Regular STS';
	}elseif($_GET['cmbTran'] == '2'){
		$type = 'Listing Fee';
	}elseif($_GET['cmbTran'] == '3'){
		$type = 'Promo Fund';	
	}elseif($_GET['cmbTran'] == '4'){
		$type = 'Shelf Enhancer';	
	}elseif($_GET['cmbTran'] == '5'){
		$type = 'Display Allowance';	
	}else{
		$type = "All STS Type";
	}
	$worksheet->write(0,0,"Uploaded STS Transmittal Report $type From ".date('m/d/Y',strtotime($_GET['txtDateFrom']))." to ".date('m/d/Y',strtotime($_GET['txtDateTo'])),$headerFormat);
	for($i=1;$i<=6;$i++) {
		if($i!=2 || $i!=3){
			$worksheet->write(0, $i, "",$headerFormat);
		}
	}
	$worksheet->write(1, 2, $_GET['cmbComp'],$headerFormat);
	$worksheet->mergeCells(1,2,1,3);
	$worksheet->setColumn(0,0,13);
	$worksheet->setColumn(0,1,15);
	$worksheet->setColumn(0,2,40);
	$worksheet->setColumn(0,3,40);
	$worksheet->setColumn(0,4,15);
	$worksheet->setColumn(0,5,15);
	$worksheet->setColumn(0,6,15);
	
	
	$worksheet->write(2,0,"Invoice No.",$headerFormat);
	$worksheet->write(2,1,"Amount",$headerFormat);
	$worksheet->write(2,2,"Supplier",$headerFormat);
	$worksheet->write(2,3,"Store",$headerFormat);
	$worksheet->write(2,4,"Hierarchy",$headerFormat);
	$worksheet->write(2,5,"Apply Date",$headerFormat);
	$worksheet->write(2,6,"Upload Date",$headerFormat);
	
		$totFund = $totUpload = $totQueue = 0;
		$ctr = 3 ;
		$arrTran = $reportsObj->uploadedTransmittal($_GET['cmbType'], $_GET['cmbTran'],$_GET['cmbComp'],$_GET['txtDateFrom'],$_GET['txtDateTo']);
		
				foreach($arrTran as $valD){
				$row = ($col==0) ? $detail2:$detail;
				$row2 = ($col==0) ? $Dept2:$Dept;
				$row3 = ($col==0) ? $Date2:$Date;
				$col = ($col==0) ? 1:0;
					$ctr++;
					$worksheet->write($ctr,0,$valD['InvNo'],$row);
					$worksheet->write($ctr,1,number_format($valD['stsApplyAmt'],2),$row2);
					$worksheet->write($ctr,2,$valD['Supplier'],$row);
					$worksheet->write($ctr,3,$valD['Store'],$row);
					$worksheet->write($ctr,4,$valD['hierarchyDesc'],$row);
					$worksheet->write($ctr,5,date('m/d/Y',strtotime($valD['stsApplyDate'])),$row3);
					$worksheet->write($ctr,6,date('m/d/Y',strtotime($valD['uploadDate'])),$row3);
					
					$totAmt += $valD['stsApplyAmt'];
					$grandAmt += $valD['stsApplyAmt'];
				}
			$worksheet->setRow($ctr,16);
			$ctr++;
			$worksheet->write($ctr,0,"Grand Total",$headerFormat);
			$worksheet->write($ctr,1,number_format($grandAmt,2),$headerFormat);
				
$workbook->close();
?>