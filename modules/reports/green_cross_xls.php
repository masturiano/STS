<?
################### INCLUDE FILE #################
	session_start();
	ini_set('include_path','C:\wamp\php\PEAR');
	include("../../includes/db.inc.php");
	include("../../includes/common.php");
	include("green_cross_obj.php");
	require_once 'Spreadsheet/Excel/Writer.php';
	
	$greenCrossObj = new greenCrossObj();
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
    
	$filename = "green_cross_summary.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("green_cross_summary");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(5,0));
	
	$worksheet->write(0,0,"GREEN CROSS SUMMARY REPORT YEAR ".$_GET['txtDateYear'],$headerFormat);
	for($i=1;$i<=2;$i++) {
		$worksheet->write(0, $i, "",$headerFormat);	
	}
	
    $ctr=3;
        
    $worksheet->setColumn(0,0,13);
    $worksheet->setColumn(0,1,20);
    $worksheet->setColumn(0,2,20);
    $worksheet->setColumn(0,3,25);
    $worksheet->setColumn(0,4,20);
    $worksheet->setColumn(0,5,13);
    $worksheet->setColumn(0,5,20);
    $worksheet->setColumn(0,6,20);
    $worksheet->setColumn(0,7,20);  
    $worksheet->setColumn(0,7,10);  
    
    $assStoreDet = $greenCrossObj->findStoreDetails($_GET['cmbStore']);
    
    if($_GET['cmbStore'] == 0){
        $display_store = "ALL";    
    }
    else{
        $display_store = $_GET['cmbStore']." - ".$assStoreDet['BRNDESC'];    
    }
    
    $worksheet->write(3,0,"Store",$headerFormat);
    $worksheet->setMerge(3, 1, 3, 2);
    $worksheet->write(3,1,$display_store,$headerFormat);
    $worksheet->write(4,0,"MONTH",$headerFormat);
    $worksheet->write(4,1,"RCR AMOUNT",$headerFormat); 
    $worksheet->write(4,2,"EX-VAT",$headerFormat); 
    $worksheet->write(4,3,"GROSS BEFORE DISCOUNT",$headerFormat); 
    $worksheet->write(4,4,"4% DISCOUNT",$headerFormat); 
    $worksheet->write(4,5,"RATE IN %",$headerFormat); 
    $worksheet->write(4,6,"B2B/EDI",$headerFormat); 
    $worksheet->write(4,7,"TO CHECK",$headerFormat); 
    $worksheet->write(4,8,"VAR",$headerFormat); 
                                                                   
    $totExpAmt = $flag = 0;
    
    $arrSummary = $greenCrossObj->getSummary($_GET['txtDateYear'],$_GET['cmbStore']);
    
    $row_counter = 5;
       
    foreach($arrSummary as $row){
        $row_left = ($col==0) ? $detail_left_color_blue:$detail_left_color_white;
        $row_center = ($col==0) ? $detail_center_color_blue:$detail_center_color_white;
        $row_right = ($col==0) ? $detail_right_color_blue:$detail_right_color_white;
        $row_right_number = ($col==0) ? $detail_right_color_blue_number:$detail_right_color_white_number;
        $col = ($col==0) ? 1:0;
    
        $worksheet->write($row_counter,0,$row['MONTH_NAME'],$row_left);
        $worksheet->write($row_counter,1,$row['SUM_RCR_AMOUNT'],$row_right_number);
        $worksheet->write($row_counter,2,$row['EX_VAT'],$row_right_number);
        $worksheet->write($row_counter,3,$row['GROSS_BEF_DISC'],$row_right_number);
        $worksheet->write($row_counter,4,$row['FOUR_PERC_DISC'],$row_right_number);
        $worksheet->write($row_counter,5,$row['RATE_IN'],$row_right_number);
        $worksheet->write($row_counter,6,$row['EDI'],$row_right);
        $worksheet->write($row_counter,7,$row['TO_CHECK'],$row_right_number);
        $worksheet->write($row_counter,8,$row['VAR'],$row_right_number);
        
        $total_rcr_amount += $row['SUM_RCR_AMOUNT'];
        $total_ex_vat += $row['EX_VAT'];
        $total_gross_bef_disc += $row['GROSS_BEF_DISC'];
        $total_four_perc_disc += $row['FOUR_PERC_DISC'];
        $total_rate_in += $row['RATE_IN'];
        $total_edi += $row['EDI'];
        $total_to_check += $row['TO_CHECK'];
        $total_var += $row['VAR'];
        
        $row_counter++;
    }
    
    $worksheet->write($row_counter,0,"TOTAL",$headerFormat);
    $worksheet->write($row_counter,1,number_format($total_rcr_amount,2),$headerFormat3);
    $worksheet->write($row_counter,2,number_format($total_ex_vat,2),$headerFormat3);
    $worksheet->write($row_counter,3,number_format($total_gross_bef_disc,2),$headerFormat3);
    $worksheet->write($row_counter,4,number_format($total_four_perc_disc,2),$headerFormat3);
    $worksheet->write($row_counter,5,number_format($total_rate_in,2),$headerFormat3);
    $worksheet->write($row_counter,6,$total_edi,$headerFormat3);
    $worksheet->write($row_counter,7,number_format($total_to_check,2),$headerFormat3);
    $worksheet->write($row_counter,8,number_format($total_var,2),$headerFormat3);
                            
$workbook->close();
?>