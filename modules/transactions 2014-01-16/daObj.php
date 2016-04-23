<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class daObj extends commonObj {
	/// type 5 For DA
	function countRegSTS(){
		if ($_SESSION['sts-userLevel']!='1')
			$filter = " AND tblstshdr.grpCode='{$_SESSION['sts-grpCode']}' ";
			
		$sql = "Select count(stsRefno) as count From tblStsHdr WHERE stsType = '5' $filter";	
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
			  tblStsHdr LEFT JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM
			WHERE stsType = '5' AND stsReFNo not in (
				SELECT TOP $start stsRefNo
				FROM tblStsHdr
				WHERE stsType = '5' $filter
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
			  tblStsHdr LEFT JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM
			WHERE stsType = '5' AND stsRefNo = '$refNo' AND stsReFNo not in (
				SELECT TOP $start stsRefNo
				FROM tblStsHdr
				WHERE stsType = '5' AND stsRefNo = '$refNo' $filter
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
			  tblStsHdr LEFT JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM
			WHERE $searchField = '$searchString' AND stsType = '5' AND stsRefno not in (
				SELECT TOP $start stsRefNo
				FROM tblStsHdr
				WHERE stsType = '5' $filter
				ORDER BY $sidx $sord
			)
			$filter ORDER BY $sidx $sord
			";
		//$sql = "Select * From tblStsHdr WHERE $searchField = $searchString ORDER BY $sidx $sord LIMIT $start , $limit";	
		return $this->getArrRes($this->execQry($sql));	
	}
	
	function findSupplier($terms){
		$sql = "SELECT TOP 10 sql_mmpgtlib..APSUPP.ASNUM as suppCode, sql_mmpgtlib..APSUPP.ASNAME as suppName, sql_mmpgtlib..APADDR.AACONT as contactPerson 
		FROM sql_mmpgtlib..APSUPP 
		LEFT  JOIN sql_mmpgtlib..APADDR on sql_mmpgtlib..APSUPP.ASNUM  = sql_mmpgtlib..APADDR.AANUM
		WHERE (sql_mmpgtlib..APSUPP.ASNUM like '%$terms%') or (sql_mmpgtlib..APSUPP.ASNAME like '%$terms%') 	
				AND  sql_mmpgtlib..APSUPP.ASNAME not like '%NTBU%'";
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
		$sqlInsert = "INSERT INTO tblStsHdr (stsRefNo, suppCode, stsDept, stsCls, stsSubCls, stsRemarks, 
			stsPaymentMode, stsTerms, nbrApplication, applyDate, enteredBy, dateEntered, grpCode, stsStat, 
			stsType, contractTag, contactPerson, contactPersonPos, endDate, origStr,vatTag) 
			VALUES 
			('$tempRefNo', '{$arr['hdnSuppCode']}', '11', '0', '0', 
			'{$arr['txtRemarks']}', '{$arr['cmbPayType']}', NULL,
			'{$arr['txtNoApplications']}', '{$arr['txtApDate']}','".$_SESSION['sts-userId']."', '".$now."', '".$_SESSION['sts-grpCode']."', 'O', '5', 'Y', '{$arr['txtRep']}', '{$arr['txtRepPos']}', '{$arr['txtEndDate']}','".$_SESSION['sts-strCode']."','{$arr['vatTag']}')";
		/*if((int)$arr['cmbCompCode']==1002)
			$strCode = '201';
		else
			$strCode = '202';
		$sqlInsertDetail = "INSERT INTO 
						tblstsdtl (
							stsRefNo, stsComp, stsStrCode, stsStrAmt, dtlStatus
							) 
						VALUES (
							$tempRefNo, '{$arr['cmbCompCode']}', '$strCode', '{$arr['txtSTSAmount']}', 'O'
							)";*/
		
		/*$sqlInsertEnhancer = "INSERT INTO tblStsEnhanceDtl (stsRefNo, brandCode, brandRem, enhanceType, dtlStatus) 
		VALUES ('$tempRefNo', '{$arr['cmbBrand']}', '{$arr['txtBRemarks']}',  '{$arr['cmbEnhancer']}', 'O') ";*/
		
		
		if ($trans) {
			$trans = $this->execQry($sqlInsert);
		}
		/*if($trans){
			$trans = $this->execQry($sqlInsertEnhancer);	
		}*/
		
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
		$sql = "SELECT     tblStsHdr.stsRefno, tblStsHdr.suppCode, tblStsHdr.stsAmt, tblStsHdr.stsRemarks, tblStsHdr.stsPaymentMode, tblStsHdr.nbrApplication, tblStsHdr.applyDate, tblStsHdr.contactPerson, tblStsHdr.contactPersonPos, sql_mmpgtlib.dbo.APSUPP.ASNAME as suppName, tblStsHdr.endDate, tblStsHdr.vatTag
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
	function getAllBranches(){
		if((int)$_SESSION['sts-strCode']!=901){
			$filter2 = "WHERE  strCode = ".(int)$_SESSION['sts-strCode']."";
		}
		$sql = "SELECT strCode+'|'+compCode strCodeComp, strCode +' - '+ brnShortDesc as strCodeBranch FROM tblBranches $filter2";
		return $this->getArrRes($this->execQry($sql));
	}
	function getFilteredBranches($compCode,$refNo){
		if($compCode != 'undefined'){
			$sql = "exec sts_getSTSBranchesComp '".$refNo."','".$_SESSION['sts-strCode']."','".$compCode."'";	
		}else{
			$sql = "exec sts_getSTSBranches '".$refNo."','".$_SESSION['sts-strCode']."'";	
		}
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
		$nbr = $this->getNbrApplication($arr['hdDtl_refNo']);
		$trans = $this->beginTran();
			for($i=0;$i<=(int)$arr['hdCtr'];$i++) {
				if($arr["switcher_$i"]=="1"){
					$sqlPar = "Insert Into tblStsDaDetail (stsRefNo, compCode, strCode, displayType, brand, location, daSize, dispSpecs, noUnits, daRemarks, perUnitAmt, stsAmt, stsVatAmt) 
					VALUES ('{$arr['hdDtl_refNo']}', '".$arr["comp_$i"]."','".$arr["ch_$i"]."', '".$arr['txtDispTyp']."', '".$arr['txtBrand']."','".$arr["txtLoc"]."','".$arr["txtSizeSpecs_$i"]."','".$arr["txtDispSpecs_$i"]."',".$arr["txtNoUnits_$i"].",'".$arr["txtRem"]."','".$arr["txtUnitAmt_$i"]."','".$arr["txtMonthly_$i"]."','".$arr["txtVatAmt_$i"]."');";
					if ($trans) {
						$trans = $this->execQry($sqlPar);
					}
					$sqlDtl = "Insert Into tblStsDtl (stsRefno, compCode, strCode, stsAmt, dtlStatus, stsVatAmt, stsEwtAmt) 
					VALUES ('{$arr['hdDtl_refNo']}', '".$arr["comp_$i"]."','".$arr["ch_$i"]."','".round($arr["txtMonthly_$i"]*$nbr,2)."','O','".round($arr["txtVatAmt_$i"]*$nbr,2)."','".round($arr["txtEwtAmt_$i"]*$nbr,2)."');";
					if ($trans) {
						$trans = $this->execQry($sqlDtl);
					}
				}
			}
		$sqlUpdateHdr = "Update tblStsHdr set stsAmt = (Select sum(stsAmt) from tblStsDtl WHERE stsRefno = '{$arr['hdDtl_refNo']}') WHERE stsRefno = '{$arr['hdDtl_refNo']}'";
		if ($trans) {
			$trans = $this->execQry($sqlUpdateHdr);
		}
		if(!$trans){
			$trans = $this->rollbackTran();
			return false;
		}else{
			$trans = $this->commitTran();
			return true;
		}	
	}
	function getNbrApplication($refNo){
		$sql = "SELECT nbrApplication From tblStsHdr WHERE stsRefno = '$refNo'";	
		$nbr =  $this->getSqlAssoc($this->execQry($sql));	
		return $nbr['nbrApplication'];
	}
	function addDaDtl($arr){
		//$this->DeleteSTSEnhancerDtl($arr['hdDtl_refNo2'],$arr['cmbBrand']);
		$trans = $this->beginTran();
		$strComp = explode("|",$arr['cmbStr']);
		
		$nbr = $this->getNbrApplication($arr['hdnRefNo2']);
		
		$sqlDtl = "Insert Into tblStsDtl (stsRefno, compCode, strCode, stsAmt, dtlStatus) 
					VALUES ('{$arr['hdnRefNo2']}', '".$strComp[1]."','".$strComp[0]."','".round($arr["txtMonthly"]*$nbr,2)."','O');";
					
		$sqlDa = "Insert Into tblStsDaDetail (stsRefno, compCode, strCode, displayType, brand, location, daSize, dispSpecs, noUnits, daRemarks, perUnitAmt, stsAmt) 
					VALUES ('{$arr['hdnRefNo2']}', '".$strComp[1]."', '".$strComp[0]."', '".$arr['txtDispTyp']."', '".$arr['txtBrand']."','".$arr["txtLoc"]."','".$arr["txtSizeSpecs"]."','".$arr["txtDispSpecs"]."','".$arr["txtNoUnits"]."','".$arr["txtRem"]."','".$arr["txtUnitAmt"]."','".$arr["txtMonthly"]."');";
					
		$sqlUpdateHdr = "Update tblStsHdr set stsAmt = (Select sum(stsAmt) from tblStsDtl WHERE stsRefno = '{$arr['hdnRefNo2']}') WHERE stsRefno = '{$arr['hdnRefNo2']}'";
		
		if ($trans) {
			$trans = $this->execQry($sqlDa);
		}
		if ($trans) {
			$trans = $this->execQry($sqlDtl);
		}
		if ($trans) {
			$trans = $this->execQry($sqlUpdateHdr);
		}
		if(!$trans){
			$trans = $this->rollbackTran();
			return false;
		}else{
			$trans = $this->commitTran();
			return true;
		}	
	}
	function updateDaDtl($arr){
		
		$trans = $this->beginTran();	
		$strComp = explode("|",$arr['cmbStr']);
		
		$nbr = $this->getNbrApplication($arr['hdnRefNo2']);
		
		//$sqlDtl = "update tblStsDtl (stsRefno, compCode, strCode, stsAmt, dtlStatus) 
					//VALUES ('{$arr['hdnRefNo']}', '".$strComp[1]."','".$strComp[0]."','".round($arr["txtMonthly"]*$nbr,2)."','O');";
		
		$sqlDa = "Update tblStsDaDetail 
					SET displayType = '".$arr['txtDispTyp']."', brand =  '".$arr['txtBrand']."', location = '".$arr["txtLoc"]."',
					 daSize = '".$arr["txtSizeSpecs"]."', dispSpecs = '".$arr["txtDispSpecs"]."', noUnits = '".$arr["txtNoUnits"]."', daRemarks = '".$arr["txtRem"]."', 
					 perUnitAmt = '".$arr["txtUnitAmt"]."', stsAmt='".$arr["txtMonthly"]."'
					WHERE stsRefno = '{$arr['hdnRefNo2']}' AND strCode = '".$strComp[0]."' AND compCode = '".$strComp[1]."'";
		if ($trans) {
			$trans = $this->execQry($sqlDa);
		}
		$sqlDtl = "Update tblStsDtl set stsAmt = '".round($arr["txtMonthly"]*$nbr,2)."' WHERE stsRefno = '{$arr['hdnRefNo2']}' AND strCode = '".$strComp[0]."' AND compCode = '".$strComp[1]."'";		
		if ($trans) {
			$trans = $this->execQry($sqlDtl);
		}
		$sqlUpdateHdr = "Update tblStsHdr set stsAmt = (Select sum(stsAmt) from tblStsDtl WHERE stsRefno = '{$arr['hdnRefNo2']}') WHERE stsRefno = '{$arr['hdnRefNo2']}'";
		
		
		if ($trans) {
			$trans = $this->execQry($sqlUpdateHdr);
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
		$trans = $this->beginTran();
		$sqlDelPar = "DELETE FROM tblStsDaDetail WHERE stsRefno = '$refNo'";
		if ($trans) {
			$trans = $this->execQry($sqlDelPar);
		}
		$sqlDel = "DELETE FROM tblStsDtl WHERE stsRefNo = '$refNo'";

		if ($trans) {
			$trans = $this->execQry($sqlDel);
		}
		$sqlUpdate = "UPDATE tblStsHdr set stsAmt = 0 WHERE stsRefno = '$refNo'";
		if(!$trans){
			$trans = $this->rollbackTran();
			return false;
		}else{
			$trans = $this->commitTran();
			return true;
		}
	}
	function DeleteSTSDaDtl($refNo,$strCode){
		$sqlDelDtl = "DELETE FROM tblStsDtl WHERE stsRefNo = '$refNo' AND strCode = '$strCode'";
		$sqlDel = "DELETE FROM tblStsDaDetail WHERE stsRefno = '$refNo' AND strCode = '$strCode'";
		
		$sqlUpdateHdr = "Update tblStsHdr set stsAmt = (Select sum(stsAmt) from tblStsDtl WHERE stsRefno = '$refNo') WHERE stsRefno = '$refNo'";
		$trans = $this->beginTran();
		if ($trans) {
			$trans = $this->execQry($sqlDelDtl);
		}
		if ($trans) {
			$trans = $this->execQry($sqlDel);
		}
		if ($trans) {
			$trans = $this->execQry($sqlUpdateHdr);
		}
		if(!$trans){
			$trans = $this->rollbackTran();
			return false;
		}else{
			$trans = $this->commitTran();
			return true;
		}
	}
	function getStsDaDtlArr($refNo){
		$sql = "SELECT * from tblStsDaDetail where stsRefno = '$refNo'";
		return $this->getArrRes($this->execQry($sql));
	}
	function updateHeader($arr){
		$sqlUpdateHdr = "UPDATE tblStsHdr SET 
			suppCode = '{$arr['hdnSuppCode']}', 
			endDate = '{$arr['txtEndDate']}', 
			stsRemarks  = '{$arr['txtRemarks']}', 
			stsDept = '11', 
			stsCls = '0', 
			stsSubCls = '0', 
			stsPaymentMode = '{$arr['cmbPayType']}', 
			nbrApplication = '{$arr['txtNoApplications']}',
			applyDate = '{$arr['txtApDate']}',
			contactPerson = '{$arr['txtRep']}',
			contactPersonPos = '{$arr['txtRepPos']}',
			enteredBy = '".$_SESSION['sts-userId']."',
			vatTag = '{$arr['vatTag']}'
			WHERE stsRefNo = '{$arr['refNo']}'";
		
		$trans = $this->beginTran();
		$count = $this->hasSTSDetail($arr['refNo']);
		///$nbr = $this->getNbrApplication($arr['refNo']);
		if((int)$count>0){		
			$arrPar = $this->getStsDaDtlArr($arr['refNo']);
				foreach($arrPar as $val) {
					$sqlParSts = "update tblStsDtl set stsAmt = '".$val['stsAmt']*$arr['txtNoApplications']."' where strCode='{$val['strCode']}' and compCode='{$val['compCode']}' and stsRefNo='{$arr['refNo']}'\n";
					if ($trans) {
						$trans = $this->execQry($sqlParSts);
					}
				}
		}
		if ($trans) {
			$trans = $this->execQry($sqlUpdateHdr);
		}
		$sqlUpdateHdr2 = "Update tblStsHdr set stsAmt = (Select sum(stsAmt) from tblStsDtl WHERE stsRefno = '{$arr['refNo']}') WHERE stsRefno = '{$arr['refNo']}'";
		if ($trans) {
			$trans = $this->execQry($sqlUpdateHdr2);
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
		$delDa = "DELETE FROM tblStsDaDetail WHERE stsRefno = '$refNo'";
		$delSTS = "DELETE FROM tblStsHdr WHERE stsRefno = '$refNo'";
		$delEnhanceDtl = "DELETE FROM tblStsEnhanceDtl WHERE stsRefno = '$refNo'";
		$trans = $this->beginTran();
		if ($trans) {
			$trans = $this->execQry($delPar);
		}
		if ($trans) {
			$trans = $this->execQry($delDa);
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
	function hasSTSDetailAll($refNo){
		$sql = "SELECT     *
			FROM         tblStsDtl
			WHERE     (stsRefno = $refNo) AND (strCode NOT IN
									  (SELECT     strCode
										FROM          tblStsDaDetail
										WHERE      (stsRefno = $refNo)))";	
		return $this->getRecCount($this->execQry($sql));
	}
	function hasEnhancer($refNo){
		$sql = "SELECT     *
			FROM         tblStsDtl
			WHERE     (stsRefno = $refNo) AND (strCode NOT IN
									  (SELECT     strCode
										FROM          tblStsDaDetail
										WHERE      (stsRefno = $refNo)))";	
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
			$newContract++;
			$sqlDtl = "UPDATE tblStsDtl set stsNo = '$tempSTSNo', dtlStatus = 'R' WHERE stsRefno = '{$val['stsRefno']}' AND compCode = '{$val['compCode']}' AND strCode = '{$val['strCode']}';";
			$sqlDaDtl = "UPDATE tblStsDaDetail SET stsNo  = '$tempSTSNo', contractNo = '$newContract' WHERE stsRefno = '{$val['stsRefno']}' AND strCode =  '{$val['strCode']}'";
			if ($trans) {
				$trans = $this->execQry($sqlDtl);
			}
			if ($trans) {
				$trans = $this->execQry($sqlDaDtl);
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
		SELECT stsNo, stsSeq, stsRefNo, compCode, strCode, suppCode, stsType, stsPaymentMode, stsDept, stsCls, stsSubCls, grpCode, stsApplyDate FROM tblstsapply WHERE  stsRefno = '$refNo'  AND stsApplyDate >= '$cancelDate';";
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
	function getContractInfo($refNo){
		$sql = "SELECT     tblStsHdr.stsRefno, tblStsHdr.dateApproved, tblStsHdr.contractNo, tblStsHdr.applyDate, tblStsHdr.nbrApplication, tblStsHdr.stsAmt, tblStsHdr.stsRemarks, DATEADD(month, 
                      tblStsHdr.nbrApplication - 1, tblStsHdr.applyDate) AS endDate, DATEADD(month, tblStsHdr.nbrApplication, tblStsHdr.applyDate) AS endDate2, tblUsers.fullName, 
                      tblStsHdr.contactPerson, tblStsHdr.contactPersonPos, sql_mmpgtlib.dbo.APSUPP.AANAME AS suppName, sql_mmpgtlib.dbo.APADDR.AAADD1 AS add1, 
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
		$sql = "SELECT     tblBranches.brnDesc AS brnDesc, tblStsDaDetail.stsRefno, tblStsDaDetail.compCode, tblStsDaDetail.strCode, tblStsDaDetail.location, 
                      tblStsDaDetail.daSize, tblStsDaDetail.dispSpecs, tblStsDaDetail.noUnits, tblStsDaDetail.daRemarks, tblStsDaDetail.contractNo, tblStsDaDetail.stsNo, 
                      tblStsDaDetail.perUnitAmt, tblStsDaDetail.stsAmt, tblDisplaySpecs.displaySpecsDesc AS displayType, tblStsDaDetail.brand
FROM         tblStsDaDetail INNER JOIN
                      tblBranches ON tblBranches.strCode = tblStsDaDetail.strCode INNER JOIN
                      tblDisplaySpecs ON tblStsDaDetail.dispSpecs  = tblDisplaySpecs.displaySpecsId WHERE tblStsDaDetail.stsRefno = '$refNo' order by tblStsDaDetail.strCode";	
		return $this->getArrRes($this->execQry($sql));
	}
	function getEnhancerDetails2($refNo){
		/*$sql = "SELECT     tblBranches.brnDesc AS brnDesc, tblStsDaDetail.stsRefno, tblStsDaDetail.compCode, tblStsDaDetail.strCode, tblStsDaDetail.location, 
                      tblStsDaDetail.daSize, tblStsDaDetail.dispSpecs, tblStsDaDetail.noUnits, tblStsDaDetail.daRemarks, tblStsDaDetail.contractNo, tblStsDaDetail.stsNo, 
                      tblStsDaDetail.perUnitAmt, tblStsDaDetail.stsAmt, tblDisplaySpecs.displaySpecsDesc AS displayType, tblStsDaDetail.brand, tblStsDtl.dtlStatus
FROM         tblStsDaDetail INNER JOIN
                      tblBranches ON tblBranches.strCode = tblStsDaDetail.strCode INNER JOIN
                      tblDisplaySpecs ON tblStsDaDetail.dispSpecs = tblDisplaySpecs.displaySpecsId LEFT OUTER JOIN
                      tblStsDtl ON tblStsDaDetail.stsRefno = tblStsDtl.stsRefno AND tblStsDaDetail.compCode = tblStsDtl.compCode AND tblStsDaDetail.strCode = tblStsDtl.strCode AND 
                      tblStsDaDetail.stsNo = tblStsDtl.stsNo WHERE tblStsDaDetail.stsRefno = '$refNo' AND (tblStsDtl.dtlStatus <> 'C' OR
                      tblStsDtl.dtlStatus IS NULL) order by tblStsDaDetail.strCode";*/
		$sql = "SELECT     tblBranches.brnDesc AS brnDesc, tblStsDaDetail.stsRefno, tblStsDaDetail.compCode, tblStsDaDetail.strCode, tblStsDaDetail.location, 
                      tblStsDaDetail.daSize, tblStsDaDetail.dispSpecs, tblStsDaDetail.noUnits, tblStsDaDetail.daRemarks, tblStsDaDetail.contractNo, tblStsDaDetail.stsNo, 
                      tblStsDaDetail.perUnitAmt, tblStsDaDetail.stsAmt, tblDisplaySpecs.displaySpecsDesc AS displayType, tblStsDaDetail.brand, tblStsDtl.dtlStatus
FROM         tblStsDaDetail INNER JOIN
                      tblBranches ON tblBranches.strCode = tblStsDaDetail.strCode INNER JOIN
                      tblDisplaySpecs ON tblStsDaDetail.dispSpecs = tblDisplaySpecs.displaySpecsId LEFT OUTER JOIN
                      tblStsDtl ON tblStsDaDetail.stsRefno = tblStsDtl.stsRefno AND tblStsDaDetail.compCode = tblStsDtl.compCode AND tblStsDaDetail.strCode = tblStsDtl.strCode AND 
                      tblStsDaDetail.stsNo = tblStsDtl.stsNo WHERE tblStsDaDetail.stsRefno = '$refNo'
					  order by tblStsDaDetail.strCode";	
		return $this->getArrRes($this->execQry($sql));
	}
	function getStsDaDtl($refNo,$strCode){
		$sql = "SELECT * FROM tblStsDaDetail 
			WHERE stsRefno = '$refNo' AND strCode = '$strCode'";	
		return $this->getSqlAssoc($this->execQry($sql));
	}
	function brandExists($refNo,$strCode){
		$sql = "SELECT * FROM tblStsDtl WHERE stsRefno = '$refNo' AND strCode = '$strCode'";
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
	function getDaContract($refNo,$strCode){
			$sql = "SELECT      tblStsDaDetail.contractNo, tblBranches.brnDesc, tblStsDtl.stsAmt, tblStsDtl.stsNo, sql_mmpgtlib.dbo.APADDR.AANUM,
		tblStsHdr.contactPersonPos, tblStsHdr.contactPerson, sql_mmpgtlib.dbo.APSUPP.ASNAME as suppName, tblStsHdr.applyDate, tblStsHdr.nbrApplication, 
		tblStsDaDetail.displayType, tblStsDaDetail.brand, tblStsDaDetail.location, tblStsDaDetail.daSize, tblStsDaDetail.dispSpecs, 
		tblStsDaDetail.noUnits, tblStsDaDetail.daRemarks,
                      tblStsHdr.endDate AS endDate,   DATEADD(month, tblStsHdr.nbrApplication, tblStsHdr.applyDate) AS endDate2,
					 sql_mmpgtlib.dbo.APADDR.AAADD1 AS add1, 
                      sql_mmpgtlib.dbo.APADDR.AAADD2 AS add2, sql_mmpgtlib.dbo.APADDR.AAADD3 AS add3, 
                      payMode = CASE tblStsHdr.stsPaymentMode WHEN 'C' THEN 'CHECK Payment' ELSE 'Invoice Deduction' END, tblGroup.grpDesc, tblStsDaDetail.perUnitAmt,
					  tblStsDaDetail.stsAmt as stsAmtDa, tblUsers.fullName, tblDisplayType.displayTypeDesc,tblDisplaySpecs.displaySpecsDesc, 
                      tblSizeSpecs.sizeSpecsDesc, tblUsers_1.fullName AS approvedBy,tblStsDtl.dtlStatus AS dtlStatus, tblStsHdr.stsPaymentMode,tblStsDaDetail.stsVatAmt,  tblStsDaDetail.stsEwtAmt,tblStsDaDetail.stsAmt,tblStsHdr.vatTag,
					   (SELECT     TOP 1 effectivityDate
                            FROM          tblcancelledSts
                            WHERE      stsRefno = tblStsHdr.stsrefNo) AS effectivityDate,
						(SELECT     TOP 1 cancelDate
                            FROM          tblcancelledSts
                            WHERE      stsRefno = tblStsHdr.stsrefNo) AS cancelDate
		FROM         tblBranches INNER JOIN
                      tblStsDaDetail ON tblBranches.strCode = tblStsDaDetail.strCode INNER JOIN
                      tblStsDtl ON tblStsDaDetail.stsRefno = tblStsDtl.stsRefno AND tblStsDaDetail.strCode = tblStsDtl.strCode INNER JOIN
                      tblStsHdr ON tblStsDaDetail.stsRefno = tblStsHdr.stsRefno INNER JOIN
                      sql_mmpgtlib.dbo.APADDR ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APADDR.AANUM  
					   INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM INNER JOIN
                      tblGroup ON tblStsHdr.grpCode = tblGroup.grpCode INNER JOIN
					  tblUsers ON tblStsHdr.enteredBy = tblUsers.userId  INNER JOIN
                      tblDisplayType ON tblStsDaDetail.displayType = tblDisplayType.displayTypeId INNER JOIN
                      tblDisplaySpecs ON tblStsDaDetail.dispSpecs = tblDisplaySpecs.displaySpecsId INNER JOIN
                      tblSizeSpecs ON tblStsDaDetail.daSize = tblSizeSpecs.sizeSpecsId  LEFT JOIN
                      tblUsers tblUsers_1 ON tblStsHdr.approvedBy = tblUsers_1.userId
		WHERE tblStsDaDetail.stsRefno = '$refNo' AND tblStsDaDetail.strCode = '$strCode'";
		return $this->getSqlAssoc($this->execQry($sql));
	}
	function getSizeSpecs(){
		$sql = "SELECT * FROM tblSizeSpecs";	
		return $this->getArrRes($this->execQry($sql));	
	}
	function getDisplaySpecs(){
		$sql = "SELECT * FROM tblDisplaySpecs";	
		return $this->getArrRes($this->execQry($sql));	
	}
	function getDisplayType(){
		$sql = "SELECT * FROM tblDisplayType";	
		return $this->getArrRes($this->execQry($sql));	
	}
	function getCancelDates($refNo){
		$sql = "SELECT Distinct stsApplyDate FROM tblStsApply where stsRefNo = '$refNo'";
		return $this->getArrRes($this->execQry($sql));	
	}
	function daExists($refNo){
		$sql = "SELECT * FROM tblStsDaDetail WHERE stsRefno = '$refNo'";
		return $this->getRecCount($this->execQry($sql));
	}
	function getDaHeader($refNo){
		$sql = "SELECT   TOP 1  displayType, brand, location, daRemarks
FROM         tblStsDaDetail
WHERE     (stsRefno = '$refNo')";
		return $this->getSqlAssoc($this->execQry($sql));
	}
	function getVat(){
		$sql = "SELECT top 1 vatMultiplier FROM tblVat";	
		$arr = $this->getSqlAssoc($this->execQry($sql));
		return $arr['vatMultiplier'];
	}
	function getEwt($dept,$class,$subClass){
		$sql = "SELECT ewtMultiplier FROM tblStsHierarchy WHERE (stsDept = '$dept') AND (stsCls = '$class') AND (stsSubCls = '$subClass')";	
		$arr = $this->getSqlAssoc($this->execQry($sql));
		return $arr['ewtMultiplier'];
	}
	function checkPayment($refNo){
		$sql = "SELECT stsPaymentMode from tblStsHdr where stsrefno = '$refNo'";
		$arr = $this->getSqlAssoc($this->execQry($sql));
		return $arr['stsPaymentMode'];	
	}
	function getMaxComp($refNo,$strCode){
		$sql = "Select max(compCode) as compCode from tblStsDtl where stsRefno = '$refNo' AND strCode = $strCode";	
		$compCode = $this->getSqlAssoc($this->execQry($sql));
		return $compCode['compCode'];
	}
	function getMaxCom2($refNo){
		$sql = "Select max(compCode) as compCode from tblStsDtl where stsRefno = '$refNo'";	
		$compCode = $this->getSqlAssoc($this->execQry($sql));
		return $compCode['compCode'];
	}
}	
?>
