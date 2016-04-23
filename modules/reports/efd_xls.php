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
	$worksheet->freezePanes(array(3,0));
    
    if($_GET['cmbGroup'] == '7'){$col_count = 12;}
    else if($_GET['cmbGroup'] == '8'){$col_count = 13;}
    else{$col_count = 12;}
	
	$worksheet->write(0,0,"EFD DETAILED REPORT DATE FROM ".date('m/01/Y',strtotime($_GET['txtDateFrom']))." TO ".date('m/t/Y',strtotime($_GET['txtDateTo'])),$headerFormat);
	for($i=1;$i<=$col_count;$i++) {
		$worksheet->write(0, $i, "",$headerFormat);	
	}
	
    $ctr=3;
	
    if($_GET['cmbGroup'] == '7'){
        
        $worksheet->setColumn(0,0,20);
        $worksheet->setColumn(0,1,20);
        $worksheet->setColumn(0,2,20);
        $worksheet->setColumn(0,3,40);
        $worksheet->setColumn(0,4,20);
        $worksheet->setColumn(0,5,40);  
        $worksheet->setColumn(0,6,20);
        $worksheet->setColumn(0,7,20);
        $worksheet->setColumn(0,8,20);
        $worksheet->setColumn(0,9,20);
        $worksheet->setColumn(0,10,20);
        $worksheet->setColumn(0,11,20);
        $worksheet->setColumn(0,12,50);
        
        $arrtTran = $efdObj->getStsDetail($_GET);  
        
        $worksheet->write(2,0,"Sts Ref No",$headerFormat);
        $worksheet->write(2,1,"Sts No",$headerFormat);
        $worksheet->write(2,2,"Supplier No",$headerFormat);
        $worksheet->write(2,3,"Supplier Name",$headerFormat);
        $worksheet->write(2,4,"Store Code",$headerFormat);
        $worksheet->write(2,5,"Store Name",$headerFormat);    
        $worksheet->write(2,6,"Amount",$headerFormat);
        $worksheet->write(2,7,"Vat Amount",$headerFormat); 
        $worksheet->write(2,8,"Apply Date",$headerFormat);
        $worksheet->write(2,9,"Approved date",$headerFormat);
        $worksheet->write(2,10,"Payment Mode",$headerFormat); 
        $worksheet->write(2,11,"Type Desc",$headerFormat); 
        $worksheet->write(2,12,"Sts Remarks",$headerFormat); 
        
        foreach($arrtTran as $val){
        
            $assStoreDet = $efdObj->findStoreDetails($val['strCode']);
            $assSupplerDet = $efdObj->getSupplierDetails($val['suppCode']);

            $totExpAmt = $flag = 0;
            
            $row_left = ($col==0) ? $detail_left_color_blue:$detail_left_color_white;
            $row_center = ($col==0) ? $detail_center_color_blue:$detail_center_color_white;
            $row_right = ($col==0) ? $detail_right_color_blue:$detail_right_color_white;
            $row_right_number = ($col==0) ? $detail_right_color_blue_number:$detail_right_color_white_number;
            $col = ($col==0) ? 1:0;
        
            $worksheet->write($ctr,0,$val['stsRefno'],$row_left);
            $worksheet->write($ctr,1,$val['stsNo']."-".$val['nbrApplication'],$row_left); 
            $worksheet->write($ctr,2,$val['suppCode'],$row_left);
            $worksheet->write($ctr,3,$val['asname'],$row_left);
            $worksheet->write($ctr,4,$val['strCode'],$row_center);
            $worksheet->write($ctr,5,$val['brnDesc'],$row_left);   
            $worksheet->write($ctr,6,$val['stsAmt'],$row_right_number);
            $worksheet->write($ctr,7,$val['stsVatAmt'],$row_right_number);
            $worksheet->write($ctr,8,date('m/d/Y',strtotime($val['applyDate'])),$row_center);
            $worksheet->write($ctr,9,date('m/d/Y',strtotime($val['dateApproved'])),$row_center);
            $worksheet->write($ctr,10,$val['stsPaymentMode'],$row_left);
            $worksheet->write($ctr,11,substr($val['dept'],0,4)."-".substr($val['cls'],0,4)."-".substr($val['subCls'],0,4),$row_left); 
            $worksheet->write($ctr,12,$val['stsRemarks'],$row_left);
            
            $totalAmount += $val['stsAmt']; 
            $totalVatAmount += $val['stsVatAmt'];  
            
            $ctr++;
        }  
        
        $worksheet->write($ctr,5,'TOTAL',$headerFormat);
        $worksheet->write($ctr,6,number_format($totalAmount,2),$headerFormat);
        $worksheet->write($ctr,7,number_format($totalVatAmount,2),$headerFormat);
        
        $ctr++;
    }
    else if($_GET['cmbGroup'] == '8'){
        
        $worksheet->setColumn(0,0,15);
        $worksheet->setColumn(0,1,15);
        $worksheet->setColumn(0,2,30);
        $worksheet->setColumn(0,3,10);
        $worksheet->setColumn(0,4,10);
        $worksheet->setColumn(0,5,40);
        $worksheet->setColumn(0,6,15);
        $worksheet->setColumn(0,7,15);
        $worksheet->setColumn(0,8,20);
        $worksheet->setColumn(0,9,15);
        $worksheet->setColumn(0,10,20);
        $worksheet->setColumn(0,11,20);
        $worksheet->setColumn(0,12,20);
        $worksheet->setColumn(0,13,40);
    
        $arrtTran = $efdObj->getOraDetail($_GET);  
        
        $worksheet->write(2,0,"Invoice Num",$headerFormat);
        $worksheet->write(2,1,"Supplier No",$headerFormat);
        $worksheet->write(2,2,"Supplier Name",$headerFormat);
        $worksheet->write(2,3,"Org Id",$headerFormat);
        $worksheet->write(2,4,"Store Code",$headerFormat);
        $worksheet->write(2,5,"Store Name",$headerFormat);
        $worksheet->write(2,6,"Store Short",$headerFormat); 
        $worksheet->write(2,7,"Invoice Date",$headerFormat);
        $worksheet->write(2,8,"Invoice Amount",$headerFormat);
        $worksheet->write(2,9,"Source",$headerFormat);
        $worksheet->write(2,10,"Match Status Flag",$headerFormat);
        $worksheet->write(2,11,"GL Line Code",$headerFormat);  
        $worksheet->write(2,12,"Line Amount",$headerFormat);  
        $worksheet->write(2,13,"Description",$headerFormat);
        
        foreach($arrtTran as $val){
        
            $assStoreDet = $efdObj->findStoreDetails($val['strCode']);
            $assSupplierDet = $efdObj->getSupplierDetails($val['SEGMENT1']);

            $row_left = ($col==0) ? $detail_left_color_blue:$detail_left_color_white;
            $row_center = ($col==0) ? $detail_center_color_blue:$detail_center_color_white;
            $row_right = ($col==0) ? $detail_right_color_blue:$detail_right_color_white;
            $row_right_number = ($col==0) ? $detail_right_color_blue_number:$detail_right_color_white_number;
            $col = ($col==0) ? 1:0;
        
            $worksheet->write($ctr,0," ".$val['INVOICE_NUM'],$row_left);
            $worksheet->write($ctr,1,$val['SEGMENT1'],$row_left);
            $worksheet->write($ctr,2,$assSupplierDet['suppName'],$row_left);
            $worksheet->write($ctr,3,$val['ORG_ID'],$row_center);
            $worksheet->write($ctr,4,$val['strCode'],$row_center);  
            $worksheet->write($ctr,5,$assStoreDet['BRNDESC'],$row_left);
            $worksheet->write($ctr,6,$assStoreDet['BRNSHORTNAME'],$row_left);   
            $worksheet->write($ctr,7,date('m/d/Y',strtotime($val['INVOICE_DATE'])),$row_center);
            $worksheet->write($ctr,8,$val['INVOICE_AMOUNT'],$row_right_number);
            $worksheet->write($ctr,9,$val['SOURCE'],$row_left);   
            $worksheet->write($ctr,10,$val['MATCH_STATUS_FLAG'],$row_left);
            $worksheet->write($ctr,11,$val['GL_LINE_CODE'],$row_left);
            $worksheet->write($ctr,12,$val['LINE_AMOUNT'],$row_right_number);
            $worksheet->write($ctr,13,$val['DESCRIPTION'],$row_left);
            
            $totalAmount += $val['INVOICE_AMOUNT']; 
            
            $ctr++;
        }
        
        $worksheet->write($ctr,7,'TOTAL',$headerFormat);
        $worksheet->write($ctr,8,number_format($totalAmount,2),$headerFormat);
        
        $ctr++;    
    }
    else{
        
        $worksheet->setColumn(0,0,20);
        $worksheet->setColumn(0,1,20);
        $worksheet->setColumn(0,2,40);
        $worksheet->setColumn(0,3,20);
        $worksheet->setColumn(0,4,30);
        $worksheet->setColumn(0,5,20);
        $worksheet->setColumn(0,6,30);
        $worksheet->setColumn(0,7,30);
        $worksheet->setColumn(0,8,30);
        $worksheet->setColumn(0,9,20);
        $worksheet->setColumn(0,10,20);
        $worksheet->setColumn(0,11,20);
        $worksheet->setColumn(0,12,40);
    
        $arrtTran = $efdObj->getEfdDetail($_GET);  
        
        $worksheet->write(2,0,"Invoice No",$headerFormat);
        $worksheet->write(2,1,"Supplier No",$headerFormat);
        $worksheet->write(2,2,"Supplier Name",$headerFormat);
        $worksheet->write(2,3,"Store Code",$headerFormat);
        $worksheet->write(2,4,"Store Name",$headerFormat);
        $worksheet->write(2,5,"Store Short",$headerFormat); 
        $worksheet->write(2,6,"PO Number / Contract No",$headerFormat);
        $worksheet->write(2,7,"RCR No / AP Batch",$headerFormat);
        $worksheet->write(2,8,"RCR Date / Cutoff Date",$headerFormat);
        $worksheet->write(2,9,"RCR Amount / Payable Amount ",$headerFormat);
        $worksheet->write(2,10,"EFD Amount",$headerFormat);
        $worksheet->write(2,11,"EFD Rate",$headerFormat);
        $worksheet->write(2,12,"EFD Notes",$headerFormat);  
        
        foreach($arrtTran as $val){
        
            $assStoreDet = $efdObj->findStoreDetails($val['strCode']);
            $assSupplerDet = $efdObj->getSupplierDetails($val['suppCode']);

            $row_left = ($col==0) ? $detail_left_color_blue:$detail_left_color_white;
            $row_center = ($col==0) ? $detail_center_color_blue:$detail_center_color_white;
            $row_right = ($col==0) ? $detail_right_color_blue:$detail_right_color_white;
            $row_right_number = ($col==0) ? $detail_right_color_blue_number:$detail_right_color_white_number;
            $col = ($col==0) ? 1:0;
        
            $worksheet->write($ctr,0,$val['invNo'],$row_left);
            $worksheet->write($ctr,1,$val['suppCode'],$row_left);
            $worksheet->write($ctr,2,$assSupplerDet['suppName'],$row_left);
            $worksheet->write($ctr,3,$val['strCode'],$row_center);
            $worksheet->write($ctr,4,$assStoreDet['BRNDESC'],$row_left);
            $worksheet->write($ctr,5,$assStoreDet['BRNSHORTNAME'],$row_left); 
            $worksheet->write($ctr,6,$val['poNo'],$row_left);
            $worksheet->write($ctr,7,$val['rcrNo'],$row_left);
            $worksheet->write($ctr,8,date('m/d/Y',strtotime($val['dateRange'])),$row_center);
            $worksheet->write($ctr,9,$val['amount'],$row_right_number);
            $worksheet->write($ctr,10,$val['efdAmount'],$row_right_number);
            $worksheet->write($ctr,11,$val['efdRate'],$row_center);
            $worksheet->write($ctr,12,$val['efdNotes'],$row_left); 
            
            $totalAmount += $val['amount']; 
            $totalEFDAmount += $val['efdAmount']; 
            
            $ctr++;
        }
        
        $worksheet->write($ctr,8,'TOTAL',$headerFormat);
        $worksheet->write($ctr,9,number_format($totalAmount,2),$headerFormat);
        $worksheet->write($ctr,10,number_format($totalEFDAmount,2),$headerFormat);
        
        $ctr++;
    }	         
		
$workbook->close();
?>
