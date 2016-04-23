<?
################### INCLUDE FILE #################
	session_start();
	ini_set('include_path','C:\wamp\php\PEAR');
	include("../../includes/db.inc.php");
	include("../../includes/common.php");
	include("efdObj.php");
	require_once 'Spreadsheet/Excel/Writer.php';
	
	$efdObj = new efdObj();
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
    
	#DETAIL ALIGN LEFT COLOR WHITE 
    $detail_left_color_white   = $workbook->addFormat(array('Size' => 10,
                                          'fgColor' => 'white',
                                          'Pattern' => 1,
                                          'border' =>1,
                                          'Align' => 'left'));
    $detail_left_color_white->setFontFamily('Calibri'); 
    #DETAIL ALIGN LEFT COLOR BLUE 
    $detail_left_color_blue   = $workbook->addFormat(array('Size' => 10,
                                          'border' =>1,
                                          'Pattern' => 1,
                                          'Align' => 'left'));
    $detail_left_color_blue->setFgColor(12); 
    $detail_left_color_blue->setFontFamily('Calibri');
    
    #DETAIL ALIGN CENTER COLOR WHITE 
    $detail_center_color_white   = $workbook->addFormat(array('Size' => 10,
                                          'fgColor' => 'white',
                                          'Pattern' => 1,
                                          'border' =>1,
                                          'Align' => 'center'));
    $detail_center_color_white->setFontFamily('Calibri'); 
    #DETAIL ALIGN CENTER COLOR BLUE 
    $detail_center_color_blue   = $workbook->addFormat(array('Size' => 10,
                                          'border' =>1,
                                          'Pattern' => 1,
                                          'Align' => 'center'));
    $detail_center_color_blue->setFgColor(12); 
    $detail_center_color_blue->setFontFamily('Calibri');
    
    #DETAIL ALIGN RIGHT COLOR WHITE 
    $detail_right_color_white   = $workbook->addFormat(array('Size' => 10,
                                          'fgColor' => 'white',
                                          'Pattern' => 1,
                                          'border' =>1,
                                          'Align' => 'right'));
    $detail_right_color_white->setFontFamily('Calibri'); 
    #DETAIL ALIGN RIGHT COLOR BLUE 
    $detail_right_color_blue   = $workbook->addFormat(array('Size' => 10,
                                          'border' =>1,
                                          'Pattern' => 1,
                                          'Align' => 'right'));
    $detail_right_color_blue->setFgColor(12); 
    $detail_right_color_blue->setFontFamily('Calibri');
    
    #DETAIL ALIGN RIGHT COLOR WHITE WITH NUMBER FORMAT 
    $detail_right_color_white_number   = $workbook->addFormat(array('Size' => 10,
                                          'fgColor' => 'white',
                                          'Pattern' => 1,
                                          'border' =>1,
                                          'Align' => 'right'));
    $detail_right_color_white_number->setFontFamily('Calibri'); 
    $detail_right_color_white_number->setNumFormat('#,##0.00');
    #DETAIL ALIGN RIGHT COLOR BLUE WITH NUMBER FORMAT
    $detail_right_color_blue_number   = $workbook->addFormat(array('Size' => 10,
                                          'border' =>1,
                                          'Pattern' => 1,
                                          'Align' => 'right'));
    $detail_right_color_blue_number->setFgColor(12); 
    $detail_right_color_blue_number->setFontFamily('Calibri');
    $detail_right_color_blue_number->setNumFormat('#,##0.00');
    
	$filename = "transaction_summary.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("transaction_summary");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(5,0));
	
	$worksheet->write(0,0,"EFD SUMMARY REPORT DATE FROM ".date('m/01/Y',strtotime($_GET['txtDateFrom']))." TO ".date('m/t/Y',strtotime($_GET['txtDateTo'])),$headerFormat);
	for($i=1;$i<=1;$i++) {
		$worksheet->write(0, $i, "",$headerFormat);	
	}
	
    $ctr=3;
        
    $worksheet->setColumn(0,0,20);
    $worksheet->setColumn(0,1,40);
    $worksheet->setColumn(0,2,20);
    $worksheet->setColumn(0,3,40);
    $worksheet->setColumn(0,4,20);
    $worksheet->setColumn(0,5,30);
    $worksheet->setColumn(0,5,30);
    $worksheet->setColumn(0,6,30);
    $worksheet->setColumn(0,7,50);  
    
    $assStoreDet = $efdObj->findStoreDetails($_GET['cmbStore']);
    $assSupplerDet = $efdObj->getSupplierDetails($_GET['cmbSupp']); 
    
    if($_GET['cmbStore'] == 0){
        $display_store = "ALL";    
    }
    else{
        $display_store = $_GET['cmbStore']." - ".$assStoreDet['BRNDESC'];    
    }
    
    if($_GET['cmbSupp'] == 0){
        $display_supplier = "ALL";    
    }
    else{
        $display_supplier = $_GET['cmbSupp']." - ".$assSupplerDet['suppName'];        
    }
    
                                           
    $worksheet->write(2,0,"Store",$headerFormat);
    $worksheet->write(2,1,$display_store,$headerFormat);  
    $worksheet->write(3,0,"Supplier",$headerFormat);
    $worksheet->write(3,1,$display_supplier,$headerFormat);
    $worksheet->write(4,0,"TYPE",$headerFormat);
    $worksheet->write(4,1,"TOTAL AMOUNT",$headerFormat); 
                                                                   
    $totExpAmt = $flag = 0;
    
    $row_left = ($col==0) ? $detail_left_color_blue:$detail_left_color_white;
    $row_center = ($col==0) ? $detail_center_color_blue:$detail_center_color_white;
    $row_right = ($col==0) ? $detail_right_color_blue:$detail_right_color_white;
    $row_right_number = ($col==0) ? $detail_right_color_blue_number:$detail_right_color_white_number;
    $col = ($col==0) ? 1:0;
                                     
    $assACC = $efdObj->getEfdSummaryACC($_GET);     
    $assACE = $efdObj->getEfdSummaryACE($_GET);     
    $assCHA = $efdObj->getEfdSummaryCHA($_GET);     
    $assECO = $efdObj->getEfdSummaryECO($_GET);     
    $assEDI = $efdObj->getEfdSummaryEDI($_GET);     
    $assSTS = $efdObj->getStsSummary($_GET);     
    $assORA = $efdObj->getOraSummary($_GET);     
    
    $worksheet->write(5,0,"ACC",$row_left);
    $worksheet->write(5,1,number_format($assACC['efdAmount'],2),$row_right_number);
    $worksheet->write(6,0,"ACE",$row_left);
    $worksheet->write(6,1,number_format($assACE['efdAmount'],2),$row_right_number);
    $worksheet->write(7,0,"CHA",$row_left);
    $worksheet->write(7,1,number_format($assCHA['efdAmount'],2),$row_right_number);
    $worksheet->write(8,0,"ECO",$row_left);
    $worksheet->write(8,1,number_format($assECO['efdAmount'],2),$row_right_number);
    $worksheet->write(9,0,"EDI",$row_left);
    $worksheet->write(9,1,number_format($assEDI['efdAmount'],2),$row_right_number);
    $worksheet->write(10,0,"STS",$row_left);
    $worksheet->write(10,1,number_format($assSTS['stsAmt'],2),$row_right_number);
    $worksheet->write(11,0,"ORA",$row_left);
    $worksheet->write(11,1,number_format($assORA['INVOICE_AMOUNT'],2),$row_right_number);
		
$workbook->close();
?>