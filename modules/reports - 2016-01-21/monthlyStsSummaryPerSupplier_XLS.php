<?
################### INCLUDE FILE #################
	session_start();
	ini_set('include_path','C:\wamp\php\PEAR');
	ini_set("memory_limit","1g");
	include("../../includes/db.inc.php");
	include("../../includes/common.php");
	include("monthlyStsSummaryPerSupplierObj.php");
	require_once 'Spreadsheet/Excel/Writer.php';
	
	$monthlyStsSummaryPerSupplierObj = new monthlyStsSummaryPerSupplierObj();
	$workbook = new Spreadsheet_Excel_Writer();
	$headerFormat = $workbook->addFormat(array('Size' => 11,
                                      'Color' => 'black',
                                      'bold'=> 1,
									  'border' => 1,
									  'Align' => 'merge'));
	$headerFormat->setFontFamily('Calibri'); 
	$headerFormat->setNumFormat('#,##0.00');
	
	$headerFormatTot = $workbook->addFormat(array('Size' => 11,
                                      'Color' => 'black',
                                      'bold'=> 1,
									  'border' => 1,
									  'Align' => 'right'));
	$headerFormatTot->setFontFamily('Calibri'); 
	$headerFormatTot->setNumFormat('#,##0.00');
	$headerFormatTot->setFgColor('blue'); 
	
	$headerFormatTot2 = $workbook->addFormat(array('Size' => 11,
                                      'Color' => 'black',
                                      'bold'=> 1,
									  'border' => 1,
									  'Align' => 'right'));
	$headerFormatTot2->setFontFamily('Calibri'); 
	$headerFormatTot2->setNumFormat('#,##0.00');
	$headerFormatTot2->setFgColor(15); 
	
	$headerFormat2 = $workbook->addFormat(array('Size' => 11,
                                      'Color' => 'black',
                                      'bold'=> 1,
									  'border' => 0,
									  'Align' => 'merge'));
	$headerFormat2->setFgColor(12); 
	
	
	$headerFormat3 = $workbook->addFormat(array('Size' => 11,
                                      'Color' => 'black',
                                      'bold'=> 1,
									  'border' => 1,
									  'Align' => 'right'));
	$headerFormat3->setFontFamily('Calibri'); 
	
	$headerFormatLastYear = $workbook->addFormat(array('Size' => 11,
                                      'Color' => 'black',
                                      'bold'=> 1,
									  'border' => 0,
									  'Align' => 'merge'));
	$headerFormatLastYear->setFgColor(12); 
	
	$headerFormatCurrYear = $workbook->addFormat(array('Size' => 11,
                                      'Color' => 'black',
                                      'bold'=> 1,
									  'border' => 0,
									  'Align' => 'merge'));
	$headerFormatCurrYear->setFgColor(15); 
	
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
    
    $number   = $workbook->addFormat(array('Size' => 10,
                                          'fgColor' => 'white',
                                          'Pattern' => 1,
                                          'border' =>1,
                                          'Align' => 'left'));
    $number->setFontFamily('Calibri'); 
    $number->setFgColor(12); 
	
	$detail   = $workbook->addFormat(array('Size' => 10,
										  'fgColor' => 'white',
										  'Pattern' => 1,
										  'border' =>1,
										  'Align' => 'left'));
	$detail->setFontFamily('Calibri'); 
	$detail->setFgColor(12); 
	$detail->setNumFormat('#,##0.00');

	$detail2   = $workbook->addFormat(array('Size' => 10,
										  'border' =>1,
										  'Pattern' => 1,
										  'Align' => 'left'));
	$detail2->setFgColor(15); 
	$detail2->setFontFamily('Calibri'); 
	$detail2->setNumFormat('#,##0.00');
	
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
	
	$filename = "Monthly_STS_summary_per_supplier.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("transaction_details");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(4,0));

	$worksheet->write(0,0,"Monthly STS Summary per Supplier From ".date('M-Y',strtotime($_GET['txtDateFrom']))." to ".date('M-Y',strtotime($_GET['txtDateTo'])),$headerFormat);
	for($i=1;$i<=1;$i++) 
	{
		$worksheet->write(0, $i, "",$headerFormat);	
	}
	$worksheet->setColumn(0,0,15);
	$worksheet->setColumn(0,1,45);
	for($iCol=2;$iCol<=100;$iCol++) {
		$worksheet->setColumn(0,$iCol,15);
	}
	
		//$arrCountMonth = $stsSummaryPerSupplierObj->getCountMonth($_GET['txtDateFrom'],$_GET['txtDateTo']);
		
		
	
		//$lastYear = substr($_GET['txtDateFrom'],-4) - 1;
		//$mergeNo = $arrCountMonth['stsActualDate'];
		
		//$mergeCtr = 2;
		//$mergeLastyearCtr = $mergeCtr + $mergeNo;
		//$worksheet->setMerge(2, 2, 2, $mergeLastyearCtr);
		
		//$worksheet->write(2,2,$lastYear,$headerFormat2);
		
		//$currYear = substr($_GET['txtDateFrom'],-4);

		//$mergeCurrYearCol = $mergeLastyearCtr + 1;
		//$mergeCurrYearCtr = $mergeCurrYearCol  + $mergeNo;
		//$worksheet->setMerge(2, $mergeCurrYearCol, 2, $mergeCurrYearCtr);
		
		//$worksheet->write(2,$mergeCurrYearCol,$currYear,$headerFormat2);
		
		
		
		//$colHeader = 1;
		
		//$arrMonth = $stsSummaryPerSupplierObj->getMonth($_GET['txtDateFrom'],$_GET['txtDateTo']);
		
		//foreach ($arrMonth as $valMonth)
		//{ 
		//	$colHeader++;
		//	$monthName = date("F", mktime(0, 0, 0, $valMonth['stsActualMonth'], 10));
		//	$worksheet->write(3,$colHeader,$monthName,$headerFormat);
		//}		
		//$colHeader++;
		//$worksheet->write(3,$colHeader,"Total",$headerFormat);
		//foreach ($arrMonth as $valMonth4)
		//{ 
		//	$colHeader++;
		//	$monthName = date("F", mktime(0, 0, 0, $valMonth4['stsActualMonth'], 10));
		//	$worksheet->write(3,$colHeader,$monthName,$headerFormat);
		//}	
		//$colHeader++;
		//$worksheet->write(3,$colHeader,"Total",$headerFormat);
		
		
		
		$row = ($col==0) ? $detail2:$detail;
		$row2 = ($col==0) ? $Dept2:$Dept;
		$row3 = ($col==0) ? $Deptc2:$Deptc;
		$row4 = $Deptc3;
		$col = ($col==0) ? 1:0;
		
		$arrDet = $monthlyStsSummaryPerSupplierObj->stsSummPerSupllierDtlLasYear($_GET['txtDateFrom'],$_GET['txtDateTo'],$arrMonth);
		$arrDet2 = $monthlyStsSummaryPerSupplierObj->stsSummPerSupllierDtlCurYear($_GET['txtDateFrom'],$_GET['txtDateTo'],$arrMonth);
		
		
		
		$dateFrom = date("Y-m-d", strtotime($_GET['txtDateFrom']));
		$dateTo = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m',strtotime($_GET['txtDateTo'])).'/01/'.date('Y',strtotime($_GET['txtDateTo'])).' 00:00:00'))));
		
		$separateDateFrom =  date("d", strtotime($_GET['txtDateFrom']));
		$separateMonthFrom =  date("m", strtotime($_GET['txtDateFrom'])) * 1;
		$separateYearFrom =  date("Y", strtotime($_GET['txtDateFrom']));
		$separateLastYearFrom = $separateYearFrom - 1;
		
		$separateDateTo =  date("d", strtotime($_GET['txtDateTo']));
		$separateMonthTo =  date("m", strtotime($_GET['txtDateTo']))  * 1;
		$separateYearTo =  date("Y", strtotime($_GET['txtDateTo']));
		$separateLastYearTo = $separateYearTo - 1;
		
		$monthGroupYear  =  date("d", strtotime($dtFrom));
		
		$countMonth = ($separateMonthTo - $separateMonthFrom) + 1;
		$colHeaderYCtr = 1;
		$colHeaderY = 3;
		$colHeaderYearTot = 3;
		$colHeaderYr = (($colHeaderY * $countMonth) + $colHeaderYearTot ) + $colHeaderYCtr;
		
		$worksheet->write(2,2,$separateLastYearTo,$headerFormatLastYear);
		$worksheet->setMerge(2, 2, 2, $colHeaderYr);
		
		$colHeaderCurrYrCtr = $colHeaderYr + 1;
		$colHeaderYCtr2 = 1;
		$colHeaderY2 = 3;
		$colHeaderYearTot2 = 3;
		$colHeaderYr2 = (((($colHeaderY2 * $countMonth) + $colHeaderYearTot2) + $colHeaderYCtr2) * 2)-1;
		
		$worksheet->write(2,$colHeaderCurrYrCtr,$separateYearTo,$headerFormatCurrYear);
		$colHeaderCurrYrCtrBorder = $colHeaderCurrYrCtr + 1;
		$worksheet->setMerge(2, $colHeaderCurrYrCtr, 2, $colHeaderYr2);
		
		$worksheet->write(3,0,"Supplier #",$headerFormat);
		$worksheet->write(3,1,"Supplier Name",$headerFormat);
		
		$colHeader = 1;
		$NumToMonth = array('1'=>'Jan','2'=>'Feb','3'=>'Mar','4'=>'Apr','5'=>'May','6'=>'Jun','7'=>'Jul','8'=>'Aug','9'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
		
		for($i=$separateMonthFrom;$i<=$separateMonthTo;$i++) {
			$colHeader++;
			$worksheet->write(3,$colHeader ,"STS ".$NumToMonth[$i],$headerFormat);
			$colHeader++;
			$worksheet->write(3,$colHeader ,"DA ".$NumToMonth[$i],$headerFormat);
			$colHeader++;
			$worksheet->write(3,$colHeader ,"PF ".$NumToMonth[$i],$headerFormat);
		}
		$colHeader++;
		$worksheet->write(3,$colHeader ,"STS TOTAL",$headerFormat);
		$colHeader++;
		$worksheet->write(3,$colHeader ,"DA TOTAL",$headerFormat);
		$colHeader++;
		$worksheet->write(3,$colHeader ,"PF TOTAL",$headerFormat);	

		for($i2=$separateMonthFrom;$i2<=$separateMonthTo;$i2++) {
			$colHeader++;
			$worksheet->write(3,$colHeader ,"STS ".$NumToMonth[$i2],$headerFormat);
			$colHeader++;
			$worksheet->write(3,$colHeader ,"DA ".$NumToMonth[$i2],$headerFormat);
			$colHeader++;
			$worksheet->write(3,$colHeader ,"PF ".$NumToMonth[$i2],$headerFormat);
		}
		$colHeader++;
		$worksheet->write(3,$colHeader ,"STS TOTAL",$headerFormat);
		$colHeader++;
		$worksheet->write(3,$colHeader ,"DA TOTAL",$headerFormat);
		$colHeader++;
		$worksheet->write(3,$colHeader ,"PF TOTAL",$headerFormat);	
		
		$ctr = 3 ;
		
		foreach ($arrDet as $valDet) {
			$ctr++;
			
			$worksheet->write($ctr,0,$valDet['asnum'],$number);
			$worksheet->write($ctr,1,$valDet['asname'],$detail);
			
			//$totAll = 0;
			
			$totStsLy = 0;
			$totDaLy = 0;
			$totPfLy = 0;
			
			$colHeader2 = 1;
			for($i=$separateMonthFrom;$i<=$separateMonthTo;$i++) {
				$i = $i * 1;
				$colHeader2++;
				$worksheet->write($ctr,$colHeader2,$valDet['sts'.$i],$detail);
				$colHeader2++;
				$worksheet->write($ctr,$colHeader2,$valDet['da'.$i],$detail);
				$colHeader2++;
				$worksheet->write($ctr,$colHeader2,$valDet['pf'.$i],$detail);
				
				//$totAll+= $valDet['sts'.$i] + $valDet['da'.$i] + $valDet['pf'.$i];
				
				$totStsLy+= $valDet['sts'.$i] ;
				$totDaLy+= $valDet['da'.$i] ;
				$totPfLy+= $valDet['pf'.$i];
		
			}
			$colHeader2++;
			$worksheet->write($ctr,$colHeader2,$totStsLy,$headerFormatTot);
			$colHeader2++;
			$worksheet->write($ctr,$colHeader2,$totDaLy,$headerFormatTot);
			$colHeader2++;
			$worksheet->write($ctr,$colHeader2,$totPfLy,$headerFormatTot);
			
		}
		
		$ctr = 3 ;
		
		foreach ($arrDet2 as $valDet2) {
			$ctr++;
			
			$totStsCy = 0;
			$totDaCy = 0;
			$totPfCy = 0;
			
			$colHeader2Cy = $colHeader2;
			for($i2=$separateMonthFrom;$i2<=$separateMonthTo;$i2++) {
				$i = $i * 1;
				$colHeader2Cy++;
				$worksheet->write($ctr,$colHeader2Cy,$valDet2['sts'.$i2],$detail2);
				$colHeader2Cy++;
				$worksheet->write($ctr,$colHeader2Cy,$valDet2['da'.$i2],$detail2);
				$colHeader2Cy++;
				$worksheet->write($ctr,$colHeader2Cy,$valDet2['pf'.$i2],$detail2);
				
				$totStsCy+= $valDet2['sts'.$i2] ;
				$totDaCy+= $valDet2['da'.$i2] ;
				$totPfCy+= $valDet2['pf'.$i2];
		
			}
			$colHeader2Cy++;
			$worksheet->write($ctr,$colHeader2Cy,$totStsCy,$headerFormatTot2);
			$colHeader2Cy++;
			$worksheet->write($ctr,$colHeader2Cy,$totDaCy,$headerFormatTot2);
			$colHeader2Cy++;
			$worksheet->write($ctr,$colHeader2Cy,$totPfCy,$headerFormatTot2);
			
		}

					
$workbook->close();
?>
