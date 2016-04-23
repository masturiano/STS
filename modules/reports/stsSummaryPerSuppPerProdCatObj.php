<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class stsSummaryPerSuppPerProdCatObj extends commonObj {

	function lastOfMonth($date) {
		return date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m',strtotime($date)).'/01/'.date('Y',strtotime($date)).' 00:00:00'))));
	}

	function getCountMonth($dtFrom,$dtTo){
		
		$dateFrom = date("Y-m-d", strtotime($dtFrom));
		$dateTo= date("Y-m-d", strtotime($dtTo));
		
		$sql = "
		SELECT     				
		count(distinct(MONTH(stsActualDate))) as stsActualDate	
		FROM         tblStsApply				
		WHERE     (stsActualDate BETWEEN '{$dateFrom}' and '{$dateTo}') 	
		";
		return $this->getSqlAssoc($this->execQry($sql));
	}
	
	function getMonth($dtFrom,$dtTo){
	
		$dateFrom = date("Y-m-d", strtotime($dtFrom));
		$dateTo= date("Y-m-d", strtotime($dtTo));

		$sql = "
		SELECT     				
		distinct(MONTH(stsActualDate)) as stsActualMonth	
		FROM         tblStsApply				
		WHERE     (stsActualDate BETWEEN '{$dateFrom}' and '{$dateTo}') 				
		order by MONTH(stsActualDate)
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function getActiveStore(){
		$sql = "
		select   
		tblBranches.strCode
		from tblBranches
		where     tblBranches.brnStat = 'A'
		order by tblBranches.strCode
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function getExistGroup(){
		$sql = "
		select grpCode,grpDesc from tblGroup
		where grpCode in (select distinct(grpCode) from tblStsApply)
		order by grpCode
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function stsSummaryPerSuppPerProdCat($dtFrom,$dtTo,$type){
	
		if($type == 1){
			$cond = '<>';
		}
		if($type == 2){
			$cond = '=';
		}
	
		$time = strtotime("-1 year", time());
		$dateFromLY = date("Y-m-d", strtotime("$dtFrom -1 year", time()));
		$dateToLY = date("Y-m-d", strtotime("$dtTo -1 year", time()));
		
		$dateFromCY = date("Y-m-d", strtotime($dtFrom));
		$dateToCY = date("Y-m-d", strtotime($dtTo));
		
		$existGroup = $this->getExistGroup();
			
			$sql .= "
			select sql_mmpgtlib.dbo.APSUPP.asnum,sql_mmpgtlib.dbo.APSUPP.asname,";
			
			foreach ($existGroup as $valGroupHL) {
			$valGroupHL = str_replace(' ','',$valGroupHL);
			$sql .= "
			isNull({$valGroupHL[grpDesc]}ApLY.stsApplyAmt,0) + isNull({$valGroupHL[grpDesc]}ArLY.stsApplyAmt,0) as {$valGroupHL[grpDesc]}StsLY,";
			}
			foreach ($existGroup as $valGroupHC) {
			$valGroupHC = str_replace(' ','',$valGroupHC);
			$sql .= "
			isNull({$valGroupHC[grpDesc]}ApCY.stsApplyAmt,0) + isNull({$valGroupHC[grpDesc]}ArCY.stsApplyAmt,0) as {$valGroupHC[grpDesc]}StsCY,";
			}
			
			
			$sql .= "
			'' as Extra
			from sql_mmpgtlib.dbo.APSUPP";
			
			foreach ($existGroup as $valGroupL) {
			$valGroupL = str_replace(' ','',$valGroupL);
				$sql .= "
				left join
				(
					select 		
					grpCode,suppCode,
					sum(tblStsApply.stsApplyAmt * -1) as stsApplyAmt		
					from tblStsApply		
					where tblStsApply.stsApplyDate between '{$dateFromLY}' and '{$dateToLY}'		
					and tblStsApply.stsType {$cond} 5	
					and tblStsApply.stsPaymentMode = 'D'		
					and grpCode = {$valGroupL[grpCode]}
					group by		
					grpCode,suppCode
				) as {$valGroupL[grpDesc]}ApLY
				on {$valGroupL[grpDesc]}ApLY.suppCode = sql_mmpgtlib.dbo.APSUPP.asnum

				left join
				(
					select 		
					grpCode,suppCode,		
					sum(tblStsApply.stsApplyAmt) as stsApplyAmt		
					from tblStsApply		
					where tblStsApply.stsApplyDate between '{$dateFromLY}' and '{$dateToLY}'	
					and tblStsApply.stsType {$cond} 5	
					and tblStsApply.stsPaymentMode = 'C'		
					and grpCode = {$valGroupL[grpCode]}	
					group by		
					grpCode,suppCode
				) as {$valGroupL[grpDesc]}ArLY
				on {$valGroupL[grpDesc]}ArLY.suppCode = sql_mmpgtlib.dbo.APSUPP.asnum";
			}
			
			
			foreach ($existGroup as $valGroupC) {
			$valGroupC = str_replace(' ','',$valGroupC);
				$sql .= "
				left join
				(
					select 		
					grpCode,suppCode,
					sum(tblStsApply.stsApplyAmt * -1) as stsApplyAmt		
					from tblStsApply		
					where tblStsApply.stsApplyDate between '{$dateFromCY}' and '{$dateToCY}'
					and tblStsApply.stsType {$cond} 5	
					and tblStsApply.stsPaymentMode = 'D'		
					and grpCode = {$valGroupC[grpCode]}
					group by		
					grpCode,suppCode
				) as {$valGroupC[grpDesc]}ApCY
				on {$valGroupC[grpDesc]}ApCY.suppCode = sql_mmpgtlib.dbo.APSUPP.asnum

				left join
				(
					select 		
					grpCode,suppCode,		
					sum(tblStsApply.stsApplyAmt) as stsApplyAmt		
					from tblStsApply		
					where tblStsApply.stsApplyDate between '{$dateFromCY}' and '{$dateToCY}'
					and tblStsApply.stsType {$cond} 5	
					and tblStsApply.stsPaymentMode = 'C'		
					and grpCode = {$valGroupC[grpCode]}	
					group by		
					grpCode,suppCode
				) as {$valGroupC[grpDesc]}ArCY
				on {$valGroupC[grpDesc]}ArCY.suppCode = sql_mmpgtlib.dbo.APSUPP.asnum";
			}
			$sql .= "
			where sql_mmpgtlib.dbo.APSUPP.asnum in (select distinct(suppCode) from tblStsApply)
			order by sql_mmpgtlib.dbo.APSUPP.asnum,sql_mmpgtlib.dbo.APSUPP.asname	
			";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function getSuppName($suppNum){
		
		$sql = "
		SELECT     suppName
		FROM         pg_pf..tblSuppliers
		WHERE     (suppCode = {$suppNum})
		";
		return $this->getSqlAssoc($this->execQry($sql));
	}
	
	function pfSummaryPerSuppPerProdCat($dtFrom,$dtTo,$type){
	
		$time = strtotime("-1 year", time());
		$dateFromLY = date("Y-m-d", strtotime("$dtFrom -1 year", time()));
		$dateToLY = date("Y-m-d", strtotime("$dtTo -1 year", time()));
		
		$dateFromCY = date("Y-m-d", strtotime($dtFrom));
		$dateToCY = date("Y-m-d", strtotime($dtTo));
		
			
			$valGroupL = str_replace(' ','',$valGroupL);
				$sql = "
				Select main.suppCode,main.minCode,main.deptDesc,main.typeCode,main.typeDesc,
				isnull(sum(tblStsDlyApLy.stsApplyAmt),0) as apStsApplyAmtLy,isnull(sum(tblStsDlyArLy.stsApplyAmt),0) as arStsApplyAmtLy,
				isnull(sum(tblStsDlyApCy.stsApplyAmt),0) as apStsApplyAmtCy,isnull(sum(tblStsDlyArCy.stsApplyAmt),0) as arStsApplyAmtCy

				from
				(
				Select ap.suppcode,d.minCode,d.deptDesc,f.typeCode,t.typeDesc
				from pg_pf..tblStsDlyAp ap 
				left join pg_pf..tblFunds f on ap.stsrefNo=f.fundNo 
				left join pg_pf..tblDepartment d on f.minCode=d.minCode
				left join pg_pf..tblFundTypes t on f.typeCode=t.typeCode
				group by ap.suppcode,d.minCode,d.deptDesc,f.typeCode,t.typeDesc

				union 

				Select ar.suppcode,d.minCode,d.deptDesc,f.typeCode,t.typeDesc
				from pg_pf..tblStsDlyAr ar 
				left join pg_pf..tblFunds f on ar.stsrefNo=f.fundNo 
				left join pg_pf..tblDepartment d on f.minCode=d.minCode
				left join pg_pf..tblFundTypes t on f.typeCode=t.typeCode
				group by ar.suppcode,d.minCode,d.deptDesc,f.typeCode,t.typeDesc
				) as main

				left join
				(
					--==( ap )==--
					Select ap.suppcode,d.minCode,d.deptDesc,t.typeDesc,f.typeCode,sum(stsApplyAmt * -1) as stsApplyAmt from pg_pf..tblStsDlyAp ap 
					inner join pg_pf..tblFunds f on ap.stsrefNo=f.fundNo 
					inner join pg_pf..tblDepartment d on f.minCode=d.minCode
					inner join pg_pf..tblFundTypes t on f.typeCode=t.typeCode
					where stsapplyDate between '{$dateFromLY}' and '{$dateToLY}'
					group by ap.suppcode,d.minCode,d.deptDesc,t.typeDesc,f.typeCode
				) tblStsDlyApLy
				on main.minCode = tblStsDlyApLy.minCode
				and main.typeCode = tblStsDlyApLy.typeCode
				and main.suppcode = tblStsDlyApLy.suppcode

				left join
				(
					--==( ar )==--
					Select ar.suppcode,d.minCode,d.deptDesc,t.typeDesc,f.typeCode,sum(stsApplyAmt) as stsApplyAmt from pg_pf..tblStsDlyAr ar 
					inner join pg_pf..tblFunds f on ar.stsrefNo=f.fundNo 
					inner join pg_pf..tblDepartment d on f.minCode=d.minCode
					inner join pg_pf..tblFundTypes t on f.typeCode=t.typeCode
					where stsapplyDate between '{$dateFromLY}' and '{$dateToLY}'
					group by ar.suppcode,d.minCode,d.deptDesc,t.typeDesc,f.typeCode
				) tblStsDlyArLy
				on main.minCode = tblStsDlyArLy.minCode
				and main.typeCode = tblStsDlyArLy.typeCode
				and main.suppcode = tblStsDlyArLy.suppcode

				left join
				(
				--==( ap )==--
					Select ap.suppcode,d.minCode,d.deptDesc,t.typeDesc,f.typeCode,sum(stsApplyAmt * -1) as stsApplyAmt from pg_pf..tblStsDlyAp ap 
					inner join pg_pf..tblFunds f on ap.stsrefNo=f.fundNo 
					inner join pg_pf..tblDepartment d on f.minCode=d.minCode
					inner join pg_pf..tblFundTypes t on f.typeCode=t.typeCode
					where stsapplyDate between '{$dateFromCY}' and '{$dateToCY}'
					group by ap.suppcode,d.minCode,d.deptDesc,t.typeDesc,f.typeCode
				) tblStsDlyApCy
				on main.minCode = tblStsDlyApCy.minCode
				and main.typeCode = tblStsDlyApCy.typeCode
				and main.suppcode = tblStsDlyApCy.suppcode

				left join
				(
					--==( ar )==--
					Select ar.suppcode,d.minCode,d.deptDesc,t.typeDesc,f.typeCode,sum(stsApplyAmt) as stsApplyAmt from pg_pf..tblStsDlyAr ar 
					inner join pg_pf..tblFunds f on ar.stsrefNo=f.fundNo 
					inner join pg_pf..tblDepartment d on f.minCode=d.minCode
					inner join pg_pf..tblFundTypes t on f.typeCode=t.typeCode
					where stsapplyDate between '{$dateFromCY}' and '{$dateToCY}'
					group by ar.suppcode,d.minCode,d.deptDesc,t.typeDesc,f.typeCode
				) tblStsDlyArCy
				on main.minCode = tblStsDlyArCy.minCode
				and main.typeCode = tblStsDlyArCy.typeCode
				and main.suppcode = tblStsDlyArCy.suppcode

				group by main.suppCode,main.minCode,main.deptDesc,main.typeDesc,main.typeCode
				order by main.deptDesc,main.suppCode,main.typeCode

				";
		return $this->getArrRes($this->execQry($sql));
	}
	
}
?>