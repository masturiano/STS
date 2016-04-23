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
		$sql = "SELECT prodName FROM tblprodgrp WHERE prodID = '$code'";	
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
		$sql = "SELECT     tblStsApply.stsType, tblStsApply.stsNo, tblStsApply.stsApplyAmt, pg_pf.dbo.tblBranches.brnDesc, sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppName, tblStsApply.stsSeq, 
                      tblStsApply.stsApplyDate, tblStsApply.stsRefno, tblStsHdr.nbrApplication, tblStsHdr.enteredBy, tblStsHdr.stsRemarks, tblStsDlyApHist.apBatch, 
                      tblStsDlyArHist.arBatch, tblStsApply.stsPaymentMode, payMode = CASE tblStsHdr.stsPaymentMode WHEN 'C' THEN 'Collection/Check' ELSE 'Invoice Deduction' END, 
                      applyStatus = CASE tblStsApply.status WHEN NULL THEN 'ONQUEUE' ELSE 'APPLIED' END
FROM         tblStsApply INNER JOIN
                      pg_pf.dbo.tblBranches ON tblStsApply.compCode = pg_pf.dbo.tblBranches.compCode AND tblStsApply.strCode = pg_pf.dbo.tblBranches.strCode INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsApply.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM LEFT OUTER JOIN
                      tblStsDlyApHist ON tblStsApply.stsNo = tblStsDlyApHist.stsNo AND tblStsApply.stsSeq = tblStsDlyApHist.stsSeq AND 
                      tblStsApply.stsRefno = tblStsDlyApHist.stsRefno LEFT OUTER JOIN
                      tblStsDlyArHist ON tblStsApply.stsNo = tblStsDlyArHist.stsNo AND tblStsApply.stsSeq = tblStsDlyArHist.stsSeq AND 
                      tblStsApply.stsRefno = tblStsDlyArHist.stsRefno LEFT OUTER JOIN
                      tblStsHdr ON tblStsApply.stsRefno = tblStsHdr.stsRefno
				WHERE tblStsApply.stsApplyDate BETWEEN '$dtFrom' AND '$dtTo' AND pg_pf.dbo.tblBranches.strCode = '$str' $stat $stsType  ORDER BY tblStsApply.status ";
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
		$sql = "SELECT     tblStsApply.stsType, tblStsApply.stsNo, tblStsApply.stsApplyAmt, pg_pf.dbo.tblBranches.brnDesc, sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppName, tblStsApply.stsSeq, 
                      tblStsApply.stsApplyDate, tblStsApply.stsRefno, tblStsHdr.nbrApplication, tblStsHdr.enteredBy, tblStsHdr.stsRemarks, tblStsDlyApHist.apBatch, 
                      tblStsDlyArHist.arBatch, payMode = CASE tblStsHdr.stsPaymentMode WHEN 'C' THEN 'Collection/Check' ELSE 'Invoice Deduction' END, 
                      applyStatus = CASE tblStsApply.status WHEN NULL THEN 'ONQUEUE' ELSE 'APPLIED' END
FROM         tblStsApply INNER JOIN
                      pg_pf.dbo.tblBranches ON tblStsApply.compCode = pg_pf.dbo.tblBranches.compCode AND tblStsApply.strCode = pg_pf.dbo.tblBranches.strCode LEFT OUTER JOIN
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
		 $sql = "SELECT     tblStsHdr.stsRefno, sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppName, tblStsHdr.stsAmt, tblStsHdr.applyDate, tblStsHdr.dateEntered, tblStsHdr.nbrApplication, 
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
					  WHERE $dt BETWEEN '$dtStart' AND '$dtEnd' AND tblStsHdr.suppCode = '$suppCode' AND tblStsHdr.stsStat = '$stat' $filter $stsType $grpVar ";	
		return $this->getArrRes($this->execQry($sql));
	}
	function transSummarySupp($trans,$dtStart,$dtEnd,$stat,$supp,$grp){
		if($stat != '0')
			$filter1 = "AND tblStsHdr.stsStat = '$stat'";
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
			$dt = "tblStsHdr.dateEntered";	
		}
		if($grp==0){
			$grpVar = "";	
		}else{
			$grpVar = "AND tblStsHdr.grpCode = '$grp'";	
		}
		$sql = "SELECT DISTINCT  sql_mmpgtlib.dbo.APADDR.AANAME AS suppName, sql_mmpgtlib.dbo.APADDR.AANUM AS suppCode
FROM         tblStsHdr INNER JOIN
                      sql_mmpgtlib.dbo.APADDR ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APADDR.AANUM
WHERE $dt  BETWEEN '$dtStart' AND '$dtEnd' AND tblStsHdr.stsStat = '$stat' $stsType $filter1 $filter2 $grpVar ORDER BY pg_pf.dbo.tblStsHdr.suppCode ";	
		return $this->getArrRes($this->execQry($sql));
	}
	function cancelledSTSSummary($dtFrom,$dtTo,$trans){
		if($trans != '0'){
			if((int)$trans == 6){
				$stsType = "AND tblStsHdr.stsDept = 2 AND tblStsHdr.stsCls = 3 AND tblStsHdr.stsSubCls = 3";
			}elseif((int)$trans == 7){
				$stsType = "AND tblStsHdr.stsDept = 2 AND tblStsHdr.stsCls = 3 AND tblStsHdr.stsSubCls = 1";
			}else{
				$stsType = "AND tblStsHdr.stsType = $trans";	
			}
		}
		/*$sql = "SELECT     
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
		tblCancelledSts.cancelDate";	*/
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
		$sql = "SELECT     pg_pf.dbo.tblBranches.brnShortDesc, tblStsDtl.stsNo, tblStsDtl.stsAmt
FROM         tblStsDtl INNER JOIN
                      pg_pf.dbo.tblBranches ON tblStsDtl.strCode = pg_pf.dbo.tblBranches.strCode AND tblStsDtl.compCode = pg_pf.dbo.tblBranches.compCode 
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
		pg_pf.dbo.tblBranches.strCode + ' - ' + pg_pf.dbo.tblBranches.brnShortDesc as branch, 
        tblStsDtl.stsAmt, 
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
		endDAte = CASE WHEN ststype = 5 THEN endDate ELSE DATEADD(month, nbrApplication, applyDate) END,
		payMode = CASE tblStsHdr.stsPaymentMode WHEN 'C' THEN 'Collection/Check' ELSE 'Invoice Deduction' END,
		companyCode = CASE tblStsDtl.compCode WHEN '700' THEN 'JR' ELSE 'PPCI' END
		FROM         tblStsDtl INNER JOIN
                      pg_pf.dbo.tblBranches ON tblStsDtl.strCode = pg_pf.dbo.tblBranches.strCode AND tblStsDtl.compCode = pg_pf.dbo.tblBranches.compCode INNER JOIN
                      tblStsHdr ON tblStsDtl.stsRefno = tblStsHdr.stsRefno INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM INNER JOIN
                      tblStsHierarchy ON tblStsHdr.stsDept = tblStsHierarchy.stsDept AND tblStsHdr.stsCls = tblStsHierarchy.stsCls AND 
                      tblStsHdr.stsSubCls = tblStsHierarchy.stsSubCls left  JOIN
                      tblGroup ON tblStsHdr.grpCode = tblGroup.grpCode  left JOIN
                      tblUsers ON tblStsHdr.approvedBy = tblUsers.userId
					  WHERE $dt BETWEEN '$dtStart' AND '$dtEnd' 
		AND tblStsHdr.suppCode = '$suppCode'
		AND tblStsHdr.stsStat = '$stat'
	  	$grpVar
		$stsType";
		/*$sql = "SELECT
		tblStsDtl.stsRefno, 
		tblStsHdr.suppCode + ' - ' + sql_mmpgtlib.dbo.APSUPP.ASNAME AS supplier, 
		tblStsDtl.stsNo, 
		pg_pf.dbo.tblBranches.strCode + ' - ' + pg_pf.dbo.tblBranches.brnShortDesc as branch, 
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
        pg_pf.dbo.tblBranches ON tblStsDtl.strCode = pg_pf.dbo.tblBranches.strCode AND 
		tblStsDtl.compCode = pg_pf.dbo.tblBranches.compCode 
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
	
	//Create2
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
		pg_pf.dbo.tblBranches.strCode + ' - ' + pg_pf.dbo.tblBranches.brnShortDesc as branch, 
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
                      pg_pf.dbo.tblBranches ON tblStsDtl.strCode = pg_pf.dbo.tblBranches.strCode AND tblStsDtl.compCode = pg_pf.dbo.tblBranches.compCode INNER JOIN
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
		 $sql = "SELECT     tblStsDtl.stsRefno, tblStsHdr.suppCode + ' - ' + sql_mmpgtlib.dbo.APSUPP.ASNAME AS supplier, tblStsDtl.stsNo, 
                      pg_pf.dbo.tblBranches.strCode + ' - ' + pg_pf.dbo.tblBranches.brnShortDesc AS branch, tblStsDtl.stsAmt, tblStsHdr.dateEntered, tblStsHdr.applyDate, 
                      tblStsHdr.stsPaymentMode, tblStsHdr.dateApproved, tblStsHierarchy.hierarchyDesc, tblGroup.grpDesc, tblStsHdr.stsRemarks, tblUsers.fullName, 
                      tblStsHdr.contractNo, tblStsDtl.compCode, tblStsHdr.nbrApplication, tblDisplaySpecs.displaySpecsDesc,
		endDAte = CASE WHEN ststype = 1 THEN DATEADD(month, nbrApplication, applyDate) ELSE endDate END,
		payMode = CASE tblStsHdr.stsPaymentMode WHEN 'C' THEN 'Collection/Check' ELSE 'Invoice Deduction' END,
		companyCode = CASE tblStsDtl.compCode WHEN '700' THEN 'JR' ELSE 'PPCI' END
		FROM         tblStsDtl INNER JOIN
                      pg_pf.dbo.tblBranches ON tblStsDtl.strCode = pg_pf.dbo.tblBranches.strCode AND tblStsDtl.compCode = pg_pf.dbo.tblBranches.compCode INNER JOIN
                      tblStsHdr ON tblStsDtl.stsRefno = tblStsHdr.stsRefno INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM INNER JOIN
                      tblStsHierarchy ON tblStsHdr.stsDept = tblStsHierarchy.stsDept AND tblStsHdr.stsCls = tblStsHierarchy.stsCls AND 
                      tblStsHdr.stsSubCls = tblStsHierarchy.stsSubCls INNER JOIN
                      tblGroup ON tblStsHdr.grpCode = tblGroup.grpCode left JOIN
                      tblUsers ON tblStsHdr.approvedBy = tblUsers.userId LEFT OUTER JOIN
                      tblStsDaDetail ON tblStsDtl.stsRefno = tblStsDaDetail.stsRefno AND tblStsDtl.strCode = tblStsDaDetail.strCode AND 
                      tblStsDtl.stsNo = tblStsDaDetail.stsNo INNER JOIN
                      tblDisplaySpecs ON tblStsDaDetail.dispSpecs = tblDisplaySpecs.displaySpecsId
					  WHERE $dt BETWEEN '$dtStart' AND '$dtEnd' 
		AND tblStsHdr.suppCode = '$suppCode'
		AND tblStsHdr.stsStat = '$stat'
		$trans_q
	  	
		$grpVar
		";
		return $this->getArrRes($this->execQry($sql));
	}
	//End Create2
	
	function getParDetailDa($refNo){
		$sql = "SELECT     pg_pf.dbo.tblBranches.brnShortDesc, tblStsDtl.stsNo, tblStsDtl.stsAmt,tblDisplaySpecs.displaySpecsDesc
FROM         tblStsDtl INNER JOIN
                      pg_pf.dbo.tblBranches ON tblStsDtl.strCode = pg_pf.dbo.tblBranches.strCode AND tblStsDtl.compCode = pg_pf.dbo.tblBranches.compCode
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
		$sql = "SELECT DISTINCT tblStsApply.strCode, pg_pf.dbo.tblBranches.brnShortDesc
FROM         tblStsApply INNER JOIN
                      pg_pf.dbo.tblBranches ON tblStsApply.compCode = pg_pf.dbo.tblBranches.compCode AND tblStsApply.strCode = pg_pf.dbo.tblBranches.strCode LEFT OUTER JOIN
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
FROM         tblStsApply INNER JOIN pg_pf.dbo.tblBranches ON tblStsApply.compCode = pg_pf.dbo.tblBranches.compCode AND tblStsApply.strCode = pg_pf.dbo.tblBranches.strCode INNER JOIN sql_mmpgtlib.dbo.APADDR ON tblStsApply.suppCode = sql_mmpgtlib.dbo.APADDR.AANUM WHERE tblStsApply.stsApplyDate BETWEEN '$dtFrom' AND '$dtTo' $stat $stsType";
		return $this->getArrRes($this->execQry($sql));
	}
	function cancelledSTSDetail($dtFrom,$dtTo,$trans,$refNo){
		if($trans != '0'){
			$stsType = "AND tblStsHdr.stsType = $trans";	
		}
		$sql = "SELECT     tblCancelledSTS.stsNo, tblCancelledSTS.stsSeq, pg_pf.dbo.tblBranches.brnShortDesc, sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppName, 
                      tblCancelledSTS.uploadedAmt, tblCancelledSTS.queueAmt, tblCancelledSTS.stsStrAmt, tblGroup.grpDesc, tblStsHierarchy.hierarchyDesc, 
					  tblUsers.fullName, 
                      tblCancelledSTS.stsRefno, tblCancelledSTS.cancelDate, tblCancelType.cancelDesc, tblCancelledSTS.cancelDate as effectiviyDate
				FROM         tblCancelledSTS INNER JOIN
                      pg_pf.dbo.tblBranches ON tblCancelledSTS.strCode = pg_pf.dbo.tblBranches.strCode INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblCancelledSTS.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM INNER JOIN
                      tblGroup ON tblCancelledSTS.grpCode = tblGroup.grpCode INNER JOIN
                      tblStsHierarchy ON tblCancelledSTS.stsDept = tblStsHierarchy.stsDept INNER JOIN
                      tblUsers ON tblCancelledSTS.cancelledBy = tblUsers.userId INNER JOIN
                      tblCancelType ON tblCancelledSTS.cancelCode = tblCancelType.cancelId INNER JOIN
					  tblStsHdr ON tblCancelledSTS.stsRefno = tblStsHdr.stsRefno
				WHERE tblCancelledSTS.cancelDate BETWEEN '$dtFrom' AND '$dtTo' AND tblCancelledSTS.stsRefno = '$refNo' $stsType
				ORDER BY tblCancelledSTS.stsRefno,tblCancelledSTS.stsNo, tblCancelledSTS.stsSeq";
		return $this->getArrRes($this->execQry($sql));
	}
	function expiredContractsSumm($monthYear,$trans){
		if($trans != '0'){
			$stsType = "AND tblStsHdr.stsType = $trans";	
		}
		$sql = "SELECT     tblStsHdr.stsRefno, tblStsHdr.stsRemarks, tblStsHdr.stsAmt, tblStsHdr.stsStartNo, tblStsHdr.stsEndNo, 
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
		$sql = "SELECT     tblStsDtl.stsNo, tblStsDtl.stsAmt,tblStsDtl.strCode,
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
                      CAST($tbl.strCode AS varchar) + '-' + CAST(pg_pf.dbo.tblBranches.brnDesc AS varchar) AS Store, tblStsHierarchy.hierarchyDesc, 
                      $tbl.stsApplyDate, $tbl.uploadDate
FROM         $tbl INNER JOIN
                      pg_pf.dbo.tblBranches ON $tbl.strCode = pg_pf.dbo.tblBranches.strCode INNER JOIN
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
                      tblStsHdr.stsPaymentMode, tblStsHdr.stsRemarks, [pg_pf].dbo.tblBranches.brnShortDesc as brnDesc, tblStsDtl.strCode, tblStsDtl.stsAmt,
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
                      [pg_pf].dbo.tblBranches ON tblStsDtl.strCode = [pg_pf].dbo.tblBranches.strCode AND tblStsDtl.compCode = [pg_pf].dbo.tblBranches.compCode
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
                      tblStsHdr.stsPaymentMode, tblStsHdr.stsRemarks, [pg_pf].dbo.tblBranches.brnShortDesc as brnDesc, tblStsDtl.strCode, tblStsDtl.stsAmt,
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
                      [pg_pf].dbo.tblBranches ON tblStsDtl.strCode = [pg_pf].dbo.tblBranches.strCode AND tblStsDtl.compCode = [pg_pf].dbo.tblBranches.compCode
					  INNER JOIN
                      tblUsers ON tblStsHdr.enteredBy = tblUsers.userId
			WHERE  CONVERT(datetime, CONVERT(varchar,tblStsHdr.dateEntered, 101))  between '$dtStart' AND '$dtEnd' AND tblStsHdr.dateApproved is null $stsType $stsGroup $stsSupp
			order by  tblStsHdr.stsRefno";	
		return $this->getArrRes($this->execQry($sql));
	}
}
?>