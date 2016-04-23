<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class reportsObj extends commonObj {
	
	function getUnreleasedSTS($dtFrom, $dtTo){
		$sql = "SELECT * FROM unreleasedSTSView WHERE date(stsDateEntered) BETWEEN '$dtFrom' AND '$dtTo' order by stscomp, grpEntered, stsrefno";
		return $this->getArrRes($this->execQry($sql));
	}
	function getReleasedSTS($dtFrom, $dtTo){
		$sql = "SELECT * FROM releasedSTSView WHERE date(dateApproved) BETWEEN '$dtFrom' AND '$dtTo'  order by stscomp, grpEntered, stsrefno";
		return $this->getArrRes($this->execQry($sql));
	}
	function getProdGrpName($code){
		//$sql = "SELECT prodName FROM tblprodgrp WHERE prodID = '$code'";
		$sql = "SELECT grpDesc FROM tblGroup WHERE grpCode = '$code'";
		return $this->getSqlAssoc($this->execQry($sql));
	}
	function getReleasedSTSAP($dtFrom,$dtTo,$status,$str,$trans){
		if($status =='O'){
			$stat = "AND (tblStsApply.status  IS NULL)";
		}elseif($status == 'A'){
			$stat = "AND tblStsApply.status  = 'A'";
		}else{
			$stat = "";	
		}
		if($trans != '0'){
			if((int)$trans == 6){
				$stsType = "AND tblStsApply.stsDept = 2 AND tblStsApply.stsCls = 3 AND tblStsApply.stsSubCls = 3";
			}elseif((int)$trans == 7){
				$stsType = "AND tblStsApply.stsDept = 2 AND tblStsApply.stsCls = 3 AND tblStsApply.stsSubCls = 1";
			}else{
				$stsType = "AND tblStsApply.stsType = $trans";	
			}
		}
		$sql = "SELECT     tblStsApply.stsType, tblStsApply.stsNo, tblStsApply.stsApplyAmt, tblBranches.brnDesc, sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppName, tblStsApply.stsSeq, 
                      tblStsApply.stsApplyDate, tblStsApply.stsRefno, tblStsHdr.nbrApplication, tblStsHdr.enteredBy, tblStsHdr.stsRemarks, tblStsDlyApHist.apBatch, tblStsApply.stsVatAmt,
                      tblStsDlyArHist.arBatch, tblStsApply.stsPaymentMode, payMode = CASE tblStsHdr.stsPaymentMode WHEN 'C' THEN 'Collection/Check' ELSE 'Invoice Deduction' END, 
                      applyStatus = CASE tblStsApply.status WHEN NULL THEN 'ONQUEUE' ELSE 'APPLIED' END
FROM         tblStsApply INNER JOIN
                      tblBranches ON tblStsApply.compCode = tblBranches.compCode AND tblStsApply.strCode = tblBranches.strCode INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsApply.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM LEFT OUTER JOIN
                      tblStsDlyApHist ON tblStsApply.stsNo = tblStsDlyApHist.stsNo AND tblStsApply.stsSeq = tblStsDlyApHist.stsSeq AND 
                      tblStsApply.stsRefno = tblStsDlyApHist.stsRefno LEFT OUTER JOIN
                      tblStsDlyArHist ON tblStsApply.stsNo = tblStsDlyArHist.stsNo AND tblStsApply.stsSeq = tblStsDlyArHist.stsSeq AND 
                      tblStsApply.stsRefno = tblStsDlyArHist.stsRefno LEFT OUTER JOIN
                      tblStsHdr ON tblStsApply.stsRefno = tblStsHdr.stsRefno
				WHERE tblStsApply.stsApplyDate BETWEEN '$dtFrom' AND '$dtTo' AND tblBranches.strCode = '$str' $stat $stsType  ORDER BY tblStsApply.status ";
		return $this->getArrRes($this->execQry($sql));
	}
	function getReleasedSTSAPSup($dtFrom,$dtTo,$status,$suppCode,$trans){
		if($status =='O'){
			$stat = "AND (tblStsApply.status IS NULL)";
		}elseif($status == 'A'){
			$stat = "AND tblStsApply.status = 'A'";
		}else{
			$stat = "";	
		}
		if($trans != '0'){
			if((int)$trans == 6){
				$stsType = "AND tblStsApply.stsDept = 2 AND tblStsApply.stsCls = 3 AND tblStsApply.stsSubCls = 3";
			}elseif((int)$trans == 7){
				$stsType = "AND tblStsApply.stsDept = 2 AND tblStsApply.stsCls = 3 AND tblStsApply.stsSubCls = 1";
			}else{
				$stsType = "AND tblStsApply.stsType = $trans";	
			}
		}
		$sql = "SELECT     tblStsApply.stsType, tblStsApply.stsNo, tblStsApply.stsApplyAmt, tblBranches.brnDesc, sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppName, tblStsApply.stsSeq, 
                      tblStsApply.stsApplyDate, tblStsApply.stsRefno, tblStsHdr.nbrApplication, tblStsHdr.enteredBy, tblStsHdr.stsRemarks, tblStsDlyApHist.apBatch, tblStsApply.stsVatAmt,
                      tblStsDlyArHist.arBatch, payMode = CASE tblStsHdr.stsPaymentMode WHEN 'C' THEN 'Collection/Check' ELSE 'Invoice Deduction' END, 
                      applyStatus = CASE tblStsApply.status WHEN NULL THEN 'ONQUEUE' ELSE 'APPLIED' END
FROM         tblStsApply INNER JOIN
                      tblBranches ON tblStsApply.compCode = tblBranches.compCode AND tblStsApply.strCode = tblBranches.strCode LEFT OUTER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsApply.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM left outer JOIN
                      tblStsDlyArHist ON tblStsApply.stsNo = tblStsDlyArHist.stsNo AND tblStsApply.stsSeq = tblStsDlyArHist.stsSeq AND 
                      tblStsApply.stsRefno = tblStsDlyArHist.stsRefno LEFT OUTER JOIN
                      tblStsDlyApHist ON tblStsApply.stsNo = tblStsDlyApHist.stsNo AND tblStsApply.stsSeq = tblStsDlyApHist.stsSeq AND 
                      tblStsApply.stsRefno = tblStsDlyApHist.stsRefno LEFT OUTER JOIN
                      tblStsHdr ON tblStsApply.stsRefno = tblStsHdr.stsRefno
				WHERE tblStsApply.stsApplyDate BETWEEN '$dtFrom' AND '$dtTo' AND sql_mmpgtlib.dbo.APSUPP.ASNUM = '$suppCode' $stat  $stsType ORDER BY tblStsApply.status";
		return $this->getArrRes($this->execQry($sql));
	}
	function getCancelledSTS($compCode, $prodGrp, $dtFrom, $dtTo){
		$sql = "SELECT * FROM cancelledSTSView WHERE stsComp = '$compCode' AND grpEntered = '$prodGrp' AND date(stsDateEntered) BETWEEN '$dtFrom' AND '$dtTo'";
		return $this->getArrRes($this->execQry($sql));
	}
	function transSummary($trans,$dtStart,$dtEnd,$stat,$suppCode,$grp){
		if($stat != '0')
			$filter = "AND tblStsHdr.stsStat = '$stat'";
		
		if($trans != '0'){
			if((int)$trans == 6){
				$stsType = "AND stsDept = 2 AND stsCls = 3 AND stsSubCls = 3";
			}elseif((int)$trans == 7){
				$stsType = "AND stsDept = 2 AND stsCls = 3 AND stsSubCls = 1";
			}else{
				$stsType = "AND stsType = $trans";	
			}
		}
		if($stat == 'R'){
			$dt = "tblStsHdr.dateApproved";	
		}else{
			$dt = "CONVERT(datetime, CONVERT(varchar,tblStsHdr.dateEntered, 101))";	
		}
		if($grp==0){
			$grpVar = "";	
		}else{
			$grpVar = "AND tblStsHdr.grpCode = '$grp'";	
		}
		 $sql = "SELECT     tblStsHdr.stsRefno, sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppName, tblStsHdr.stsAmt, tblStsHdr.applyDate, tblStsHdr.dateEntered, tblStsHdr.nbrApplication, tblStsHdr.dateApproved,
                      tblStsHdr.stsPaymentMode, tblStsHdr.contractNo, tblStsHdr.stsRemarks, tblStsHdr.stsType, tblUsers.fullName,
                          (SELECT     hierarchyDesc
                            FROM          tblStsHierarchy
                            WHERE      tblStsHierarchy.stsDept = tblStsHdr.stsDept AND levelCode = 1) AS dept,
                          (SELECT     hierarchyDesc
                            FROM          tblStsHierarchy
                            WHERE      tblStsHierarchy.stsDept = tblStsHdr.stsDept AND tblStsHierarchy.stsCls = tblStsHdr.stsCls AND levelCode = 2) AS cls,
                          (SELECT     hierarchyDesc
                            FROM          tblStsHierarchy
                            WHERE      tblStsHierarchy.stsDept = tblStsHdr.stsDept AND tblStsHierarchy.stsCls = tblStsHdr.stsCls AND tblStsHierarchy.stsSubCls = tblStsHdr.stsSubCls AND levelCode = 3) AS subCls, payMode = CASE tblStsHdr.stsPaymentMode WHEN 'C' THEN 'Collection/Check' ELSE 'Invoice Deduction' END 
FROM         tblStsHdr INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM INNER JOIN
                      tblUsers ON tblStsHdr.enteredBy = tblUsers.userId
					  WHERE $dt BETWEEN '$dtStart' AND '$dtEnd' AND tblStsHdr.suppCode = '$suppCode' AND tblStsHdr.stsStat = '$stat' $filter $stsType $grpVar AND tblStsHdr.stsRefno in (SELECT distinct stsRefno FROM tblStsDtl)";	
		return $this->getArrRes($this->execQry($sql));
	}
	function transSummarySupp($trans,$dtStart,$dtEnd,$stat,$supp,$grp){
		if($stat != '0'){
			if($stat == 'R'){
				$filter1 = "AND (tblStsHdr.stsStat = '$stat' OR tblStsHdr.stsStat = 'C')";
			}else{
				$filter1 = "AND tblStsHdr.stsStat = '$stat'";
			}
		}
		if($supp != '0'){
			$filter2 = "AND tblStsHdr.suppCode = '$supp'";
		}
		if($trans != '0'){
			if((int)$trans == 6){
				$stsType = "AND stsDept = 2 AND stsCls = 3 AND stsSubCls = 3";
			}elseif((int)$trans == 7){
				$stsType = "AND stsDept = 2 AND stsCls = 3 AND stsSubCls = 1";
			}else{
				$stsType = "AND stsType = $trans";	
			}
		}
		if($stat == 'R'){
			$dt = "tblStsHdr.dateApproved";	
		}else{
			$dt = "CONVERT(datetime, CONVERT(varchar,tblStsHdr.dateEntered, 101))";
		}
		if($grp==0){
			$grpVar = "";	
		}else{
			$grpVar = "AND tblStsHdr.grpCode = '$grp'";	
		}
		$sql = "SELECT DISTINCT  sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppName, sql_mmpgtlib.dbo.APSUPP.ASNUM AS suppCode
FROM         tblStsHdr left JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM
WHERE $dt  BETWEEN '$dtStart' AND '$dtEnd' $stsType $filter1 $filter2 $grpVar AND tblStsHdr.stsRefno in (SELECT distinct stsRefno FROM tblStsDtl) ORDER BY sql_mmpgtlib.dbo.APSUPP.ASNUM ";	
		return $this->getArrRes($this->execQry($sql));
	}
	function cancelledSTSSummary($dtFrom,$dtTo,$trans,$cmbCancelType){
		if($trans != '0'){
			if((int)$trans == 6){
				$stsType = "AND tblStsHdr.stsDept = 2 AND tblStsHdr.stsCls = 3 AND tblStsHdr.stsSubCls = 3";
			}elseif((int)$trans == 7){
				$stsType = "AND tblStsHdr.stsDept = 2 AND tblStsHdr.stsCls = 3 AND tblStsHdr.stsSubCls = 1";
			}else{
				$stsType = "AND tblStsHdr.stsType = $trans";	
			}
		}
		if($cmbCancelType != '0'){
			$stsCancelType = "AND tblCancelledSTS.cancelCode = $cmbCancelType";
		}
		/*
		$sql = "SELECT     
		tblStsHdr.stsRefno, 
		tblStsHdr.stsRemarks, 
		pg_pf.dbo.tblSuppliers.suppName, 
		tblStsHdr.stsAmt, 
		tblStsHdr.stsStartNo, 
		tblStsHdr.stsEndNo, 
		tblStsHdr.cancelDate, 
        tblCancelType.cancelDesc, 
		tblUsers.fullName, 
		SUM(tblCancelledSTS.uploadedAmt) AS uploadedAmt, 
		SUM(tblCancelledSTS.queueAmt) AS queueAmt, 
		tblCancelledSTS.cancelDate AS effectivityDate
		FROM   
		tblStsHdr INNER JOIN
        pg_pf.dbo.tblSuppliers ON tblStsHdr.suppCode = pg_pf.dbo.tblSuppliers.suppCode 
		LEFT OUTER JOIN
        tblCancelledSTS ON tblStsHdr.stsRefno = tblCancelledSTS.stsRefno 
		LEFT OUTER JOIN
        tblUsers ON tblStsHdr.cancelledBy = tblUsers.userId 
		LEFT OUTER JOIN
        tblCancelType ON tblStsHdr.cancelId = tblCancelType.cancelId
		WHERE (tblStsHdr.stsStat = 'C') 
		AND tblStsHdr.cancelDate BETWEEN '$dtFrom' AND '$dtTo' 
		$stsType 
		GROUP BY tblStsHdr.stsRefno, 
		tblStsHdr.stsRemarks, 
		pg_pf.dbo.tblSuppliers.suppName, 
		tblStsHdr.stsAmt, 
		tblStsHdr.stsStartNo, 
		tblStsHdr.stsEndNo, 
		tblStsHdr.cancelDate, 
        tblCancelType.cancelDesc, 
		tblUsers.fullName, 
		tblCancelledSts.cancelDate";	
		*/
		$sql = "SELECT     tblCancelledSTS.stsRefno, SUM(tblCancelledSTS.uploadedAmt) AS uploadedAmt, SUM(tblCancelledSTS.queueAmt) AS queueAmt, 
                      tblCancelledSTS.cancelDate AS effectivityDate, tblStsHdr.stsAmt, tblStsHdr.stsStartNo, tblStsHdr.stsEndNo, sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppName, 
                      tblUsers.fullName, tblCancelledSTS.cancelDate, tblStsHdr.stsRemarks, tblCancelType.cancelDesc
FROM         tblCancelledSTS INNER JOIN
                      tblStsHdr ON tblCancelledSTS.stsRefno = tblStsHdr.stsRefno INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblCancelledSTS.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM INNER JOIN
                      tblUsers ON tblCancelledSTS.cancelledBy = tblUsers.userId INNER JOIN
                      tblCancelType ON tblCancelledSTS.cancelCode = tblCancelType.cancelId
					  WHERE tblCancelledSTS.cancelDate BETWEEN '$dtFrom' AND '$dtTo' 
					  $stsType 
					  $stsCancelType
GROUP BY tblCancelledSTS.stsRefno, tblCancelledSTS.uploadedAmt, tblCancelledSTS.queueAmt, tblCancelledSTS.cancelDate, tblStsHdr.stsAmt, tblStsHdr.stsStartNo, 
                      tblStsHdr.stsEndNo, sql_mmpgtlib.dbo.APSUPP.ASNAME, tblUsers.fullName, tblStsHdr.stsRemarks, tblCancelType.cancelDesc";
		return $this->getArrRes($this->execQry($sql));
	}
	/*
	function findSupplier(){
		$sql = "SELECT DISTINCT 
                      sql_mmpgtlib.dbo.APADDR.AANAME AS suppName, sql_mmpgtlib.dbo.APADDR.AANUM AS suppCode, CAST(sql_mmpgtlib.dbo.APADDR.AANUM AS varchar) 
                      + ' - ' + sql_mmpgtlib.dbo.APADDR.AANAME AS suppCodeName
FROM         tblStsHdr INNER JOIN
                      sql_mmpgtlib.dbo.APADDR ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APADDR.AANUM
ORDER BY sql_mmpgtlib.dbo.APADDR.AANAME";
		return $this->getArrRes($this->execQry($sql));
		
	}
	*/
	function findSupplier(){
		$sql = "SELECT DISTINCT 
                      sql_mmpgtlib.dbo.APSUPP.ASNAME 

AS suppName, sql_mmpgtlib.dbo.APSUPP.ASNUM AS 

suppCode, CAST(sql_mmpgtlib.dbo.APSUPP.ASNUM AS 

varchar) 
                      + ' - ' + 

sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppCodeName
FROM         tblStsHdr left  JOIN
                      sql_mmpgtlib.dbo.APSUPP ON 

tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM
ORDER BY sql_mmpgtlib.dbo.APSUPP.ASNAME";
		return $this->getArrRes($this->execQry($sql));
	}

	function getParDetail($refNo){
		$sql = "SELECT     tblBranches.brnShortDesc, tblStsDtl.stsNo, tblStsDtl.stsAmt
FROM         tblStsDtl INNER JOIN
                      tblBranches ON tblStsDtl.strCode = tblBranches.strCode AND tblStsDtl.compCode = tblBranches.compCode 
					  WHERE tblStsDtl.stsRefno = '$refNo'";	
		return $this->getArrRes($this->execQry($sql));
	}
	//Create
	function getParDetail2($trans,$dtStart,$dtEnd,$stat,$suppCode,$grp){
		//if($trans != '0'){
		//	$trans_q = "AND tblStsHdr.stsType = $trans";	
		//}
		if($trans==0){
			$trans_q = "";	
		}else{
			$trans_q = "AND tblStsHdr.stsType = '$trans'";	
		}
		
		if($trans != '0'){
			if($trans == 6){
				$stsType = "AND tblStsHdr.stsDept = 2 AND tblStsHdr.stsCls = 3 AND tblStsHdr.stsSubCls = 3";
			}elseif($trans == 7){
				$stsType = "AND tblStsHdr.stsDept = 2 AND tblStsHdr.stsCls = 3 AND tblStsHdr.stsSubCls = 1";
			}else{
				$stsType = "AND tblStsHdr.stsType = $trans";	
			}
		}
		
		if($stat == 'R'){
			$dt = "tblStsHdr.dateApproved";
			$includeCancel = "OR tblStsHdr.stsStat = 'C'";
		}else{
			$dt = "CONVERT(datetime, CONVERT(varchar,tblStsHdr.dateEntered, 101))";
		}
		
		if($suppCode == '0'){
			$suppCode_q = "";
		}else{
			$suppCode_q = "AND tblStsHdr.suppCode = $suppCode";
		}
		
		if($grp==0){
			$grpVar = "";	
		}else{
			$grpVar = "AND tblStsHdr.grpCode = '$grp'";	
		}
		$sql = "SELECT
		tblStsDtl.stsRefno, 
		tblStsHdr.suppCode + ' - ' + sql_mmpgtlib.dbo.APSUPP.ASNAME AS supplier, 
		tblStsDtl.stsNo, 
		tblBranches.strCode + ' - ' + tblBranches.brnShortDesc as branch, 
        tblStsDtl.stsAmt, 
		tblStsDtl.stsVatAmt, 
		tblStsHdr.dateEntered, 
		tblStsHdr.applyDate, 
		tblStsHdr.stsPaymentMode,
		tblStsHdr.dateApproved, 
		tblStsHierarchy.hierarchyDesc, 
        tblGroup.grpDesc,
		tblStsHdr.stsRemarks,
		tblUsers.fullName,
		tblStsHdr.contractNo,
		tblStsDtl.compCode,
		tblStsHdr.nbrApplication,
		tblBranches.compCode,
		tblStsDtl.dtlStatus,
		endDAte = CASE WHEN ststype = 5 THEN endDate ELSE DATEADD(month, nbrApplication, applyDate) END,
		payMode = CASE tblStsHdr.stsPaymentMode WHEN 'C' THEN 'Collection/Check' ELSE 'Invoice Deduction' END,
		companyCode = CASE tblStsDtl.compCode WHEN '700' THEN 'JR' ELSE 'PPCI' END,
		tblStsHierarchy.glMajor + tblStsHierarchy.glMinor as glCode
		FROM         tblStsDtl left JOIN
                      tblBranches ON tblStsDtl.strCode = tblBranches.strCode AND tblStsDtl.compCode = tblBranches.compCode left JOIN
                      tblStsHdr ON tblStsDtl.stsRefno = tblStsHdr.stsRefno left JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM left JOIN
                      tblStsHierarchy ON tblStsHdr.stsDept = tblStsHierarchy.stsDept AND tblStsHdr.stsCls = tblStsHierarchy.stsCls AND 
                      tblStsHdr.stsSubCls = tblStsHierarchy.stsSubCls left  JOIN
                      tblGroup ON tblStsHdr.grpCode = tblGroup.grpCode  left JOIN
                      tblUsers ON tblStsHdr.approvedBy = tblUsers.userId
		WHERE 
		$dt BETWEEN '$dtStart' AND '$dtEnd' 
		AND tblStsHdr.suppCode = '$suppCode'
		AND (tblStsHdr.stsStat = '$stat' $includeCancel)
		AND tblStsHdr.stsRefno in (SELECT distinct stsRefno FROM tblStsDtl)
		
	  	$grpVar
		$stsType";
		
		/*$sql = "SELECT
		tblStsDtl.stsRefno, 
		tblStsHdr.suppCode + ' - ' + sql_mmpgtlib.dbo.APSUPP.ASNAME AS supplier, 
		tblStsDtl.stsNo, 
		tblBranches.strCode + ' - ' + tblBranches.brnShortDesc as branch, 
        tblStsDtl.stsAmt, 
		tblStsHdr.dateEntered, 
		tblStsHdr.applyDate, 
		tblStsHdr.stsPaymentMode,
		tblStsHdr.dateApproved, 
		tblStsHierarchy.hierarchyDesc, 
        tblGroup.grpDesc,
		tblDisplaySpecs.displaySpecsDesc,
		payMode = CASE tblStsHdr.stsPaymentMode WHEN 'C' THEN 'Collection/Check' ELSE 'Invoice Deduction' END
FROM    tblDisplaySpecs 
		INNER JOIN
        tblStsDaDetail ON tblDisplaySpecs.displaySpecsId = tblStsDaDetail.dispSpecs 
		INNER JOIN
        tblStsDtl 
		INNER JOIN
        tblBranches ON tblStsDtl.strCode = tblBranches.strCode AND 
		tblStsDtl.compCode = tblBranches.compCode 
		INNER JOIN
        tblStsHdr ON tblStsDtl.stsRefno = tblStsHdr.stsRefno 
		INNER JOIN
        sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM 
		INNER JOIN
        tblStsHierarchy ON tblStsHdr.stsDept = tblStsHierarchy.stsDept AND tblStsHdr.stsCls = tblStsHierarchy.stsCls 
		AND tblStsHdr.stsSubCls = tblStsHierarchy.stsSubCls
		INNER JOIN
        tblGroup ON tblStsHdr.grpCode = tblGroup.grpCode ON tblStsDaDetail.compCode = tblStsDtl.compCode
		WHERE $dt BETWEEN '$dtStart' AND '$dtEnd' 
		AND tblStsHdr.suppCode = '$suppCode'
		AND tblStsHdr.stsStat = 'R'
		$trans_q
	  	$suppCode_q
		$grpVar
		$stsType";	*/
		
		return $this->getArrRes($this->execQry($sql));
	}
	//End Create

	//Create2 for dispaly allowance
	function getParDetail3($trans,$dtStart,$dtEnd,$stat,$suppCode,$grp){
		if($trans==0){
			$trans_q = "";	
		}else{
			$trans_q = "AND tblStsHdr.stsType = '$trans'";	
		}
		
		if($trans != '0'){
			if((int)$trans == 6){
				$stsType = "AND tblStsHdr.stsDept = 2 AND tblStsHdr.stsCls = 3 AND tblStsHdr.stsSubCls = 3";
			}elseif((int)$trans == 7){
				$stsType = "AND tblStsHdr.stsDept = 2 AND tblStsHdr.stsCls = 3 AND tblStsHdr.stsSubCls = 1";
			}else{
				$stsType = "AND tblStsHdr.stsType = $trans";	
			}
		}
		
		if($stat == 'R'){
			$dt = "CONVERT(datetime, CONVERT(varchar,tblStsHdr.dateApproved, 101))";
			$includeCancel = "OR tblStsHdr.stsStat = 'C'";
		}else{
			$dt = "CONVERT(datetime, CONVERT(varchar,tblStsHdr.dateEntered, 101))";
		}
		
		if($suppCode == '0'){
			$suppCode_q = "";
		}else{
			$suppCode_q = "AND tblStsHdr.suppCode = $suppCode";
		}
		
		if($grp==0){
			$grpVar = "";	
		}else{
			$grpVar = "AND tblStsHdr.grpCode = '$grp'";	
		}
		/*$sql = "SELECT
		tblStsDtl.stsRefno, 
		tblStsHdr.suppCode + ' - ' + sql_mmpgtlib.dbo.APSUPP.ASNAME AS supplier, 
		tblStsDtl.stsNo, 
		tblBranches.strCode + ' - ' + tblBranches.brnShortDesc as branch, 
        tblStsDtl.stsAmt, 
		tblStsHdr.dateEntered, 
		tblStsHdr.applyDate, 
		tblStsHdr.stsPaymentMode,
		tblStsHdr.dateApproved, 
		tblStsHierarchy.hierarchyDesc, 
        tblGroup.grpDesc,
		tblDisplaySpecs.displaySpecsDesc,
		payMode = CASE tblStsHdr.stsPaymentMode WHEN 'C' THEN 'Collection/Check' ELSE 'Invoice Deduction' END
		FROM         tblStsDtl INNER JOIN
                      tblBranches ON tblStsDtl.strCode = tblBranches.strCode AND tblStsDtl.compCode = tblBranches.compCode INNER JOIN
                      tblStsHdr ON tblStsDtl.stsRefno = tblStsHdr.stsRefno INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM INNER JOIN
                      tblStsHierarchy ON tblStsHdr.stsDept = tblStsHierarchy.stsDept AND tblStsHdr.stsCls = tblStsHierarchy.stsCls AND 
                      tblStsHdr.stsSubCls = tblStsHierarchy.stsSubCls INNER JOIN
                      tblGroup ON tblStsHdr.grpCode = tblGroup.grpCode INNER JOIN
                      tblStsDaDetail ON tblStsDtl.stsRefno = tblStsDaDetail.stsRefno INNER JOIN
                      tblDisplaySpecs ON tblStsDaDetail.dispSpecs = tblDisplaySpecs.displaySpecsId
					   WHERE $dt BETWEEN '$dtStart' AND '$dtEnd' 
		AND tblStsHdr.suppCode = '$suppCode'
		$trans_q
	  	$suppCode_q
		$grpVar
		$stsType";	*/	
		 $sql = "
				SELECT     
				tblStsDtl.stsRefno, 
				tblStsHdr.suppCode + ' - ' + sql_mmpgtlib.dbo.APSUPP.ASNAME AS supplier, 
				tblStsDtl.stsNo, 
                tblBranches.strCode + ' - ' + tblBranches.brnShortDesc AS branch, 
				tblStsDtl.stsAmt, 
				tblStsDtl.stsVatAmt, 
				tblStsHdr.dateEntered, 
				tblStsHdr.applyDate, 
                tblStsHdr.stsPaymentMode, 
				tblStsHdr.dateApproved, 
				tblStsHierarchy.hierarchyDesc, 
				tblGroup.grpDesc, 
				tblStsHdr.stsRemarks, 
				tblUsers.fullName, 
                tblStsHdr.contractNo, 
				tblStsDtl.compCode, 
				tblStsHdr.nbrApplication, 
				tblDisplaySpecs.displaySpecsDesc, 
				tblStsHdr.stsRemarks, 
				endDAte = CASE WHEN ststype = 1 THEN DATEADD(month, nbrApplication, applyDate) ELSE endDate END,
				payMode = CASE tblStsHdr.stsPaymentMode WHEN 'C' THEN 'Collection/Check' ELSE 'Invoice Deduction' END,
				companyCode = CASE tblStsDtl.compCode WHEN '700' THEN 'JR' ELSE 'PPCI' END
		FROM         tblStsDtl INNER JOIN
                      tblBranches ON tblStsDtl.strCode = tblBranches.strCode AND tblStsDtl.compCode = tblBranches.compCode INNER JOIN
                      tblStsHdr ON tblStsDtl.stsRefno = tblStsHdr.stsRefno INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM INNER JOIN
                      tblStsHierarchy ON tblStsHdr.stsDept = tblStsHierarchy.stsDept AND tblStsHdr.stsCls = tblStsHierarchy.stsCls AND 
                      tblStsHdr.stsSubCls = tblStsHierarchy.stsSubCls INNER JOIN
                      tblGroup ON tblStsHdr.grpCode = tblGroup.grpCode left JOIN
                      tblUsers ON tblStsHdr.approvedBy = tblUsers.userId LEFT OUTER JOIN
                      tblStsDaDetail ON tblStsDtl.stsRefno = tblStsDaDetail.stsRefno AND tblStsDtl.strCode = tblStsDaDetail.strCode AND 
                      tblStsDtl.stsNo = tblStsDaDetail.stsNo left  JOIN
                      tblDisplaySpecs ON tblStsDaDetail.dispSpecs = tblDisplaySpecs.displaySpecsId
					  WHERE $dt BETWEEN '$dtStart' AND '$dtEnd' 
		AND tblStsHdr.suppCode = '$suppCode'
		AND (tblStsHdr.stsStat = '$stat' $includeCancel)
		AND tblStsHdr.stsRefno in (SELECT distinct stsRefno FROM tblStsDtl)
		$trans_q
		$grpVar 
		";
		return $this->getArrRes($this->execQry($sql));
	}
	//End Create2
	
	function getParDetailDa($refNo){
		$sql = "SELECT     tblBranches.brnShortDesc, tblStsDtl.stsNo, tblStsDtl.stsAmt,tblDisplaySpecs.displaySpecsDesc
FROM         tblStsDtl INNER JOIN
                      tblBranches ON tblStsDtl.strCode = tblBranches.strCode AND tblStsDtl.compCode = tblBranches.compCode
					  INNER JOIN tblStsDaDetail on  (tblStsDaDetail.stsRefno = tblStsDtl.stsRefno AND tblStsDaDetail.compCode = tblStsDtl.compCode AND tblStsDaDetail.strCode = tblStsDtl.strCode)  INNER JOIN
                      tblDisplaySpecs ON tblStsDaDetail.dispSpecs = tblDisplaySpecs.displaySpecsId
					  WHERE tblStsDtl.stsRefno = '$refNo'";	
		return $this->getArrRes($this->execQry($sql));
	}
	function getStsBranch($dtFrom,$dtTo,$status,$trans){
		if($status =='O'){
			$stat = "AND (tblStsApply.status IS NULL)";
		}elseif($status == 'A'){
			$stat = "AND tblStsApply.status = 'A'";
		}else{
			$stat = "";	
		}
		if($trans != '0'){
			if((int)$trans == 6){
				$stsType = "AND tblStsApply.stsDept = 2 AND tblStsApply.stsCls = 3 AND tblStsApply.stsSubCls = 3";
			}elseif((int)$trans == 7){
				$stsType = "AND tblStsApply.stsDept = 2 AND tblStsApply.stsCls = 3 AND tblStsApply.stsSubCls = 1";
			}else{
				$stsType = "AND tblStsApply.stsType = $trans";	
			}
		}
		$sql = "SELECT DISTINCT tblStsApply.strCode, tblBranches.brnShortDesc
FROM         tblStsApply INNER JOIN
                      tblBranches ON tblStsApply.compCode = tblBranches.compCode AND tblStsApply.strCode = tblBranches.strCode LEFT OUTER JOIN
                      tblStsHdr ON tblStsApply.stsRefno = tblStsHdr.stsRefno
				WHERE tblStsApply.stsApplyDate BETWEEN '$dtFrom' AND '$dtTo' $stat $stsType";
		return $this->getArrRes($this->execQry($sql));
	}
	function getStsSupp($dtFrom,$dtTo,$status,$trans){
		if($status =='O'){
			$stat = "AND (tblStsApply.status IS NULL)";
		}elseif($status == 'A'){
			$stat = "AND tblStsApply.status = 'A'";
		}else{
			$stat = "";	
		}
		if($trans != '0'){
			if((int)$trans == 6){
				$stsType = "AND tblStsApply.stsDept = 2 AND tblStsApply.stsCls = 3 AND tblStsApply.stsSubCls = 3";
			}elseif((int)$trans == 7){
				$stsType = "AND tblStsApply.stsDept = 2 AND tblStsApply.stsCls = 3 AND tblStsApply.stsSubCls = 1";
			}else{
				$stsType = "AND tblStsApply.stsType = $trans";	
			}
		}
		$sql = "SELECT DISTINCT sql_mmpgtlib.dbo.APADDR.AANAME AS suppName, sql_mmpgtlib.dbo.APADDR.AANUM as suppCode
FROM         tblStsApply INNER JOIN tblBranches ON tblStsApply.compCode = tblBranches.compCode AND tblStsApply.strCode = tblBranches.strCode INNER JOIN sql_mmpgtlib.dbo.APADDR ON tblStsApply.suppCode = sql_mmpgtlib.dbo.APADDR.AANUM WHERE tblStsApply.stsApplyDate BETWEEN '$dtFrom' AND '$dtTo' $stat $stsType";
		return $this->getArrRes($this->execQry($sql));
	}
	function cancelledSTSDetail($dtFrom,$dtTo,$trans,$pMode,$cmbCancelType){
		if($trans != '0'){
			$stsType = "AND tblStsHdr.stsType = $trans";	
		}
		if($pMode != '0'){
			$paymentMode = "AND tblCancelledSTS.stsPaymentMode = '$pMode'";	
		}
		if($cmbCancelType != '0'){
			$stsCancelType = "AND tblCancelledSTS.cancelCode = $cmbCancelType";
		}
		/*
		$sql = "SELECT     tblCancelledSTS.stsNo, tblCancelledSTS.stsSeq, tblBranches.brnShortDesc, sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppName, 
                      tblCancelledSTS.uploadedAmt, tblCancelledSTS.queueAmt, tblCancelledSTS.stsStrAmt, tblGroup.grpDesc, tblStsHierarchy.hierarchyDesc, 
                      tblUsers.fullName, tblCancelledSTS.stsRefno, tblCancelledSTS.cancelDate, tblCancelType.cancelDesc, tblCancelledSTS.cancelDate AS effectiviyDate, 
                      tblStsHdr.stsRefno AS Expr1, tblStsHdr.suppCode
FROM         tblCancelledSTS INNER JOIN
                      tblBranches ON tblCancelledSTS.strCode = tblBranches.strCode left JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblCancelledSTS.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM left JOIN
                      tblGroup ON tblCancelledSTS.grpCode = tblGroup.grpCode left JOIN
                      tblStsHierarchy ON tblCancelledSTS.stsDept = tblStsHierarchy.stsDept AND tblCancelledSTS.stsCls = tblStsHierarchy.stsCls AND 
                      tblCancelledSTS.stsSubCls = tblStsHierarchy.stsSubCls left JOIN
                      tblUsers ON tblCancelledSTS.cancelledBy = tblUsers.userId left JOIN
                      tblCancelType ON tblCancelledSTS.cancelCode = tblCancelType.cancelId left JOIN
                      tblStsHdr ON tblCancelledSTS.stsRefno = tblStsHdr.stsRefno
				WHERE tblCancelledSTS.cancelDate BETWEEN '$dtFrom' AND '$dtTo' AND tblCancelledSTS.stsRefno = '$refNo' $stsType
				ORDER BY tblCancelledSTS.stsRefno,tblCancelledSTS.stsNo, tblCancelledSTS.stsSeq";
		*/
		/*$sql = "SELECT     tblStsHdr.stsRefno, tblStsHdr.stsRemarks, tblCancelledSTS.stsNo, tblStsDtl.stsAmt, tblStsDtl.compCode,
		(SELECT     SUM(stsVatAmt)
                           FROM          tblStsDtl
                           WHERE      tblStsHdr.stsRefNo = tblStsDtl.stsRefNo) AS stsVatAmt,
		 tblStsDtl.stsVatAmt, tblCancelledSTS.stsSeq, tblCancelledSTS.uploadedAmt, 
                      tblCancelledSTS.queueAmt, tblCancelledSTS.cancelledBy, tblCancelledSTS.cancelDate, tblCancelledSTS.effectivityDate, tblCancelledSTS.stsStrAmt, 
                      sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppName, tblUsers.fullName, tblCancelType.cancelDesc, tblCancelledSTS.stsPaymentMode,
					  CASE WHEN tblStsDtl.stsAmt IS NULL THEN tblCancelledSTS.stsStrAmt ELSE tblStsDtl.stsAmt END as amtStsStr
FROM         tblCancelledSTS LEFT JOIN
                      tblStsHdr ON tblCancelledSTS.stsRefno = tblStsHdr.stsRefno LEFT JOIN
                      tblCancelType ON tblCancelledSTS.cancelCode = tblCancelType.cancelId LEFT OUTER JOIN
                      tblUsers ON tblCancelledSTS.cancelledBy = tblUsers.userId LEFT OUTER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblCancelledSTS.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM LEFT OUTER JOIN
                      tblStsDtl ON tblCancelledSTS.stsRefno = tblStsDtl.stsRefno AND tblCancelledSTS.stsNo = tblStsDtl.stsNo
					  $stsType
					WHERE
					tblCancelledSTS.cancelDate BETWEEN '$dtFrom' AND '$dtTo' 
					$stsType
					$paymentMode
				ORDER BY tblCancelledSTS.stsNo";
				*/
		$sql = "SELECT     tblCancelledSTS.stsRefno, tblStsHdr.stsRemarks, tblCancelledSTS.stsNo, tblStsDtl.stsAmt, tblStsDtl.compCode,
                          tblStsDtl.stsVatAmt as stsVatAmount, tblCancelledSTS.stsSeq, tblCancelledSTS.uploadedAmt, 
                      tblCancelledSTS.queueAmt, tblCancelledSTS.cancelledBy, tblCancelledSTS.cancelDate, tblCancelledSTS.effectivityDate, tblCancelledSTS.stsStrAmt, 
                      sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppName, tblUsers.fullName, tblCancelType.cancelDesc, tblCancelledSTS.stsPaymentMode,   tblStsHierarchy.hierarchyDesc,
                      tblCancelledSTS.stsType, tblCancelledSTS.replacementSts, tblBranches.brnDesc, tblStsHdr.dateEntered, tblBranches.strCode,
					  CASE WHEN tblStsDtl.stsAmt IS NULL THEN tblCancelledSTS.stsStrAmt ELSE tblStsDtl.stsAmt END as amtStsStr,
					  companyCode = CASE tblStsDtl.compCode WHEN '700' THEN 'JR' ELSE 'PPCI' END,
					  payMode = CASE tblStsHdr.stsPaymentMode WHEN 'C' THEN 'Collection/Check' ELSE 'Invoice Deduction' END
FROM         tblCancelledSTS INNER JOIN
                      tblStsHierarchy ON tblCancelledSTS.stsDept = tblStsHierarchy.stsDept AND tblCancelledSTS.stsCls = tblStsHierarchy.stsCls AND 
                      tblCancelledSTS.stsSubCls = tblStsHierarchy.stsSubCls  LEFT OUTER JOIN
                      tblStsHdr ON tblCancelledSTS.stsRefno = tblStsHdr.stsRefno LEFT OUTER JOIN
                      tblCancelType ON tblCancelledSTS.cancelCode = tblCancelType.cancelId LEFT OUTER JOIN
                      tblUsers ON tblCancelledSTS.cancelledBy = tblUsers.userId LEFT OUTER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblCancelledSTS.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM LEFT OUTER JOIN
                      tblStsDtl ON tblCancelledSTS.stsRefno = tblStsDtl.stsRefno AND tblCancelledSTS.stsNo = tblStsDtl.stsNo LEFT OUTER JOIN
                      tblBranches ON tblCancelledSTS.strCode = tblBranches.strCode
					WHERE
					tblCancelledSTS.cancelDate BETWEEN '$dtFrom' AND '$dtTo' 
					$stsType
					$paymentMode
					$stsCancelType
				ORDER BY tblCancelledSTS.stsNo";
				//AND tblCancelledSTS.stsRefno = '$refNo' $stsType
		return $this->getArrRes($this->execQry($sql));
	}
	
	function cancelledSTSDetailHeader($dtFrom,$dtTo,$trans,$pMode,$cmbCancelType){
		if($trans != '0'){
			$stsType = "AND tblStsHdr.stsType = $trans";	
		}
		if($pMode != '0'){
			$paymentMode = "AND tblStsHdr.stsPaymentMode = '$pMode'";	
		}
		if($cmbCancelType != '0'){
			$stsCancelType = "AND tblStsHdr.cancelId = $cmbCancelType";
		}
		$sql = "SELECT     tblStsHdr.stsRefno, tblStsHdr.stsRemarks, sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppName, tblStsApply.stsApplyAmt, tblStsApply.stsVatAmt, tblStsHdr.cancelDate, 
                      tblStsHdr.cancelledBy, tblStsHdr.cancelDate AS effectivityDate, tblStsHierarchy.hierarchyDesc, tblStsHdr.stsPaymentMode, tblStsApply.compCode, 
                      tblUsers.fullName, tblStsApply.stsNo, 
                      tblStsApply.stsSeq, tblStsHdr.stsType, tblStsHdr.cancelId, tblBranches.brnDesc, tblStsHdr.dateEntered, tblBranches.strCode,
					  companyCode = CASE tblStsApply.compCode WHEN '700' THEN 'JR' ELSE 'PPCI' END,
					  payMode = CASE tblStsHdr.stsPaymentMode WHEN 'C' THEN 'Collection/Check' ELSE 'Invoice Deduction' END
FROM         tblStsHdr INNER JOIN
                      tblStsApply ON tblStsHdr.stsRefno = tblStsApply.stsRefno AND tblStsHdr.stsDept = tblStsApply.stsDept AND tblStsHdr.stsCls = tblStsApply.stsCls AND 
                      tblStsHdr.stsSubCls = tblStsApply.stsSubCls LEFT OUTER JOIN
                      tblStsHierarchy ON tblStsHdr.stsSubCls = tblStsHierarchy.stsSubCls AND tblStsHdr.stsCls = tblStsHierarchy.stsCls AND 
                      tblStsHdr.stsDept = tblStsHierarchy.stsDept LEFT OUTER JOIN
                      tblBranches ON tblStsApply.strCode = tblBranches.strCode LEFT OUTER JOIN
                      tblUsers ON tblStsHdr.cancelledBy = tblUsers.userId LEFT OUTER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM
WHERE 
(tblStsHdr.stsRefno NOT IN
                          (SELECT DISTINCT (stsRefno)
                            FROM          tblCancelledSTS)) AND
					(tblStsHdr.cancelDate BETWEEN '$dtFrom' AND '$dtTo')
					$stsType
					$paymentMode
					$stsCancelType
				ORDER BY tblStsApply.stsNo";
				//AND tblCancelledSTS.stsRefno = '$refNo' $stsType
		return $this->getArrRes($this->execQry($sql));
	}
	
	function expiredContractsSumm($monthYear,$trans){
		if($trans != '0'){
			$stsType = "AND tblStsHdr.stsType = $trans";	
		}
		$sql = "SELECT     tblStsHdr.stsRefno, tblStsHdr.stsRemarks, tblStsHdr.stsAmt, tblStsHdr.stsPaymentMode,
		                          (SELECT     SUM(stsVatAmt)
                            FROM          tblStsDtl
                            WHERE      tblStsHdr.stsRefNo = tblStsDtl.stsRefNo) AS uploadedVatAmt,
		tblStsHdr.stsStartNo, tblStsHdr.stsEndNo, 
                          (SELECT     SUM(stsApplyAmt)
                            FROM          tblstsapply
                            WHERE      tblstsapply.stsRefNo = tblStsHdr.stsRefNo AND status = 'A') AS uploadedAmt,
                          (SELECT     SUM(stsApplyAmt)
                            FROM          tblstsapply
                            WHERE      tblstsapply.stsRefNo = tblStsHdr.stsRefNo AND status IS NULL) AS queueAmt, tblUsers.fullName, 
                      CASE WHEN ststype = '5' THEN endDate ELSE DATEADD(month, nbrApplication - 1, applyDate) END AS expiration,
					  CASE WHEN tblStsHdr.stsStat = 'R' THEN 'RELEASED' ELSE 'OPEN' END as status,
					  sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppName
FROM         tblStsHdr INNER JOIN
                      tblUsers ON tblStsHdr.enteredBy = tblUsers.userId
					  INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM
WHERE     month(CASE WHEN ststype = '5' THEN endDate ELSE DATEADD(month, nbrApplication - 1, applyDate) END) = month('".$monthYear."') AND 
                      year(CASE WHEN ststype = '5' THEN endDate ELSE DATEADD(month, nbrApplication - 1, applyDate) END) = year('".$monthYear."') $stsType";
		return $this->getArrRes($this->execQry($sql));
	}
	function expiredContractDtl($stsRefNo,$trans){
		if($trans != '0'){
			$stsType = "AND tblStsHdr.stsType = $trans";	
		}
		$sql = "SELECT     tblStsDtl.stsNo, tblStsDtl.stsAmt,tblStsDtl.stsVatAmt,tblStsDtl.strCode, tblStsHdr.stsPaymentMode,
                          (SELECT     SUM(stsApplyAmt)
                            FROM          tblstsapply
                            WHERE      tblstsapply.stsNo = tblStsDtl.stsNo AND status = 'A') AS uploadedAmt,
                          (SELECT     SUM(stsApplyAmt)
                            FROM          tblstsapply
                            WHERE      tblstsapply.stsRefNo = tblStsDtl.stsRefNo AND status IS NULL) AS queueAmt, tblGroup.grpDesc, tblStsHierarchy.hierarchyDesc, tblStsHdr.stsType
FROM         tblStsDtl INNER JOIN
                      tblStsHdr ON tblStsDtl.stsRefno = tblStsHdr.stsRefno INNER JOIN
                      tblGroup ON tblStsHdr.grpCode = tblGroup.grpCode INNER JOIN
                      tblStsHierarchy ON tblStsHdr.stsDept = tblStsHierarchy.stsDept
WHERE     (tblStsDtl.stsNo IS NOT NULL) and tblStsDtl.stsRefno = '$stsRefNo' $stsType";	
		return $this->getArrRes($this->execQry($sql));
	}
	
	function uploadedTransmittal($type,$trans,$comp, $dtStart,$dtEnd){
		if($type=='AP')
		{
			$tbl = 'tblStsDlyApHist';
		}
		else
		{
			$tbl = 'tblStsDlyArHist';
			$stsVatAmt = ',tblStsDlyArHist.stsVatAmt';
		}
		
		if($trans==0)
		{
			$colQ = "";
		}
		else
		{
			$colQ = " AND (tblTransType.typeCode = $trans)";
		}
		
		if($comp=='PPCI')
		{
			$colQ2 = "AND ($tbl.compCode <> 700)";
		}
		elseif($comp=='PG-JR')
		{
			$colQ2 = "AND ($tbl.compCode = 700)";
		}
		else
		{
			$colQ2 = "";
		}
		
		$sql = "SELECT     CAST(tblTransType.typePrefix AS varchar) + CAST($tbl.stsNo AS varchar) + '-' + CAST($tbl.stsSeq AS varchar) AS InvNo, 
                      $tbl.stsApplyAmt, CAST($tbl.suppCode AS varchar) + '-' + CAST(pg_pf.dbo.tblSuppliers.suppName AS varchar) AS Supplier, 
                      CAST($tbl.strCode AS varchar) + '-' + CAST(tblBranches.brnDesc AS varchar) AS Store, tblStsHierarchy.hierarchyDesc, 
                      $tbl.stsApplyDate, $tbl.uploadDate $stsVatAmt
FROM         $tbl INNER JOIN
                      tblBranches ON $tbl.strCode = tblBranches.strCode INNER JOIN
                      pg_pf.dbo.tblSuppliers ON $tbl.suppCode = pg_pf.dbo.tblSuppliers.suppCode INNER JOIN
                      tblTransType ON $tbl.stsType = tblTransType.typeCode INNER JOIN
                      tblStsHierarchy ON $tbl.stsDept = tblStsHierarchy.stsDept AND $tbl.stsCls = tblStsHierarchy.stsCls AND 
                      $tbl.stsSubCls = tblStsHierarchy.stsSubCls
WHERE     ($tbl.uploadDate BETWEEN '$dtStart' AND '$dtEnd') $colQ $colQ2";
		return $this->getArrRes($this->execQry($sql));
	}
	function findGroup(){
		$sql = "select * from tblgroup";	
		return $this->getArrRes($this->execQry($sql));
	}
	function findGroupName($grpCode){
		$sql = "SELECT grpDesc FROM tblGroup where grpCode = '$grpCode'";	
		$grpName = $this->getSqlAssoc($this->execQry($sql));
		return $grpName['grpDesc']==''? 'ALL':$grpName['grpDesc'];
	}
	function stsSummary($trans,$dtStart,$dtEnd,$group,$suppCode){
		if($trans != '0'){
			if((int)$trans == 6){
				$stsType = "AND tblStsHdr.stsDept = 2 AND tblStsHdr.stsCls = 3 AND tblStsHdr.stsSubCls = 3";
			}elseif((int)$trans == 7){
				$stsType = "AND tblStsHdr.stsDept = 2 AND tblStsHdr.stsCls = 3 AND tblStsHdr.stsSubCls = 1";
			}else{
				$stsType = "AND tblStsHdr.stsType = $trans";	
			}
		}
		
		$stsGroup = "AND grpCode = $group";	
		
		if($suppCode != '0'){
			$stsSupp = "AND tblStsHdr.suppCode = $suppCode";	
		}
		$sql = "SELECT     tblStsHdr.stsRefno, tblStsHdr.suppCode, sql_mmpgtlib.dbo.APSUPP.ASNAME as suppName, tblStsDtl.stsNo, tblStsHdr.nbrApplication, tblStsHdr.dateApproved, 
                      tblStsHdr.stsPaymentMode, tblStsHdr.stsRemarks, tblBranches.brnShortDesc as brnDesc, tblStsDtl.strCode, tblStsDtl.stsAmt, tblStsDtl.stsVatAmt,
                          (SELECT     hierarchyDesc
                            FROM          tblStsHierarchy
                            WHERE      tblStsHierarchy.stsDept = tblStsHdr.stsDept AND levelCode = 1) AS dept,
                          (SELECT     hierarchyDesc
                            FROM          tblStsHierarchy
                            WHERE      tblStsHierarchy.stsDept = tblStsHdr.stsDept AND tblStsHierarchy.stsCls = tblStsHdr.stsCls AND levelCode = 2) AS cls,
                          (SELECT     hierarchyDesc
                            FROM          tblStsHierarchy
                            WHERE      tblStsHierarchy.stsDept = tblStsHdr.stsDept AND tblStsHierarchy.stsCls = tblStsHdr.stsCls AND tblStsHierarchy.stsSubCls = tblStsHdr.stsSubCls AND 
                                                   levelCode = 3) AS subCls, payMode = CASE tblStsHdr.stsPaymentMode WHEN 'C' THEN 'Collection/Check' ELSE 'Invoice Deduction' END
FROM         tblStsHdr INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM INNER JOIN
                      tblStsDtl ON tblStsHdr.stsRefno = tblStsDtl.stsRefno INNER JOIN
                      tblBranches ON tblStsDtl.strCode = tblBranches.strCode AND tblStsDtl.compCode = tblBranches.compCode
			WHERE  tblStsHdr.dateApproved between '$dtStart' AND '$dtEnd' $stsType $stsGroup $stsSupp
			order by  tblStsHdr.stsRefno";	
		return $this->getArrRes($this->execQry($sql));
	}
	function stsSummaryUnapproved($trans,$dtStart,$dtEnd,$group,$suppCode){
		if($trans != '0'){
			if((int)$trans == 6){
				$stsType = "AND tblStsHdr.stsDept = 2 AND tblStsHdr.stsCls = 3 AND tblStsHdr.stsSubCls = 3";
			}elseif((int)$trans == 7){
				$stsType = "AND tblStsHdr.stsDept = 2 AND tblStsHdr.stsCls = 3 AND tblStsHdr.stsSubCls = 1";
			}else{
				$stsType = "AND tblStsHdr.stsType = $trans";	
			}
		}
		
		$stsGroup = "AND tblStsHdr.grpCode = $group";	
		
		if($suppCode != '0'){
			$stsSupp = "AND tblStsHdr.suppCode = $suppCode";	
		}
		$sql = "SELECT     tblStsHdr.stsRefno, tblStsHdr.suppCode, sql_mmpgtlib.dbo.APSUPP.ASNAME as suppName, tblStsDtl.stsNo, tblStsHdr.nbrApplication, tblStsHdr.dateEntered, 
                      tblStsHdr.stsPaymentMode, tblStsHdr.stsRemarks, tblBranches.brnShortDesc as brnDesc, tblStsDtl.strCode, tblStsDtl.stsAmt,
                          (SELECT     hierarchyDesc
                            FROM          tblStsHierarchy
                            WHERE      tblStsHierarchy.stsDept = tblStsHdr.stsDept AND levelCode = 1) AS dept,
                          (SELECT     hierarchyDesc
                            FROM          tblStsHierarchy
                            WHERE      tblStsHierarchy.stsDept = tblStsHdr.stsDept AND tblStsHierarchy.stsCls = tblStsHdr.stsCls AND levelCode = 2) AS cls,
                          (SELECT     hierarchyDesc
                            FROM          tblStsHierarchy
                            WHERE      tblStsHierarchy.stsDept = tblStsHdr.stsDept AND tblStsHierarchy.stsCls = tblStsHdr.stsCls AND tblStsHierarchy.stsSubCls = tblStsHdr.stsSubCls AND 
                                                   levelCode = 3) AS subCls, payMode = CASE tblStsHdr.stsPaymentMode WHEN 'C' THEN 'Collection/Check' ELSE 'Invoice Deduction' END, tblUsers.userName
FROM         tblStsHdr INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM INNER JOIN
                      tblStsDtl ON tblStsHdr.stsRefno = tblStsDtl.stsRefno INNER JOIN
                      tblBranches ON tblStsDtl.strCode = tblBranches.strCode AND tblStsDtl.compCode = tblBranches.compCode
					  INNER JOIN
                      tblUsers ON tblStsHdr.enteredBy = tblUsers.userId
			WHERE  CONVERT(datetime, CONVERT(varchar,tblStsHdr.dateEntered, 101))  between '$dtStart' AND '$dtEnd' AND tblStsHdr.dateApproved is null $stsType $stsGroup $stsSupp
			order by  tblStsHdr.stsRefno";	
		return $this->getArrRes($this->execQry($sql));
	}
	
	function supplierNotToBeUse($dtStart,$dtEnd){
		$sql = "

SELECT     sql_mmpgtlib.dbo.APSUPP.ASNAME, tblStsDlyAr.suppCode, tblStsDlyAr.stsNo, tblStsDlyAr.stsSeq, tblStsDlyAr.stsRefno, tblDepartment.deptDesc, tblStsHdr.stsAmt, 
                      tblStsDlyAr.stsApplyDate, tblStsDlyAr.stsApplyAmt
FROM         tblStsDlyAr INNER JOIN
                      tblDepartment ON tblStsDlyAr.stsDept = tblDepartment.minCode AND tblStsDlyAr.compCode = tblDepartment.compCode INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsDlyAr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM INNER JOIN
                      tblStsHdr ON tblStsDlyAr.stsRefno = tblStsHdr.stsRefno AND tblStsDlyAr.suppCode = tblStsHdr.suppCode
WHERE     (tblStsDlyAr.suppCode IN
                          (SELECT     ASNUM
                            FROM          sql_mmpgtlib..APSUPP
                            WHERE      (ASNAME LIKE '%NTBU%'))) OR
                      (tblStsDlyAr.suppCode IN
                          (SELECT     ADNUM
                            FROM          sql_mmpgtlib..APSUPA
                            WHERE      (ADSTS = 'I'))) AND (tblStsDlyAr.stsApplyDate >= '$dtStart') AND (tblStsDlyAr.stsApplyDate <= '$dtEnd')
UNION
SELECT     sql_mmpgtlib.dbo.APSUPP.ASNAME, tblStsDlyAp.suppCode, tblStsDlyAp.stsNo, tblStsDlyAp.stsSeq, tblStsDlyAp.stsRefno, tblDepartment.deptDesc, tblStsHdr.stsAmt, 
                      tblStsDlyAp.stsApplyDate, tblStsDlyAp.stsApplyAmt
FROM         tblStsDlyAp INNER JOIN
                      tblDepartment ON tblStsDlyAp.stsDept = tblDepartment.minCode AND tblStsDlyAp.compCode = tblDepartment.compCode INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsDlyAp.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM INNER JOIN
                      tblStsHdr ON tblStsDlyAp.stsRefno = tblStsHdr.stsRefno AND tblStsDlyAp.suppCode = tblStsHdr.suppCode
WHERE     (tblStsDlyAp.suppCode IN
                          (SELECT     ASNUM
                            FROM          sql_mmpgtlib..APSUPP
                            WHERE      (ASNAME LIKE '%NTBU%'))) OR
                      (tblStsDlyAp.suppCode IN
                          (SELECT     ADNUM
                            FROM          sql_mmpgtlib..APSUPA
                            WHERE      (ADSTS = 'I'))) AND (tblStsDlyAp.stsApplyDate >= '$dtStart') AND (tblStsDlyAp.stsApplyDate <= '$dtEnd')";
		
		return $this->getArrRes($this->execQry($sql));
	}
	function findCancelType(){
		$sql = "
		SELECT     cancelId, cancelDesc, cancelStat, createdBy, dateAdded, refRequiredTag
FROM         tblCancelType
WHERE     (cancelStat = 'A')
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function autoApproved($dtStart,$dtEnd,$group){

		//$stsGroup = "AND grpCode = $group";	

		$sql = "
		SELECT     
		tblStsHdr.stsRefno, tblStsHdr.suppCode, tblStsHdr.stsAmt, tblStsHdr.stsRemarks, tblStsHdr.stsPaymentMode, 
		tblStsHdr.nbrApplication, tblStsHdr.applyDate, tblStsHdr.enteredBy, tblStsHdr.dateEntered, tblStsHdr.grpCode, 
		tblStsHdr.dateApproved, tblStsHdr.approvedBy, tblStsHdr.autoTag, tblGroup.grpDesc, sql_mmpgtlib.dbo.APSUPP.ASNAME,  
		tblUsers_1.fullName as enteredBy, tblUsers_2.fullName AS approvedBy,
		payMode = CASE tblStsHdr.stsPaymentMode WHEN 'C' THEN 'Collection/Check' ELSE 'Invoice Deduction' END
		FROM tblStsHdr LEFT OUTER JOIN
		tblUsers tblUsers_1 ON tblStsHdr.approvedBy = tblUsers_1.userId LEFT OUTER JOIN
		tblUsers tblUsers_2 ON tblStsHdr.enteredBy = tblUsers_2.userId LEFT OUTER JOIN
		tblGroup ON tblStsHdr.grpCode = tblGroup.grpCode LEFT OUTER JOIN
		sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM
		WHERE (tblStsHdr.autoTag = 'Y') 
		AND (tblStsHdr.dateApproved BETWEEN '$dtStart' AND '$dtEnd')";	
		return $this->getArrRes($this->execQry($sql));
	}
	
	function deductionsNature($dtStart,$dtEnd,$suppCode){
		$sql = "
		SELECT     tblStsDlyApHist.strCode, sql_mmpgtlib.dbo.TBLSTR.STRNAM, tblStsDlyApHist.stsNo, tblTransType.typePrefix + CAST(tblStsDlyApHist.stsNo AS nvarchar) 
		+ '-' + CAST(tblStsDlyApHist.stsSeq AS nvarchar) AS invoice, tblStsHdr.suppCode, sql_mmpgtlib.dbo.APSUPP.ASNAME, tblStsDlyApHist.stsApplyAmt, tblStsHdr.applyDate, tblStsApply.stsApplyDate, 
		tblStsHdr.nbrApplication, tblStsHdr.endDate, tblStsHdr.contractNo, tblStsHdr.enteredBy, tblStsHdr.approvedBy, tblStsHdr.stsRemarks, 
		userCreated.fullName AS createdBy, userApprove.fullName AS approvedBy, OraApChecks.Col001 AS creationDate, OraApChecks.Col002 AS invoiceNum, 
		OraApChecks.Col003 AS segment1, OraApChecks.Col004 AS checkNumber, OraApChecks.Col005 AS lastUpdateDate, OraApChecks.Col006 AS orgId
		FROM         tblStsDlyApHist INNER JOIN
		tblStsHdr ON tblStsDlyApHist.stsRefno = tblStsHdr.stsRefno INNER JOIN
		tblStsApply ON tblStsDlyApHist.stsNo = tblStsApply.stsNo AND tblStsDlyApHist.stsSeq = tblStsApply.stsSeq AND 
		tblStsDlyApHist.stsRefno = tblStsApply.stsRefno LEFT OUTER JOIN
		tblUsers userCreated ON userCreated.userId = tblStsHdr.enteredBy LEFT OUTER JOIN
		tblUsers userApprove ON userApprove.userId = tblStsHdr.approvedBy LEFT OUTER JOIN
		tblTransType ON tblTransType.typeCode = tblStsDlyApHist.stsType LEFT OUTER JOIN
		sql_mmpgtlib.dbo.APSUPP on sql_mmpgtlib.dbo.APSUPP.ASNUM = tblStsHdr.suppCode LEFT OUTER JOIN
		sql_mmpgtlib.dbo.TBLSTR ON sql_mmpgtlib.dbo.TBLSTR.STRNUM = tblStsDlyApHist.strCode LEFT OUTER JOIN
		OraApChecks ON OraApChecks.Col002 = tblTransType.typePrefix + CAST(tblStsDlyApHist.stsNo AS nvarchar) + '-' + CAST(tblStsDlyApHist.stsSeq AS nvarchar) AND 
		OraApChecks.Col003 = tblStsHdr.suppCode
		WHERE     (tblStsHdr.applyDate BETWEEN '{$dtStart}' AND '{$dtEnd}')
		and tblStsHdr.suppCode = {$suppCode}
		order by sql_mmpgtlib.dbo.TBLSTR.STRNAM,tblStsHdr.applyDate
		";	
		return $this->getArrRes($this->execQry($sql));
	}
	
	function deductionsSupplier($suppCode){
		$sql = "SELECT DISTINCT 
		sql_mmpgtlib.dbo.APSUPP.ASNAME
		AS suppName, sql_mmpgtlib.dbo.APSUPP.ASNUM AS 
		suppCode, CAST(sql_mmpgtlib.dbo.APSUPP.ASNUM AS 
		varchar) 
		+ ' - ' + 
		sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppCodeName
		FROM         tblStsHdr left  JOIN
		sql_mmpgtlib.dbo.APSUPP ON 
		tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM
		where sql_mmpgtlib.dbo.APSUPP.ASNUM = {$suppCode}
		";
		return $this->getSqlAssoc($this->execQry($sql));
	}
	
	####onHold
	function getStsOnHold($dtFrom,$dtTo){
		$sql = "SELECT     tblStsHdr.*, sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppName
FROM         tblStsHdr INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM
			where dateEntered between '$dtFrom' AND '$dtTo' AND onHoldTag = 'Y'";
		return $this->getArrRes($this->execQry($sql));
	}
}
?>