<?
################### INCLUDE FILE #################
	session_start();
	ini_set('include_path','D:\wamp\php\PEAR');
	ini_set("memory_limit","1g");
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
	
	$filename = "transaction_details.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("transaction_details");
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
	$worksheet->write(0,0,"STS Transactions Detailed Report $status $type From ".date('m/d/Y',strtotime($_GET['txtDateFrom']))." to ".date('m/d/Y',strtotime($_GET['txtDateTo'])),$headerFormat);
	for($i=1;$i<=15;$i++) {
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
	
	/*Original
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
	*/
	$worksheet->write(2,0,"STS REF.",$headerFormat);
	$worksheet->write(2,1,"Vendor",$headerFormat);
	$worksheet->write(2,2,"Company",$headerFormat);
	$worksheet->write(2,3,"Sts No.",$headerFormat);
	$worksheet->write(2,4,"Branch",$headerFormat);
	$worksheet->write(2,5,"Sts Amt.",$headerFormat);
	$worksheet->write(2,6,"Date Entered",$headerFormat);
	$worksheet->write(2,7,"App. Start Date-No.",$headerFormat);
	$worksheet->write(2,8,"End Date",$headerFormat);
	$worksheet->write(2,9,"Mode of Payment",$headerFormat);
	$worksheet->write(2,10,"Contract No",$headerFormat);
	$worksheet->write(2,11,"Date Approved",$headerFormat);
	$worksheet->write(2,12,"Approved By",$headerFormat);
	$worksheet->write(2,13,"Hierarchy",$headerFormat);
	$worksheet->write(2,14,"Group",$headerFormat);
	$worksheet->write(2,15,"Display Specs",$headerFormat);

	
	
		$ctrRow = 3;
		$totFund = $totUpload = $totQueue = 0;
		$ctr = 1 ;
		$arrTran = $reportsObj->transSummarySupp($_GET['cmbTran'],$_GET['txtDateFrom'],$_GET['txtDateTo'],$_GET['cmbStatus'],$_GET['cmbSupp'],$_GET['cmbGroup']);
		
			foreach ($arrTran as $val) {
				$totAmt = 0;
				$totExpAmt = $flag = 0;
				$row = ($col==0) ? $detail2:$detail;
				$row2 = ($col==0) ? $Dept2:$Dept;
				$row3 = ($col==0) ? $Deptc2:$Deptc;
				$col = ($col==0) ? 1:0;
				//$arrH = $reportsObj->transSummary($_GET['cmbTran'],$_GET['txtDateFrom'],$_GET['txtDateTo'],$_GET['cmbStatus'],$val['suppCode'],$_GET['cmbGroup']);
				$ctr++;

				/*foreach($arrH as $valD){
				
					$totAmt += $valD['stsAmt'];
					$grandAmt += $valD['stsAmt'];*/
					$arrDet = $reportsObj->getParDetail3($_GET['cmbTran'],$_GET['txtDateFrom'],$_GET['txtDateTo'],$_GET['cmbStatus'],$val['suppCode'],$_GET['cmbGroup']);
					$ctr++;
					
					/*Original
					if($valH['stsType']=='5'){
						$worksheet->write($ctr,3,"DA NO.",$row);
					}else{
						$worksheet->write($ctr,3,"STS NO.",$row);
					}
					
					$worksheet->write($ctr,4,"BRANCH",$row2);
					$worksheet->write($ctr,5,"STORE AMOUNT",$row2);
					*/
					
					/*
					foreach ($arrDet as $valDet) {
						$ctr++;
						$worksheet->write($ctr,0,$valDet['stsNo'],$row);
						$worksheet->write($ctr,1,$valDet['brnShortDesc'],$row);
						$worksheet->write($ctr,2,number_format($valDet['stsAmt'],2),$row2);
					}
					*/
					$ctr++;
					$worksheet->write($ctr,0,$val['suppName'],$headerFormat2);
					foreach ($arrDet as $valDet) {
						$ctr++;
						$worksheet->write($ctr,0,$valDet['stsRefno'],$row2);
						$worksheet->write($ctr,1,$valDet['supplier'],$row);
						$worksheet->write($ctr,2,$valDet['companyCode'],$row);
						$worksheet->write($ctr,3,$valDet['stsNo'],$row2);
						$worksheet->write($ctr,4,$valDet['branch'],$row);
						$worksheet->write($ctr,5,number_format($valDet['stsAmt'],2),$row2);
						$worksheet->write($ctr,6,date('m/d/Y',strtotime($valDet['dateEntered'])),$row3);
						//$worksheet->write($ctr,7,date('m/d/Y',strtotime($valDet['applyDate'])),$row3);
						$worksheet->write($ctr,7,($valDet['applyDate'] !="") ? date('m/d/Y',strtotime($valDet['applyDate']))."-".$valDet['nbrApplication']:"",$row);
						$worksheet->write($ctr,8,date('m/d/Y',strtotime($valDet['endDAte'])),$row3);
						$worksheet->write($ctr,9,$valDet['payMode'],$row);
						$worksheet->write($ctr,10,$valDet['contractNo'],$row);
						if($valDet['stsNo'] != ""){
						$worksheet->write($ctr,11,date('m/d/Y',strtotime($valDet['dateApproved'])),$row3);
						}else{
						$worksheet->write($ctr,11,'',$row3);
						}
						$worksheet->write($ctr,12,$valDet['fullName'],$row2);
						$worksheet->write($ctr,13,$valDet['hierarchyDesc'],$row);
						$worksheet->write($ctr,14,$valDet['grpDesc'],$row);
						$worksheet->write($ctr,15,$valDet['displaySpecsDesc'],$row);

						$totAmt += $valDet['stsAmt'];
					}
					
					
					$ctr++;
					$grandAmt += $totAmt;
				$worksheet->write($ctr,4,"Sub Total",$headerFormat);
				$worksheet->write($ctr,5,number_format($totAmt,2),$headerFormat);
				}
			if($grandAmt != ""){
			$ctr++;
			$worksheet->write($ctr,4,"Grand Total",$headerFormat);
			$worksheet->write($ctr,5,number_format($grandAmt,2),$headerFormat);
			}
$workbook->close();
?>
