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
	function uploadToOracle(){		
			//$arrCompAp = $this->getDistinctCompCodeInAP();
			$ctr2 = 0;
			$trans = $this->beginTran();
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
					$apBatchNo = "STS".$tempApNo;
					$arBatchNo = $apBatchNo;
					
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
					$sql = "SELECT     tblStsDlyApHist.stsNo, tblStsDlyApHist.stsSeq, tblStsDlyApHist.stsApplyDate, tblStsDlyApHist.suppCode, tblStsDlyApHist.stsApplyAmt, tblStsDlyApHist.compCode, 
                      pg_pf.dbo.tblBranches.brnShortName, pg_pf.dbo.tblBranches.businessLine, pg_pf.dbo.tblBranches.compCode as compCodeHO, tblStsHdr.applyDate, tblStsDlyApHist.strCode, 
                      tblStsHierarchy.glMajor, tblStsHierarchy.glMinor, tblStsHierarchy.hierarchyDesc, tblStsDlyApHist.stsType
FROM         tblStsDlyApHist left outer JOIN
                      tblStsHdr ON tblStsDlyApHist.stsRefno = tblStsHdr.stsRefno INNER JOIN
                      pg_pf.dbo.tblBranches ON tblStsDlyApHist.strCode = pg_pf.dbo.tblBranches.strCode INNER JOIN
                      tblStsHierarchy ON tblStsDlyApHist.stsDept = tblStsHierarchy.stsDept AND tblStsDlyApHist.stsCls = tblStsHierarchy.stsCls AND 
                      tblStsDlyApHist.stsSubCls = tblStsHierarchy.stsSubCls
WHERE       (tblStsDlyApHist.stsNo IN
                          (SELECT     refNo
                            FROM          tempTbl)) AND (uploadDate = '2/4/2013') AND tblStsDlyApHist.compCode = '700'
					";
					
					
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
					$fileLocMMSAPIRDM = "../../exportfiles/mms/AP/".trim("ADM").$datefileMD.$datefileY."_".$todayTime.$SECONDS.".CSV"; 
					$fileNameMMSAPIRDM=trim("ADM").$datefileMD.$datefileY."_".$todayTime.$SECONDS.".CSV";
					
					
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
							$accountMajor = $valCon['compCode'].$valCon['stsGL'];
						}
						
						if($valCon['stsType']=='3'){
							$prefix = 'PF';
						}elseif($valCon['stsType']=='5'){
							$prefix = 'DA';
						}else{
							$prefix = 'ST';
						}
					
					if( $valCon['strCode']=='101' || $valCon['strCode']=='102' || $valCon['strCode']=='103' || $valCon['strCode']=='104' || $valCon['strCode']=='105') 
							$mmsCompCode = $valCon['strCode'];
						else{
							$mmsCompCode = 	$valCon['compCodeHO'];
						}	
						##### APIRD
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
							//"1," . /// up to december only
							//"13," . /// up to december only
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
					
					############### APHEAD
					$mmsAPHeadContent = $mmsAPHeadContent.
						$apBatchNo.",". ##batch
						"1,". ##type
						"AAAUSER,". ##user
						"STS WEB ".$apBatchNo.",". ## Remarks
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
						
				//	fwrite($handle2, $contents);
				//	fclose($handle2) ;
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
						$ftp_server = "192.168.200.100";  
						$ftp_user_name = "dtsuser"; 
						$ftp_user_pass = "dtsuser"; 
						$conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server");        
						
						$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) 
						or die("You do not have access to this ftp server!");   
						
						############### Oracle Text File Upload
						/*$destination_file = "/pgtflr/APMFLR/Unprocess/".$file_name2;
						$upload = ftp_put($conn_id, $destination_file, $file_path, FTP_BINARY); */
						
						$destination_file = "/pgtflr/APMFLR/Unprocess/".$fileNameMMSApEnt;
						//$upload = ftp_put($conn_id, $destination_file, $fileLocMMSApEnt, FTP_BINARY);  // upload the file
						
						$destination_file = "/pgtflr/APMFLR/Unprocess/".$fileNameMMSApIwt;
						//$upload = ftp_put($conn_id, $destination_file, $fileLocMMSApIwt, FTP_BINARY);  // upload the file		
						
						$destination_file = "/pgtflr/APMFLR/Unprocess/".$fileNameMMSAPIDST;
						//$upload = ftp_put($conn_id, $destination_file, $fileLocMMSAPIDST, FTP_BINARY);  // upload the file		
						
						$destination_file = "/pgtflr/APMFLR/Unprocess/".$fileNameMMSAPHEAD;
						//$upload = ftp_put($conn_id, $destination_file, $fileLocMMSAPHEAD, FTP_BINARY);  // upload the file		
						
							$destination_file = "/pgtflr/APMFLR/Unprocess/".$fileNameMMSAPIRDM;
						ftp_close($conn_id); 
					}
					$sqlUpdateAp = "UPDATE tblStsDlyApHist  SET status = 'A', uploadApFile = '".$datefileMD.$datefileY."_".$todayTime.$SECONDS."', apBatch = '$apBatchNo' WHERE   (tblStsDlyApHist.stsNo IN
                          (SELECT     refNo
                            FROM          tempTbl)) AND (uploadDate = '2/4/2013') AND  tblStsDlyApHist.compCode = '700'";	
					
					if ($trans){
						$trans = $this->execQry($sqlUpdateAp);
					}
					if(!$trans){
						$trans = $this->rollbackTran();
						return false;
					}else{
						$trans = $this->commitTran();
						return true;
					}
			//return $this->getArrRes($this->execQry($sql));
		}
		
		function getDistinctCompCodeInAR(){
			$sql = "SELECT DISTINCT compCode FROM tblstsdlyar;";	
			return $this->getArrRes($this->execQry($sql));
		}
		
		function getAPARLastNo($field,$table,$compCode){
			$sql = "SELECT $field FROM $table WHERE stsComp = '$compCode'";
			$No = $this->getSqlAssoc($this->execQry($sql));
			return $No["$field"];
		}
		function getSubledger($compCode){
			$sql = "SELECT  TOP 1   CTLENT, CTLCMP, CTLGLC
				FROM         sql_mmpgtlib.dbo.ARZCTL
				WHERE     (CTLCMP LIKE '%trade%') AND CTLGLC = '$compCode'";	
			$No = $this->getSqlAssoc($this->execQry($sql));
			return $No["CTLENT"];
		}
}
	
?>