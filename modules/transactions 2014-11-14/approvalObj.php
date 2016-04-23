<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class approvalObj extends commonObj {
	function getStsForApproval($suppCode){
		if($suppCode == ""){
			$qry = "";	
		}else{
			$qry = "AND tblStsHdr.suppCode = '$suppCode'";
		}
		/*$sql = "SELECT     tblStsHdr.stsRefno, tblStsHdr.suppCode, pg_pf.dbo.tblSuppliers.suppName, tblStsHdr.stsAmt, tblUsers.fullName, tblStsHdr.applyDate, tblStsHdr.dateEntered
FROM         tblStsHdr INNER JOIN
                      pg_pf.dbo.tblSuppliers ON tblStsHdr.suppCode = pg_pf.dbo.tblSuppliers.suppCode INNER JOIN
                      tblUsers ON tblStsHdr.enteredBy = tblUsers.userId
		WHERE tblStsHdr.suppCode = '$suppCode' AND tblStsHdr.grpCode = '".$_SESSION['sts-grpCode']."' AND stsStat = 'O'  AND stsAmt is not null";*/
		$sql = "SELECT     tblStsHdr.stsRefno, tblStsHdr.suppCode, sql_mmpgtlib.dbo.APSUPP.ASNAME as suppName, tblStsHdr.stsAmt, tblUsers.fullName, tblStsHdr.applyDate, tblStsHdr.dateEntered
FROM         tblStsHdr INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM INNER JOIN
                      tblUsers ON tblStsHdr.enteredBy = tblUsers.userId
		WHERE  stsStat = 'O' $qry AND tblStsHdr.grpCode = '".$_SESSION['sts-grpCode']."' ";
		return $this->getArrRes($this->execQry($sql)); 
	}
	function getSTSType($refNo){
		$sql = "SELECT stsType FROM tblStsHdr where stsRefno = '$refNo'";
		$type = $this->getSqlAssoc($this->execQry($sql));
		return $type['stsType'];
	}
	
	function approveSTS($arr){
		for($i=0;$i<=(int)$arr['hdCtr2'];$i++) {
			if($arr["switcher_".$i]=="1"){
				//$arr["hdnSuppCode_$i"].$arr["refNo_$i"];
				################UPDATE STS NO
				//echo $arr['refNo_'.$i].$i." mike";
				//echo $arr['refNo_$i']."mike";
				$stsType = $this->getSTSType($arr['refNo_'.$i]);
				$stsCount = $this->countSTSDetail($arr['refNo_'.$i]);
				
				if($stsCount > 0){
					$sqlGetSTSNo = "exec getLastSTSNo";
					$stsNo = $this->getSqlAssoc($this->execQry($sqlGetSTSNo));
					
					$trans = $this->beginTran();
					$a = $stsNo['stsNo']+$stsCount;
					$sqlUpdateSTSNo = "UPDATE pg_pf..tblStsNo SET stsNo = ".$a." ";
					if ($trans) {
						$trans = $this->execQry($sqlUpdateSTSNo);
					}
					if ($trans){
						$trans = $this->commitTran();
					}
					##############contract number
					if((int)$stsType==5){
						$trans3 = $this->beginTran();
						$sqlGetContractNo = "exec getLastContractNo";
						$contractNo = $this->getSqlAssoc($this->execQry($sqlGetContractNo));
						$newContract = $contractNo['lastContractNo'];
						$b = $newContract+$stsCount;
						$sqlUpdateContractNo = "UPDATE pg_pf..tblContractNo SET lastContractNo = '".$b."' ";
						if ($trans3) {
							$trans3 = $this->execQry($sqlUpdateContractNo);
						}
						if ($trans3){
							$trans3 = $this->commitTran();
						}
					}
					################ end of contract no
					
					
					############################END OF STS
					######################ASSIGNING OF STS NO
					$tempSTSNo = (int)$stsNo['stsNo'];
					$startingSTS = (int)$stsNo['stsNo']+1;
					$arrPar = $this->getParticipants($arr['refNo_'.$i]);
			
					$trans1 = $this->beginTran();
					foreach($arrPar as $val){
						$tempSTSNo++;
						
						$sqlDtl = "UPDATE tblStsDtl set stsNo = '$tempSTSNo', dtlStatus = 'R' WHERE stsRefno = '{$val['stsRefno']}' AND compCode = '{$val['compCode']}' AND strCode = '{$val['strCode']}';";
						if ($trans1) {
							$trans1 = $this->execQry($sqlDtl);
						}
						if((int)$stsType==5){
							$newContract++;
							$sqlDaDtl = "UPDATE tblStsDaDetail SET stsNo  = '$tempSTSNo', contractNo = '$newContract' WHERE stsRefno = '{$val['stsRefno']}' AND strCode =  '{$val['strCode']}'";
							if ($trans1) {
								$trans1 = $this->execQry($sqlDaDtl);
								
							}
						}
					}
					################ END OF STSNO
					
					############### UPDATE HEADER
					$sqlUpdateHeader = "UPDATE tblStsHdr SET stsStartNo = '$startingSTS', stsEndNo = '$tempSTSNo', approvedBy = '".$_SESSION['sts-userId']."', dateApproved = '".date('m/d/Y')."', stsStat = 'R' WHERE stsRefNo = '".$arr['refNo_'.$i]."';";
					if((int)$stsType==2){
						$this->generateListingBatch($arr['refNo_'.$i]);
					}
					if ($trans1){
						$trans1 = $this->execQry($sqlUpdateHeader);
					}
					if(!$trans1){
						$trans1 = $this->rollbackTran();
					}else{
						$trans1 = $this->commitTran();
						$noRefNo .= $arr['refNo_'.$i].",";
					}
				}else{
					$errorRefNo .= $arr['refNo_'.$i].",";
				}
				############### END OF UPDATE HEADER
			}
		}
		return $errorRefNo."|".$noRefNo;
	}
	function generateListingBatch($refNo){
		$getToExport = "SELECT     tblStsDtl.strCode, tblStsDtl.stsAmt, tblStsDtl.stsNo, tblStsHdr.stsRemarks, tblStsDtl.stsRefno
					FROM         tblStsDtl INNER JOIN
                      tblStsHdr ON tblStsDtl.stsRefno = tblStsHdr.stsRefno WHERE tblStsDtl.stsRefno = '$refNo'";
			$arrContent = $this->getArrRes($this->execQry($getToExport));
			
			$sqlGetLastBatch = "SELECT listFeeNo FROM tblListingFeeNo";
			$oldBatch = $this->getSqlAssoc($this->execQry($sqlGetLastBatch));
			$newBatch = (int)$oldBatch['listFeeNo']+1;//new voucher number
			
			$trans = $this->beginTran();
			
			$qryUpdateApNo = "UPDATE tblListingFeeNo SET listFeeNo = ".$newBatch." ";
			if ($trans){
				$trans = $this->execQry($qryUpdateApNo);
			}
			$listBatchNo = sprintf("%09s", $newBatch)."X";
			
			$gmt = time() + (8 * 60 * 60);
			$todayTime = date("His");
			$datefileMD = date("md");
			$datefileY = date("y");
			
			$fileLocMMSApEnt = "../../exportfiles/listingFee/".trim("STU").$datefileMD.$datefileY."_".$todayTime.$SECONDS.".CSV"; 
			$fileNameMMSApEnt=trim("STU").$datefileMD.$datefileY."_".$todayTime.$SECONDS.".CSV"; 
			
			foreach($arrContent as $val){
				$mmsApEntContent = $mmsApEntContent.
					$listBatchNo.",". ###STS Batch
					sprintf("%010s", $val['stsNo']).",". ###STS #
					$val['strCode'].",". ### STore Code
					"0,". ### Blank
					substr(str_replace(",","-",$val['stsRemarks']),0,30).",". ### Remarks
					$val['stsAmt']. ### Amount
					"\r\n";
				
			}
			$sqlReUpdateHeader = "UPDATE tblStsHdr SET stsRemarks = '".$listBatchNo." ".$val['stsRemarks']."' WHERE stsRefNo = '$refNo';";
			if ($trans){
				$trans = $this->execQry($sqlReUpdateHeader);
			}	
			if(!$trans){
				$trans = $this->rollbackTran();
			}else{
				$trans = $this->commitTran();
			}
			if (file_exists($fileNameMMSApEnt)) {
				unlink($fileLocMMSApEnt);
			} 
			$mmsApEntHandler = fopen($fileLocMMSApEnt, "x");
						
			fwrite($mmsApEntHandler, $mmsApEntContent);
			fclose($mmsApEntHandler);		
			
			$ftp_server = "192.168.200.100";  
			$ftp_user_name = "dtsuser"; 
			$ftp_user_pass = "dtsuser"; 
			$conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server");        
						
			$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die("You do not have access to this ftp server!");   
			
			$destination_file = "/pgtflr/APMFLR/Unprocess/".$fileNameMMSApEnt;
			$upload = ftp_put($conn_id, $destination_file, $fileLocMMSApEnt, FTP_BINARY);  // upload the file
			
			ftp_close($conn_id); 
			return true;	
	}
	function countSTSDetail($refNo){
		$sql = "SELECT * From tblStsDtl Where stsRefno = '$refNo'";
		return  $this->getRecCount($this->execQry($sql));
	}
	function getParticipants($refNo){
		$sql = "SELECT * FROM tblStsDtl WHERE stsRefno = '$refNo'";
		return $this->getArrRes($this->execQry($sql));
	}
}
?>