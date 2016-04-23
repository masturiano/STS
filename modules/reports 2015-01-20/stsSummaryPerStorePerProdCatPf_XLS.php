<?
################### INCLUDE FILE #################
	session_start();
	ini_set('include_path','D:\wamp\php\PEAR');
	ini_set("memory_limit","1g");
	include("../../includes/db.inc.php");
	include("../../includes/common.php");
	include("stsSummaryPerStorePerProdCatObj.php");
	require_once 'Spreadsheet/Excel/Writer.php';
	
	$stsSummaryPerStorePerProdCatObj = new stsSummaryPerStorePerProdCatObj();
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

	$detail2   = $workbook->addFormat(array('Size' => 10,
										  'border' =>1,
										  'Pattern' => 1,
										  'Align' => 'right'));
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
	
	$filename = "STS_summary_per_store.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("transaction_details");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(4,0));
	
	$type = array(1=>'STS',2=>'DA');

	$worksheet->setMerge(0, 0, 0, 3);
	$worksheet->write(0,0,$type[$_GET['cmbType']]."Promo Fund Summary per Store per Product Category From ".date('m/d/Y',strtotime($_GET['txtDateFrom']))." to ".date('m/d/Y',strtotime($_GET['txtDateTo'])),$headerFormat);
	for($i=1;$i<=3;$i++) 
	{
		$worksheet->write(0, $i, "",$headerFormat);	
	}
	$worksheet->setColumn(0,0,20);
	$worksheet->setColumn(0,1,45);
	$worksheet->setColumn(0,2,25);
	$worksheet->setColumn(0,3,30);
	$worksheet->setColumn(0,4,20);
	$worksheet->setColumn(0,5,20);
	$worksheet->setColumn(0,6,20);
	$worksheet->setColumn(0,7,20);
	$worksheet->setColumn(0,8,20);
	$worksheet->setColumn(0,9,20);
	$worksheet->setColumn(0,10,20);
	
		//$arrCountMonth = $stsSummaryPerSuppPerProdCatObj ->getCountMonth($_GET['txtDateFrom'],$_GET['txtDateTo']);
		
		$lastYear = substr($_GET['txtDateFrom'],-4) - 1;
		
		$worksheet->setMerge(2, 2, 2, 5);
		$worksheet->write(2,2,$lastYear,$headerFormat2);
		$worksheet->write(2,3,'',$headerFormat2);
		$worksheet->write(2,4,'',$headerFormat2);
		$worksheet->write(2,5,'',$headerFormat2);

		
		$currYear = substr($_GET['txtDateFrom'],-4);

		$worksheet->setMerge(2, 6, 2, 9);
		$worksheet->write(2,6,$currYear,$headerFormat2);
		$worksheet->write(2,7,'',$headerFormat2);
		$worksheet->write(2,8,'',$headerFormat2);
		$worksheet->write(2,9,'',$headerFormat2);
		
		//$worksheet->write(3,0,"Supplier Code",$headerFormat);
		//$worksheet->write(3,1,"Supplier Name",$headerFormat);
		//$existGroup = $stsSummaryPerSuppPerProdCatObj->getExistGroup();
		//$ctrH = 1;
		//foreach ($existGroup as $valGroupHL) {
		//	$ctrH++;
		//	$worksheet->write(3,$ctrH,$valGroupHL['grpDesc'],$headerFormat);
		//}
		//$ctrH++;
		//$worksheet->write(3,$ctrH,"TOTAL $lastYear ",$headerFormat);
		//foreach ($existGroup as $valGroupHC) {
		//	$ctrH++;
		//	$worksheet->write(3,$ctrH,$valGroupHC['grpDesc'],$headerFormat);
		//}
		//$ctrH++;
		//$worksheet->write(3,$ctrH,"TOTAL $currYear ",$headerFormat);
		
		//$colHeader = 1;
		
		$worksheet->write(3,0,"Store Code",$headerFormat);
		$worksheet->write(3,1,"Store Name",$headerFormat);
		$worksheet->write(3,2,"Department Description",$headerFormat);
		$worksheet->write(3,3,"Type Description",$headerFormat);
		$worksheet->write(3,4,"Ap Amount Last Year",$headerFormat);
		$worksheet->write(3,5,"Ar Amount Last Year",$headerFormat);
		$worksheet->write(3,6,"Total Amount Last Year",$headerFormat);
		$worksheet->write(3,7,"Ap Amount Cur Year",$headerFormat);
		$worksheet->write(3,8,"Ar Amount Cur Year",$headerFormat);
		$worksheet->write(3,9,"Total Amount Cur Year",$headerFormat);
		
		
		$ctr = 3 ;
		
		$row = ($col==0) ? $detail2:$detail;
		$row2 = ($col==0) ? $Dept2:$Dept;
		$row3 = ($col==0) ? $Deptc2:$Deptc;
		$row4 = $Deptc3;
		$col = ($col==0) ? 1:0;
		
		$arrDet = $stsSummaryPerStorePerProdCatObj ->pfSummaryPerStorePerProdCat($_GET['txtDateFrom'],$_GET['txtDateTo'],$_GET['cmbType']);
		
		//$NumToMonth = array('1'=>'Jan','2'=>'Feb','3'=>'Mar','4'=>'Apr','5'=>'May','6'=>'Jun','7'=>'Jul','8'=>'Aug','9'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
		
		foreach ($arrDet as $valDet) {
		
			$ctr++;
			
			$worksheet->write($ctr,0,$valDet['strCode'],$detail );
			$strName = $stsSummaryPerStorePerProdCatObj ->getStoreName($valDet['strCode']);
			$worksheet->write($ctr,1,$strName['brnDesc'],$detail3);
			//$worksheet->write($ctr,2,$valDet['minCode'],$detail3);
			$worksheet->write($ctr,2,$valDet['deptDesc'],$detail3);
			//$worksheet->write($ctr,4,$valDet['typeCode'],$detail3);
			$worksheet->write($ctr,3,$valDet['typeDesc'],$detail3);
			
			$worksheet->write($ctr,4,$valDet['apStsApplyAmtLy'],$detail2);
			$worksheet->write($ctr,5,$valDet['arStsApplyAmtLy'],$detail2);
			$totApArLy = $valDet['apStsApplyAmtLy'] + $valDet['arStsApplyAmtLy'];
			$worksheet->write($ctr,6,$totApArLy,$detail2);
			
			$worksheet->write($ctr,7,$valDet['apStsApplyAmtCy'],$detail2);
			$worksheet->write($ctr,8,$valDet['arStsApplyAmtCy'],$detail2);
			$totApArCy = $valDet['apStsApplyAmtCy'] + $valDet['arStsApplyAmtCy'];
			$worksheet->write($ctr,9,$totApArCy,$detail2);
			
			$grandTotApLy += $valDet['apStsApplyAmtLy'];
			$grandTotArLy += $valDet['arStsApplyAmtLy'];
			
			$grandTotApCy += $valDet['apStsApplyAmtCy'];
			$grandTotArCy += $valDet['arStsApplyAmtCy'];
	
		}
		$ctr++;
		
		$worksheet->setMerge($ctr, 0, $ctr, 3);
		$worksheet->write($ctr,0,'TOTAL',$headerFormat);
		$worksheet->write($ctr,1,'',$headerFormat);
		$worksheet->write($ctr,2,'',$headerFormat);
		$worksheet->write($ctr,3,'',$headerFormat);
		$worksheet->write($ctr,4,$grandTotApLy,$headerFormat);
		$worksheet->write($ctr,5,$grandTotArLy,$headerFormat);
		$grandTotApArLy += $grandTotApLy + $grandTotArLy;
		$worksheet->write($ctr,6,$grandTotApArLy,$headerFormat);
		$worksheet->write($ctr,7,$grandTotApCy,$headerFormat);
		$worksheet->write($ctr,8,$grandTotArCy,$headerFormat);
		$grandTotApArCy += $grandTotApCy + $grandTotArCy;
		$worksheet->write($ctr,9,$grandTotApArCy,$headerFormat);

$workbook->close();
?>
