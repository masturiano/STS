<?
################### INCLUDE FILE #################
	session_start();
	ini_set('include_path','C:\wamp\php\PEAR');
	ini_set("memory_limit","1g");
	include("../../includes/db.inc.php");
	include("../../includes/common.php");
	include("wtc2015Obj.php");
	require_once 'Spreadsheet/Excel/Writer.php';
	
	$wtc = new wtcObj();
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
	
	$filename = "WTC_2015.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("wtc2015");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(4,0));

	$worksheet->write(0,0,"WTC 2015",$headerFormat);
	for($i=1;$i<=7;$i++) 
	{
		$worksheet->write(0, $i, "",$headerFormat);	
	}
	$worksheet->setColumn(0,0,12);
	$worksheet->setColumn(1,1,35);
	$worksheet->setColumn(2,2,10);
	$worksheet->setColumn(3,3,15);
	$worksheet->setColumn(4,4,12);
	$worksheet->setColumn(5,5,31);
	$worksheet->setColumn(6,6,12);
	$worksheet->setColumn(7,7,12);        
		
		$worksheet->write(3,0,"VENDOR NO",$headerFormat);
		$worksheet->write(3,1,"VENDOR",$headerFormat);
		$worksheet->write(3,2,"CATEGORY",$headerFormat);
		$worksheet->write(3,3,"ACTIVITY",$headerFormat);
		$worksheet->write(3,4,"STORE CODE",$headerFormat);
		$worksheet->write(3,5,"STORE NAME",$headerFormat);
		$worksheet->write(3,6,"NET REBATES",$headerFormat);
		$worksheet->write(3,7,"INVOICE NO",$headerFormat);
		
		# GET DETAIL DATA FROM DATABASE
		$arrDet = $wtc->wtcDtl();
        #  START OF COUNTER
        $ctr = 3;
        
		foreach ($arrDet as $valDet) {
            
            # COUNTER
			$ctr++;
            
            $row_left = ($col==0) ? $detail_left_color_blue:$detail_left_color_white;
            $row_center = ($col==0) ? $detail_center_color_blue:$detail_center_color_white;
            $row_right = ($col==0) ? $detail_right_color_blue:$detail_right_color_white;
            $row_right_number = ($col==0) ? $detail_right_color_blue_number:$detail_right_color_white_number;
            $col = ($col==0) ? 1:0;
            
			$worksheet->write($ctr,0,$valDet['VENDOR_NO'],$row_left);
			$worksheet->write($ctr,1,$valDet['VENDOR'],$row_left);
			$worksheet->write($ctr,2,$valDet['CATEGORY'],$row_left);
			$worksheet->write($ctr,3,$valDet['ACTIVITY'],$row_left);
			$worksheet->write($ctr,4,$valDet['STORE_CODE'],$row_center);
			$worksheet->write($ctr,5,$valDet['STORE_NAME'],$row_left);
			$worksheet->write($ctr,6,$valDet['NET_REBATES'],$row_right_number);
			$worksheet->write($ctr,7,$valDet['INVOICE_NO'],$row_left);
			
			$totNetRebates += $valDet['NET_REBATES'];
		}
		$ctr++;
		$worksheet->write($ctr,5,"Total",$headerFormat);
		$worksheet->write($ctr,6,$totNetRebates,$headerFormat);
		
					
$workbook->close();
?>
