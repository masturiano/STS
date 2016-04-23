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
	$filename = "sts_conditional.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("sts_conditional");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(3,0));
	
	
	$worksheet->write(0,0,"STS Conditional From ".date('m/d/Y',strtotime($_GET['txtDateFrom']))." to ".date('m/d/Y',strtotime($_GET['txtDateTo'])),$headerFormat);
	for($i=1;$i<=4;$i++) {
		$worksheet->write(0, $i, "",$headerFormat);	
	}
	$worksheet->setColumn(0,0,14);
	$worksheet->setColumn(0,1,20);
	$worksheet->setColumn(0,2,10);
	$worksheet->setColumn(0,3,18);
	
	
	
	$worksheet->write(2,0,"STS REF.",$headerFormat);
	$worksheet->write(2,1,"Supplier",$headerFormat);
	$worksheet->write(2,2,"Hold Until",$headerFormat);
	$worksheet->write(2,3,"Remarks",$headerFormat);
	
		$ctrRow = 3;
		$totFund = $totUpload = $totQueue = 0;
		$ctr = 3 ;
		$arrTran = $reportsObj->getStsOnHold($_GET['txtDateFrom'],$_GET['txtDateTo']);
		
		foreach($arrTran as $valD){
			$ctr++;
			$worksheet->write($ctr,0,$valD['stsRefno'],$row2);
			$worksheet->write($ctr,1,$valD['suppCode']."-".$valD['suppName'],$row2);
			$worksheet->write($ctr,2,date('m/d/Y',strtotime($valD['holdingDate'])),$row2);
			$worksheet->write($ctr,3,$valD['stsRemarks'],$row);				
		}
		$ctr++;
$workbook->close();
?>
