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
	$filename = "NE AR For posting.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("Ar");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(3,0));

	$worksheet->write(0,0,"NE AR For posting",$headerFormat);
	for($i=1;$i<=7;$i++) {
		if($i!=2 || $i!=3){
			$worksheet->write(0, $i, "",$headerFormat);
		}
	}

	$worksheet->setColumn(0,0,20);
	$worksheet->setColumn(0,1,20);
	$worksheet->setColumn(0,2,20);
	$worksheet->setColumn(0,3,20);
	$worksheet->setColumn(0,4,20);
	$worksheet->setColumn(0,5,20);
    $worksheet->setColumn(0,6,20);
    $worksheet->setColumn(0,7,20);
	$worksheet->setColumn(0,8,20);
	
	$worksheet->write(2,0,"BTBTCH",$headerFormat);
	$worksheet->write(2,1,"AROINV",$headerFormat);
	$worksheet->write(2,2,"OPNREF",$headerFormat);
	$worksheet->write(2,3,"CUSNUM",$headerFormat);
	$worksheet->write(2,4,"ASNAME",$headerFormat);
	$worksheet->write(2,5,"OPNIVD",$headerFormat);
	$worksheet->write(2,6,"OPNAMT",$headerFormat);
	$worksheet->write(2,7,"OPNSTR",$headerFormat);
	
		$totFund = $totUpload = $totQueue = 0;
		$ctr = 3 ;
		$arrTran = $reportsObj->uploadedArNeTransmittal();

			foreach($arrTran as $valD){
			$row = ($col==0) ? $detail2:$detail;
			$row2 = ($col==0) ? $Dept2:$Dept;
			$row3 = ($col==0) ? $Date2:$Date;
			$col = ($col==0) ? 1:0;
            
				$ctr++;
                $worksheet->write($ctr,0,$valD['BTBTCH'],$row);
                $worksheet->write($ctr,1,$valD['AROINV'],$row);
                $worksheet->write($ctr,2,$valD['OPNREF'],$row);
                $worksheet->write($ctr,3,$valD['CUSNUM'],$row);
                $worksheet->write($ctr,4,$valD['ASNAME'],$row);
                $worksheet->write($ctr,5,date('Y-m-d',strtotime($valD['OPNIVD'])),$row);
                $worksheet->write($ctr,6,number_format($valD['OPNAMT'],2),$row2);
                $worksheet->write($ctr,7,$valD['OPNSTR'],$row);
				
				//$totAmt += $valD['OPNAMT'];
			}
			//$ctr++;
			//$worksheet->write($ctr,5,"Grand Total",$headerFormat);
			//$worksheet->write($ctr,6,number_format($totAmt,2),$headerFormat);
				
$workbook->close();
?>