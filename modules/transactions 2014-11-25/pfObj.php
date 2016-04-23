<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class pfObj extends commonObj {
	
	function countRegSTS(){
		if ($_SESSION['sts-userLevel']!='1')
			$filter = " AND tblstshdr.grpEntered='{$_SESSION['sts-minCode']}' ";
			
		$sql = "Select count(stsRefNo) as count From tblStsHdr WHERE stsType = '3' $filter";	
		return $this->getSqlAssoc($this->execQry($sql));
	}
	
	function getPaginatedPFSTS($sidx,$sord,$start,$limit){	
		//$sql = "Select * From tblStsHdr ORDER BY $sidx $sord LIMIT $start , $limit";	
		if ($_SESSION['sts-userLevel']!='1')
			$filter = " AND tblstshdr.grpEntered='{$_SESSION['sts-minCode']}' ";
			
		$sql = "SELECT
			tblstshdr.stsRefNo,
			tblstshdr.suppCode,
			tblstshdr.stsDept,
			tblstshdr.stsRemarks,
			tblstshdr.stsDateEntered,
			tblstshdr.stsTag,
			CASE tblstshdr.stsStat
				WHEN 'O' THEN 'OPEN'
				WHEN 'C' THEN 'CANCELLED'
				WHEN 'R' THEN 'RELEASED'
			END AS stsStat,
			tblsuppliers.suppName,
			tblCompany.compShortName,
			tblstshdr.stsComp,
			tblstshdr.stsDate,
			(SELECT DISTINCT stsTransTypeName FROM tblststranstype WHERE stsTransTypeDept = tblstshdr.stsDept AND 	stsTransTypeLvl = 1) as dept
			FROM
			tblstshdr
			LEFT OUTER JOIN tblsuppliers ON tblstshdr.suppCode = tblsuppliers.suppCode
			LEFT OUTER JOIN tblcompany ON tblstshdr.stsComp = tblcompany.compCode
			WHERE stsType = '3' $filter
			ORDER BY $sidx $sord LIMIT $start , $limit
			";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function getPaginatedPFSTSComp($sidx,$sord,$start,$limit,$compCode){	
		if($compCode=='0'){
			$comp = "";	
		}else{
			$comp = "AND stsComp = '$compCode'";	
		}
		
		if ($_SESSION['sts-userLevel']!='1')
			$filter = " AND tblstshdr.grpEntered='{$_SESSION['sts-minCode']}' ";
			
		$sql = "SELECT
			tblstshdr.stsRefNo,
			tblstshdr.suppCode,
			tblstshdr.stsDept,
			tblstshdr.stsRemarks,
			tblstshdr.stsDateEntered,
			tblstshdr.stsTag,
			CASE tblstshdr.stsStat
				WHEN 'O' THEN 'OPEN'
				WHEN 'C' THEN 'CANCELLED'
				WHEN 'R' THEN 'RELEASED'
			END AS stsStat,
			tblsuppliers.suppName,
			tblCompany.compShortName,
			tblstshdr.stsComp,
			tblstshdr.stsDate,
			(SELECT DISTINCT stsTransTypeName FROM tblststranstype WHERE stsTransTypeDept = tblstshdr.stsDept AND 	stsTransTypeLvl = 1) as dept
			FROM
			tblstshdr
			LEFT OUTER JOIN tblsuppliers ON tblstshdr.suppCode = tblsuppliers.suppCode
			LEFT OUTER JOIN tblcompany ON tblstshdr.stsComp = tblcompany.compCode
			WHERE stsType = '3' $comp $filter
			ORDER BY $sidx $sord LIMIT $start , $limit
			";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function searchPFSTS($sidx,$sord,$start,$limit,$searchField,$searchString){
		
		if ($_SESSION['sts-userLevel']!='1')
			$filter = " AND tblstshdr.grpEntered='{$_SESSION['sts-minCode']}' ";
			
		$sql = "SELECT
			tblstshdr.stsRefNo,
			tblstshdr.suppCode,
			tblstshdr.stsDept,
			tblstshdr.stsRemarks,
			tblstshdr.stsDateEntered,
			tblstshdr.stsTag,
			CASE tblstshdr.stsStat
				WHEN 'O' THEN 'OPEN'
				WHEN 'C' THEN 'CANCELLED'
				WHEN 'R' THEN 'RELEASED'
			END AS stsStat,
			tblsuppliers.suppName,
			tblCompany.compShortName,
			tblstshdr.stsComp,
			tblstshdr.stsDate,
			(SELECT DISTINCT stsTransTypeName FROM tblststranstype WHERE stsTransTypeDept = tblstshdr.stsDept AND 	stsTransTypeLvl = 1) as dept
			FROM
			tblstshdr
			LEFT OUTER JOIN tblsuppliers ON tblstshdr.suppCode = tblsuppliers.suppCode
			LEFT OUTER JOIN tblcompany ON tblstshdr.stsComp = tblcompany.compCode
			WHERE $searchField = '$searchString' AND stsType = '3' $filter ORDER BY $sidx $sord LIMIT $start , $limit
			";
		//$sql = "Select * From tblStsHdr WHERE $searchField = $searchString ORDER BY $sidx $sord LIMIT $start , $limit";	
		return $this->getArrRes($this->execQry($sql));	
	}
	function getAllCompany(){
		$sql = "SELECT * FROM tblcompany where compStat is NULL or compStat = 'A'";	
		return $this->getArrRes($this->execQry($sql));	
	}
	function findSupplier($terms){
		$sql = "SELECT suppCode, suppName,suppCurr FROM tblSuppliers WHERE (suppCode like '%$terms%') or (suppName like '%$terms%') 	
				and (suppStat is null or suppStat <> 'D') LIMIT 10";
		return $this->getArrRes($this->execQry($sql));
	}
	function getAllDept(){
		$sql = "SELECT * FROM tblststranstype WHERE stsTransTypeLvl = 1";	
		return $this->getArrRes($this->execQry($sql));
	}
	function findClass($dept){
		$sql = "SELECT * FROM tblststranstype WHERE stsTransTypeLvl = 2 AND stsTransTypeDept = $dept";	
		return $this->getArrRes($this->execQry($sql));
	}
	function findSubClass($dept,$class){
		$sql = "SELECT * FROM tblststranstype WHERE stsTransTypeLvl = 3 AND stsTransTypeDept = $dept AND stsTransTypeClass = $class";	
		return $this->getArrRes($this->execQry($sql));
	}
	function saveHeader($arr){
		$strCode = '';
		$now = date('Y-m-d H:i:s');
		$sqlCount = "SELECT refNo FROM tblrefNo WHERE compCode = '{$arr['cmbCompCode']}'";
		$refNo = $this->getSqlAssoc($this->execQry($sqlCount));
		$tempRefNo = (int)$refNo['refNo']+1;
		$arr['txtTerms']==''? $terms = 'NULL': $terms = $arr['txtTerms'];
		$sqlInsert = "INSERT INTO tblStsHdr (stsRefNo, suppCode, stsDept, stsCls, stsSubCls, stsAmt, stsRemarks, 
			stsPaymentMode, stsTerms, nbrApplication, applyDate, stsEnteredBy, stsDateEntered, grpEntered, stsComp, stsStat, 
			stsType, suppCurr) 
			VALUES 
			('$tempRefNo', '{$arr['hdnSuppCode']}', '8', '0', '0', 
			'{$arr['txtSTSAmount']}', '{$arr['txtRemarks']}', '{$arr['cmbPayType']}', '$terms', 
			'{$arr['txtNoApplications']}', '{$arr['txtApDate']}','".$_SESSION['sts-userId']."', '".$now."', '".$_SESSION['sts-minCode']."', '{$arr['cmbCompCode']}', 'O', '3', '{$arr['cmbSuppCurr']}')";
		if((int)$arr['cmbCompCode']==1002)
			$strCode = '201';
		else
			$strCode = '202';
		$sqlInsertDetail = "INSERT INTO 
						tblstsdtl (
							stsRefNo, stsComp, stsStrCode, stsStrAmt, dtlStatus
							) 
						VALUES (
							$tempRefNo, '{$arr['cmbCompCode']}', '$strCode', '{$arr['txtSTSAmount']}', 'O'
							)";
		$sqlUpdateRefNo = "Update tblrefno set refNo = $tempRefNo WHERE compCode = '{$arr['cmbCompCode']}'";
		$trans = $this->beginTran();
		if ($trans) {
			$trans = $this->execQry($sqlInsert);
		}
		if($trans){
			$trans = $this->execQry($sqlInsertDetail );	
		}
		if ($trans) {
			$trans = $this->execQry($sqlUpdateRefNo);
		}
		if(!$trans){
			$trans = $this->rollbackTran();
			return false;
		} else{
			$trans = $this->commitTran();
			return true;
		}	
	}
	function getLastSTSInserted(){
		$sql = "SELECT *
			FROM `tblStsHdr`
			ORDER BY stsRefNo DESC
			LIMIT 1 ";
		return $this->getSqlAssoc($this->execQry($sql));
	}
	function getRegSTSInfoAssoc($refNo,$compCode){
		$sql = "SELECT * FROM tblStsHdr WHERE stsRefNo = '$refNo' AND stsComp = '$compCode'";	
		return $this->getSqlAssoc($this->execQry($sql));
	}
	function getRegSTSDetails($refNo,$compCode){
		$sql = "SELECT
			tblbranch.brnDesc,
			tblstsdtl.stsStrAmt,
			tblstsdtl.stsNo,
			CASE tblstsdtl.dtlStatus
				WHEN 'O' THEN 'OPEN'
				WHEN 'C' THEN 'CANCELLED'
				WHEN 'R' THEN 'RELEASED'
			END AS dtlStatus
			FROM
			tblstsdtl
			LEFT OUTER JOIN tblbranch ON tblstsdtl.stsStrCode = tblbranch.brnCode
			WHERE
			tblstsdtl.stsRefNo =  '$refNo' AND tblstsdtl.stsComp =  '$compCode'";	
		return $this->getArrRes($this->execQry($sql));
	}
	function findStore($compCode){
		$sql = "SELECT * from tblbranch where compCode = '$compCode'";	
		return $this->getArrRes($this->execQry($sql));
	}
	function getSTSInfo($refNo,$compCode){
		$sql = "SELECT * FROM tblstshdr WHERE stsRefNo = '$refNo' AND stsComp = '$compCode'";
		return $this->getSqlAssoc($this->execQry($sql));
	}
	function getFilteredBranches($compCode){
		$sql = "SELECT * from tblbranch where compCode = '$compCode'";	
		return $this->getArrRes($this->execQry($sql));
	}
	function AddSTSDtl($arr){
		$trans = $this->beginTran();
		for($i=0;$i<=(float)$arr['hdCtr'];$i++) {
			if($arr["txt_$i"]!=""){
				$sqlPar = "Insert Into tblstsdtl (stsRefNo, stsComp, stsStrCode, stsStrAmt, dtlStatus) 
				VALUES ('{$arr['hdDtl_refNo']}', '{$arr['hdCompCode']}','".$arr["ch_$i"]."','".$arr["txt_$i"]."','O');";
				if ($trans) {
					$trans = $this->execQry($sqlPar);
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
	function DeleteSTSDtl($refNo,$compCode){
		$sqlDel = "DELETE FROM tblstsdtl WHERE stsRefNo = '$refNo' AND stsComp = '$compCode'";
		$trans = $this->beginTran();
		if ($trans) {
			$trans = $this->execQry($sqlDel);
		}
		if(!$trans){
			$trans = $this->rollbackTran();
			return false;
		}else{
			$trans = $this->commitTran();
			return true;
		}
	}
	function updateHeader($arr){
		$sqlUpdate = "UPDATE tblStsHdr SET 
			suppCode = '{$arr['hdnSuppCode']}', 
			stsAmt = '{$arr['txtSTSAmount']}', 
			stsRemarks  = '{$arr['txtRemarks']}', 
			suppCurr  = '{$arr['cmbSuppCurr']}', 
			stsDept = '{$arr['cmbDept']}', 
			stsCls = '{$arr['cmbClass']}', 
			stsSubCls = '{$arr['cmbSubClass']}', 
			stsRemarks = '{$arr['txtRemarks']}', 
			stsPaymentMode = '{$arr['cmbPayType']}', 
			stsTerms = '{$arr['txtTerms']}', 
			nbrApplication = '{$arr['txtNoApplications']}',
			stsComp = '{$arr['cmbCompCode']}',
			applyDate = '{$arr['txtApDate']}'
			WHERE stsRefNo = '{$arr['refNo']}' AND stsComp = '{$arr['cmbCompCode']}'";
		
		if((int)$arr['cmbCompCode']==1002)
			$strCode = '201';
		else
			$strCode = '202';
			
		$sqlUpdateDtl = "UPDATE tblstsdtl SET
			stsStrAmt = '{$arr['txtSTSAmount']}'
			WHERE stsRefNo = '{$arr['refNo']}'
				AND stsComp = '{$arr['cmbCompCode']}'
				AND stsStrCode = '$strCode'
		";	
		$trans = $this->beginTran();
		if ($trans) {
			$trans = $this->execQry($sqlUpdate);
		}
		if ($trans) {
			$trans = $this->execQry($sqlUpdateDtl);
		}
		if(!$trans){
			$trans = $this->rollbackTran();
			return false;
		}else{
			$trans = $this->commitTran();
			return true;
		}
	}
	function DeleteSTS($refNo,$compCode){
		$delPar = "DELETE FROM tblstsdtl WHERE stsRefNo = '$refNo' AND stsComp = '$compCode'";
		$delSTS = "DELETE FROM tblstshdr WHERE stsRefNo = '$refNo' AND stsComp = '$compCode'";
		$trans = $this->beginTran();
		if ($trans) {
			$trans = $this->execQry($delPar);
		}
		if ($trans) {
			$trans = $this->execQry($delSTS);
		}
		if(!$trans){
			$trans = $this->rollbackTran();
			return false;
		}else{
			$trans = $this->commitTran();
			return true;
		}
	}
	function hasSTSDetail($refNo,$compCode){
		$sql = "SELECT * FROM tblstsdtl WHERE stsRefNo = '$refNo' AND stsComp = '$compCode'";	
		return $this->getRecCount($this->execQry($sql));
	}
	function releaseSTS($refNo,$compCode){
		$now = date('Y-m-d H:i:s');
		$sqlGetSTSNo = "SELECT stsNo FROM tblstsno WHERE compCode = '$compCode'";
		$stsNo = $this->getSqlAssoc($this->execQry($sqlGetSTSNo));
		
		$tempSTSNo = (int)$stsNo['stsNo'];
		$startingSTS = (int)$$stsNo['stsNo']+1;
		$arrPar = $this->getParticipants($refNo,$compCode);
		
		$trans = $this->beginTran();
		foreach($arrPar as $val){
			$tempSTSNo++;
			
			$sqlDtl = "UPDATE tblstsdtl set stsNo = '$tempSTSNo', dtlStatus = 'A' WHERE stsRefNo = '{$val['stsRefNo']}' AND stsComp = '{$val['stsComp']}' AND stsStrCode = '{$val['stsStrCode']}';";
			if ($trans) {
				$trans = $this->execQry($sqlDtl);
			}
		}
		$sqlUpdateSTSNo = "UPDATE tblstsno SET stsNo = '$tempSTSNo' WHERE compCode = '$compCode';";
		$sqlUpdateHeader = "UPDATE tblstshdr SET stsStartNo = '$tempSTSNo', stsEndNo = '$tempSTSNo', approvedBy = '".$_SESSION['sts-userId']."', dateApproved = '".date('Y-m-d')."', stsTag = 'Y', stsDate = '".date('Y-m-d')."', stsStat = 'R'
			WHERE stsComp = '$compCode' AND stsRefNo = '$refNo';";
		
		if ($trans){
			$trans = $this->execQry($sqlUpdateHeader);
		}
		if ($trans){
			$trans = $this->execQry($sqlUpdateSTSNo);
		}
		if(!$trans){
			$trans = $this->rollbackTran();
			return false;
		}else{
			$trans = $this->commitTran();
			return true;
		}	
	}
	function getParticipants($refNo,$compCode){
		$sql = "SELECT * FROM tblstsdtl WHERE stsRefNo = '$refNo' AND stsComp = '$compCode'";
		return $this->getArrRes($this->execQry($sql));
	}
	function distinctSuppCur(){
		$sql = "SELECT DISTINCT suppCurr FROM tblsuppliers";	
		return $this->getArrRes($this->execQry($sql));
	}
	function calculateUploadedAmt($refNo, $compCode){
		$sql = "SELECT SUM(stsApplyAmt) as stsApplyAmt FROM tblstsapply WHERE stsComp = '$compCode' AND stsRefNo = '$refNo' AND status = 'A'";	
		return $this->getSqlAssoc($this->execQry($sql));
	}
	function calculateQueuedAmt($refNo, $compCode){
		$sql = "SELECT SUM(stsApplyAmt) as stsApplyAmt FROM tblstsapply WHERE stsComp ='$compCode' AND stsRefNo = '$refNo' AND status IS NULL";
		return $this->getSqlAssoc($this->execQry($sql));	
	}
	
	function getLastCancelledId(){
		$sql = "SELECT MAX(cancelId) as cancelId FROM tblcanceltype;";	
		$lastId = $this->getSqlAssoc($this->execQry($sql));
		return $lastId['cancelId'];
	}
	
	function cancelSTS($refNo,$compCode,$reason){
		$trans = $this->beginTran();
		
		$sqlInsertReason = "INSERT INTO tblcanceltype (cancelId, cancelDesc, cancelStat, createdBy, dateAdded) 
			VALUES (NULL, '$reason', 'A', '".$_SESSION['sts-userId']."', '".date('Y-m-d')."');";
		if($trans){
			$trans = $this->execQry($sqlInsertReason);	
		}else{
			echo "sqlInsertReason";	
		}
		
		if($trans){
			$lastId = $this->getLastCancelledId();
		}else{
			echo "getLastCancelledId";	
		}
		
		$uploadAmt = $this->calculateUploadedAmt($refNo, $compCode);
		
		$sqlInsertCancelled = "INSERT INTO tblcancelledsts (stsNo, stsSeq, stsRefNo, stsComp, stsStrCode, suppCode, stsPaymentMode, stsDept, stsCls, stsSubCls, grpEntered, applyDate) 
		SELECT stsNo, stsSeq, stsRefNo, stsComp, stsStrCode, suppCode, stsPaymentMode, stsDept, stsCls, stsSubCls, grpEntered,  stsApplyDate FROM tblstsapply WHERE stsComp = '$compCode' AND stsRefNo = '$refNo';";
		if($trans){
			$trans = $this->execQry($sqlInsertCancelled);	
		}else{
			echo "sqlInsertCancelled";	
		}
		
		$strAmt = $this->getStrAmt($refNo,$compCode);
		$sqlUpdateCancelled = "UPDATE tblcancelledsts SET stsStrAmt='".$strAmt."',uploadedAmt = '".$uploadAmt['stsApplyAmt']."', cancelledBy = '".$_SESSION['sts-userId']."', cancelDate = '".date('Y-m-d')."', cancelId = '".$lastId."' WHERE stsComp = '$compCode' AND stsRefNo = '$refNo';";
		if($trans){
			$trans = $this->execQry($sqlUpdateCancelled);	
		}else{
			echo "sqlInsertCancelled";	
		}
		
		$sqlDelStsApply = "DELETE FROM tblstsapply WHERE stsRefNo = '$refNo' AND stsComp = '$compCode';";
		if($trans){
			$trans = $this->execQry($sqlDelStsApply);	
		}else{
			echo "sqlDelStatApply";
		}
		
		$sqlUpdateSTSHdr = "UPDATE tblstshdr SET stsStat = 'C' WHERE stsRefNo = '$refNo' AND stsComp = '$compCode'";
		if($trans){
			$trans = $this->execQry($sqlUpdateSTSHdr);	
		}else{
			echo "sqlUpdateSTSHdr";	
		}
		
		if(!$trans){
			$trans = $this->rollbackTran();
			return false;
		}else{
			$trans = $this->commitTran();
			return true;
		}	
	}
	function getStrAmt($refNo, $compCode){
		$sql = "SELECT stsStrAmt FROM tblstsdtl WHERE stsRefNo = '$refNo' AND stsComp = '$compCode'";	
		$amt = $this->getSqlAssoc($this->execQry($sql));
		return $amt['stsStrAmt'];
	}
}	
?>