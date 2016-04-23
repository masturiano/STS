<?
################### INCLUDE FILE #################
	session_start();
	ini_set('include_path','D:\wamp\php\PEAR');
	ini_set("memory_limit","1g");
	include("../../includes/db.inc.php");
	include("../../includes/common.php");
	include("stsSummaryPerStoreObj.php");
	require_once 'Spreadsheet/Excel/Writer.php';
	
	$stsSummaryPerStoreObj = new stsSummaryPerStoreObj();
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
	$detail->setNumFormat('#,##0.00');	

	$detail2   = $workbook->addFormat(array('Size' => 10,
										  'border' =>1,
										  'Pattern' => 1,
										  'Align' => 'left'));
	$detail2->setFgColor(12); 
	$detail2->setFontFamily('Calibri'); 
	$detail2->setNumFormat('#,##0.00');	
	
	$detail3   = $workbook->addFormat(array('Size' => 10,
										  'border' =>1,
										  'Pattern' => 1,
										  'Align' => 'left'));
	$detail3->setFgColor(12); 
	$detail3->setFontFamily('Calibri'); 
	
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
	
	$filename = "STS_summary_per_store.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("transaction_details");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(4,0));

	$worksheet->write(0,0,"Monthly STS Summary per Store From ".date('m/d/Y',strtotime($_GET['txtDateFrom']))." to ".date('m/d/Y',strtotime($_GET['txtDateTo'])),$headerFormat);
	for($i=1;$i<=1;$i++) 
	{
		$worksheet->write(0, $i, "",$headerFormat);	
	}
	$worksheet->setColumn(0,0,15);
	$worksheet->setColumn(0,1,45);
	$worksheet->setColumn(0,2,15);
	$worksheet->setColumn(0,3,15);
	$worksheet->setColumn(0,4,15);
	$worksheet->setColumn(0,5,15);
	$worksheet->setColumn(0,6,15);
	$worksheet->setColumn(0,7,15);
	$worksheet->setColumn(0,8,15);
	$worksheet->setColumn(0,9,15);
	$worksheet->setColumn(0,10,15);
	$worksheet->setColumn(0,11,15);
	$worksheet->setColumn(0,12,15);
	$worksheet->setColumn(0,13,15);
	$worksheet->setColumn(0,14,15);
	$worksheet->setColumn(0,15,15);
	$worksheet->setColumn(0,16,15);
	$worksheet->setColumn(0,17,15);
	$worksheet->setColumn(0,18,15);
	$worksheet->setColumn(0,19,15);
	$worksheet->setColumn(0,20,15);
	$worksheet->setColumn(0,21,15);
	$worksheet->setColumn(0,22,15);
	$worksheet->setColumn(0,23,15);
	$worksheet->setColumn(0,24,15);
	$worksheet->setColumn(0,25,15);
	$worksheet->setColumn(0,26,15);
	$worksheet->setColumn(0,27,15);
	$worksheet->setColumn(0,28,15);
	$worksheet->setColumn(0,29,15);
	$worksheet->setColumn(0,30,15);
	
		$arrCountMonth = $stsSummaryPerStoreObj ->getCountMonth($_GET['txtDateFrom'],$_GET['txtDateTo']);
		
		
	
		$lastYear = substr($_GET['txtDateFrom'],-4) - 1;
		
		$worksheet->setMerge(2, 2, 2, 11);
		$worksheet->write(2,2,$lastYear,$headerFormat2);
		
		$currYear = substr($_GET['txtDateFrom'],-4);

		$worksheet->setMerge(2, 12, 2, 21);
		$worksheet->write(2,12,$currYear,$headerFormat2);
		
		$worksheet->write(3,0,"Store Code",$headerFormat);
		$worksheet->write(3,1,"Store Name",$headerFormat);
		$worksheet->write(3,2,"STS AP",$headerFormat);
		$worksheet->write(3,3,"STS AR",$headerFormat);
		$worksheet->write(3,4,"STS TOTAL",$headerFormat);
		$worksheet->write(3,5,"DA AP",$headerFormat);
		$worksheet->write(3,6,"DA AR",$headerFormat);
		$worksheet->write(3,7,"DA TOTAL",$headerFormat);
		$worksheet->write(3,8,"PF AP",$headerFormat);
		$worksheet->write(3,9,"PF AR",$headerFormat);
		$worksheet->write(3,10,"PF TOTAL",$headerFormat);
		$worksheet->write(3,11,"TOTAL $lastYear ",$headerFormat);
		$worksheet->write(3,12,"STS AP",$headerFormat);
		$worksheet->write(3,13,"STS AR",$headerFormat);
		$worksheet->write(3,14,"STS TOTAL",$headerFormat);
		$worksheet->write(3,15,"DA AP",$headerFormat);
		$worksheet->write(3,16,"DA AR",$headerFormat);
		$worksheet->write(3,17,"DA TOTAL",$headerFormat);
		$worksheet->write(3,18,"PF AP",$headerFormat);
		$worksheet->write(3,19,"PF AR",$headerFormat);
		$worksheet->write(3,20,"PF TOTAL",$headerFormat);
		$worksheet->write(3,21,"TOTAL $currYear ",$headerFormat);
		
		$colHeader = 1;
		
		$ctr = 3 ;
		
		$row = ($col==0) ? $detail2:$detail;
		$row2 = ($col==0) ? $Dept2:$Dept;
		$row3 = ($col==0) ? $Deptc2:$Deptc;
		$row4 = $Deptc3;
		$col = ($col==0) ? 1:0;
		
		$arrDet = $stsSummaryPerStoreObj ->stsSummPerStoreDtl($_GET['txtDateFrom'],$_GET['txtDateTo']);
		
		$NumToMonth = array('1'=>'Jan','2'=>'Feb','3'=>'Mar','4'=>'Apr','5'=>'May','6'=>'Jun','7'=>'Jul','8'=>'Aug','9'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
		
		foreach ($arrDet as $valDet) {
		
			$ctr++;
			$worksheet->write($ctr,0,$valDet['strCode'],$detail3);
			$worksheet->write($ctr,1,$valDet['brnDesc'],$row);
			$worksheet->write($ctr,2,$valDet['ApStsLastYear'],$row);
			$worksheet->write($ctr,3,$valDet['ArStsLastYear'],$row);
			$worksheet->write($ctr,4,$valDet['stsLastYear'],$row);
			$worksheet->write($ctr,5,$valDet['ApDaLastYear'],$row);
			$worksheet->write($ctr,6,$valDet['ArDaLastYear'],$row);
			$worksheet->write($ctr,7,$valDet['daLastYear'],$row);
			$worksheet->write($ctr,8,$valDet['ApPfLastYear'],$row);
			$worksheet->write($ctr,9,$valDet['ArPfLastYear'],$row);
			$worksheet->write($ctr,10,$valDet['pfLastYear'],$row);
			$totLY =  $valDet['stsLastYear'] + $valDet['daLastYear'] + $valDet['pfLastYear'];
			$worksheet->write($ctr,11,$totLY ,$row);
			$worksheet->write($ctr,12,$valDet['ApStsCurrYear'],$row);
			$worksheet->write($ctr,13,$valDet['ArStsCurrYear'],$row);
			$worksheet->write($ctr,14,$valDet['stsCurrYear'],$row);
			$worksheet->write($ctr,15,$valDet['ApDaCurrYear'],$row);
			$worksheet->write($ctr,16,$valDet['ArDaCurrYear'],$row);
			$worksheet->write($ctr,17,$valDet['daCurrYear'],$row);
			$worksheet->write($ctr,18,$valDet['ApPfCurrYear'],$row);
			$worksheet->write($ctr,19,$valDet['ArPfCurrYear'],$row);
			$worksheet->write($ctr,20,$valDet['pfCurrYear'],$row);
			$totCY =  $valDet['stsCurrYear'] + $valDet['daCurrYear'] + $valDet['pfCurrYear'];
			$worksheet->write($ctr,21,$totCY ,$row);
			
			$totApStsLastYear += $valDet['ApStsLastYear'];
			$totArStsLastYear += $valDet['ArStsLastYear'];
			$totstsLastYear += $valDet['stsLastYear'];
			$totApDaLastYear += $valDet['ApDaLastYear'];
			$totArDaLastYear += $valDet['ArDaLastYear'];
			$totdaLastYear += $valDet['daLastYear'];
			$totApPfLastYear += $valDet['ApPfLastYear'];
			$totArPfLastYear += $valDet['ArPfLastYear'];
			$totpfLastYear += $valDet['pfLastYear'];
			$tottotLY += $totLY;
			$totApStsCurrYear += $valDet['ApStsCurrYear'];
			$totArStsCurrYear += $valDet['ArStsCurrYear'];
			$totstsCurrYear += $valDet['stsCurrYear'];
			$totApDaCurrYear += $valDet['ApDaCurrYear'];
			$totArDaCurrYear += $valDet['ArDaCurrYear'];
			$totdaCurrYear += $valDet['daCurrYear'];
			$totApPfCurrYear += $valDet['ApPfCurrYear'];
			$totArPfCurrYear += $valDet['ArPfCurrYear'];
			$totpfCurrYear += $valDet['pfCurrYear'];
			$tottotCY += $totCY;
		}
		$ctr++;
		
		$worksheet->write($ctr,0,'TOTAL',$headerFormat);
		$worksheet->write($ctr,1,'',$headerFormat);
		$worksheet->write($ctr,2,$totApStsLastYear,$headerFormat);
		$worksheet->write($ctr,3,$totArStsLastYear,$headerFormat);
		$worksheet->write($ctr,4,$totstsLastYear,$headerFormat);
		$worksheet->write($ctr,5,$totApDaLastYear,$headerFormat);
		$worksheet->write($ctr,6,$totArDaLastYear,$headerFormat);
		$worksheet->write($ctr,7,$totdaLastYear,$headerFormat);
		$worksheet->write($ctr,8,$totApPfLastYear,$headerFormat);
		$worksheet->write($ctr,9,$totArPfLastYear,$headerFormat);
		$worksheet->write($ctr,10,$totpfLastYear,$headerFormat);
		$worksheet->write($ctr,11,$tottotLY,$headerFormat);
		$worksheet->write($ctr,12,$totApStsCurrYear,$headerFormat);
		$worksheet->write($ctr,13,$totArStsCurrYear,$headerFormat);
		$worksheet->write($ctr,14,$totstsCurrYear,$headerFormat);
		$worksheet->write($ctr,15,$totApDaCurrYear,$headerFormat);
		$worksheet->write($ctr,16,$totArDaCurrYear,$headerFormat);
		$worksheet->write($ctr,17,$totdaCurrYear,$headerFormat);
		$worksheet->write($ctr,18,$totApPfCurrYear,$headerFormat);
		$worksheet->write($ctr,19,$totArPfCurrYear,$headerFormat);
		$worksheet->write($ctr,20,$totpfCurrYear,$headerFormat);
		$worksheet->write($ctr,21,$tottotCY,$headerFormat);
		
					
$workbook->close();
?>
