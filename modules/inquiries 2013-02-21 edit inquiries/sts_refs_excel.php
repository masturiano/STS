<?
################### INCLUDE FILE #################
	session_start();
	ini_set('include_path','D:\wamp\php\PEAR');
	include("../../includes/db.inc.php");
	include("../../includes/common.php");
	include("inquiriesObj.php");
	require_once 'Spreadsheet/Excel/Writer.php';
	
	$inquiryObj = new inquiriesObj();
	$workbook = new Spreadsheet_Excel_Writer();
	$headerFormat = $workbook->addFormat(array('Size' => 11,
                                      'Color' => 'black',
                                      'bold'=> 1,
									  'border' => 1,
									  'Align' => 'merge'));
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
	$filename = "sts_refs.xls";
	$workbook->send($filename);
	$worksheet=&$workbook->addWorksheet("STS REFS");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(2, 0));
	$worksheet->setColumn(0,0,18);
	$worksheet->setColumn(0,1,10);
	$worksheet->setColumn(0,2,8);
	$worksheet->setColumn(0,3,8);
	$worksheet->setColumn(0,4,8);
	$worksheet->setColumn(0,5,11);
	$worksheet->setColumn(0,6,11);
	$worksheet->setColumn(0,7,19);
	$worksheet->setColumn(0,8,19);
	$worksheet->setColumn(0,9,30);
	$worksheet->setColumn(0,10,30);
	$worksheet->setColumn(0,11,25);
	$worksheet->setColumn(0,12,15);
	$worksheet->setColumn(0,13,15);
	$worksheet->setColumn(0,14,25);
	$worksheet->setColumn(0,15,15);
	
	$worksheet->write(1,0,"MODE OF PAYMENT",$headerFormat);
	$worksheet->write(1,1,"STS#",$headerFormat);
	$worksheet->write(1,2,"REF#",$headerFormat);
	$worksheet->write(1,3,"CONT.#",$headerFormat);
	$worksheet->write(1,4,"APPLY DATE",$headerFormat);
	$worksheet->write(1,5,"END DATE",$headerFormat);	
	$worksheet->write(1,6,"STS ENTRY DATE",$headerFormat);
	$worksheet->write(1,7,"NO OF APPLICATION",$headerFormat);
	$worksheet->write(1,8,"STORE",$headerFormat);
	$worksheet->write(1,9,"VENDOR#",$headerFormat);
	$worksheet->write(1,10,"VENDOR NAME",$headerFormat);
	$worksheet->write(1,11,"AMOUNT",$headerFormat);
	$worksheet->write(1,12,"APPROVED DATE",$headerFormat);
	$worksheet->write(1,13,"REMARKS",$headerFormat);
	$worksheet->write(1,14,"COMPANY",$headerFormat);
	$worksheet->write(1,15,"USER",$headerFormat);
		$ctr= 1;
		$totalAmt = 0 ;
		$arrSTS=$inquiryObj->getSTSDetailsRes($_GET['contractNo']);
			foreach($arrSTS as $valSTS){
				$ctr++;
				$row = ($col==0) ? $detail2:$detail;
				$row2 = ($col==0) ? $Dept2:$Dept;
				$col = ($col==0) ? 1:0;
				$worksheet->setRow($ctr,16);
				$payMode = ($valSTS['stsPaymentMode']=='D') ? "Invoice Deduction":"Check/Collection";
				$worksheet->write($ctr,0,$payMode,$row);
				$worksheet->write($ctr,1,$valSTS['stsNo'],$row2);
				$worksheet->write($ctr,2,$valSTS['stsRefno'],$row2);
				$worksheet->write($ctr,3,$valSTS['contractNo'],$row2);
				$worksheet->write($ctr,4,date("m/d/Y",strtotime($valSTS['applyDate'])),$row);
				$worksheet->write($ctr,5,date("m/d/Y",strtotime($valSTS['endDate'])),$row);	
				$worksheet->write($ctr,6,date("m/d/Y",strtotime($valSTS['dateEntered'])),$row);
				$worksheet->write($ctr,7,$valSTS['nbrApplication'],$row2);
				$worksheet->write($ctr,8,$valSTS['brnShortDesc'],$row);
				$worksheet->write($ctr,9,$valSTS['suppCode'],$row2);
				$worksheet->write($ctr,10,$valSTS['suppName'],$row);
				$worksheet->write($ctr,11,number_format($valSTS['stsAmt'],2),$row2);
				$worksheet->write($ctr,12,date("m/d/Y",strtotime($valSTS['dateApproved'])),$row);
				$worksheet->write($ctr,13,$valSTS['stsRemarks'],$row);
				$worksheet->write($ctr,14,$valSTS['compShort'],$row);
				$worksheet->write($ctr,15,$valSTS['fullName'],$row);
				$totalAmt += $valSTS['stsAmt'] ;
			}
				$ctr++;
				$worksheet->setRow($ctr,16);
				$worksheet->write($ctr,10,"Total",$headerFormat);	
				$worksheet->write($ctr,11,number_format($totalAmt,2),$headerFormat);
				
$workbook->close();
?>