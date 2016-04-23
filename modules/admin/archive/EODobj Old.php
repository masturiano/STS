<?

	$now = date('Y-m-d H:i:s');
	ini_set("date.timezone","Asia/Manila");
	
	class EODobj extends commonObj {

		function ExtractTblSTShdr() {
			
			$qryGetHdr = "SELECT
							tblstshdr.stsRefNo,
							tblstshdr.stsComp,
							tblstshdr.suppCode,
							tblstshdr.stsDept,
							tblstshdr.stsCls,
							tblstshdr.stsSubCls,
							tblstshdr.stsAmt,
							tblstshdr.stsPaymentMode,
							tblstshdr.stsTerms,
							tblstshdr.nbrApplication,
							tblstshdr.applyDate,
							tblstshdr.stsStartNo,
							tblstshdr.stsApplyTag,
							tblstshdr.grpEntered,
							tblstshdr.stsTag,
							tblstshdr.stsStat,
							tblstsdtl.stsStrCode,
							tblstsdtl.stsStrAmt,
							tblstsdtl.stsNo,
							tblstsdtl.dtlStatus
							FROM
							tblstshdr
							INNER JOIN tblstsdtl ON tblstshdr.stsRefNo = tblstsdtl.stsRefNo AND tblstshdr.stsComp = tblstsdtl.stsComp
							WHERE
								tblstshdr.stsApplyTag is null AND
								tblstshdr.stsTag = 'Y' AND 
								tblstshdr.stsStat = 'R'
								AND
								tblstshdr.applyDate <= '".date('Y-m-d')."'";
								
			$arrGetDtl = $this->getArrRes($this->execQry($qryGetHdr));
			
			$trans = $this->beginTran();
			//if(count($arrGetDtl)>0){
			foreach($arrGetDtl as $val){
				$seqNo = 0;
				$strBal = $val['stsStrAmt'];
				$applyDate = $val['applyDate'];
				$applyAmtW = $strBal / $val['nbrApplication'];
				$max = $val['nbrApplication'];
				for($a = 1; $a<=$max; $a++){
					$stsApplyAmt = 0;
					$seqNo++;
					
					if($strBal > $applyAmtW){
						$stsApplyAmt = $applyAmtW;	
					}else{
						$stsApplyAmt = $strBal;	
					}
					if($val['stsPaymentMode'] =='D'){
						$stsApplyAmt = $stsApplyAmt * -1;	
					}
					$sqlInsertApplySts = "INSERT INTO tblstsapply 
						(stsNo, stsSeq, stsRefNo, stsComp, stsStrCode, suppCode, stsDept, stsCls, stsSubCls, grpEntered, stsApplyAmt, stsApplyDate, stsPaymentMode) 
						VALUES ('{$val['stsNo']}', '$seqNo', '{$val['stsRefNo']}', '{$val['stsComp']}', '{$val['stsStrCode']}',
						'{$val['suppCode']}', '{$val['stsDept']}', '{$val['stsCls']}', '{$val['stsSubCls']}', 
						'{$val['grpEntered']}', '$stsApplyAmt', '$applyDate', '{$val['stsPaymentMode']}');";
						
					if ($trans) {
						$trans = $this->execQry($sqlInsertApplySts);
					}
					if($val['stsPaymentMode'] =='D'){
						$stsApplyAmt = $stsApplyAmt * -1;	
					}
					$strBal = $strBal - $stsApplyAmt;
					$applyDate = date('Y-m-d',strtotime(date("Y-m-d", strtotime($applyDate)) . " +1 month"));
				}
				$sqlHdr = "UPDATE tblstshdr SET stsApplyTag = 'Y', applyTagDate = '".date('Y-m-d')."' WHERE stsRefNo = '{$val['stsRefNo']}' AND stsComp = '{$val['stsComp']}'";
				if ($trans){
					$trans = $this->execQry($sqlHdr);
				}else{
					echo "sqlHdr";
				}
			}
			
		/*	if(!$trans){
				$trans = $this->rollbackTran();
				return false;
			}else{
				$trans = $this->commitTran();
				return true;
			}	
		}
		
		function extractTransacAPAR(){*/
			######Processing Part 2######
			$actualDate = date('Y-m-d');
			
			//$trans = $this->beginTran();
			
			$sqlInsertAp = "INSERT INTO 
				tblstsdlyap (stsNo, stsSeq, stsRefNo, stsComp, stsStrCode, suppCode, stsDept, stsCls, stsSubCls, 
					grpEntered, stsApplyAmt, stsApplyDate, stsActualDate, stsPaymentMode)
				SELECT stsNo, stsSeq, stsRefNo, stsComp, stsStrCode, suppCode, stsDept, stsCls, stsSubCls, 
					grpEntered, stsApplyAmt, stsApplyDate, stsActualDate, stsPaymentMode
					FROM tblstsapply WHERE stsApplyDate <= '$actualDate' AND stsPaymentMode = 'D' AND status IS NULL;";
			
			if ($trans){
				$trans = $this->execQry($sqlInsertAp);
			}else{
				echo "sqlInsertAp";	
			}
			$sqlInsertAR = "INSERT INTO 
				tblstsdlyar (stsNo, stsSeq, stsRefNo, stsComp, stsStrCode, suppCode, stsDept, stsCls, stsSubCls, 
					grpEntered, stsApplyAmt, stsApplyDate, stsActualDate, stsPaymentMode)
				SELECT stsNo, stsSeq, stsRefNo, stsComp, stsStrCode, suppCode, stsDept, stsCls, stsSubCls, 
					grpEntered, stsApplyAmt, stsApplyDate, stsActualDate, stsPaymentMode
					FROM tblstsapply WHERE stsApplyDate <= '$actualDate' AND stsPaymentMode = 'C'  AND status IS NULL;";
			if ($trans){
				$trans = $this->execQry($sqlInsertAR);
			}else{
				echo "sqlInsertAR";	
			}
			
			$sqlTagDaily = "UPDATE tblstsapply SET stsActualDate='$actualDate', status = 'A', uploadDate = '$actualDate' WHERE stsApplyDate <= '$actualDate'  AND status IS NULL;";
			
			if ($trans){
				$trans = $this->execQry($sqlTagDaily);
			}else{
				echo "sqlTagDaily";	
			}
			
			$arrCompAp = $this->getDistinctCompCodeInAP();
			
			if(count($arrCompAp)>0){	
				foreach($arrCompAp as $valAp){
					
					$refNo = $this->getAPARLastNo('lastApBatch','tblapbatch',$valAp['stsComp']);	
					$refNo++;
					
					$sqlUpdateDlyAP = "UPDATE tblstsdlyap SET uploadApRef ='$refNo' WHERE stsComp = '{$valAp['stsComp']}'";
					if ($trans){
						$trans = $this->execQry($sqlUpdateDlyAP);
					}else{
						echo "sqlUpdateDlyAP";	
					}
				
					$sqlUpdateApNo = "UPDATE tblapbatch SET lastApBatch = '$refNo' WHERE stsComp = '{$valAp['stsComp']}'";
					if ($trans){
						$trans = $this->execQry($sqlUpdateApNo);
					}else{
						echo "sqlUpdateApNo";	
					}
				}
			}
			$arrCompAr = $this->getDistinctCompCodeInAR();

			if(count($arrCompAr)>0){
				foreach($arrCompAr as $valAr){
					$refNo = $this->getAPARLastNo('lastArBatch','tblarbatch',$valAr['stsComp']);	
					$refNo++;
					$sqlUpdateDlyAR = "UPDATE tblstsdlyar SET uploadArRef = '$refNo' WHERE stsComp = '{$valAr['stsComp']}'";
					if ($trans){
						$trans = $this->execQry($sqlUpdateDlyAR);
					}else{
						echo "sqlUpdateDlyAR";	
					}
					$sqlUpdateArNo = "UPDATE tblarbatch SET lastArBatch = '$refNo' WHERE stsComp = '{$valAr['stsComp']}'";
					if ($trans){
						$trans = $this->execQry($sqlUpdateArNo);
					}else{
						echo "sqlUpdateArNo";	
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
		function uploadToOracle(){
			$gmt = time() + (8 * 60 * 60);
			$todayTime = date("His");
			$datefileMD = date("md");
			$datefileY = date("y");
			$ctr2 = 0;
			$arrCompAp = $this->getDistinctCompCodeInAP();
			$trans = $this->beginTran();
			if(count($arrCompAp)>0){
				foreach($arrCompAp as $valAp){
					$sql = "SELECT
					tblstshdr.stsAmt,
					tblstsdlyap.stsNo,
					tblstsdlyap.stsSeq,
					tblstsdlyap.stsRefNo,
					tblstsdlyap.stsComp,
					tblstsdlyap.stsStrCode,
					tblstsdlyap.suppCode,
					tblsuppliers.suppName,
					tblstsdlyap.stsApplyDate,
					tblstshdr.suppCurr,
					tblstsdlyap.stsApplyAmt,
					tblstsdlyap.stsDept,
					tblstshdr.applyDate,
					tblstshdr.stsType,
					tblststranstype.stsGL,
					(SELECT stsTransTypeName FROM tblststranstype WHERE stsTransTypeDept = tblstsdlyap.stsDept 
					AND stsTransTypeLvl = 1) as dept
					FROM
					tblstsdlyap
					INNER JOIN tblstshdr ON tblstsdlyap.stsRefNo = tblstshdr.stsRefNo AND tblstsdlyap.stsComp = tblstshdr.stsComp
					INNER JOIN tblsuppliers ON tblstsdlyap.suppCode = tblsuppliers.suppCode
					INNER JOIN tblststranstype ON tblstsdlyap.stsDept = tblststranstype.stsTransTypeDept AND tblstsdlyap.stsCls = 
					tblststranstype.stsTransTypeClass AND tblstsdlyap.stsSubCls = tblststranstype.stsTransTypeSClass
					WHERE tblstsdlyap.stsComp = '{$valAp['stsComp']}'
					";
					
					if($valAp['stsComp']==1002){
						$fileFolder = "subic";
						$fileCode = "DS";	
					}else{
						$fileFolder = "clark";
						$fileCode = "DC";
					}
					$file_path="../../exportfiles/$fileFolder/AP/$fileCode".$datefileMD.$datefileY."_".$todayTime.".401"; 
					$file_name2="$fileCode".$datefileMD.$datefileY."_".$todayTime.".401";
					if (file_exists($file_path)) {
						unlink($file_path);
					} 
					$handle2 = fopen($file_path, "x");
					
					$arrContent = $this->getArrRes($this->execQry($sql));
					
					foreach($arrContent as $valCon){
						$ctr++;
						$maj = substr($valCon['stsGL'],0,3);
						$min = substr($valCon['stsGL'],3,6);
						if((int)$valCon['stsType'] == 1){
							if(($maj >= 700 && $maj <= 999)&&($min >= 101 && $min <=123)){
								$department = $min;
								$accountMajor = "101".$maj."000";	
							}else{
								$department = "0";
								$accountMajor = "101".$maj.$min;
							}
						}else{
							$department = "0";
							$accountMajor = "101".$valCon['stsGL'];
						}
						$contents .= "STS".$valCon['stsNo']."-".$valCon['stsSeq']."|";
						$contents .= "DEBIT|";
						$contents .= date("d-M-Y",strtotime($valCon['applyDate']))."|";
						$contents .= $valCon['suppCode']."|";
						$contents .= $fileCode."|";
						$contents .= $valCon['stsApplyAmt']."|";
						$contents .= $valCon['dept']."|";
						$contents .= date("d-M-Y",strtotime($valCon['stsApplyDate']))."|";
						$contents .= date("d-M-Y",strtotime($valCon['stsApplyDate']))."|";
						$contents .= date("d-M-Y",strtotime($valCon['stsApplyDate']))."|";
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
						$contents .= $valAp['stsComp']."|";
						$contents .= $fileCode."|";
						$contents .= "007|";
						$contents .= $department."|"; ##department
						$contents .= "0|"; ##section
						$contents .= $accountMajor."|"; ##major
						$contents .= $accountMajor."|"; ##minor
						$contents .= $valCon['stsApplyAmt']."|"; 
						$contents .= "XX";
						$contents .= "|";
						$contents .= "|";
						$contents .= "|";
						$contents .= "|";
						$contents .= $valCon['suppCurr']."|";
						$contents .= date("d-M-Y",strtotime($valCon['applyDate']))."|";
						$contents .= "|";
						$contents .= $file_name2."|\r\n";
					}
					fwrite($handle2, $contents);
					fclose($handle2);
					unset($contents);
					if ($ctr>0) {
						$ftp_server = "192.168.200.100";  
						$ftp_user_name = "dtsuser"; 
						$ftp_user_pass = "dtsuser"; 
						$conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server");        
						
						$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) 
						or die("You do not have access to this ftp server!");   
						
						$destination_file = $file_name2;
						$upload = ftp_put($conn_id, $destination_file, $file_path, FTP_BINARY); 
						ftp_close($conn_id); 
					}
					
					$sqlUpdateAp = "UPDATE tblstsdlyap SET dlyStatus = 'A', uploadDate = '".date('Y-m-d')."', uploadApFile = '$file_name2' WHERE stsComp = '{$valAp['stsComp']}'";
					$sqlInsertAp = "INSERT INTO tblstsdlyaphist SELECT * FROM tblstsdlyap WHERE stsComp = '{$valAp['stsComp']}'";
					$sqlDelAp = "DELETE FROM tblstsdlyap WHERE stsComp = '{$valAp['stsComp']}'";
					
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
			$arrCompAr = $this->getDistinctCompCodeInAR();
			
			if(count($arrCompAr)>0){
				foreach($arrCompAr as $valAr){
					$sqlAr = "SELECT
					tblstsdlyar.stsNo,
					tblstsdlyar.stsSeq,
					tblstsdlyar.stsComp,
					tblststranstype.stsGL,
					tblstsdlyar.stsApplyDate,
					tblstsdlyar.stsApplyAmt,
					tblstshdr.suppCurr,
					tblstsdlyar.suppCode
					FROM
					tblstsdlyar
					INNER JOIN tblstshdr ON tblstsdlyar.stsRefNo = tblstshdr.stsRefNo AND tblstsdlyar.stsComp = tblstshdr.stsComp
					INNER JOIN tblststranstype ON tblstsdlyar.stsDept = tblststranstype.stsTransTypeDept 
					AND tblstsdlyar.stsCls = tblststranstype.stsTransTypeClass 
					AND tblstsdlyar.stsSubCls = tblststranstype.stsTransTypeSClass
					WHERE tblstsdlyar.stsComp = '{$valAr['stsComp']}'
					";
					if($valAp['stsComp']==1002){
						$fileFolder = "subic";
						$fileCode = "DS";	
					}else{
						$fileFolder = "clark";
						$fileCode = "DC";
					}
					$file_path="../../exportfiles/$fileFolder/AR/$fileCode".$datefileMD.$datefileY."_".$todayTime.".H01"; 
					$file_name2="$fileCode".$datefileMD.$datefileY."_".$todayTime.".H01";
					if (file_exists($file_path)) {
						unlink($file_path);
					} 
					$handle2 = fopen($file_path, "x");
					
					$arrContent = $this->getArrRes($this->execQry($sqlAr));
					foreach($arrContent as $valCon){
						$ctr++;
						$contents .= "STS".$valCon['stsNo']."-".$valCon['stsSeq']."|";
						$contents .= date("d-M-Y",strtotime($valCon['stsApplyDate']))."|";
						$contents .= "STS".$valCon['stsGL']."|";
						$contents .= $fileCode."|";
						$contents .= $valCon['stsComp'].$valCon['suppCode']."|";
						$contents .= $fileCode."|";
						$contents .= "1|";
						$contents .= "007|";
						$contents .= "STS|";
						$contents .= date("d-M-Y",strtotime($valCon['stsApplyDate']))."|";
						$contents .= "STS".$valCon['stsNo']."-".$valCon['stsSeq']."|";
						$contents .= "STS".$valCon['stsNo']."-".$valCon['stsSeq']."|";
						$contents .= "1|";
						$contents .= $valCon['stsApplyAmt']."|";
						$contents .= "|";
						$contents .= $valCon['suppCurr']."|";
						$contents .= "0|";
						$contents .= $file_name2."|\r\n";
					}
					fwrite($handle2, $contents);
					fclose($handle2);
					unset($contents);
					if ($ctr>0) {
						$ftp_server = "192.168.200.100";  
						$ftp_user_name = "dtsuser"; 
						$ftp_user_pass = "dtsuser"; 
						$conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server");        
						
						$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) 
						or die("You do not have access to this ftp server!");   
						
						$destination_file = $file_name2;
						$upload = ftp_put($conn_id, $destination_file, $file_path, FTP_BINARY); 
						ftp_close($conn_id); 
					}
					$sqlUpdateAr = "UPDATE tblstsdlyar SET dlyStatus = 'A', uploadDate = '".date('Y-m-d')."', uploadArFile = '$file_name2' WHERE stsComp = '{$valAr['stsComp']}'";
					$sqlInsertAr = "INSERT INTO tblstsdlyarhist SELECT * FROM tblstsdlyar WHERE stsComp = '{$valAr['stsComp']}'";
					$sqlDelAr = "DELETE FROM tblstsdlyar WHERE stsComp = '{$valAr['stsComp']}'";
					
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
			$sql = "SELECT DISTINCT stsComp FROM tblstsdlyap;";	
			return $this->getArrRes($this->execQry($sql));
		}
		function getDistinctCompCodeInAR(){
			$sql = "SELECT DISTINCT stsComp FROM tblstsdlyar;";	
			return $this->getArrRes($this->execQry($sql));
		}
		function getAPARLastNo($field,$table,$compCode){
			$sql = "SELECT $field FROM $table WHERE stsComp = '$compCode'";
			$No = $this->getSqlAssoc($this->execQry($sql));
			return $No["$field"];
		}
	}
?>