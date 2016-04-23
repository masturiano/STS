<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");

class inquiriesObj extends commonObj {
	function checkStsRefNo($no){
		$sqlValidate = "SELECT stsRefNo FROM tblstshdr WHERE stsRefNo = '$no'";
		return $this->getRecCount($this->execQry($sqlValidate));
	}
	function getSTSHdr($no){
		/*$sql = "SELECT     tblStsHdr.stsRefno, tblStsHdr.suppCode,sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppName, tblStsHierarchy.hierarchyDesc, tblStsHdr.stsAmt, tblStsHdr.stsRemarks, tblStsHdr.nbrApplication, 
                      tblStsHdr.applyDate, tblUsers.fullName, tblStsHdr.dateApproved, tblStsHdr.dateEntered, tblStsHdr.stsStartNo, tblStsHdr.stsEndNo, DATEADD(month, 
                      tblStsHdr.nbrApplication - 1, tblStsHdr.applyDate) AS endDate, tblUsers_1.fullName AS approvedBy,tblStsHdr.dateEntered, tblStsHdr.cancelDate, tblStsHdr.stsStat, 
                      payMode = CASE tblStsHdr.stsPaymentMode WHEN 'C' THEN 'Collection/Check' ELSE 'Invoice Deduction' END, tblStsHdr.contractNo, tblStsHdr.stsType
FROM         tblStsHdr INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM  INNER JOIN
                      tblStsHierarchy ON tblStsHdr.stsDept = tblStsHierarchy.stsDept INNER JOIN
                      tblUsers ON tblStsHdr.enteredBy = tblUsers.userId left  JOIN
                      tblUsers tblUsers_1 ON tblStsHdr.approvedBy = tblUsers_1.userId 
		WHERE tblStsHdr.stsRefNo = '$no'
		";*/
		$sql = "SELECT 
				tblStsHdr.stsRefno, 
				tblStsHdr.suppCode,
				sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppName, 
				tblStsHierarchy.hierarchyDesc, 
				tblStsHdr.stsAmt, 
				tblStsHdr.stsRemarks, 
				tblStsHdr.nbrApplication, 
				tblStsHdr.applyDate, 
				tblUsers.fullName, 
				tblStsHdr.dateApproved, 
				tblStsHdr.dateEntered, 
				tblStsHdr.stsStartNo, 
				tblStsHdr.stsEndNo, 
				tblUsers_1.fullName AS approvedBy,
				tblStsHdr.dateEntered, 
				tblStsHdr.cancelDate, 
				tblStsHdr.stsStat, 
				tblStsHdr.endDate as endDateApp5,
				DATEADD(month, tblStsHdr.nbrApplication, tblStsHdr.applyDate) AS endDate, 
				payMode = CASE tblStsHdr.stsPaymentMode WHEN 'C' THEN 'Collection/Check' ELSE 'Invoice Deduction' END, tblStsHdr.contractNo, tblStsHdr.stsType
				FROM tblStsHdr INNER JOIN
				sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM  
				INNER JOIN tblStsHierarchy ON tblStsHdr.stsDept = tblStsHierarchy.stsDept 
				INNER JOIN tblUsers ON tblStsHdr.enteredBy = tblUsers.userId left JOIN
				tblUsers tblUsers_1 ON tblStsHdr.approvedBy = tblUsers_1.userId 
				WHERE tblStsHdr.stsRefNo = '$no'
				";
		
		return $this->getSqlAssoc($this->execQry($sql));	
	}
	function getCancelledDate($stsNo){
		$sql = "SELECT cancelDate from tblCancelledSts WHERE stsNo = '$stsNo'";	
		$dt = $this->getSqlAssoc($this->execQry($sql));	
		return $dt['cancelDate'];
	}
	function getSTSHdrContract($no){
		$sql = "SELECT     tblStsDtl.stsRefno, tblStsDtl.stsNo, tblStsHdr.dateApproved, tblStsHdr.applyDate, tblStsHdr.nbrApplication, tblStsHdr.suppCode, pg_pf.dbo.tblBranches.brnShortDesc, 
                      tblStsDtl.stsAmt, tblStsHierarchy.hierarchyDesc, tblStsHdr.stsRemarks, tblStsHdr.stsPaymentMode, tblUsers.fullName, tblStsDaDetail.contractNo, sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppName, tblUsers_1.fullName AS approvedBy, 
                      tblStsHdr.dateEntered, tblStsHdr.cancelDate, tblStsHdr.stsStat, tblStsDtl.strCode, 
					  tblStsHdr.endDate as endDateApp5,
					  DATEADD(month, tblStsHdr.nbrApplication, tblStsHdr.applyDate) AS endDate, 
					  payMode = CASE tblStsHdr.stsPaymentMode WHEN 'C' THEN 'Collection/Check' ELSE 'Invoice Deduction' END,  tblStsHdr.stsType
FROM         tblStsHdr INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM INNER JOIN
                      tblStsHierarchy ON tblStsHdr.stsDept = tblStsHierarchy.stsDept INNER JOIN
                      tblUsers ON tblStsHdr.enteredBy = tblUsers.userId INNER JOIN
                      tblStsDaDetail ON tblStsHdr.stsRefno = tblStsDaDetail.stsRefno INNER JOIN
                      tblStsDtl ON tblStsHdr.stsRefno = tblStsDtl.stsRefno INNER JOIN
                      tblUsers tblUsers_1 ON tblStsHdr.approvedBy = tblUsers_1.userId   INNER JOIN
                      pg_pf.dbo.tblBranches ON tblStsDtl.compCode = pg_pf.dbo.tblBranches.compCode AND tblStsDtl.strCode = pg_pf.dbo.tblBranches.strCode
		WHERE tblStsDaDetail.contractNo = '$no'
		";
		return $this->getSqlAssoc($this->execQry($sql));	
	}
	function checkStsNo($no){
		$sqlValidate = "SELECT tblstsdtl.stsNo FROM tblstshdr 
		INNER JOIN tblstsdtl ON tblstshdr.stsRefNo = tblstsdtl.stsRefNo
		WHERE tblstsdtl.stsNo = '$no'";
		return $this->getRecCount($this->execQry($sqlValidate));
	}
	function checkContractNo($no){
		$sql = "SELECT contractNo FROM tblStsDaDetail WHERE contractNo = '$no'";
		return $this->getRecCount($this->execQry($sql));
	}
	function getAmt($refNo, $stat, $toSearch){
		if($stat == 'Q'){
			$qry = 'AND stsActualDate is NULL';
		}else{
			$qry = 'AND stsActualDate is not NULL';
		}
		if($toSearch =='R'){
			$ref = "stsRefNo = '$refNo'";
		}else{
			$ref = "stsNo = '$refNo'";
		}
		$sql = "SELECT sum(stsApplyAmt) as amt FROM tblstsapply WHERE $ref $qry";	
		$amt =  $this->getSqlAssoc($this->execQry($sql));	
		return $amt['amt'];
	}
	function getContractAmt($contractNo, $stat){
		if($stat == 'Q'){
			$qry = "AND tblStsApply.stsActualDate is NULL";
		}else{
			$qry = "AND tblStsApply.stsActualDate is not NULL";
		}
		$sql = "SELECT     SUM(tblStsApply.stsApplyAmt) AS amt
FROM         tblStsApply LEFT OUTER JOIN
                      tblStsDaDetail ON tblStsApply.stsNo = tblStsDaDetail.stsNo
				WHERE tblStsDaDetail.contractNo = '$contractNo' $qry ";
		$amt =  $this->getSqlAssoc($this->execQry($sql));	
		return $amt['amt'];
	}
	function getSTSDet($no){
		$sql ="SELECT     tblStsDtl.stsRefno, tblStsDtl.stsNo, tblStsHdr.dateApproved, tblStsHdr.applyDate, tblStsHdr.nbrApplication, tblStsHdr.suppCode, pg_pf.dbo.tblBranches.brnShortDesc, 
                      tblStsDtl.stsAmt, tblStsHierarchy.hierarchyDesc, tblStsHdr.stsRemarks, tblStsHdr.stsPaymentMode, tblUsers.fullName, tblStsDaDetail.contractNo, sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppName, tblUsers_1.fullName AS approvedBy, 
                      tblStsHdr.dateEntered, tblStsHdr.cancelDate, tblStsDtl.dtlStatus, tblStsDtl.strCode, 
					  tblStsHdr.endDate as endDateApp5,
					  DATEADD(month, tblStsHdr.nbrApplication, tblStsHdr.applyDate) AS endDate, 
					  payMode = CASE tblStsHdr.stsPaymentMode WHEN 'C' THEN 'Collection/Check' ELSE 'Invoice Deduction' END,  			tblStsHdr.stsType
FROM         tblStsDtl INNER JOIN
                      tblStsHdr ON tblStsDtl.stsRefno = tblStsHdr.stsRefno LEFT OUTER JOIN
                      tblStsDaDetail ON tblStsDtl.stsRefno = tblStsDaDetail.stsRefno AND tblStsDtl.stsNo = tblStsDaDetail.stsNo INNER JOIN
                      pg_pf.dbo.tblBranches ON tblStsDtl.compCode = pg_pf.dbo.tblBranches.compCode AND tblStsDtl.strCode = pg_pf.dbo.tblBranches.strCode INNER JOIN
                      tblStsHierarchy ON tblStsHdr.stsDept = tblStsHierarchy.stsDept INNER JOIN
                      tblUsers ON tblStsHdr.enteredBy = tblUsers.userId INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM INNER JOIN
                      tblUsers tblUsers_1 ON tblStsHdr.approvedBy = tblUsers_1.userId WHERE tblStsDtl.stsNo = '$no' and tblStsHierarchy.levelCode = 1";	
		return $this->getSqlAssoc($this->execQry($sql));	
	}
	function getSTSDetailsRes($contNo){
		$sql = "SELECT     tblStsHdr.stsPaymentMode, tblStsDtl.stsNo, tblStsDtl.stsRefno, pg_pf.dbo.tblCompany.compShort, tblStsHdr.contractNo, tblStsHdr.applyDate, DATEADD(month, 
                      tblStsHdr.nbrApplication - 1, tblStsHdr.applyDate) AS endDate, tblStsHdr.dateEntered, tblStsHdr.nbrApplication, pg_pf.dbo.tblBranches.brnShortDesc, 
                      tblStsHdr.suppCode, sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppName, tblStsDtl.stsAmt, tblStsHdr.dateApproved, tblStsHdr.stsRemarks, tblUsers.fullName
FROM         tblStsDtl INNER JOIN
                      tblStsHdr ON tblStsDtl.stsRefno = tblStsHdr.stsRefno INNER JOIN
                      pg_pf.dbo.tblCompany ON tblStsDtl.compCode = pg_pf.dbo.tblCompany.compCode INNER JOIN
                      pg_pf.dbo.tblBranches ON tblStsDtl.strCode = pg_pf.dbo.tblBranches.strCode AND tblStsDtl.compCode = pg_pf.dbo.tblBranches.compCode 
					  INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM  INNER JOIN
                      tblUsers ON tblStsHdr.enteredBy = tblUsers.userId 
					  WHERE tblStsHdr.contractNo = '$contNo' ";	
		return $this->getArrRes($this->execQry($sql));
	}
	function getPaginatedSTS($sidx,$sord,$start,$limit){	
		//$sql = "Select * From tblStsHdr ORDER BY $sidx $sord LIMIT $start , $limit";	
		if ($_SESSION['sts-userLevel']!='1')
			$filter = "AND tblstshdr.grpEntered='{$_SESSION['sts-grpCode']}' ";
		
		$sql = "SELECT TOP  $limit
			tblstshdr.stsRefNo,
			tblstshdr.suppCode,
			tblstshdr.stsDept,
			tblstshdr.stsRemarks,
			tblstshdr.dateEntered,
			CASE tblstshdr.stsStat
				WHEN 'O' THEN 'OPEN'
				WHEN 'C' THEN 'CANCELLED'
				WHEN 'R' THEN 'RELEASED'
			END AS stsStat,
			 sql_mmpgtlib.dbo.APADDR.AANAME as suppName,
			tblstshdr.dateApproved,
			(SELECT DISTINCT hierarchyDesc FROM tblStsHierarchy WHERE stsDept = tblstshdr.stsDept AND 	levelCode = 1) as dept
			FROM
			  tblStsHdr INNER JOIN
                      sql_mmpgtlib.dbo.APADDR ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APADDR.AANUM
			WHERE stsReFNo not in (
				SELECT TOP $start stsRefNo
				FROM tblStsHdr
				WHERE stsRefNo <> 0 $filter
				ORDER BY $sidx $sord
			)
			$filter 
			ORDER BY $sidx $sord
			";
		return $this->getArrRes($this->execQry($sql));
	}
	function searchSTS($sidx,$sord,$start,$limit,$searchField,$searchString){
		
		if ($_SESSION['sts-userLevel']!='1')
			$filter = " AND tblstshdr.grpEntered='{$_SESSION['sts-grpCode']}' ";
			
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
			sql_mmpgtlib.dbo.APADDR.AANAME as suppName,
			tblCompany.compShortName,
			tblstshdr.stsComp,
			tblstshdr.stsDate,
			(SELECT DISTINCT stsTransTypeName FROM tblststranstype WHERE stsTransTypeDept = tblstshdr.stsDept AND 	stsTransTypeLvl = 1) as dept
			FROM
			 tblStsHdr INNER JOIN
                      sql_mmpgtlib.dbo.APADDR ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APADDR.AANUM
			LEFT OUTER JOIN tblcompany ON tblstshdr.stsComp = tblcompany.compCode
			WHERE $searchField = '$searchString' $filter ORDER BY $sidx $sord LIMIT $start , $limit
			";
		//$sql = "Select * From tblStsHdr WHERE $searchField = $searchString ORDER BY $sidx $sord LIMIT $start , $limit";	
		return $this->getArrRes($this->execQry($sql));	
	}
	function countRegSTS(){
		if ($_SESSION['sts-userLevel']!='1')
			$filter = "WHERE tblstshdr.grpEntered='{$_SESSION['sts-grpCode']}' ";			
		$sql = "Select count(stsRefno) as count From tblStsHdr $filter";	
		return $this->getSqlAssoc($this->execQry($sql));
	}
	function getSTSHdrDtl($refNo){
		$sql = "SELECT     tblStsHdr.stsRefno AS stsRefno, sql_mmpgtlib.dbo.APADDR.AANAME AS suppName, tblStsHdr.stsAmt, tblUsers.fullName, tblStsHdr.dateEntered, 
                      tblStsHdr.stsRemarks, payMode = CASE tblStsHdr.stsPaymentMode WHEN 'C' THEN 'Collection/Check' ELSE 'Invoice Deduction' END
FROM         tblStsHdr INNER JOIN
                      sql_mmpgtlib.dbo.APADDR ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APADDR.AANUM INNER JOIN
                      tblUsers ON tblStsHdr.enteredBy = tblUsers.userId WHERE tblStsHdr.stsRefno = '$refNo'";	
		return $this->getSqlAssoc($this->execQry($sql));
	}
	function getUploaded($refNo){
		$sql = "SELECT     tblStsApply.stsNo, tblStsApply.stsSeq, pg_pf.dbo.tblBranches.brnShortDesc, tblStsApply.stsApplyAmt
FROM         tblStsApply INNER JOIN
                      pg_pf.dbo.tblBranches ON tblStsApply.strCode = pg_pf.dbo.tblBranches.strCode WHERE status = 'A' AND stsRefno = '$refNo'";
		return $this->getArrRes($this->execQry($sql));	
	}
	function getOnqueue($refNo){
		$sql = "SELECT     tblStsApply.stsNo, tblStsApply.stsSeq, pg_pf.dbo.tblBranches.brnShortDesc, tblStsApply.stsApplyAmt
FROM         tblStsApply INNER JOIN
                      pg_pf.dbo.tblBranches ON tblStsApply.strCode = pg_pf.dbo.tblBranches.strCode WHERE status IS NULL AND stsRefno = '$refNo'";
		return $this->getArrRes($this->execQry($sql));	
	}
	
}
?>