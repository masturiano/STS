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
	$filename = "sts_onhold.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("sts_onhold");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(3,0));
	
	$arrH = $reportsObj->getCancelledSTSHdr($_GET['refNo'],date('m/d/Y'));
	
	$worksheet->write(0,0,"Supplier: ".$arrH['suppCode']."-".$arrH['supName'],$headerFormat2);
	$worksheet->write(1,0,"STS Ref No: ".$arrH['stsRefno'],$headerFormat2);
	$worksheet->write(2,0,"Cancelled By: ".$arrH['fullName'],$headerFormat2);
	$worksheet->write(3,0,"Cancelled Date: ".date('m/d/Y',strtotime($arrH['cancelDate'])),$headerFormat2);
	$worksheet->write(4,0,"Reason: ".$arrH['cancelDesc'],$headerFormat2);
	$worksheet->write(5,0,"Replacement STS: ".$arrH['replacementSts'],$headerFormat2);
	
	
	$worksheet->setColumn(0,0,14);
	$worksheet->setColumn(0,1,20);
	$worksheet->setColumn(0,2,10);
	$worksheet->setColumn(0,3,18);
	
	
	
	$worksheet->write(6,0,"STS NO.",$headerFormat);
	$worksheet->write(6,1,"Store Code",$headerFormat);
	$worksheet->write(6,2,"Store Name",$headerFormat);
	$worksheet->write(6,3,"Application Date",$headerFormat);
	$worksheet->write(6,4,"Uploaded Amt",$headerFormat);
	$worksheet->write(6,5,"Onqueue Amt",$headerFormat);
	$worksheet->write(6,6,"Store Amt",$headerFormat);
	
		$ctrRow = 3;
		$totFund = $totUpload = $totQueue = 0;
		$ctr = 7 ;
		
		$arrTran = $reportsObj->getCancelledDtl($_GET['refNo'],date('m/d/Y'));
		
		foreach($arrTran as $valD){
			$ctr++;
			$worksheet->write($ctr,0,$valD['stsNo']."-".$valD['stsSeq'],$row2);
			$worksheet->write($ctr,1,$valD['strCode'],$row2);
			$worksheet->write($ctr,2,$valD['brnDesc'],$row2);
			$worksheet->write($ctr,3,date('m/d/Y',strtotime($valD['stsApplyDate'])),$row);
			$worksheet->write($ctr,4,number_format($valD['uploadedAmt'],2),$row);
			$worksheet->write($ctr,5,number_format($valD['queueAmt'],2),$row);
			$worksheet->write($ctr,6,number_format($valD['stsStrAmt'],2),$row);
			$totUploaded = round($valD['uploadedAmt'],2);
			$totQueue = round($valD['queueAmt'],2);
			$totStore = round($valD['stsStrAmt'],2);
		}
		$ctr++;
		$worksheet->write($ctr,3,"Total Amount: ",$row);
		$worksheet->write($ctr,4,number_format($totUploaded,2),$row);
		$worksheet->write($ctr,5,number_format($totQueue,2),$row);
		$worksheet->write($ctr,6,number_format($totStore,2),$row);
$workbook->close();
?>

