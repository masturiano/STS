<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
	
class EODobj extends commonObj {
	
	function ExtractTblSTShdr() {
		$sql = "Exec sts_test.dbo.sts_EOD2";
		//$sql = "Exec sts_EOD2";
		//return $this->execQry($sql);
		return mssql_query($sql);
	}
	function ExtractAPAR(){
		//$sql = "Exec sts_extractTransacAPAR";
		//return  $this->execQry($sql);
	//	mssql_select_db("sts_test");
		$sql = "Exec sts_test.dbo.sts_extractTransacAPAR";
		return mssql_query($sql);
	}
	function getStsNo(){
		$trans = $this->beginTran();
		$qryGetToApNo = "SELECT stscontrolno from sts..controlno";	
		$oldSTS = $this->getSqlAssoc($this->execQry($qryGetToApNo));
		$newAP = (int)$oldSTS['stscontrolno']+435;
		$qryUpdateApNo = "UPDATE sts..controlno SET stscontrolno = ".$newAP." ";
		if ($trans){
			$trans = $this->execQry($qryUpdateApNo);
		}
		if(!$trans){
			$trans = $this->rollbackTran();
			return "Error";
		}else{
			$trans = $this->commitTran();
			return (int)$oldSTS['stscontrolno'];
		}
	}
	function uploadToOracle(){
			
			$ctr2 = 0;
			$arrCompAp = $this->getDistinctCompCodeInAP();
			
			$trans = $this->beginTran();
			
			
			if(count($arrCompAp)>0){
				foreach($arrCompAp as $valAp){
					$totDetailAmt = 0;
					#### Batch Number
					$qryGetToApNo = "SELECT apBatchNo FROM tblApBatchNo";
					$oldAP = $this->getSqlAssoc($this->execQry($qryGetToApNo));
					$newAP = (int)$oldAP['apBatchNo']+1;//new voucher number
					
					$qryUpdateApNo = "UPDATE tblApBatchNo SET apBatchNo = ".$newAP." ";
					if ($trans){
						$trans = $this->execQry($qryUpdateApNo);
					}
					
					$tempApNo = sprintf("%05s", $newAP);
					$apBatchNo = "ST".$tempApNo;
					
					
					$gmt = time() + (8 * 60 * 60);
					$todayTime = date("His");
					$datefileMD = date("md");
					$datefileY = date("y");
					$mmsApEntContent="";
					$mmsApEntIwt="";
					$mmsIDstContent="";
					$mmsAPHeadContent="";
					$mmsApEntHandler = "";
					$mmsApIwtHandler = "";
					$mmsApIDstHandler = "";
					$mmsApHeadHandler = "";
					
					$sql = "SELECT     tblStsDlyAp.stsNo, tblStsDlyAp.stsSeq, tblStsDlyAp.stsApplyDate, tblStsDlyAp.stsActualDate, tblStsDlyAp.suppCode, tblStsDlyAp.stsApplyAmt, tblStsDlyAp.compCode, 
                      pg_pf.dbo.tblBranches.brnShortName, pg_pf.dbo.tblBranches.businessLine, pg_pf.dbo.tblBranches.compCode as compCodeHO,tblStsHdr.applyDate, tblStsDlyAp.strCode, tblStsHierarchy.glMajor, 
                      tblStsHierarchy.glMinor, tblStsHierarchy.hierarchyDesc, tblStsDlyAp.stsType
FROM         tblStsDlyAp left JOIN
                      tblStsHdr ON tblStsDlyAp.stsRefno = tblStsHdr.stsRefno INNER JOIN
                      pg_pf.dbo.tblBranches ON tblStsDlyAp.strCode = pg_pf.dbo.tblBranches.strCode INNER JOIN
                      tblStsHierarchy ON tblStsDlyAp.stsDept = tblStsHierarchy.stsDept AND tblStsDlyAp.stsCls = tblStsHierarchy.stsCls AND 
                      tblStsDlyAp.stsSubCls = tblStsHierarchy.stsSubCls
					WHERE tblStsDlyAp.compCode = '{$valAp['compCode']}'
					";
					
					################# ORACLE TEXT FILE UPLOAD
					if($valAp['compCode']==700){
						$fileFolder = "PGJR";
						$fileCode = "PJ";	
					}else{
						$fileFolder = "PPCI";
						$fileCode = "PG";
					}
					$file_path="../../exportfiles/$fileFolder/AP/$fileCode".$datefileMD.$datefileY."_".$todayTime.".401"; 
					$file_name2="$fileCode".$datefileMD.$datefileY."_".$todayTime.".401";
					/*if (file_exists($file_path)) {
						unlink($file_path);
					} */
					$handle2 = fopen($file_path, "a");
					
					
					### mms file
					### APIENT
					$fileLocMMSApEnt = "../../exportfiles/mms/AP/".trim("APES").$datefileMD.$datefileY."_".$todayTime.$SECONDS.".CSV"; 
					$fileNameMMSApEnt=trim("APES").$datefileMD.$datefileY."_".$todayTime.$SECONDS.".CSV"; 
								
					### APIWT
					$fileLocMMSApIwt = "../../exportfiles/mms/AP/".trim("APWS").$datefileMD.$datefileY."_".$todayTime.$SECONDS.".CSV"; 
					$fileNameMMSApIwt=trim("APWS").$datefileMD.$datefileY."_".$todayTime.$SECONDS.".CSV"; 
								
					### APIDST
					$fileLocMMSAPIDST = "../../exportfiles/mms/AP/".trim("APDS").$datefileMD.$datefileY."_".$todayTime.$SECONDS.".CSV"; 
					$fileNameMMSAPIDST=trim("APDS").$datefileMD.$datefileY."_".$todayTime.$SECONDS.".CSV"; 
							
					### APIDST
					$fileLocMMSAPHEAD = "../../exportfiles/mms/AP/".trim("APHS").$datefileMD.$datefileY."_".$todayTime.$SECONDS.".CSV"; 
					$fileNameMMSAPHEAD=trim("APHS").$datefileMD.$datefileY."_".$todayTime.$SECONDS.".CSV";
					
					### APIRDM
					##### mike sept 12 2012
					$fileLocMMSAPIRDM = "../../exportfiles/mms/AP/".trim("ADMS").$datefileMD.$datefileY."_".$todayTime.$SECONDS.".CSV"; 
					$fileNameMMSAPIRDM=trim("ADMS").$datefileMD.$datefileY."_".$todayTime.$SECONDS.".CSV";
					
					$arrContent = $this->getArrRes($this->execQry($sql));
					$ctr=0;
					
					foreach($arrContent as $valCon){
						$ctr++;
						
						if((int)$valCon['stsType'] == 1){
							if(($valCon['glMajor'] >= 700 && $valCon['glMajor'] <= 999)&&($valCon['glMinor'] >= 101 && $valCon['glMinor'] <=123)){
								$department = $valCon['glMinor'];
								$accountMajor = $valCon['compCode'].$valCon['glMajor']."000";	
							}else{
								$department = "0";
								$accountMajor = $valCon['compCode'].$valCon['glMajor'].$valCon['glMinor'];
							}
						}else{
							$department = "0";
							$accountMajor = $valCon['compCode'].$valCon['glMajor'].$valCon['glMinor'];
						}
						
						if($valCon['stsType']=='3' || $valCon['stsType']=='7' || $valCon['stsType']=='8'){
							$prefix = 'PF';
						}elseif($valCon['stsType']=='5'){
							$prefix = 'DA';
						}else{
							$prefix = 'ST';
						}
						################ Oracle Text File Upload
						$contents .= $prefix.$valCon['stsNo']."-".$valCon['stsSeq']."|";
						$contents .= "DEBIT|";
						$contents .= date("d-M-Y",strtotime($valCon['stsApplyDate']))."|";
						$contents .= $valCon['suppCode']."|";
						$contents .= $valCon['brnShortName']."|";
						$contents .= $valCon['stsApplyAmt']."|";
						$contents .= $valCon['hierarchyDesc']."|";
						$contents .= date("d-M-Y",strtotime($valCon['stsApplyDate']))."|";
						$contents .= date("d-M-Y",strtotime($valCon['stsApplyDate']))."|";
						$contents .= date("d-M-Y")."|";
						$contents .= "STS|";
						$contents .= "1|";
						$contents .= $valCon['stsApplyAmt']."|";
						$contents .= "ITEM|";
						$contents .= "|";
						$contents .= "|";
						$contents .= "|";
						$contents .= "|";
						$contents .= "|";
						$contents .= "|";
						$contents .= "|";
						$contents .= "|";
						$contents .= "|";
						$contents .= "|";
						$contents .= $valCon['compCode']."|";
						$contents .= $valCon['brnShortName']."|";
						$contents .= $valCon['businessLine']."|";
						$contents .= $department."|"; ##department
						$contents .= "0|"; ##section
						$contents .= $accountMajor."|"; ##major
						$contents .= $accountMajor."|"; ##minor
						$contents .= $valCon['stsApplyAmt']."|"; 
						$contents .= "XX|";
						$contents .= "|";
						$contents .= "|";
						$contents .= "|";
						$contents .= "PHP|";
						$contents .= date("d-M-Y",strtotime($valCon['stsApplyDate']))."|";
						$contents .= "|";
						$contents .= $file_name2."|\r\n";
						
						
						
						
						##### mike sept 12 2012
						if( $valCon['strCode']=='101' || $valCon['strCode']=='102' || $valCon['strCode']=='103' || $valCon['strCode']=='104' || $valCon['strCode']=='105') 
							$mmsCompCode = $valCon['strCode'];
						else{
							$mmsCompCode = 	$valCon['compCodeHO'];
						}
						##### APIRD
						if((string)$mmsCompCode=='302'){
						$mmsAPIRDMContent = $mmsAPIRDMContent.
							$apBatchNo.",". ###Entry Batch Number
							$mmsCompCode.",". ###Company Number
							"1,". ####Vendor Type 
							$valCon['suppCode'].",". ####Vendor Number
							$prefix.$valCon['stsNo']."-".$valCon['stsSeq']."," .  ###Invoice Number
							$valCon['hierarchyDesc']."\r\n"; 	### Remarks
						
						$mmsApEntContent = $mmsApEntContent.
							$apBatchNo.",". ###Entry Batch Number
							$mmsCompCode.",". ###Company Number
							"1,". ####Vendor Type 
							$valCon['suppCode'].",".  ###Vendor Number
							$prefix.$valCon['stsNo']."-".$valCon['stsSeq']."," .  ###Invoice Number
							"1," . ###  Terms Code
							"PHP,". ###Currency Code
							"1,". ###Curncy Exchange Rate
							"0,". ####Other Vendor
							$valCon['stsApplyAmt']."," .  ###Original Amount
							"0,". ###Freight Amount
							"0,". ###Other Deductions
							"0," .  ##Disallowed Amount
							$valCon['stsApplyAmt'].",". ###Invoice Amount
							"0,". ###Tax Amount
							"0,". ###Discount Amount
							"0,". ###Tax Discount Amount
							$valCon['stsApplyAmt']."," . ##netAmt
							"0,". ##disc %
							$valCon['stsApplyAmt']."," . ##hash amt
							"0,".  ##has qty
							"0,".  ##matched amt
							"0,".  ##matched qty
							"0,". ## credits
							"0,".  ##total retail
							$valCon['stsApplyAmt']."," . ## total netAmt
							"0,".  ##total retail
							"1,".  ##Invoice Received Century1 2000 0 1900
							date("ymd",strtotime($valCon['stsApplyDate'])).",".   ##Invoice Received Date
							"0,".  ## Invoice Date Century 1 2000 0 1900
							date("ymd",strtotime($valCon['stsApplyDate'])).",".   ##Invoice Date
							"1,".  ## Date to Pay Century
							date("ymd",strtotime($valCon['stsApplyDate']))."," . 	## Date to Pay
							"0," . 	###Discount Date Century
							"0," . 	###Discount Date
							"0," . 	### Warehouse Received ntury
							"0," . 	###Warehouse Received te
							"0," . 	### Posting Date Century
							"0," . 	### PPosting Date
							date("m")."," . ##Posting Period
							date("y"). "," .  ##Posting Fiscal Year
							"1," . 	###Posting Fiscal Century
							"0," . 	###EDI Transmission ID
							"0," . 	### EDI Transmission Century
							"0," . 	###EDI Transmission Date
							$valCon['hierarchyDesc']."," . ###Invoice Notes 
							"1," . 	###Clerk
							$valCon['strCode'].",". 
							"XXX," . 	### Landed Cost Factor
							"F," . 	###  Matched Flag
							"R," . 	###  Credit Authorization Flag
							"M," . 	###  Entry Mode Reference
							"1," . 	###  Sequential Entry Number
							"N," . 	###  Multiple P.O. Flag
							"XXXX," . 	###  Default Allocation
							"0," . 	###  Credit Discount Amount
							"1," . 	###  Default Payment Method
							"0," . 	###  OTP Matched Receiver
							"PHP," . 	###  To currency
							"SYS," . 	###  OTP Matched Receiver
							"0"."\r\n"; 	###  Multiply Divide Flag
							
							############### APIWT
							$mmsApEntIwt = $mmsApEntIwt.
							$apBatchNo.",". ###Entry Batch Number
							$mmsCompCode.",". ####Company Number
							"1,". ###Vendor Type
							$valCon['suppCode'].",". ####Vendor Number
							$prefix.$valCon['stsNo']."-".$valCon['stsSeq']. "," .  ###Invoice Number
							"1," .  ####Sequence Number
							"XX,".  ###Withholding tax code
							$valCon['stsApplyAmt']."," .  ### COST AMOUNT
							"0," . #### Vat Amt
							$valCon['stsApplyAmt'].",". #### AMT W/O VAT
							"0,". #### WHT AMT
							$valCon['stsApplyAmt']."," . ####AMT W/O WHT
							"90,". #### VAT MAJOR
							"1,".  #### VAT MINOR
							$valCon['strCode'].",".  #### VAT STORE
							"350,". #### WHT MAJOR
							"9,". #### WHT MINOR
							$valCon['strCode']."\r\n"; ####WHT STORE
							
							############# APIDST
							$mmsIDstContent = $mmsIDstContent.
								$apBatchNo.",".
								$mmsCompCode.",".
								"1,".
								$valCon['suppCode'].",". 
								$prefix.$valCon['stsNo']."-".$valCon['stsSeq']. ",". 
								"1,". 
								$valCon['compCode'].",".
								$valCon['glMajor'].",". 
								$valCon['glMinor'].",". 
								$valCon['strCode'].",". 
								$valCon['stsApplyAmt'].",". 
								"0,".
								"Y,".
								"N,".
								"N".
								"\r\n";
								
							$totDetailAmt += $valCon['stsApplyAmt'];
						}
							
					}
					
					############### APHEAD
					if((string)$mmsCompCode=='302'){
						$mmsAPHeadContent = $mmsAPHeadContent.
						$apBatchNo.",". ##batch
						"1,". ##type
						"MJALIM,". ##user
						"STS/DAC ".$apBatchNo.",". ## Remarks
						$ctr.",". ## Total Records
						$totDetailAmt.",". ##Total Amt
						$ctr.",". ## Total Actual record
						$totDetailAmt.",". ##Total actual Amt
						"N,". ##with error?
						"1,". ## Clerk
						"N,". ##recurring batch
						"N,". ## auto recur
						"XXXXXX,". ## batch prefix
						"0,". ## batch sched
						"1,". ## batch century
						"0,". ## last update date
						"XXXXXXXXXXXX". ## last invoice used
						"\r\n";
					}
						
					
						
						fwrite($handle2, $contents);
						fclose($handle2) ;
						unset($contents);
						############mms AP entered text file
						if (file_exists($fileNameMMSApEnt)) {
							unlink($fileLocMMSApEnt);
						} 
						$mmsApEntHandler = fopen($fileLocMMSApEnt, "a");
						
						fwrite($mmsApEntHandler, $mmsApEntContent);
						fclose($mmsApEntHandler);		
						################# APIWT
						if (file_exists($fileNameMMSApIwt)) {
							unlink($fileLocMMSApIwt);
						} 
						$mmsApIwtHandler = fopen ($fileLocMMSApIwt, "a");
						fwrite($mmsApIwtHandler, $mmsApEntIwt);
						fclose($mmsApIwtHandler);
						
						################# APIDST
						if (file_exists($fileNameMMSAPIDST)) {
							unlink($fileLocMMSAPIDST);
						} 
						$mmsApIDstHandler = fopen ($fileLocMMSAPIDST, "a");
						fwrite($mmsApIDstHandler, $mmsIDstContent);
						fclose($mmsApIDstHandler);
						
						################# APHEAD
						if (file_exists($fileNameMMSAPHEAD)) {
							unlink($fileLocMMSAPHEAD);
						} 
						$mmsApHeadHandler = fopen ($fileLocMMSAPHEAD, "a");
						fwrite($mmsApHeadHandler, $mmsAPHeadContent);
						fclose($mmsApHeadHandler);
						
						
						################# APIRDM
						if (file_exists($fileNameMMSAPIRDM)) {
							unlink($fileLocMMSAPIRDM);
						} 		
						$mmsAPIRDMHandler = fopen ($fileLocMMSAPIRDM, "a");
						fwrite($mmsAPIRDMHandler, $mmsAPIRDMContent);
						fclose($mmsAPIRDMHandler);
						
					############### upload to mms
					if ($ctr>0) {
						$ftp_server = "192.168.200.136";  
						$ftp_user_name = "ppcioracle"; 
						$ftp_user_pass = "oracle"; 
						$conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server");        
						
						$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) 
						or die("You do not have access to this ftp server!");   
						
						############### Oracle Text File Upload
						$destination_file = $file_name2;
						$upload = ftp_put($conn_id, $destination_file, $file_path, FTP_BINARY); 
						
						$destination_file = "/pgtflr/APMFLR/Unprocess/".$fileNameMMSApEnt;
						//$upload = ftp_put($conn_id, $destination_file, $fileLocMMSApEnt, FTP_BINARY);  // upload the file
						
						$destination_file = "/pgtflr/APMFLR/Unprocess/".$fileNameMMSApIwt;
						//$upload = ftp_put($conn_id, $destination_file, $fileLocMMSApIwt, FTP_BINARY);  // upload the file		
						
						$destination_file = "/pgtflr/APMFLR/Unprocess/".$fileNameMMSAPIDST;
						//$upload = ftp_put($conn_id, $destination_file, $fileLocMMSAPIDST, FTP_BINARY);  // upload the file		
						
						$destination_file = "/pgtflr/APMFLR/Unprocess/".$fileNameMMSAPHEAD;
						//$upload = ftp_put($conn_id, $destination_file, $fileLocMMSAPHEAD, FTP_BINARY);  // upload the file		
						
						$destination_file = "/pgtflr/APMFLR/Unprocess/".$fileNameMMSAPIRDM;
						//$upload = ftp_put($conn_id, $destination_file, $fileLocMMSAPIRDM, FTP_BINARY);  // upload the file
						//ftp_close($conn_id); 
					}
					
					$sqlUpdateAp = "UPDATE tblStsDlyAp SET status = 'A', uploadDate = '".date('Y-m-d')."', uploadApFile = '".$datefileMD.$datefileY."_".$todayTime.$SECONDS."', apBatch = '$apBatchNo' WHERE compCode = '{$valAp['compCode']}'";
					$sqlInsertAp = "INSERT INTO tblStsDlyApHist SELECT * FROM tblstsdlyap WHERE compCode = '{$valAp['compCode']}'";
					$sqlDelAp = "DELETE FROM tblStsDlyAp WHERE compCode = '{$valAp['compCode']}'";
					
					if ($trans){
						$trans = $this->execQry($sqlUpdateAp);
					}
					if ($trans){
						$trans = $this->execQry($sqlInsertAp);
					}
					if ($trans){
						$trans = $this->execQry($sqlDelAp);
					}
					
				}
			}
			
			###############AR
			$arrCompAr = $this->getDistinctCompCodeInAR();
			
			if(count($arrCompAr)>0){
				
				foreach($arrCompAr as $valAr){
					$totDetailAmt = 0;
					#### Batch Number
					$qryGetToApNo = "SELECT apBatchNo FROM tblApBatchNo";
					$oldAP = $this->getSqlAssoc($this->execQry($qryGetToApNo));
					$newAP = (int)$oldAP['apBatchNo']+1;//new voucher number
					
					$qryUpdateApNo = "UPDATE tblApBatchNo SET apBatchNo = ".$newAP." ";
					if ($trans){
						$trans = $this->execQry($qryUpdateApNo);
					}
					
					$tempApNo = sprintf("%05s", $newAP);
					$arBatchNo =  sprintf("%06s", $newAP);
					//$arBatchNo = $apBatchNo;
						
					$mmsApEntContent="";
					$mmsApEntIwt="";
					$mmsIDstContent="";
					$mmsAPHeadContent="";
					$mmsApEntHandler = "";
					$mmsApIwtHandler = "";
					$mmsApIDstHandler = "";
					$mmsApHeadHandler = "";
								
					$sqlAr = "SELECT     tblStsDlyAr.stsNo, tblStsDlyAr.stsSeq, tblStsDlyAr.stsApplyDate, tblStsDlyAr.stsActualDate, tblStsDlyAr.suppCode, tblStsDlyAr.stsApplyAmt, tblStsDlyAr.compCode, 
                      tblStsHierarchy.glMajor, tblStsHierarchy.glMinor, pg_pf.dbo.tblBranches.brnShortName, pg_pf.dbo.tblBranches.businessLine, pg_pf.dbo.tblBranches.compCodeHO, tblStsHdr.applyDate,  tblStsHierarchy.hierarchyDesc, tblStsDlyAr.strCode, tblStsDlyAr.stsType
FROM         tblStsDlyAr LEFT OUTER JOIN
                      tblStsHdr ON tblStsDlyAr.stsRefno = tblStsHdr.stsRefno INNER JOIN
                       tblStsHierarchy ON tblStsDlyAr.stsDept = tblStsHierarchy.stsDept AND tblStsDlyAr.stsCls = tblStsHierarchy.stsCls AND 
                      tblStsDlyAr.stsSubCls = tblStsHierarchy.stsSubCls INNER JOIN
                      pg_pf.dbo.tblBranches ON tblStsDlyAr.strCode = pg_pf.dbo.tblBranches.strCode
					WHERE tblstsdlyar.compCode = '{$valAr['compCode']}'
					";
					
					if($valAr['compCode']==700){
						$fileFolder = "PGJR";
						$fileCode = "PJ";	
					}else{
						$fileFolder = "PPCI";
						$fileCode = "PG";
					}
					
					
					$gmt = time() + (8 * 60 * 60);
					$todayTime = date("His");
					$datefileMD = date("md");
					$datefileY = date("y");
					
					$file_path="../../exportfiles/$fileFolder/AR/$fileCode".$datefileMD.$datefileY."_".$todayTime.".H01"; 
					$file_name2="$fileCode".$datefileMD.$datefileY."_".$todayTime.".H01";
					/*if (file_exists($file_path)) {
						unlink($file_path);
					} */
					$handle2 = fopen($file_path, "a");
					
					### mms file
					### APIENT
					
					
					$fileLocMMSApEnt = "../../exportfiles/mms/AR/".trim("ARES").$datefileMD.$datefileY."_".$todayTime.$SECONDS.".CSV"; 
					$fileNameMMSApEnt=trim("ARES").$datefileMD.$datefileY."_".$todayTime.$SECONDS.".CSV"; 
								
					### APIDST
					$fileLocMMSAPIDST = "../../exportfiles/mms/AR/".trim("ARDS").$datefileMD.$datefileY."_".$todayTime.$SECONDS.".CSV"; 
					$fileNameMMSAPIDST=trim("ARDS").$datefileMD.$datefileY."_".$todayTime.$SECONDS.".CSV"; 
							
					### APIDST
					$fileLocMMSAPHEAD = "../../exportfiles/mms/AR/".trim("ARHS").$datefileMD.$datefileY."_".$todayTime.$SECONDS.".CSV"; 
					$fileNameMMSAPHEAD=trim("ARHS").$datefileMD.$datefileY."_".$todayTime.$SECONDS.".CSV";
					
					$arrContent = $this->getArrRes($this->execQry($sqlAr));
					
					$ctr=0;
					foreach($arrContent as $valCon){
						
						$qryGetToArCtr = "SELECT arCtr FROM tblArCtr";
						$oldArCtr = $this->getSqlAssoc($this->execQry($qryGetToArCtr));
						$newArCtr = (int)$oldArCtr['arCtr']+1;//new voucher number
						
						$qryUpdateArCtr = "UPDATE tblArCtr SET arCtr = ".$newArCtr." ";
						if ($trans){
							$trans = $this->execQry($qryUpdateArCtr);
						}
						
						if($valCon['stsType']=='3' || $valCon['stsType']=='7' || $valCon['stsType']=='8'){
							$prefix = 'PF';
						}elseif($valCon['stsType']=='5'){
							$prefix = 'DA';
						}else{
							$prefix = 'ST';
						}
						
						
						//if((int)$valCon['stsType'] == 1){
//							if(($valCon['glMajor'] >= 700 && $valCon['glMajor'] <= 999)&&($valCon['glMinor'] >= 101 && $valCon['glMinor'] <=123)){
//								$department = $valCon['glMinor'];
//								$accountMajor = $valCon['compCode'].$valCon['glMajor']."000";	
//							}else{
//								$department = "0";
//								$accountMajor = $valCon['compCode'].$valCon['glMajor'].$valCon['glMinor'];
//							}
//						}else{
//							$department = "0";
//							$accountMajor = $valCon['compCode'].$valCon['glMajor'].$valCon['glMinor'];
//						}
					
						if( $valCon['strCode']=='101' || $valCon['strCode']=='102' || $valCon['strCode']=='103' || $valCon['strCode']=='104' || $valCon['strCode']=='105') 
							$mmsCompCode = $valCon['strCode'];
						else{
							$mmsCompCode = 	$valCon['compCode'];
						}
						$subLedger = $this->getSubledger($mmsCompCode);
						$subLedger2 = explode(",",$subLedger);
						$getCustCode = "SELECT ASRCUS FROM sql_mmpgtlib..APSREB WHERE ASNUM = '{$valCon['suppCode']}'";
						$custCode = $this->getSqlAssoc($this->execQry($getCustCode));
						
						$ctr++;
						$contents .= $prefix.$valCon['stsNo']."-".$valCon['stsSeq']."|";
						$contents .= date("d-M-Y",strtotime($valCon['stsApplyDate']))."|";
						$contents .= "STS".$valCon['glMajor'].$valCon['glMinor']."|";
						$contents .= $valCon['brnShortName']."|";
						$contents .= $custCode['ASRCUS']."|";
						$contents .= $valCon['brnShortName']."|";
						$contents .= "1|";
						$contents .= $valCon['businessLine']."|";
						$contents .= "STS|";
						$contents .= date("d-M-Y",strtotime($valCon['stsApplyDate']))."|";
						$contents .= $prefix.$valCon['stsNo']."-".$valCon['stsSeq']."|";
						$contents .= $prefix.$valCon['stsNo']."-".$valCon['stsSeq']."|";
						$contents .= "1|";
						$contents .= $valCon['stsApplyAmt']."|";
						$contents .= "|";
						$contents .= "PHP|";
						$contents .= "0|";
						$contents .= $file_name2."|\r\n";
						
						if((string)$mmsCompCode =='302'){
						$mmsApEntContent = $mmsApEntContent.
							"MBALIG2,". ### User
							$newArCtr.",". ### AR INVOICE NO mike
							"0,". #### OPNTRX
							$custCode['ASRCUS'].",".  ###Customer #
							"1," .  ### OPNIVC
							date("ymd",strtotime($valCon['stsApplyDate'])).",".   ##Invoice Received Date
							"0,". ###OPNDUC
							"0,". ###OPNDUD
							$valCon['stsApplyAmt']."," .  ###Invoice Amount
							$prefix.$valCon['stsNo']."-".$valCon['stsSeq'].",". ###Reference no
							"X,". ###OPNFLG
							"X,". ###OPNDSP
							"0," .  ##OPNDSC Amount
							"0," .  ##OPNDSD
							$subLedger2[0].",". ###HO Location
							"0," .  ##OPNTYP
							"0," .  ##OPNMAJ
							"0," .  ##OPNMIN
							$valCon['strCode'].",".
							$subLedger2['1'].",". ##disc %
							"1," . ##OPNRAT
							"PHP,".  ##OPCODF
							"PHP,".  ## OPCODT
							"SYS,".  ##OPCVTP
							"X,". ## OPCVMD
							"VT,".  ##ITAX1
							"0," . ## ITAX1A
							"XXXXXX,".  ## ITAX2
							"0," . ## ITAX1A
							"XXXXXX,".  ## ITAX2
							"0," . ## ITAX1A
							"XXXXXX,".  ## ITAX2
							"0," . ## ITAX1A
							"0," . ## ITAX1A
							$arBatchNo.",". ## ar batch no
							"0,". ##0
							"XXXXXXXXXX,". ##0
							"0,". ##0
							"1".  ##
							"\r\n"; 	###  Multiply Divide Flag
						
						##### Distribution SETS
						
						
						$mmsIDstContent = $mmsIDstContent.
								$subLedger2[1].",". ##subledger
								$custCode['ASRCUS'].",". ####customer no
								"D,". ## disp type
								$subLedger2[0].",".  ### HO Location
								$newArCtr.",".  ## Invoice No mike
								$valCon['stsApplyAmt'].",". 
								$valCon['glMajor'].",". 
								$valCon['glMinor'].",". 
								$valCon['strCode'].",".
								"0,". 
								"XXX,". 
								"XXX,". 
								"XXX,". 
								"X,". 
								"0,".
								"XXX,". 
								"XXX,". 
								"XXX,". 
								"X,". 
								"0,".
								"0,".
								"0".
								"\r\n";
							$totDetailAmt += $valCon['stsApplyAmt'];
						}
					}
					fwrite($handle2, $contents);
					fclose($handle2);
					unset($contents);
					############### APHEAD
					if((string)$mmsCompCode =='302'){
					$mmsAPHeadContent = $mmsAPHeadContent.
						"ARZ039,". ##"ARZ039"
						"MBALIG2,". ##user
						"STS WEB ".$arBatchNo.",". ## Remarks
						$ctr.",". ## Total Records
						$totDetailAmt.",". ##Total Amt
						$ctr.",". ## Total Actual record
						$totDetailAmt.",". ##Total actual Amt
						"N,". ##with error?
						$subLedger2[0].",".  ### HO Location
						$subLedger2[1].",". ## subledger
						"1,". ## BTSTAT
						"0,". ## BTCOD
						$arBatchNo."". ## AR BatchNo
						"\r\n";
					}
					
					
					
					
					############mms AP entered text file
					if (file_exists($fileNameMMSApEnt)) {
						unlink($fileLocMMSApEnt);
					} 
					$mmsApEntHandler = fopen($fileLocMMSApEnt, "a");
						
					fwrite($mmsApEntHandler, $mmsApEntContent);
					fclose($mmsApEntHandler);		
					
						
					################# APIDST
					if (file_exists($fileNameMMSAPIDST)) {
						unlink($fileLocMMSAPIDST);
					} 
					$mmsApIDstHandler = fopen ($fileLocMMSAPIDST, "a");
					fwrite($mmsApIDstHandler, $mmsIDstContent);
					fclose($mmsApIDstHandler);
						
					################# APHEAD
					if (file_exists($fileNameMMSAPHEAD)) {
						unlink($fileLocMMSAPHEAD);
					} 
					$mmsApHeadHandler = fopen ($fileLocMMSAPHEAD, "a");
					fwrite($mmsApHeadHandler, $mmsAPHeadContent);
					fclose($mmsApHeadHandler);
						
					if ($ctr>0) {
						$ftp_server = "192.168.200.136";  
						$ftp_user_name = "ppcioracle"; 
						$ftp_user_pass = "oracle"; 
						$conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server");        
						
						$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) 
						or die("You do not have access to this ftp server!");   
						
						$destination_file = $file_name2;
						$upload = ftp_put($conn_id, $destination_file, $file_path, FTP_BINARY); 
						
						
						$destination_file = "/pgtflr/APMFLR/Unprocess/".$fileNameMMSApEnt;
						//$upload = ftp_put($conn_id, $destination_file, $fileLocMMSApEnt, FTP_BINARY);  // upload the file
							
						$destination_file = "/pgtflr/APMFLR/Unprocess/".$fileNameMMSAPIDST;
						//$upload = ftp_put($conn_id, $destination_file, $fileLocMMSAPIDST, FTP_BINARY);  // upload the file		
						
						$destination_file = "/pgtflr/APMFLR/Unprocess/".$fileNameMMSAPHEAD;
						//$upload = ftp_put($conn_id, $destination_file, $fileLocMMSAPHEAD, FTP_BINARY);  // upload the file		
						
						//ftp_close($conn_id); 
					}
					$sqlUpdateAr = "UPDATE tblstsdlyar SET status = 'A', uploadDate = '".date('Y-m-d')."', uploadArFile = '".$datefileMD.$datefileY."_".$todayTime.$SECONDS."', arBatch = '$arBatchNo' WHERE compCode = '{$valAr['compCode']}'";
					$sqlInsertAr = "INSERT INTO tblstsdlyarhist SELECT * FROM tblstsdlyar WHERE compCode = '{$valAr['compCode']}'";
					$sqlDelAr = "DELETE FROM tblstsdlyar WHERE compCode = '{$valAr['compCode']}'";
					
					if ($trans){
						$trans = $this->execQry($sqlUpdateAr);
					}
					if ($trans){
						$trans = $this->execQry($sqlInsertAr);
					}
					if ($trans){
						$trans = $this->execQry($sqlDelAr);
					}
				}
			}
			if(!$trans){
				$trans = $this->rollbackTran();
				return false;
			}else{
				$trans = $this->commitTran();
				return true;
			}
		}
		function getDistinctCompCodeInAP(){
			$sql = "SELECT DISTINCT compCode FROM tblstsdlyap order by compCode;";	
			return $this->getArrRes($this->execQry($sql));
		}
		
		function getDistinctCompCodeInAR(){
			$sql = "SELECT DISTINCT compCode FROM tblstsdlyar  order by compCode;";	
			return $this->getArrRes($this->execQry($sql));
		}
		
		function getAPARLastNo($field,$table,$compCode){
			$sql = "SELECT $field FROM $table WHERE stsComp = '$compCode'";
			$No = $this->getSqlAssoc($this->execQry($sql));
			return $No["$field"];
		}
		function getSubledger($compCode){
			 $sql = "SELECT  TOP 1   *
				FROM         tblARZCTL
				WHERE     CTLGLC = '$compCode'";	
			$No = $this->getSqlAssoc($this->execQry($sql));
			return $No["CTLHDO"].",".$No["CTLENT"];
		}
}
	
?>