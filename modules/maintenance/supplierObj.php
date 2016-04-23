<?

$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");

class supplierObj extends commonObj {
	
	function getHeaderInformation($refNo){
		$sql = "SELECT     tblStsHdr.stsRefno, tblStsHdr.stsAmt, tblStsHdr.stsRemarks, tblUsers.fullName, tblStsHdr.dateEntered, tblStsHdr.dateApproved, tblStsHdr.applyDate, 
                      tblStsHdr.nbrApplication, sql_mmpgtlib.dbo.APSUPP.ASNAME, tblStsHdr.suppCode, 
                      payMode = CASE tblStsHdr.stsPaymentMode WHEN 'C' THEN 'CHECK Payment ' ELSE ' Invoice Deduction ' END
FROM         tblStsHdr INNER JOIN
                      tblUsers ON tblStsHdr.enteredBy = tblUsers.userId INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM WHERE stsRefno = $refNo";	
		return $this->getSqlAssoc($this->execQry($sql));
	}
	
	function findSupplier(){
		$sql = "SELECT ASNUM, cast(ASNUM as nvarchar) +' - '+ ASNAME as suppCodeName FROM sql_mmpgtlib..apsupp WHERE ASNAME not like '%NTBU%'
		order by ASNAME";	
		return $this->getArrRes($this->execQry($sql));
	}
	
	function changeSupplier($arr){
		$trans = $this->beginTran();
		
		$sqlInsert = "INSERT INTO tblChangedSupp (stsRefNo, oldSuppCode, newSuppCode, reason, changedBy, dateChanged) VALUES ('{$arr['hdnRefNo']}', '{$arr['hdnOldSuppCode']}', '{$arr['cmbSupp']}', '".str_replace("'","",$arr['txtReason'])."', '".$_SESSION['sts-userId']."', '".date('m/d/Y')."')";	
		
		if ($trans){
			$trans = $this->execQry($sqlInsert);
		}
		
		$sqlUpdateHdr = "UPDATE tblStsHdr SET suppCode = '{$arr['cmbSupp']}' WHERE stsRefno = '{$arr['hdnRefNo']}'";
		if ($trans){
			$trans = $this->execQry($sqlUpdateHdr);
		}
		
		$sqlApply = "UPDATE tblStsApply SET suppCode = '{$arr['cmbSupp']}' WHERE stsRefno = '{$arr['hdnRefNo']}'";
		if ($trans){
			$trans = $this->execQry($sqlApply);
		}
		
		$sqlDlyAr = "UPDATE tblStsDlyAr SET suppCode = '{$arr['cmbSupp']}' WHERE stsRefno = '{$arr['hdnRefNo']}'";
		if ($trans){
			$trans = $this->execQry($sqlDlyAr);
		}
		
		$sqlDlyAp = "UPDATE tblStsDlyAp SET suppCode = '{$arr['cmbSupp']}' WHERE stsRefno = '{$arr['hdnRefNo']}'";
		if ($trans){
			$trans = $this->execQry($sqlDlyAp);
		}
		
		$sqlDlyArHist = "UPDATE tblStsDlyArHist SET suppCode = '{$arr['cmbSupp']}' WHERE stsRefno = '{$arr['hdnRefNo']}'";
		if ($trans){
			$trans = $this->execQry($sqlDlyArHist);
		}
		
		$sqlDlyApHist = "UPDATE tblStsDlyApHist SET suppCode = '{$arr['cmbSupp']}' WHERE stsRefno = '{$arr['hdnRefNo']}'";
		if ($trans){
			$trans = $this->execQry($sqlDlyApHist);
		}
		
		if(!$trans){
			$trans = $this->rollbackTran();
			return false;
		}else{
			$trans = $this->commitTran();
			return true;
		}	
	}
}
?>