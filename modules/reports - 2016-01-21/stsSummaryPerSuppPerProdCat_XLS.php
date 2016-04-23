<?
################### INCLUDE FILE #################
	session_start();
	ini_set('include_path','C:\wamp\php\PEAR');
	ini_set("memory_limit","1g");
	include("../../includes/db.inc.php");
	include("../../includes/common.php");
	include("stsSummaryPerSuppPerProdCatObj.php");
	require_once 'Spreadsheet/Excel/Writer.php';
	
	$stsSummaryPerSuppPerProdCatObj = new stsSummaryPerSuppPerProdCatObj();
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
										  //'fgColor' => 'white',
										  'Pattern' => 1,
										  'border' =>1,
										  'Align' => 'left'));
	$detail->setFgColor(12); 
	$detail->setFontFamily('Calibri'); 
	//$detail->setNumFormat('#,##0.00');	

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
	$detail3->setNumFormat('#,##0.00');	
	
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
	
	$filename = "STS_summary_per_supplier.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("transaction_details");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(4,0));
	
	$type = array(1=>'STS',2=>'DA');

	$worksheet->write(0,0,$type[$_GET['cmbType']]." Summary per Supp per Product Category From ".date('m/d/Y',strtotime($_GET['txtDateFrom']))." to ".date('m/d/Y',strtotime($_GET['txtDateTo'])),$headerFormat);
	for($i=1;$i<=1;$i++) 
	{
		$worksheet->write(0, $i, "",$headerFormat);	
	}
	$worksheet->setColumn(0,0,20);
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
	$worksheet->setColumn(0,31,15);
	$worksheet->setColumn(0,32,15);
	$worksheet->setColumn(0,33,15);
	$worksheet->setColumn(0,34,15);
	$worksheet->setColumn(0,35,15);
	$worksheet->setColumn(0,36,15);
	$worksheet->setColumn(0,37,15);
	$worksheet->setColumn(0,38,15);
	$worksheet->setColumn(0,39,15);
	$worksheet->setColumn(0,40,15);
	
		$arrCountMonth = $stsSummaryPerSuppPerProdCatObj ->getCountMonth($_GET['txtDateFrom'],$_GET['txtDateTo']);
		
		$lastYear = substr($_GET['txtDateFrom'],-4) - 1;
		
		$worksheet->setMerge(2, 2, 2, 12);
		$worksheet->write(2,2,$lastYear,$headerFormat2);
		
		$currYear = substr($_GET['txtDateFrom'],-4);

		$worksheet->setMerge(2, 13, 2, 22);
		$worksheet->write(2,12,$currYear,$headerFormat2);
		
		$worksheet->write(3,0,"Supplier Code",$headerFormat);
		$worksheet->write(3,1,"Supplier Name",$headerFormat);
		$existGroup = $stsSummaryPerSuppPerProdCatObj->getExistGroup();
		$ctrH = 1;
		foreach ($existGroup as $valGroupHL) {
			$ctrH++;
			$worksheet->write(3,$ctrH,$valGroupHL['grpDesc'],$headerFormat);
		}
		$ctrH++;
		$worksheet->write(3,$ctrH,"TOTAL $lastYear ",$headerFormat);
		foreach ($existGroup as $valGroupHC) {
			$ctrH++;
			$worksheet->write(3,$ctrH,$valGroupHC['grpDesc'],$headerFormat);
		}
		$ctrH++;
		$worksheet->write(3,$ctrH,"TOTAL $currYear ",$headerFormat);
		
		$colHeader = 1;
		
		$ctr = 3 ;
		
		$row = ($col==0) ? $detail2:$detail;
		$row2 = ($col==0) ? $Dept2:$Dept;
		$row3 = ($col==0) ? $Deptc2:$Deptc;
		$row4 = $Deptc3;
		$col = ($col==0) ? 1:0;
		
		$arrDet = $stsSummaryPerSuppPerProdCatObj ->stsSummaryPerSuppPerProdCat($_GET['txtDateFrom'],$_GET['txtDateTo'],$_GET['cmbType']);
		
		$NumToMonth = array('1'=>'Jan','2'=>'Feb','3'=>'Mar','4'=>'Apr','5'=>'May','6'=>'Jun','7'=>'Jul','8'=>'Aug','9'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
		
		foreach ($arrDet as $valDet) {
		
			$ctr++;
			
			$worksheet->write($ctr,0,$valDet['asnum'],$detail);
			$worksheet->write($ctr,1,$valDet['asname'],$detail3);
			
			$ctrG = 1;
			
			$totStsLY = 0;
			$totStsCY = 0;

			foreach ($existGroup as $valGroupL) {
				$valGroupL = str_replace(' ','',$valGroupL);
				$ctrG++;
				$worksheet->write($ctr,$ctrG,$valDet[$valGroupL['grpDesc'].'StsLY'],$detail3);
				$totStsLY += $valDet[$valGroupL['grpDesc'].'StsLY'];
			}
			$distotStsLY = $totStsLY ;
			$ctrG++;
			$worksheet->write($ctr,$ctrG,$distotStsLY,$detail3);
			
			foreach ($existGroup as $valGroupC) {
				$valGroupC = str_replace(' ','',$valGroupC);
				$ctrG++;
				$worksheet->write($ctr,$ctrG,$valDet[$valGroupC['grpDesc'].'StsCY'],$detail3);
				$totStsCY += $valDet[$valGroupC['grpDesc'].'StsCY'];
			}
			$distotStsCY = $totStsCY ;
			$ctrG++;
			$worksheet->write($ctr,$ctrG,$distotStsCY,$detail3);
			
			//$worksheet->write($ctr,20,$valDet['pfCurrYear'],$row);
			//$totCY =  $valDet['stsCurrYear'] + $valDet['daCurrYear'] + $valDet['pfCurrYear'];
			//$worksheet->write($ctr,21,$totCY ,$row);
			
			
		}
		$ctr++;
		
		//$worksheet->write($ctr,0,'TOTAL',$headerFormat);
		//$worksheet->write($ctr,1,'',$headerFormat);
		//$worksheet->write($ctr,2,$totApStsLastYear,$headerFormat);

					
$workbook->close();
?>
