<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");

class cancelObj extends commonObj {
	function getApprovedSTS($suppCode){
		$sql = "SELECT     tblStsHdr.stsRefno, tblStsHdr.suppCode, pg_pf.dbo.tblSuppliers.suppName, tblStsHdr.stsAmt, tblUsers.fullName, tblStsHdr.applyDate, tblStsHdr.dateEntered, 
                      tblUsers_1.fullName  as approvedBy
FROM         tblStsHdr INNER JOIN
                      pg_pf.dbo.tblSuppliers ON tblStsHdr.suppCode = pg_pf.dbo.tblSuppliers.suppCode INNER JOIN
                      tblUsers ON tblStsHdr.enteredBy = tblUsers.userId INNER JOIN
                      tblUsers tblUsers_1 ON tblStsHdr.approvedBy = tblUsers_1.userId
		WHERE  tblStsHdr.suppCode = '$suppCode' AND stsStat = 'R' $qry AND tblStsHdr.grpCode = tblUsers.grpCode";
		return $this->getArrRes($this->execQry($sql)); 
        // tblStsHdr.grpCode = '".$_SESSION['sts-grpCode']."' 
	}
	function getAprrovedSTSDtl($refNo){
		$sql = "SELECT   tblStsDtl.stsRefno,  tblStsDtl.stsNo, tblStsDtl.stsAmt, sql_mmpgtlib.dbo.APSUPP.ASNUM, sql_mmpgtlib.dbo.APSUPP.ASNAME, tblStsDtl.strCode, tblBranches.brnDesc, 
                      tblStsHdr.nbrApplication,  tblStsHdr.applyDate,
                          (SELECT     SUM(tblStsApply.stsapplyAmt)
                            FROM          tblStsApply
                            WHERE      status = 'A' AND tblStsApply.stsNo = tblStsDtl.stsNo) AS uploadedAmt,
							(SELECT     SUM(tblStsApply.stsapplyAmt)
                            FROM          tblStsApply
                            WHERE      status is null AND tblStsApply.stsNo = tblStsDtl.stsNo) AS onqueue
FROM         tblStsDtl INNER JOIN
                      tblStsHdr ON tblStsDtl.stsRefno = tblStsHdr.stsRefno INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM INNER JOIN
                      tblBranches ON tblStsDtl.strCode = tblBranches.strCode
					  WHERE tblStsDtl.stsRefno = $refNo AND tblStsDtl.dtlStatus = 'R'";	
		return $this->getArrRes($this->execQry($sql)); 
	}
	function getCancelDates($refNo){
		$sql = "SELECT Distinct stsApplyDate FROM tblStsApply where stsRefNo = '$refNo' and status IS NULL";
		return $this->getArrRes($this->execQry($sql));	
	}
	function getLastCancelledId(){
		$sql = "SELECT MAX(cancelId) as cancelId FROM tblcanceltype;";	
		$lastId = $this->getSqlAssoc($this->execQry($sql));
		return $lastId['cancelId'];
	}
	function getCancelledSTS($refNo){
		$sql = "SELECT * FROM tblCancelledSts WHERE stsRefno = '$refNo'";	
		return $this->getArrRes($this->execQry($sql));
	}
	
	function cancelSTSWHOLE($refNo,$reason,$cancelDate,$replacement){
		$cancelDate = date('m/d/Y');
		
		$trans = $this->beginTran();
		/*$sqlInsertReason = "INSERT INTO tblCancelType (cancelDesc, cancelStat, createdBy, dateAdded) 
			VALUES ('$reason', 'A', '".$_SESSION['sts-userId']."', '".date('m/d/Y',strtotime($cancelDate))."');";
		if($trans){
			$trans = $this->execQry($sqlInsertReason);	
		}
		if($trans){
			$lastId = $this->getLastCancelledId();
		}
		*/
			$sqlInsertCancelled = "INSERT INTO tblcancelledsts (stsNo, stsSeq, stsRefNo, compCode, strCode, suppCode, stsType, stsPaymentMode, stsDept, stsCls, stsSubCls, grpCode, stsApplyDate, stsStrAmt,cancelDate, cancelCode, cancelledBy, effectivityDate, replacementSts) 
		SELECT     tblStsDtl.stsNo, '0' AS seq, tblStsDtl.stsRefno, tblStsDtl.compCode, tblStsDtl.strCode, tblStsHdr.suppCode, tblStsHdr.stsType, tblStsHdr.stsPaymentMode, 
                      tblStsHdr.stsDept, tblStsHdr.stsCls, tblStsHdr.stsSubCls, tblStsHdr.grpCode, tblStsHdr.applyDate,tblStsDtl.stsAmt,  '".date('m/d/Y',strtotime($cancelDate))."', '".$reason."', '".$_SESSION['sts-userId']."', '".date('m/d/Y',strtotime($cancelDate))."', '".$replacement."'
FROM         tblStsDtl INNER JOIN
                      tblStsHdr ON tblStsDtl.stsRefno = tblStsHdr.stsRefno WHERE  tblStsDtl.stsRefno = '$refNo';";
		if($trans){
			$trans = $this->execQry($sqlInsertCancelled);	
		}
		
		$sqlUpdateSTSHdr = "UPDATE tblstshdr SET stsStat = 'C', cancelDate = '".date('m/d/Y')."', cancelledBy = '".$_SESSION['sts-userId']."', cancelId = '".$lastId."' WHERE stsRefNo = '$refNo'";
		if($trans){
			$trans = $this->execQry($sqlUpdateSTSHdr);	
		}
		$sqlUpdateSTSDtl = "UPDATE tblstsdtl SET dtlStatus = 'C' WHERE stsRefNo = '$refNo'";
		if($trans){
			$trans = $this->execQry($sqlUpdateSTSDtl);	
		}
		if(!$trans){
			$trans = $this->rollbackTran();
			return false;
		}else{
			$trans = $this->commitTran();
			return true;
		}	
	}
	function cancelSTSREOPEN($refNo,$reason,$cancelDate,$replacement){
		$cancelDate = date('m/d/Y');
		
		$trans = $this->beginTran();
		/*$sqlInsertReason = "INSERT INTO tblCancelType (cancelDesc, cancelStat, createdBy, dateAdded) 
			VALUES ('$reason', 'A', '".$_SESSION['sts-userId']."', '".date('m/d/Y',strtotime($cancelDate))."');";
		if($trans){
			$trans = $this->execQry($sqlInsertReason);	
		}
		if($trans){
			$lastId = $this->getLastCancelledId();
		}*/
		
		$sqlInsertCancelled = "INSERT INTO tblcancelledsts (stsNo, stsSeq, stsRefNo, compCode, strCode, suppCode, stsType, stsPaymentMode, stsDept, stsCls, stsSubCls, grpCode, stsApplyDate, stsStrAmt,cancelDate, cancelCode, cancelledBy, effectivityDate,replacementSts) 
		SELECT     tblStsDtl.stsNo, '0' AS seq, tblStsDtl.stsRefno, tblStsDtl.compCode, tblStsDtl.strCode, tblStsHdr.suppCode, tblStsHdr.stsType, tblStsHdr.stsPaymentMode, 
                      tblStsHdr.stsDept, tblStsHdr.stsCls, tblStsHdr.stsSubCls, tblStsHdr.grpCode, tblStsHdr.applyDate,tblStsDtl.stsAmt,  '".date('m/d/Y')."', '".$reason."', '".$_SESSION['sts-userId']."',  '".date('m/d/Y',strtotime($cancelDate))."', '".$replacement."'
FROM         tblStsDtl INNER JOIN
                      tblStsHdr ON tblStsDtl.stsRefno = tblStsHdr.stsRefno WHERE  tblStsDtl.stsRefno = '$refNo';";
		if($trans){
			$trans = $this->execQry($sqlInsertCancelled);	
		}
		
		$sqlUpdateSTSHdr = "UPDATE tblstshdr SET stsStat = 'O', approvedBy = NULL, dateApproved = NULL  WHERE stsRefNo = '$refNo'";
		if($trans){
			$trans = $this->execQry($sqlUpdateSTSHdr);	
		}
		$sqlUpdateSTSDtl = "UPDATE tblstsdtl SET dtlStatus = 'O', stsNo = NULL WHERE stsRefNo = '$refNo'";
		if($trans){
			$trans = $this->execQry($sqlUpdateSTSDtl);	
		}
		if(!$trans){
			$trans = $this->rollbackTran();
			return false;
		}else{
			$trans = $this->commitTran();
			return true;
		}	
	}
	function getCancelledSTSDtl($refNo,$stsNo){
		$sql = "SELECT * FROM tblCancelledSts WHERE stsRefno = '$refNo' and stsNo = '$stsNo'";	
		return $this->getArrRes($this->execQry($sql));
	}
	function getCancelReason(){
		$sql = "SELECT * FROM tblCancelType WHERE cancelStat = 'A'";	
		return $this->getArrRes($this->execQry($sql));
	}
	function ifStsRequired($id){
		$sql = "SELECT * FROM tblCancelType WHERE cancelStat = 'A' AND cancelId = '$id'";	
		$lastId = $this->getSqlAssoc($this->execQry($sql));
		return $lastId['refRequiredTag'];
	}
	function calculateUploadedAmt($stsNo,$compCode,$strCode,$seqNo){
		$sql = "SELECT SUM(stsApplyAmt) as stsApplyAmt FROM tblstsapply WHERE stsNo = '$stsNo' AND status = 'A' AND compCode = '$compCode' AND strCode='$strCode' AND stsSeq = '$seqNo'";	
		return $this->getSqlAssoc($this->execQry($sql));
	}
	function calculateQueuedAmt($stsNo,$compCode,$strCode,$seqNo){
		$sql = "SELECT SUM(stsApplyAmt) as stsApplyAmt FROM tblstsapply WHERE stsNo = '$stsNo' AND status IS NULL AND compCode = '$compCode' AND strCode = '$strCode' AND stsSeq = '$seqNo'";
		return $this->getSqlAssoc($this->execQry($sql));	
	}
	function cancelStsDtl($arr){
		$trans = $this->beginTran();
		for($i=0;$i<=(int)$arr['hdCtr2'];$i++) {
			if((int)$arr["switcher_".$i]==1){
				//$cancelDate = date('m/d/Y',strtotime($arr['cmbCancelDate']));
				$arr['cmbCancelDate']==''? $cancelDate = date('m/d/Y') : $cancelDate =date('m/d/Y',strtotime($arr['cmbCancelDate']));
				
				####### insert cancel Type
				/*$sqlInsertReason = "INSERT INTO tblCancelType (cancelDesc, cancelStat, createdBy, dateAdded) VALUES ('".$arr['txtReason']."', 'A', '".$_SESSION['sts-userId']."', '".date('m/d/Y',strtotime($cancelDate))."');";
				if($trans){
					$trans = $this->execQry($sqlInsertReason);	
				}
				if($trans){
					$lastId = $this->getLastCancelledId();
				}*/
				####### end cancel type
				####### back up cancelled STS
				$sqlInsertCancelled = "INSERT INTO tblcancelledsts (stsNo, stsSeq, stsRefNo, compCode, strCode, suppCode, stsType, stsPaymentMode, stsDept, stsCls, stsSubCls, grpCode, stsApplyDate,cancelledBy,cancelDate,cancelCode,effectivityDate,stsStrAmt,replacementSts) SELECT stsNo, stsSeq, stsRefNo, compCode, strCode, suppCode, stsType, stsPaymentMode, stsDept, stsCls, stsSubCls, grpCode, stsApplyDate,  '".$_SESSION['sts-userId']."', '".date('m/d/Y')."', '".$arr['txtReason']."','".$cancelDate."', stsApplyAmt, '".$arr['stsRefNo']."' FROM tblstsapply WHERE  stsRefno = '".$arr['hdnRefno']."' AND stsApplyDate >= '$cancelDate' AND stsNo = '".$arr['stsNo_'.$i]."';";
				if($trans){
					$trans = $this->execQry($sqlInsertCancelled);	
				}
				####### end of cancelled STS
				
				$arrCancelled = $this->getCancelledSTSDtl($arr['hdnRefno'],$arr['stsNo_'.$i]);
		
				foreach($arrCancelled as $val){
					
					$uploadAmt = $this->calculateUploadedAmt($val['stsNo'],$val['compCode'],$val['strCode'],$val['stsSeq']);
					$uploadAmt['stsApplyAmt']=='' ? $totUploadAmt = 'NULL' : $totUploadAmt = $uploadAmt['stsApplyAmt'];
					
					$qAmt = $this->calculateQueuedAmt($val['stsNo'],$val['compCode'],$val['strCode'],$val['stsSeq']);
					$qAmt['stsApplyAmt']=='' ? $totQAmt = 'NULL' : $totQAmt = $qAmt['stsApplyAmt'];
					
					$sqlUpdateCancelled = "UPDATE tblCancelledSts SET uploadedAmt = ".$totUploadAmt.", queueAmt = ".$totQAmt." WHERE stsNo = '{$val['stsNo']}' AND compCode = '".$val['compCode']."' AND strCode = '".$val['strCode']."' AND stsSeq = '{$val['stsSeq']}'\n;";
					
					if($trans){
						$trans = $this->execQry($sqlUpdateCancelled);
					}
				}
				$sqlDelStsApply = "DELETE FROM tblstsapply WHERE stsRefNo = '".$arr['hdnRefno']."' AND stsNo = '".$arr['stsNo_'.$i]."' AND stsApplyDate >= '$cancelDate' AND status is NULL;";
				if($trans){
					$trans = $this->execQry($sqlDelStsApply);	
				}
				
				
				$sqlUpdateSTSDtl = "UPDATE tblstsdtl SET dtlStatus = 'C' WHERE stsRefNo = '".$arr['hdnRefno']."' AND stsNo = '".$arr['stsNo_'.$i]."'";
					if($trans){
						$trans = $this->execQry($sqlUpdateSTSDtl);	
					}
				$sqlCountCancelled = "SELECT DISTINCT dtlStatus FROM tblStsDtl WHERE stsRefNo = '".$arr['hdnRefno']."'";
				if($this->getRecCount($this->execQry($sqlCountCancelled)) == 1){
					$sqlUpdateSTSHdr = "UPDATE tblstshdr SET stsStat = 'C', cancelDate = '".date('m/d/Y')."', cancelledBy = '".$_SESSION['sts-userId']."', cancelId = '".$lastId."' WHERE stsRefNo = '".$arr['hdnRefno']."'";
					if($trans){
						$trans = $this->execQry($sqlUpdateSTSHdr);	
					}
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
	
	function validateUser($username,$password){
		$pword = base64_encode($password);
		$sql = "SELECT * FROM tblUsers where isManager = 'Y' and userName = '$username' and userPass = '$pword'";
		return $this->getRecCount($this->execQry($sql));
	}
}
?>