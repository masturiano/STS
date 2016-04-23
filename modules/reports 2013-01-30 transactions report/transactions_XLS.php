<?
################### INCLUDE FILE #################
	session_start();
	ini_set('include_path','D:\wamp\php\PEAR');
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
	$filename = "transaction_summary.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("transaction_summary");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(3,0));
	if ($_GET['cmbStatus']=='R') {
			$status = " Approved";
		} elseif ($_GET['cmbStatus']=='O') {
			$status = " Unapproved";
		} else {
			$status = "Approved and Unapproved";
		}
	if($_GET['cmbTran'] == '1'){
		$type = 'Regular STS';
	}elseif($_GET['cmbTran'] == '2'){
		$type = 'Listing Fee';	
	}elseif($_GET['cmbTran'] == '4'){
		$type = 'Shelf Enhancer';	
	}elseif($_GET['cmbTran'] == '4'){
		$type = 'Display Allowance';	
	}else{
		$type = "All STS Type";
	}
	$worksheet->write(0,0,"STS Transactions Summarized Report $status $type From ".date('m/d/Y',strtotime($_GET['txtDateFrom']))." to ".date('m/d/Y',strtotime($_GET['txtDateTo'])),$headerFormat);
	for($i=1;$i<=10;$i++) {
		$worksheet->write(0, $i, "",$headerFormat);	
	}
	$worksheet->setColumn(0,0,10);
	$worksheet->setColumn(0,1,15);
	$worksheet->setColumn(0,2,10);
	$worksheet->setColumn(0,3,10);
	$worksheet->setColumn(0,4,15);
	$worksheet->setColumn(0,5,15);
	$worksheet->setColumn(0,6,15);
	$worksheet->setColumn(0,7,15);
	$worksheet->setColumn(0,8,20);
	$worksheet->setColumn(0,9,20);
	
	
	$worksheet->write(2,0,"STS REF.",$headerFormat);
	$worksheet->write(2,1,"Amount",$headerFormat);
	$worksheet->write(2,2,"Date Ent.",$headerFormat);
	$worksheet->write(2,3,"App. Start Date-No.",$headerFormat);
	$worksheet->write(2,4,"Mode of Payment",$headerFormat);
	$worksheet->write(2,5,"Contract No.",$headerFormat);
	$worksheet->write(2,6,"Approved Date",$headerFormat);
	$worksheet->write(2,7,"Approved By",$headerFormat);
	$worksheet->write(2,8,"Hierarchy",$headerFormat);
	$worksheet->write(2,9,"Remarks",$headerFormat);
	
	
	
		$ctrRow = 3;
		$totFund = $totUpload = $totQueue = 0;
		$ctr = 3 ;
		$arrTran = $reportsObj->transSummarySupp($_GET['cmbTran'],$_GET['txtDateFrom'],$_GET['txtDateTo'],$_GET['cmbStatus'],$_GET['cmbSupp']);
		
			foreach ($arrTran as $val) {
				$totAmt = 0;
				$totExpAmt = $flag = 0;
				$row = ($col==0) ? $detail2:$detail;
				$row2 = ($col==0) ? $Dept2:$Dept;
				$col = ($col==0) ? 1:0;
				$arrH = $reportsObj->transSummary($_GET['cmbTran'],$_GET['txtDateFrom'],$_GET['txtDateTo'],$_GET['cmbStatus'],$val['suppCode']);
				$ctr++;
				$worksheet->write($ctr,0,$val['suppName'],$headerFormat2);
				foreach($arrH as $valD){
					$ctr++;
					$worksheet->write($ctr,0,$valD['stsRefno'],$row2);
					$worksheet->write($ctr,1,number_format($valD['stsAmt'],2),$row2);
					$worksheet->write($ctr,2,date('m/d/Y',strtotime($valD['dateEntered'])),$row2);
					$worksheet->write($ctr,3,($valD['applyDate'] !="") ? date('m/d/Y',strtotime($valD['applyDate']))."-".$valD['nbrApplication']:"",$row);
					$worksheet->write($ctr,4,$valD['payMode'],$row2);
					$worksheet->write($ctr,5,$valD['contractNo'],$row2);
					$worksheet->write($ctr,6,date('m/d/Y',strtotime($valD['dateApproved'])),$row2);
					$worksheet->write($ctr,7,$valD['fullName'],$row2);
					$worksheet->write($ctr,8,$valD['dept']."-".$valD['cls']."-".$valD['subCls'],$row);
					$worksheet->write($ctr,9,$valD['stsRemarks'],$row);
					
					$totAmt += $valD['stsAmt'];
					$grandAmt += $valD['stsAmt'];
				}
				$ctr++;
				$worksheet->write($ctr,0,"Sub Total",$headerFormat);
				$worksheet->write($ctr,1,number_format($totAmt,2),$headerFormat);
			}
			$worksheet->setRow($ctr,16);
			$ctr++;
			$worksheet->write($ctr,0,"Grand Total",$headerFormat);
			$worksheet->write($ctr,1,number_format($grandAmt,2),$headerFormat);
				
$workbook->close();
?>
