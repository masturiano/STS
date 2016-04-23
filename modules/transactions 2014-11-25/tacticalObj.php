<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class tactObj extends commonObj {
	
	function countRegSTS(){
		if ($_SESSION['sts-userLevel']!='1')
			$filter = " AND tblstshdr.grpCode='{$_SESSION['sts-grpCode']}' ";
			
		$sql = "Select count(stsRefno) as count From tblStsHdr WHERE stsType = '6' $filter";	
		return $this->getSqlAssoc($this->execQry($sql));
	}
	
	function getPaginatedDispSTS($sidx,$sord,$start,$limit){	
		//$sql = "Select * From tblStsHdr ORDER BY $sidx $sord LIMIT $start , $limit";	
		//if ($_SESSION['sts-userLevel']!='1')
		if($_SESSION['sts-username']!='mike')
			$filter = "AND tblstshdr.grpCode='{$_SESSION['sts-grpCode']}' AND tblstshdr.origStr='{$_SESSION['sts-strCode']}'";
		
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
			sql_mmpgtlib.dbo.APSUPP.ASNAME as suppName,
			tblstshdr.dateApproved,
			(SELECT DISTINCT hierarchyDesc FROM tblStsHierarchy WHERE stsDept = tblstshdr.stsDept AND 	levelCode = 1) as dept
			FROM
			  tblStsHdr INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM
			WHERE stsType = '6' AND stsReFNo not in (
				SELECT TOP $start stsRefNo
				FROM tblStsHdr
				WHERE stsType = '6' $filter
				ORDER BY $sidx $sord
			)
			$filter
			ORDER BY $sidx $sord
			";
		return $this->getArrRes($this->execQry($sql));
	}
	function getPaginatedDispSTSSearch($sidx,$sord,$start,$limit,$refNo){	
		//$sql = "Select * From tblStsHdr ORDER BY $sidx $sord LIMIT $start , $limit";	
		//if ($_SESSION['sts-userLevel']!='1')
		if($_SESSION['sts-username']!='mike')
			$filter = "AND tblstshdr.grpCode='{$_SESSION['sts-grpCode']}' AND tblstshdr.origStr='{$_SESSION['sts-strCode']}'";
		
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
			sql_mmpgtlib.dbo.APSUPP.ASNAME as suppName,
			tblstshdr.dateApproved,
			(SELECT DISTINCT hierarchyDesc FROM tblStsHierarchy WHERE stsDept = tblstshdr.stsDept AND 	levelCode = 1) as dept
			FROM
			  tblStsHdr INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM
			WHERE stsType = '6' AND stsRefNo = '$refNo' AND stsReFNo not in (
				SELECT TOP $start stsRefNo
				FROM tblStsHdr
				WHERE stsType = '6' AND stsRefNo = '$refNo' $filter
				ORDER BY $sidx $sord
			)
			$filter
			ORDER BY $sidx $sord
			";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function searchDispSTS($sidx,$sord,$start,$limit,$searchField,$searchString){
		
		//if ($_SESSION['sts-userLevel']!='1')
		if($_SESSION['sts-username']!='mike')
			$filter = "AND tblstshdr.grpCode='{$_SESSION['sts-grpCode']}' AND tblstshdr.origStr='{$_SESSION['sts-strCode']}'";
			
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
			sql_mmpgtlib.dbo.APSUPP.ASNAME as suppName,
			tblCompany.compShortName,
			tblstshdr.stsComp,
			tblstshdr.stsDate,
			(SELECT DISTINCT stsTransTypeName FROM tblststranstype WHERE stsTransTypeDept = tblstshdr.stsDept AND 	stsTransTypeLvl = 1) as dept
			FROM
			 tblStsHdr INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM
			LEFT OUTER JOIN tblcompany ON tblstshdr.stsComp = tblcompany.compCode
			WHERE $searchField = '$searchString' AND stsType = '6' $filter ORDER BY $sidx $sord LIMIT $start , $limit
			";
		//$sql = "Select * From tblStsHdr WHERE $searchField = $searchString ORDER BY $sidx $sord LIMIT $start , $limit";	
		return $this->getArrRes($this->execQry($sql));	
	}
	
	function findSupplier($terms){
		$sql = "SELECT TOP 10sql_mmpgtlib..APSUPP.ASNUM as suppCode, sql_mmpgtlib..APSUPP.ASNAME as suppName, sql_mmpgtlib..APADDR.AACONT as contactPerson 
		FROM sql_mmpgtlib..APSUPP 
		INNER JOIN sql_mmpgtlib..APADDR on sql_mmpgtlib..APSUPP.ASNUM  = sql_mmpgtlib..APADDR.AANUM
		WHERE (sql_mmpgtlib..APSUPP.ASNUM like '%$terms%') or (sql_mmpgtlib..APSUPP.ASNAME like '%$terms%') 	
				AND  sql_mmpgtlib..APSUPP.ASNAME not like '%NTBU%'";
		return $this->getArrRes($this->execQry($sql));
	}
	function getAllDept(){
		$sql = "SELECT * FROM tblStsHierarchy WHERE levelCode = 1 AND transType = 1";	
		return $this->getArrRes($this->execQry($sql));
	}
	function findClass($dept){
		$sql = "SELECT * FROM tblStsHierarchy WHERE levelCode = 2 AND stsDept = $dept  AND transType = 1";	
		return $this->getArrRes($this->execQry($sql));
	}
	function findSubClass($dept,$class){
		$sql = "SELECT * FROM tblStsHierarchy WHERE levelCode = 3 AND stsDept = $dept AND stsCls = $class  AND transType = 1";	
		return $this->getArrRes($this->execQry($sql));
	}
	function saveHeader($arr){
		$strCode = '';
		$now = date('m/d/Y H:i:s');
		$trans = $this->beginTran();
		$sqlCount = "SELECT refNo FROM tblrefNo";
		$refNo = $this->getSqlAssoc($this->execQry($sqlCount));
		$tempRefNo = (int)$refNo['refNo']+1;
		
		$sqlUpdateRefNo = "Update tblrefno set refNo = $tempRefNo";
		if ($trans) {
			$trans = $this->execQry($sqlUpdateRefNo);
		}
		//$arr['txtTerms']==''? $terms = 'NULL': $terms = $arr['txtTerms'];
		$sqlInsert = "INSERT INTO tblStsHdr (stsRefNo, suppCode, stsDept, stsCls, stsSubCls, stsAmt, stsRemarks, 
			stsPaymentMode, stsTerms, nbrApplication, applyDate, enteredBy, dateEntered, grpCode, stsStat, 
			stsType, contractTag, contactPerson, contactPersonPos, origStr, endDate, vatTag) 
			VALUES 
			('$tempRefNo', '{$arr['hdnSuppCode']}', '2', '2', '4', 
			'{$arr['txtSTSAmount']}', '{$arr['txtRemarks']}', '{$arr['cmbPayType']}', NULL,
			'{$arr['txtNoApplications']}', '{$arr['txtApDate']}','".$_SESSION['sts-userId']."', '".$now."', '".$_SESSION['sts-grpCode']."', 'O', '6', 'Y', '{$arr['txtRep']}', '{$arr['txtRepPos']}','".$_SESSION['sts-strCode']."', '{$arr['txtEndDate']}', '{$arr['vatTag']}')";
			
		if ($trans) {
			$trans = $this->execQry($sqlInsert);
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
		$sql = "SELECT TOP 1 stsRefNo
			FROM tblStsHdr
			ORDER BY stsRefNo DESC";
		return $this->getSqlAssoc($this->execQry($sql));
	}
	function getRegSTSInfoAssoc($refNo){
		$sql = "SELECT     tblStsHdr.stsRefno, tblStsHdr.suppCode, tblStsHdr.stsAmt, tblStsHdr.stsRemarks, tblStsHdr.stsPaymentMode, tblStsHdr.nbrApplication, tblStsHdr.applyDate, tblStsHdr.endDate, tblStsHdr.contactPerson, tblStsHdr.contactPersonPos,  tblStsHdr.stsDept, tblStsHdr.stsCls, tblStsHdr.stsSubCls, sql_mmpgtlib.dbo.APSUPP.ASNAME as suppName, tblStsHdr.vatTag
		FROM         tblStsHdr INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM WHERE tblStsHdr.stsRefNo = '$refNo'";	
		return $this->getSqlAssoc($this->execQry($sql));
	}
	function getRegSTSDetails($refNo){
		$sql = "SELECT     tblStsDtl.stsRefno, tblStsDtl.compCode, tblStsDtl.strCode, tblStsDtl.stsAmt, tblStsDtl.enhanceType, tblBranches.brnShortDesc, tblBranches.brnDesc
FROM         tblBranches INNER JOIN
                      tblStsDtl ON tblBranches.compCode = tblStsDtl.compCode AND tblBranches.strCode = tblStsDtl.strCode WHERE tblstsdtl.stsRefNo =  '$refNo'";	
		return $this->getArrRes($this->execQry($sql));
	}
	function findStore($compCode){
		$sql = "SELECT * from tblbranch where compCode = '$compCode'";	
		return $this->getArrRes($this->execQry($sql));
	}
	function getSTSInfo($refNo){
		$sql = "SELECT     *
				FROM         tblStsHdr WHERE tblStsHdr.stsRefNo = '$refNo'";
		return $this->getSqlAssoc($this->execQry($sql));
	}
	function getFilteredBranches($compCode,$refNo){
		
		if($compCode != 'undefined'){
			$filter = "AND compCode = '$compCode'";
		}
		if((int)$_SESSION['sts-strCode']!=901){
			$filter2 = "AND strCode = ".(int)$_SESSION['sts-strCode']."";
		}
			$sql = "SELECT     tblBranches.*,
                          (SELECT     stsAmt
                            FROM          tblstsdtl
                            WHERE      compCode = tblBranches.compCode AND strCode = tblBranches.strCode AND stsRefNo = $refNo) AS stsAmt
FROM         tblBranches WHERE compCode is not null $filter $filter2";	
		
		return $this->getArrRes($this->execQry($sql));
	}
	function getEnhancerBranches($refNo){
		$sql = "SELECT     tblStsDtl.stsRefno, tblBranches.brnShortDesc, tblBranches.brnDesc, tblBranches.strCode
FROM         tblStsDtl INNER JOIN
                      tblBranches ON tblStsDtl.compCode = tblBranches.compCode AND tblStsDtl.strCode = tblBranches.strCode WHERE tblStsDtl.stsRefno = '$refNo'";
		return $this->getArrRes($this->execQry($sql));
	}
	function getEnhancerBranchesWithBrand($refNo,$brandCode){
		$sql = "SELECT     tblStsDtl.strCode,
                          (SELECT     enhanceType
                            FROM          tblStsEnhanceDtl
                            WHERE      (brandCode = $brandCode) AND (stsRefno = $refNo) AND strCode = tblstsdtl.strCode) AS enhanceType, tblBranches.brnDesc, tblStsDtl.stsRefno
FROM         tblStsDtl INNER JOIN
                      tblBranches ON tblStsDtl.compCode = tblBranches.compCode AND tblStsDtl.strCode = tblBranches.strCode
WHERE     (tblStsDtl.stsRefno = $refNo)";
		return $this->getArrRes($this->execQry($sql));
	}
	function getBrandEnhancerBranches($refNo,$brandCode){
		
	}
	function AddSTSDtl($arr){
		
		$this->DeleteSTSDtl($arr['hdDtl_refNo']);
		$trans = $this->beginTran();
		
			for($i=0;$i<=(float)$arr['hdCtr'];$i++) {
				if($arr["txt_$i"]!=""){
					$sqlPar = "Insert Into tblStsDtl (stsRefno, compCode, strCode, stsAmt, dtlStatus) 
					VALUES ('{$arr['hdDtl_refNo']}', '".$arr["comp_$i"]."','".$arr["ch_$i"]."','".$arr["txt_$i"]."','O');";
					if ($trans) {
						$trans = $this->execQry($sqlPar);
					}
				}
			}
		$sqlDel = "DELETE FROM  tblStsEnhanceDtl WHERE strCode NOT IN (SELECT strCode FROM tblStsDtl WHERE stsRefNo = '{$arr['hdDtl_refNo']}') AND stsRefno = '{$arr['hdDtl_refNo']}'";
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
	function addEnhancerDtl($arr){
		$this->DeleteSTSEnhancerDtl($arr['hdDtl_refNo2'],$arr['cmbBrand']);
		$trans = $this->beginTran();
			for($i=0;$i<=(float)$arr['hdCtr2'];$i++) {
				if($arr["switcher_$i"]=="1"){
					$sqlPar = "Insert Into tblStsEnhanceDtl (stsRefNo, strCode, category, brandCode, brandRem, enhanceType, dtlStatus) 
					VALUES ('{$arr['hdDtl_refNo2']}', '".$arr["ch2_$i"]."', '".$arr['txtCat']."', '".$arr['cmbBrand']."','".$arr["txtBRemarks"]."','".$arr["cmbEnhancer_$i"]."','O');";
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
	function DeleteSTSDtl($refNo){
		
		
		$sqlDel = "DELETE FROM tblStsDtl WHERE stsRefNo = '$refNo'";
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
	function DeleteSTSEnhancerDtl($refNo,$brandCode){
		$sqlDel = "DELETE FROM tblStsEnhanceDtl WHERE stsRefno = '$refNo' AND brandCode = '$brandCode'";
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
	function getDetailAmt($refNo){
		$sql = "SELECT sum(stsAmt) as stsAmt from tblStsDtl where stsRefno = '$refNo'";
		$arr = $this->getSqlAssoc($this->execQry($sql));
		return $arr['stsAmt'];
	}
	function updateHeader($arr){
		$sqlUpdateHdr = "UPDATE tblStsHdr SET 
			suppCode = '{$arr['hdnSuppCode']}', 
			stsAmt = '{$arr['txtSTSAmount']}', 
			stsRemarks  = '{$arr['txtRemarks']}', 
			stsPaymentMode = '{$arr['cmbPayType']}', 
			nbrApplication = '{$arr['txtNoApplications']}',
			applyDate = '{$arr['txtApDate']}',
			endDate = '{$arr['txtEndDate']}',
			contactPerson = '{$arr['txtRep']}',
			contactPersonPos = '{$arr['txtRepPos']}',
			enteredBy = '".$_SESSION['sts-userId']."',
			vatTag = '{$arr['vatTag']}'
			WHERE stsRefNo = '{$arr['refNo']}'";
		
		/*$sqlUpdateEnhance = "UPDATE tblStsEnhanceDtl SET brandCode = '{$arr['cmbBrand']}', enhanceType = '{$arr['cmbEnhancer']}', brandRem = '{$arr['txtBRemarks']}'
		WHERE stsRefNo = '{$arr['refNo']}'";*/
		$trans = $this->beginTran();
		
		/*$count = $this->hasSTSDetail($arr['refNo']);
		if((int)$count>0){	
			$arrPar = $this->getParticipants($arr['refNo']);
			$noPar = $this->countParticipants($arr['refNo']);
			$allocStsAmt  = $stsAmount = (float)$arr['txtSTSAmount'];
			$accumulatedSTS = 0;
			$percent = round(100/$noPar,4);
			$ctr = 0;
				foreach($arrPar as $val) {
					$ctr++;
					$allocPerParticipant = 0;
					if ($ctr!=$noPar) {
						$allocPerParticipant = round($stsAmount * ((float)$percent/100),2);
					} else {
						$allocPerParticipant = $stsAmount - $accumulatedSTS;
					}
		
					$accumulatedSTS += $allocPerParticipant;	
					$sqlParSts = "update tblStsDtl set stsAmt = '$allocPerParticipant' where strCode='{$val['strCode']}' and compCode='{$val['compCode']}' and stsRefNo='{$val['stsRefno']}'\n";
					if ($trans) {
						$trans = $this->execQry($sqlParSts);
					}
				}
		}*/
		if ($trans) {
			$trans = $this->execQry($sqlUpdateHdr);
		}
		/*if($trans){
			$trans = $this->execQry($sqlUpdateEnhance);	
		}*/
		if(!$trans){
			$trans = $this->rollbackTran();
			return false;
		}else{
			$trans = $this->commitTran();
			return true;
		}
	}
	
	function DeleteSTS($refNo){
		$delPar = "DELETE FROM tblStsDtl WHERE stsRefno = '$refNo'";
		$delSTS = "DELETE FROM tblStsHdr WHERE stsRefno = '$refNo'";
		$delEnhanceDtl = "DELETE FROM tblStsEnhanceDtl WHERE stsRefno = '$refNo'";
		$trans = $this->beginTran();
		if ($trans) {
			$trans = $this->execQry($delPar);
		}
		if ($trans) {
			$trans = $this->execQry($delSTS);
		}
		if ($trans) {
			$trans = $this->execQry($delEnhanceDtl);
		}
		if(!$trans){
			$trans = $this->rollbackTran();
			return false;
		}else{
			$trans = $this->commitTran();
			return true;
		}
	}
	function hasSTSDetail($refNo){
		$sql = "SELECT * FROM tblStsDtl WHERE stsRefno = '$refNo'";	
		return $this->getRecCount($this->execQry($sql));
	}
	function hasEnhancer($refNo){
		$sql = "SELECT * FROM tblStsEnhanceDtl WHERE stsRefno = '$refNo'";	
		return $this->getRecCount($this->execQry($sql));
	}
	function releaseSTS($refNo){
		$now = date('Y-m-d H:i:s');
		$sqlGetSTSNo = "SELECT stsNo FROM pg_pf..tblStsNo";
		$stsNo = $this->getSqlAssoc($this->execQry($sqlGetSTSNo));
		$qryGetContractNo = "SELECT lastContractNo FROM pg_pf..tblContractNo";
		$contractNo = $this->getSqlAssoc($this->execQry($qryGetContractNo));
		$newContract = (int)$contractNo['lastContractNo']+1;
		$tempSTSNo = (int)$stsNo['stsNo'];
		$startingSTS = (int)$stsNo['stsNo']+1;
		$arrPar = $this->getParticipants($refNo);
		
		$trans = $this->beginTran();
		foreach($arrPar as $val){
			$tempSTSNo++;
			
			$sqlDtl = "UPDATE tblStsDtl set stsNo = '$tempSTSNo', dtlStatus = 'R' WHERE stsRefno = '{$val['stsRefno']}' AND compCode = '{$val['compCode']}' AND strCode = '{$val['strCode']}';";
			if ($trans) {
				$trans = $this->execQry($sqlDtl);
			}
		}
		$sqlUpdateSTSNo = "UPDATE pg_pf..tblStsNo SET stsNo = '$tempSTSNo';";
		$sqlUpdateContract = "UPDATE pg_pf..tblContractNo SET lastContractNo = '$newContract'";
		$sqlUpdateHeader = "UPDATE tblStsHdr SET stsStartNo = '$startingSTS', stsEndNo = '$tempSTSNo', approvedBy = '".$_SESSION['sts-userId']."', dateApproved = '".date('Y-m-d')."', stsStat = 'R', contractNo = '$newContract'
			WHERE stsRefNo = '$refNo';";
		$sqlUpdateEnhncer = "UPDATE tblStsEnhanceDtl SET dtlStatus = 'R' WHERE stsRefno = '$refNo'";
		
		if ($trans){
			$trans = $this->execQry($sqlUpdateHeader);
		}
		if ($trans){
			$trans = $this->execQry($sqlUpdateSTSNo);
		}
		if ($trans){
			$trans = $this->execQry($sqlUpdateContract);
		}
		if ($trans){
			$trans = $this->execQry($sqlUpdateEnhncer);
		}
		if(!$trans){
			$trans = $this->rollbackTran();
			return false;
		}else{
			$trans = $this->commitTran();
			return true;
		}	
	}
	function getParticipants($refNo){
		$sql = "SELECT * FROM tblStsDtl WHERE stsRefno = '$refNo'";
		return $this->getArrRes($this->execQry($sql));
	}
	function countParticipants($refNo){
		$sql = "SELECT * FROM tblStsDtl WHERE stsRefno = '$refNo'";
		return $this->getRecCount($this->execQry($sql));
	}
	function distinctSuppCur(){
		$sql = "SELECT DISTINCT suppCurr FROM tblsuppliers";	
		return $this->getArrRes($this->execQry($sql));
	}
	function calculateUploadedAmt($stsNo,$compCode,$strCode,$seqNo){
		$sql = "SELECT SUM(stsApplyAmt) as stsApplyAmt FROM tblstsapply WHERE stsNo = '$stsNo' AND status = 'A' AND compCode = '$compCode' AND strCode='$strCode' AND stsSeq = '$seqNo'";	
		return $this->getSqlAssoc($this->execQry($sql));
	}
	function calculateQueuedAmt($stsNo,$compCode,$strCode,$seqNo){
		$sql = "SELECT SUM(stsApplyAmt) as stsApplyAmt FROM tblstsapply WHERE stsNo = '$stsNo' AND status IS NULL AND compCode = '$compCode' AND strCode = '$strCode' AND stsSeq = '$seqNo'";
		return $this->getSqlAssoc($this->execQry($sql));	
	}
	function calculateUploadedAmtSum($refNo){
		$sql = "SELECT SUM(stsApplyAmt) as stsApplyAmt FROM tblstsapply WHERE stsRefno = '$refNo' AND status = 'A' ";	
		return $this->getSqlAssoc($this->execQry($sql));
	}
	function calculateQueuedAmtSum($refNo){
		$sql = "SELECT SUM(stsApplyAmt) as stsApplyAmt FROM tblstsapply WHERE stsRefno = '$refNo' AND status IS NULL";
		return $this->getSqlAssoc($this->execQry($sql));	
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
	function cancelSTS($refNo,$reason,$cancelDate){
		$trans = $this->beginTran();
		
		$sqlInsertReason = "INSERT INTO tblCancelType (cancelDesc, cancelStat, createdBy, dateAdded) 
			VALUES ('$reason', 'A', '".$_SESSION['sts-userId']."', '".date('m/d/Y',strtotime($cancelDate))."');";
		if($trans){
			$trans = $this->execQry($sqlInsertReason);	
		}
		
		if($trans){
			$lastId = $this->getLastCancelledId();
		}
		
		$sqlInsertCancelled = "INSERT INTO tblcancelledsts (stsNo, stsSeq, stsRefNo, compCode, strCode, suppCode, stsType, stsPaymentMode, stsDept, stsCls, stsSubCls, grpCode, stsApplyDate) 
		SELECT stsNo, stsSeq, stsRefNo, compCode, strCode, suppCode, stsType, stsPaymentMode, stsDept, stsCls, stsSubCls, grpCode, stsApplyDate FROM tblstsapply WHERE  stsRefno = '$refNo' AND stsApplyDate >= '$cancelDate';";
		if($trans){
			$trans = $this->execQry($sqlInsertCancelled);	
		}
		$arrCancelled = $this->getCancelledSTS($refNo);
		
		foreach($arrCancelled as $val){
			
			$uploadAmt = $this->calculateUploadedAmt($val['stsNo'],$val['compCode'],$val['strCode'],$val['stsSeq']);
			$uploadAmt['stsApplyAmt']=='' ? $totUploadAmt = 'NULL' : $totUploadAmt = $uploadAmt['stsApplyAmt'];
			
			$qAmt = $this->calculateQueuedAmt($val['stsNo'],$val['compCode'],$val['strCode'],$val['stsSeq']);
			$qAmt['stsApplyAmt']=='' ? $totQAmt = 'NULL' : $totQAmt = $qAmt['stsApplyAmt'];
			
			$strAmt = $this->getStrAmt($refNo,$val['compCode'],$val['strCode']);
			$sqlUpdateCancelled = "UPDATE tblCancelledSts SET stsStrAmt = ".$strAmt.", uploadedAmt = ".$totUploadAmt.", queueAmt = ".$totQAmt.", cancelledBy = '".$_SESSION['sts-userId']."', cancelDate = '".date('m/d/Y',strtotime($cancelDate))."', cancelCode = '".$lastId."' WHERE stsNo = '{$val['stsNo']}' AND compCode = '".$val['compCode']."' AND strCode = '".$val['strCode']."' AND stsSeq = '{$val['stsSeq']}'\n;";
			
			if($trans){
				$trans = $this->execQry($sqlUpdateCancelled);	
			}
		}
		
		$sqlDelStsApply = "DELETE FROM tblstsapply WHERE stsRefNo = '$refNo' AND stsApplyDate >= '$cancelDate';";
		if($trans){
			$trans = $this->execQry($sqlDelStsApply);	
		}
		
		$sqlUpdateSTSHdr = "UPDATE tblstshdr SET stsStat = 'C', cancelDate = '".date('m/d/Y',strtotime($cancelDate))."', cancelledBy = '".$_SESSION['sts-userId']."', cancelId = '".$lastId."' WHERE stsRefNo = '$refNo'";
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
	function getStrAmt($refNo,$compCode,$strCode){
		$sql = "SELECT stsAmt FROM tblstsdtl WHERE stsRefNo = '$refNo' AND compCode = '$compCode' AND strCode = '$strCode'";	
		$amt = $this->getSqlAssoc($this->execQry($sql));
		return $amt['stsAmt'];
	}
	function getAllBrand(){
		$sql = "SELECT * FROM tblBrand WHERE stat = 'A'";
		return $this->getArrRes($this->execQry($sql));
	}
	function getAllEnhancerType(){
		$sql = "SELECT * FROm tblEnhancerType WHERE stat = 'A'";	
		return $this->getArrRes($this->execQry($sql));
	}
	function PrintContract($refNo) {
		$count = $this->checkifPrinted($refNo);
		if ( (int)$count > 0) {
			$fields = "SET stsDatePrinted='".date('m/d/Y')."',stsPrintedBy='".$_SESSION['sts-userId']."'";
		} else {
			$fields = "SET stsDateReprinted='".date('m/d/Y')."',stsReprintedBy='".$_SESSION['sts-userId']."'";
		}
		$sqlPrintContract = "Update tblStsHdr $fields where stsRefNo='$refNo'";
		$Trns = $this->beginTran();
		if ($Trns) {
			$Trns = $this->execQry($sqlPrintContract);
		}
		if(!$Trns){
			$Trns = $this->rollbackTran();
			return false;
		} else{
			$Trns = $this->commitTran();
			return true;
		}	
	}
	function checkifPrinted($refNo) {
		$sql = "SELECT * FROM tblstshdr WHERE stsRefNo = '$refNo' AND stsPrintedBy IS NULL";
		return $this->getRecCount($this->execQry($sql));
	}
	function tagPrinted($refNo){
		$sqlUpdate = "UPDATE tblstshdr SET stsPrintedBy = '".$_SESSION['sts-userId']."', stsDatePrinted = '".date('Y-m-d H:i:s')."' WHERE stsRefno = '$refNo' ";	
		$trans = $this->beginTran();
		if ($trans){
			$trans = $this->execQry($sqlUpdate);
		}
		if(!$trans){
			$trans = $this->rollbackTran();
			return false;
		}else{
			$trans = $this->commitTran();
			return true;
		}	
	}
	function tagRePrinted($refNo){
		$sqlUpdate = "UPDATE tblstshdr SET stsReprintedBy = '".$_SESSION['sts-userId']."', stsDateReprinted = '".date('Y-m-d H:i:s')."' WHERE stsRefno = '$refNo'";	
		$trans = $this->beginTran();
		if ($trans){
			$trans = $this->execQry($sqlUpdate);
		}
		if(!$trans){
			$trans = $this->rollbackTran();
			return false;
		}else{
			$trans = $this->commitTran();
			return true;
		}	
	}
	function getContractInfo($refNo){
		$sql = "SELECT     tblStsHdr.stsRefno, tblStsHdr.dateApproved, tblStsHdr.contractNo, tblStsHdr.applyDate, tblStsHdr.nbrApplication, tblStsHdr.stsAmt, tblStsHdr.stsRemarks, DATEADD(month, 
                      tblStsHdr.nbrApplication - 1, tblStsHdr.applyDate) AS endDate, DATEADD(month, tblStsHdr.nbrApplication, tblStsHdr.applyDate) AS endDate2, tblUsers.fullName, 
                      tblStsHdr.contactPerson, tblStsHdr.contactPersonPos, sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppName, sql_mmpgtlib.dbo.APADDR.AAADD1 AS add1, 
                      sql_mmpgtlib.dbo.APADDR.AAADD2 AS add2, sql_mmpgtlib.dbo.APADDR.AAADD3 AS add3, 
                      payMode = CASE tblStsHdr.stsPaymentMode WHEN 'C' THEN 'CHECK Payment ' ELSE ' Invoice Deduction ' END
FROM         tblStsHdr INNER JOIN
                      tblUsers ON tblStsHdr.enteredBy = tblUsers.userId INNER JOIN
                      sql_mmpgtlib.dbo.APADDR ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APADDR.AANUM 
					  INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM 
					  WHERE tblStsHdr.stsRefNo = '$refNo'";
		return $this->getSqlAssoc($this->execQry($sql));
	}
	function getDistinctCompanies(){
		$sql = "SELECT compCode,compShort FROM tblCompany";	
		return $this->getArrRes($this->execQry($sql));
	}
	function getEnhancerDetails($refNo){
		$sql = "SELECT distinct tblBrand.stsBrandDesc, tblStsEnhanceDtl.brandRem, tblStsEnhanceDtl.category, tblStsEnhanceDtl.brandCode, tblStsEnhanceDtl.stsRefno
FROM         tblStsEnhanceDtl INNER JOIN
                      tblBrand ON tblStsEnhanceDtl.brandCode = tblBrand.stsBrand WHERE tblStsEnhanceDtl.stsRefno = '$refNo'";	
		return $this->getArrRes($this->execQry($sql));
	}
	function getEnhancerHeader($refNo,$brandCode){
		$sql = "SELECT distinct tblBrand.stsBrandDesc, tblStsEnhanceDtl.brandRem, tblStsEnhanceDtl.category, tblStsEnhanceDtl.brandCode, tblStsEnhanceDtl.stsRefno
FROM         tblStsEnhanceDtl INNER JOIN
                      tblBrand ON tblStsEnhanceDtl.brandCode = tblBrand.stsBrand WHERE tblStsEnhanceDtl.stsRefno = '$refNo' AND tblStsEnhanceDtl.brandCode = '$brandCode'";	
		return $this->getSqlAssoc($this->execQry($sql));
	}
	function brandExists($refNo,$brandCode){
		$sql = "SELECT * FROM tblStsEnhanceDtl WHERE stsRefno = '$refNo' AND brandCode = '$brandCode'";
		return $this->getRecCount($this->execQry($sql));
	}
	function getDistinctCategoryBrand($refNo){
		$sql = "SELECT DISTINCT tblStsEnhanceDtl.category, tblBrand.stsBrandDesc, tblStsEnhanceDtl.brandCode, tblStsEnhanceDtl.stsRefno
FROM         tblStsEnhanceDtl INNER JOIN
                      tblBrand ON tblStsEnhanceDtl.brandCode = tblBrand.stsBrand WHERE tblStsEnhanceDtl.stsRefno='$refNo'";	
		return $this->getArrRes($this->execQry($sql));
	}
	function getBrandParticipants($refNo,$brandCode){
		$sql = "SELECT     tblStsEnhanceDtl.category, tblBrand.stsBrandDesc, tblStsEnhanceDtl.brandCode, tblStsEnhanceDtl.stsRefno, tblEnhancerType.enhanceDesc, 
                      tblBranches.brnDesc
FROM         tblStsEnhanceDtl INNER JOIN
                      tblBrand ON tblStsEnhanceDtl.brandCode = tblBrand.stsBrand INNER JOIN
                      tblEnhancerType ON tblStsEnhanceDtl.enhanceType = tblEnhancerType.enhanceType INNER JOIN
                      tblBranches ON tblStsEnhanceDtl.strCode = tblBranches.strCode WHERE stsRefno = '$refNo' AND brandCode = '$brandCode'";
		return $this->getArrRes($this->execQry($sql));	
	}
	function getSTSPrint($refNo){
		$sql = "SELECT     tblStsHdr.stsRefno, tblUsers.fullName  as approvedBy, tblStsHdr.dateApproved, sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppName, tblStsHdr.stsType, 
                      tblUsers_1.fullName AS enteredBy, tblGroup.grpDesc, tblStsHdr.nbrApplication, tblStsHdr.applyDate,  paymode = CASE tblStsHdr.stsPaymentMode WHEN 'D' THEN 'Invoice Deduction' ELSE 'Collection' END, tblStsHdr.stsRemarks, DATEADD(month, tblStsHdr.nbrApplication - 1, tblStsHdr.applyDate) AS endDate,
                          (SELECT     tblStsHierarchy.hierarchyDesc AS hierarchyDesc
                            FROM          tblStsHierarchy
                            WHERE      ((tblStsHierarchy.levelCode = 1) AND (tblStsHierarchy.stsDept = tblstshdr.stsDept))) AS Dept,
                          (SELECT     tblStsHierarchy.hierarchyDesc AS hierarchyDesc
                            FROM          tblStsHierarchy
                            WHERE      ((tblStsHierarchy.levelCode = 2) AND (tblStsHierarchy.stsDept = tblstshdr.stsDept) AND (tblStsHierarchy.stsCls = tblstshdr.stsCls))) AS Class,
                          (SELECT     tblStsHierarchy.hierarchyDesc AS hierarchyDesc
                            FROM          tblStsHierarchy
                            WHERE      ((tblStsHierarchy.levelCode = 3) AND (tblStsHierarchy.stsDept = tblstshdr.stsDept) AND (tblStsHierarchy.stsCls = tblstshdr.stsCls) AND 
                                                   (tblStsHierarchy.stsSubCls = tblstshdr.stsSubCls))) AS SClass, tblStsHdr.stsAmt
FROM         tblStsHdr LEFT OUTER JOIN
                      tblUsers ON tblStsHdr.approvedBy = tblUsers.userId INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM INNER JOIN
                      tblUsers tblUsers_1 ON tblStsHdr.enteredBy = tblUsers_1.userId INNER JOIN
                      tblGroup ON tblUsers_1.grpCode = tblGroup.grpCode
					  WHERE tblStsHdr.stsRefno = '$refNo'";	
		return $this->getSqlAssoc($this->execQry($sql));
	}
	function getRegStsParticipants($refNo){
		$sql = "SELECT     tblStsDtl.stsRefno, tblStsDtl.compCode, tblStsDtl.strCode, tblStsDtl.stsAmt, tblStsDtl.stsNo, tblBranches.brnShortDesc
FROM         tblStsDtl INNER JOIN
                      tblBranches ON tblStsDtl.compCode = tblBranches.compCode AND tblStsDtl.strCode = tblBranches.strCode
					  WHERe stsRefno = '$refNo'";	
		return $this->getArrRes($this->execQry($sql));	
	}
	function getCancelDates($refNo){
		$sql = "SELECT Distinct stsApplyDate FROM tblStsApply where stsRefNo = '$refNo'";
		return $this->getArrRes($this->execQry($sql));	
	}
	function checkNoOfUnapproved(){
		$sql = "SELECT     stsRefno
			FROM         tblStsHdr
			WHERE     (dateApproved IS NULL) AND (enteredBy = ".$_SESSION['sts-userId'].")";	
		return $this->getRecCount($this->execQry($sql));
	}
	function getStsHdrAmt($refNo){
		$sql = "SELECT stsAmt from tblStsHdr Where stsRefno = $refNo";	
		$arr = $this->getSqlAssoc($this->execQry($sql));
		return $arr['stsAmt'];
	}
	function checkPayment($refNo){
		$sql = "SELECT stsPaymentMode from tblStsHdr where stsrefno = '$refNo'";
		$arr = $this->getSqlAssoc($this->execQry($sql));
		return $arr['stsPaymentMode'];	
	}
}	
?>
