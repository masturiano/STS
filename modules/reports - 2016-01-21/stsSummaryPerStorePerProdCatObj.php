<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class stsSummaryPerStorePerProdCatObj extends commonObj {

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
	
	function stsSummaryPerStorePerProdCat($dtFrom,$dtTo,$type){
	
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
			select tblBranches.strCode,tblBranches.brnDesc,";
			
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
			from tblBranches";
			
			foreach ($existGroup as $valGroupL) {
			$valGroupL = str_replace(' ','',$valGroupL);
				$sql .= "
				left join
				(
					select 
					grpCode,strCode,
					sum(tblStsApply.stsApplyAmt * -1) as stsApplyAmt
					from tblStsApply
					where tblStsApply.stsApplyDate between '{$dateFromLY}' and '{$dateToLY}'
					and tblStsApply.stsType {$cond} 5
					and tblStsApply.stsPaymentMode = 'D'
					and grpCode = {$valGroupL[grpCode]}
					group by
					grpCode,strCode
				) as {$valGroupL[grpDesc]}ApLY
				on {$valGroupL[grpDesc]}ApLY.strCode = tblBranches.strCode

				left join
				(
					select 
					grpCode,strCode,
					sum(tblStsApply.stsApplyAmt) as stsApplyAmt
					from tblStsApply
					where tblStsApply.stsApplyDate between '{$dateFromLY}' and '{$dateToLY}'
					and tblStsApply.stsType {$cond} 5
					and tblStsApply.stsPaymentMode = 'C'
					and grpCode = {$valGroupL[grpCode]}
					group by
					grpCode,strCode
				) as {$valGroupL[grpDesc]}ArLY
				on {$valGroupL[grpDesc]}ArLY.strCode = tblBranches.strCode";
			}
			
			
			foreach ($existGroup as $valGroupC) {
			$valGroupC = str_replace(' ','',$valGroupC);
				$sql .= "
				left join
				(
					select 
					grpCode,strCode,
					sum(tblStsApply.stsApplyAmt * -1) as stsApplyAmt
					from tblStsApply
					where tblStsApply.stsApplyDate between '{$dateFromCY}' and '{$dateToCY}'
					and tblStsApply.stsType {$cond} 5
					and tblStsApply.stsPaymentMode = 'D'
					and grpCode = {$valGroupC[grpCode]}
					group by
					grpCode,strCode
				) as {$valGroupC[grpDesc]}ApCY
				on {$valGroupC[grpDesc]}ApCY.strCode = tblBranches.strCode

				left join
				(
					select 
					grpCode,strCode,
					sum(tblStsApply.stsApplyAmt) as stsApplyAmt
					from tblStsApply
					where tblStsApply.stsApplyDate between '{$dateFromCY}' and '{$dateToCY}'
					and tblStsApply.stsType {$cond} 5
					and tblStsApply.stsPaymentMode = 'C'
					and grpCode = {$valGroupC[grpCode]}
					group by
					grpCode,strCode
				) as {$valGroupC[grpDesc]}ArCY
				on {$valGroupC[grpDesc]}ArCY.strCode = tblBranches.strCode";
			}
			$sql .= "
			order by tblBranches.strCode,tblBranches.brnDesc
			";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function getStoreName($strNum){
		
		$sql = "
		SELECT     brnDesc
		FROM         tblBranches
		WHERE     (strCode = {$strNum})
		";
		return $this->getSqlAssoc($this->execQry($sql));
	}
	
	function pfSummaryPerStorePerProdCat($dtFrom,$dtTo,$type){
	
		$time = strtotime("-1 year", time());
		$dateFromLY = date("Y-m-d", strtotime("$dtFrom -1 year", time()));
		$dateToLY = date("Y-m-d", strtotime("$dtTo -1 year", time()));
		
		$dateFromCY = date("Y-m-d", strtotime($dtFrom));
		$dateToCY = date("Y-m-d", strtotime($dtTo));
		
			
			$valGroupL = str_replace(' ','',$valGroupL);
				$sql = "
				Select main.strCode,main.minCode,main.deptDesc,main.typeCode,main.typeDesc,
				isnull(sum(tblStsDlyApLy.stsApplyAmt),0) as apStsApplyAmtLy,isnull(sum(tblStsDlyArLy.stsApplyAmt),0) as arStsApplyAmtLy,
				isnull(sum(tblStsDlyApCy.stsApplyAmt),0) as apStsApplyAmtCy,isnull(sum(tblStsDlyArCy.stsApplyAmt),0) as arStsApplyAmtCy

				from
				(
				Select ap.strCode,d.minCode,d.deptDesc,f.typeCode,t.typeDesc
				from pg_pf..tblStsDlyAp ap 
				left join pg_pf..tblFunds f on ap.stsrefNo=f.fundNo 
				left join pg_pf..tblDepartment d on f.minCode=d.minCode
				left join pg_pf..tblFundTypes t on f.typeCode=t.typeCode
				group by ap.strCode,d.minCode,d.deptDesc,f.typeCode,t.typeDesc

				union 

				Select ar.strCode,d.minCode,d.deptDesc,f.typeCode,t.typeDesc
				from pg_pf..tblStsDlyAr ar 
				left join pg_pf..tblFunds f on ar.stsrefNo=f.fundNo 
				left join pg_pf..tblDepartment d on f.minCode=d.minCode
				left join pg_pf..tblFundTypes t on f.typeCode=t.typeCode
				group by ar.strCode,d.minCode,d.deptDesc,f.typeCode,t.typeDesc
				) as main

				left join
				(
					--==( ap )==--
					Select ap.strCode,d.minCode,d.deptDesc,t.typeDesc,f.typeCode,sum(stsApplyAmt * -1) as stsApplyAmt from pg_pf..tblStsDlyAp ap 
					inner join pg_pf..tblFunds f on ap.stsrefNo=f.fundNo 
					inner join pg_pf..tblDepartment d on f.minCode=d.minCode
					inner join pg_pf..tblFundTypes t on f.typeCode=t.typeCode
					where stsapplyDate between '{$dateFromLY}' and '{$dateToLY}'
					group by ap.strCode,d.minCode,d.deptDesc,t.typeDesc,f.typeCode
				) tblStsDlyApLy
				on main.minCode = tblStsDlyApLy.minCode
				and main.typeCode = tblStsDlyApLy.typeCode
				and main.strCode = tblStsDlyApLy.strCode

				left join
				(
					--==( ar )==--
					Select ar.strCode,d.minCode,d.deptDesc,t.typeDesc,f.typeCode,sum(stsApplyAmt) as stsApplyAmt from pg_pf..tblStsDlyAr ar 
					inner join pg_pf..tblFunds f on ar.stsrefNo=f.fundNo 
					inner join pg_pf..tblDepartment d on f.minCode=d.minCode
					inner join pg_pf..tblFundTypes t on f.typeCode=t.typeCode
					where stsapplyDate between '{$dateFromLY}' and '{$dateToLY}'
					group by ar.strCode,d.minCode,d.deptDesc,t.typeDesc,f.typeCode
				) tblStsDlyArLy
				on main.minCode = tblStsDlyArLy.minCode
				and main.typeCode = tblStsDlyArLy.typeCode
				and main.strCode = tblStsDlyArLy.strCode

				left join
				(
				--==( ap )==--
					Select ap.strCode,d.minCode,d.deptDesc,t.typeDesc,f.typeCode,sum(stsApplyAmt * -1) as stsApplyAmt from pg_pf..tblStsDlyAp ap 
					inner join pg_pf..tblFunds f on ap.stsrefNo=f.fundNo 
					inner join pg_pf..tblDepartment d on f.minCode=d.minCode
					inner join pg_pf..tblFundTypes t on f.typeCode=t.typeCode
					where stsapplyDate between '{$dateFromCY}' and '{$dateToCY}'
					group by ap.strCode,d.minCode,d.deptDesc,t.typeDesc,f.typeCode
				) tblStsDlyApCy
				on main.minCode = tblStsDlyApCy.minCode
				and main.typeCode = tblStsDlyApCy.typeCode
				and main.strCode = tblStsDlyApCy.strCode

				left join
				(
					--==( ar )==--
					Select ar.strCode,d.minCode,d.deptDesc,t.typeDesc,f.typeCode,sum(stsApplyAmt) as stsApplyAmt from pg_pf..tblStsDlyAr ar 
					inner join pg_pf..tblFunds f on ar.stsrefNo=f.fundNo 
					inner join pg_pf..tblDepartment d on f.minCode=d.minCode
					inner join pg_pf..tblFundTypes t on f.typeCode=t.typeCode
					where stsapplyDate between '{$dateFromCY}' and '{$dateToCY}'
					group by ar.strCode,d.minCode,d.deptDesc,t.typeDesc,f.typeCode
				) tblStsDlyArCy
				on main.minCode = tblStsDlyArCy.minCode
				and main.typeCode = tblStsDlyArCy.typeCode
				and main.strCode = tblStsDlyArCy.strCode

				group by main.strCode,main.minCode,main.deptDesc,main.typeDesc,main.typeCode
				order by main.deptDesc,main.strCode,main.typeCode
				";
		return $this->getArrRes($this->execQry($sql));
	}
	
}
?>