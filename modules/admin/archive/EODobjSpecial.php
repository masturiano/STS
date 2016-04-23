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
	function getAllChecks(){
		$sql = "SELECT DISTINCT oracleCheck as checkNo
FROM         _unmatched_worksheetB_APIENT";	
		return $this->getArrRes($this->execQry($sql));
	}
	function uploadToOracle($checkNo){		
			
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
					$apBatchNo = "REC".$tempApNo;
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
					$sql = "SELECT     _unmatched_worksheetB_APIENT.*, _unmatched_worksheetB_APIWT.*, _unmatched_worksheetB_APADST.*
FROM         _unmatched_worksheetB_APIENT INNER JOIN
                      _unmatched_worksheetB_APIWT ON _unmatched_worksheetB_APIENT.AIEBCH = _unmatched_worksheetB_APIWT.WTEBCH AND _unmatched_worksheetB_APIENT.AICMP = _unmatched_worksheetB_APIWT.WTCMP AND 
                      _unmatched_worksheetB_APIENT.AITYPE = _unmatched_worksheetB_APIWT.WTTYPE AND _unmatched_worksheetB_APIENT.AINUM = _unmatched_worksheetB_APIWT.WTNUM AND 
                      _unmatched_worksheetB_APIENT.AIINV = _unmatched_worksheetB_APIWT.WTINV INNER JOIN
                      _unmatched_worksheetB_APADST ON _unmatched_worksheetB_APIWT.WTEBCH = _unmatched_worksheetB_APADST.AIEBCH AND _unmatched_worksheetB_APIWT.WTCMP = _unmatched_worksheetB_APADST.AICMP AND 
                      _unmatched_worksheetB_APIWT.WTTYPE = _unmatched_worksheetB_APADST.AITYPE AND _unmatched_worksheetB_APIWT.WTNUM = _unmatched_worksheetB_APADST.AINUM AND 
                      _unmatched_worksheetB_APIWT.WTINV = _unmatched_worksheetB_APADST.AIINV AND _unmatched_worksheetB_APIWT.WTSEQ = _unmatched_worksheetB_APADST.APSEQ
					   WHERE _unmatched_worksheetB_APIENT.checkNo = '$checkNo'";
					
					
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
					
						
						
						$mmsApEntContent = $mmsApEntContent.
							$apBatchNo.",". ###Entry Batch Number
							$valCon['AICMP'].",". ###Company Number
							$valCon['AITYPE'].",". ####Vendor Type 
							$valCon['AINUM'].",".  ###Vendor Number
							trim($valCon['AIINV'])."," .  ###Invoice Number
							(int)trim($valCon['AITRMS'])."," . ###  Terms Code
							"PHP,". ###Currency Code
							"1,". ###Curncy Exchange Rate
							"0,". ####Other Vendor
							$valCon['AIORIG']."," .  ###Original Amount
							$valCon['AIFRT'].",". ###Freight Amount
							"0,". ###Other Deductions
							"0," .  ##Disallowed Amount
							$valCon['AIAMT'].",". ###Invoice Amount
							$valCon['AIGSTP'].",". ###Tax Amount
							"0,". ###Discount Amount
							"0,". ###Tax Discount Amount
							$valCon['AINET']."," . ##netAmt
							"0,". ##disc %
							$valCon['AIHSHA']."," . ##hash amt
							"0,".  ##has qty
							"0,".  ##matched amt
							"0,".  ##matched qty
							$valCon['AITOCR'].",". ## credits
							"0,".  ##total retail
							$valCon['AITONT']."," . ## total netAmt
							"0,".  ##total retail
							"1,".  ##Invoice Received Century1 2000 0 1900
							$valCon['AIDTRC'].",".   ##Invoice Received Date
							"1,".  ## Invoice Date Century 1 2000 0 1900
							$valCon['AIDTIV'].",".   ##Invoice Date
							"1,".  ## Date to Pay Century
							$valCon['AIDTPY']."," . 	## Date to Pay
							"0," . 	###Discount Date Century
							"0," . 	###Discount Date
							"0," . 	### Warehouse Received ntury
							"0," . 	###Warehouse Received te
							$valCon['AICNPS']."," . 	### Posting Date Century
							"0," . 	### PPosting Date
							$valCon['AIPERN']."," . ##Posting Period
							$valCon['AIFYRN']."," .  ##Posting Fiscal Year
							"1," . 	###Posting Fiscal Century
							"0," . 	###EDI Transmission ID
							"0," . 	### EDI Transmission Century
							"0," . 	###EDI Transmission Date
							trim($valCon['AINOTE'])."," . ###Invoice Notes 
							"1," . 	###Clerk
							$valCon['AISTR'].",". 
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
							$valCon['WTCMP'].",". ####Company Number
							"1,". ###Vendor Type
							$valCon['WTNUM'].",". ####Vendor Number
							trim($valCon['WTINV']). "," .  ###Invoice Number
							$ctr. "," .  ####Sequence Number
							$valCon['WTCOD'].",".  ###Withholding tax code
							$valCon['WTCAMT']."," .  ### COST AMOUNT
							$valCon['WTVAMT']."," . #### Vat Amt
							$valCon['WVNET'].",". #### AMT W/O VAT
							$valCon['WTWAMT'].",". #### WHT AMT
							$valCon['WWNET']."," . ####AMT W/O WHT
							$valCon['WVAC1'].",". #### VAT MAJOR
							$valCon['WVAC2'].",".  #### VAT MINOR
							$valCon['WVAC3'].",".  #### VAT STORE
							$valCon['WWAC1'].",". #### WHT MAJOR
							$valCon['WWAC2'].",". #### WHT MINOR
							$valCon['WWAC3']."\r\n"; ####WHT STORE
							
							############# APIDST
							$mmsIDstContent = $mmsIDstContent.
								$apBatchNo.",".
								$valCon['AICMP'].",".
								"1,".
								$valCon['AINUM'].",". 
								trim($valCon['AIINV']). ",". 
								$ctr.",". 
								$valCon['APACMP'].",".
								$valCon['APAMAJ'].",". 
								$valCon['APAMIN'].",". 
								$valCon['APASTR'].",". 
								$valCon['APAAMT'].",". 
								"0,".
								"Y,".
								"N,".
								"N".
								"\r\n";
								
							$totDetailAmt += $valCon['APAAMT'];
					}
					
					############### APHEAD
					$mmsAPHeadContent = $mmsAPHeadContent.
						$apBatchNo.",". ##batch
						"1,". ##type
						"AAAUSR,". ##user
						"ORA RECON ".$checkNo.",". ## Remarks
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
						
						
						/*################# APIRDM
						if (file_exists($fileNameMMSAPIRDM)) {
							unlink($fileLocMMSAPIRDM);
						} 		
						$mmsAPIRDMHandler = fopen ($fileLocMMSAPIRDM, "a");
						fwrite($mmsAPIRDMHandler, $mmsAPIRDMContent);
						fclose($mmsAPIRDMHandler);*/
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
					
						ftp_close($conn_id); 
					}
					$sqlUpdateAp = "UPDATE _forRewriteAPIENT  SET apRef = '".$datefileMD.$datefileY."_".$todayTime.$SECONDS." - ".$apBatchNo."'  WHERE  _forRewriteAPIENT.checkNo = '$checkNo'";	
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