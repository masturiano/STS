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
			
			$ctr2 = 0;
			
			
			$trans = $this->beginTran();
			
		
			###############AR
	
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
								
					$sqlAr = "SELECT     tblStsDlyArHist.stsNo, tblStsDlyArHist.stsSeq, tblStsDlyArHist.stsApplyDate, tblStsDlyArHist.stsActualDate, tblStsDlyArHist.suppCode, tblStsDlyArHist.stsApplyAmt, tblStsDlyArHist.compCode, 
                      tblStsHierarchy.glMajor, tblStsHierarchy.glMinor, [pg_pf].dbo.tblBranches.brnShortName, [pg_pf].dbo.tblBranches.businessLine, [pg_pf].dbo.tblBranches.compCodeHO, tblStsHdr.applyDate,  tblStsHierarchy.hierarchyDesc, tblStsDlyArHist.strCode, tblStsDlyArHist.stsType
FROM         tblStsDlyArHist LEFT JOIN
                      tblStsHdr ON tblStsDlyArHist.stsRefno = tblStsHdr.stsRefno INNER JOIN
                       tblStsHierarchy ON tblStsDlyArHist.stsDept = tblStsHierarchy.stsDept AND tblStsDlyArHist.stsCls = tblStsHierarchy.stsCls AND 
                      tblStsDlyArHist.stsSubCls = tblStsHierarchy.stsSubCls INNER JOIN
                      [pg_pf].dbo.tblBranches ON tblStsDlyArHist.strCode = [pg_pf].dbo.tblBranches.strCode
					WHERE     (tblStsDlyArHist.compCode = '700') AND (tblStsDlyArHist.uploadDate BETWEEN '2/1/2013' AND '2/5/2013')
					";
					
					/*if($valAr['compCode']==700){
						$fileFolder = "PGJR";
						$fileCode = "PJ";	
					}else{
						$fileFolder = "PPCI";
						$fileCode = "PG";
					}*/
					$gmt = time() + (8 * 60 * 60);
					$todayTime = date("His");
					$datefileMD = date("md");
					$datefileY = date("y");
					
					$fileFolder = "PGJR";
					$fileCode = "PJ";	
						
					$file_path="../../exportfiles/$fileFolder/AR/$fileCode".$datefileMD.$datefileY."_".$todayTime.".H01"; 
					$file_name2="$fileCode".$datefileMD.$datefileY."_".$todayTime.".H01";
					
					
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
						
						if($valCon['stsType']=='3'){
							$prefix = 'PF';
						}elseif($valCon['stsType']=='5'){
							$prefix = 'DA';
						}else{
							$prefix = 'STS';
						}
						$ctr++;
						
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
						$contents .= date("d-M-Y")."|";
						$contents .= $prefix.$valCon['stsNo']."-".$valCon['stsSeq']."|";
						$contents .= $prefix.$valCon['stsNo']."-".$valCon['stsSeq']."|";
						$contents .= "1|";
						$contents .= $valCon['stsApplyAmt']."|";
						$contents .= "|";
						$contents .= "PHP|";
						$contents .= "0|";
						$contents .= $file_name2."|\r\n";
						
						/*$mmsApEntContent = $mmsApEntContent.
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
							"\r\n"; 	###  Multiply Divide Flag*/
						
						##### Distribution SETS
						
						
						/*$mmsIDstContent = $mmsIDstContent.
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
							$totDetailAmt += $valCon['stsApplyAmt'];*/
					}
					############### APHEAD
					/*$mmsAPHeadContent = $mmsAPHeadContent.
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
						"\r\n";*/
					
					if (file_exists($file_path)) {
						unlink($file_path);
					} 
					$handle2 = fopen($file_path, "x");
						
					fwrite($handle2, $contents);
					fclose($handle2);
					unset($contents);
					
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
						
						ftp_close($conn_id); 
					}
					
					/*$sqlUpdateAr = "UPDATE tblStsDlyArHist  SET status = 'A', uploadArFile = '".$datefileMD.$datefileY."_".$todayTime.$SECONDS."', arBatch = '$arBatchNo' WHERE  tblStsDlyArHist.compCode = '700' AND tblStsDlyArHist.uploadDate >= '2/1/2013'";	
					if ($trans){
						$trans = $this->execQry($sqlUpdateAr);
					}*/
					
			
			if(!$trans){
				$trans = $this->rollbackTran();
				return false;
			}else{
				$trans = $this->commitTran();
				return true;
			}
		}
		function getDistinctCompCodeInAP(){
			$sql = "SELECT DISTINCT compCode FROM tblstsdlyap;";	
			return $this->getArrRes($this->execQry($sql));
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
			$sql = "SELECT  TOP 1   *
				FROM         sql_mmpgtlib.dbo.ARZCTL
				WHERE     (CTLCMP LIKE '%trade%') AND CTLGLC = '$compCode'";	
			$No = $this->getSqlAssoc($this->execQry($sql));
			return $No["CTLHDO"];
		}
}
	
?>