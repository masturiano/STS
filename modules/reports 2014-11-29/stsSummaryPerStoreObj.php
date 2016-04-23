<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class stsSummaryPerStoreObj extends commonObj {

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
	
	function stsSummPerStoreDtl($dtFrom,$dtTo){
	
		$time = strtotime("-1 year", time());
		$dateFromLY = date("Y-m-d", strtotime("$dtFrom -1 year", time()));
		$dateToLY = date("Y-m-d", strtotime("$dtTo -1 year", time()));
		
		$dateFromCY = date("Y-m-d", strtotime($dtFrom));
		$dateToCY = date("Y-m-d", strtotime($dtTo));

			$sql .= "
			SELECT   
			tblBranches.strCode,
			tblBranches.brnDesc,
			ApStsLastYear.stsApplyAmt as ApStsLastYear,
			ArStsLastYear.stsApplyAmt as ArStsLastYear,
			ApStsCurrYear.stsApplyAmt as ApStsCurrYear,
			ArStsCurrYear.stsApplyAmt as ArStsCurrYear,
			ApDaLastYear.stsApplyAmt as ApDaLastYear,
			ArDaLastYear.stsApplyAmt as ArDaLastYear,
			ApDaCurrYear.stsApplyAmt as ApDaCurrYear,
			ArDaCurrYear.stsApplyAmt as ArDaCurrYear,
			ApPfLastYear.stsApplyAmt as ApPfLastYear,
			ArPfLastYear.stsApplyAmt as ArPfLastYear,
			ApPfCurrYear.stsApplyAmt as ApPfCurrYear,
			ArPfCurrYear.stsApplyAmt as ArPfCurrYear,
			isNull(ApStsLastYear.stsApplyAmt,0) + isNull(ArStsLastYear.stsApplyAmt,0) as stsLastYear,
			isNull(ApStsCurrYear.stsApplyAmt,0) + isNull(ArStsCurrYear.stsApplyAmt,0) as stsCurrYear,
			isNull(ApDaLastYear.stsApplyAmt,0) + isNull(ArDaLastYear.stsApplyAmt,0) as daLastYear,
			isNull(ApDaCurrYear.stsApplyAmt,0) + isNull(ArDaCurrYear.stsApplyAmt,0) as daCurrYear,
			isNull(ApPfLastYear.stsApplyAmt,0) + isNull(ArPfLastYear.stsApplyAmt,0) as pfLastYear,
			isNull(ApPfCurrYear.stsApplyAmt,0) + isNull(ArPfCurrYear.stsApplyAmt,0) as pfCurrYear
			FROM tblBranches

			-- sts ap last year
			left join
			(SELECT   
			tblStsApply.strCode,
			tblBranches.brnDesc,
			sum(tblStsApply.stsApplyAmt * -1) as stsApplyAmt
			FROM         tblBranches
			left join
			tblStsApply on tblStsApply.strCode = tblBranches.strCode
			WHERE     
			tblStsApply.stsApplyDate between '{$dateFromLY}' and '{$dateToLY}'
			and tblStsApply.stsType <> 5
			and tblStsApply.stsPaymentMode = 'D'
			group by 
			tblBranches.brnDesc,
			tblStsApply.strCode) as ApStsLastYear
			on ApStsLastYear.strCode = tblBranches.strCode

			-- da ap last year
			left join
			(SELECT   
			tblStsApply.strCode,
			tblBranches.brnDesc,
			sum(tblStsApply.stsApplyAmt * -1) as stsApplyAmt
			FROM         tblBranches
			left join
			tblStsApply on tblStsApply.strCode = tblBranches.strCode
			WHERE     
			tblStsApply.stsApplyDate between '{$dateFromLY}' and '{$dateToLY}'
			and tblStsApply.stsType = 5
			and tblStsApply.stsPaymentMode = 'D'
			group by 
			tblBranches.brnDesc,
			tblStsApply.strCode) as ApDaLastYear
			on ApDaLastYear.strCode = tblBranches.strCode

			-- pf ap last year
			left join
			(select 
			pg_pf..tblBranches.brnDesc,
			pg_pf..tblstsDlyap.strCode,
			sum(pg_pf..tblstsDlyap.stsApplyAmt * -1) as stsApplyAmt
			from pg_pf..tblBranches
			left join pg_pf..tblstsDlyap on pg_pf..tblstsDlyap.strCode = pg_pf..tblBranches.strCode
			inner join pg_pf..tblFunds on pg_pf..tblstsDlyap.stsrefno = pg_pf..tblFunds.fundNo 
			inner join pg_pf..tblDepartment on pg_pf..tblFunds.minCode = pg_pf..tblDepartment.minCode
			where pg_pf..tblstsDlyap.stsApplyDate between '{$dateFromLY}' and '{$dateToLY}'
			group by pg_pf..tblBranches.brnDesc,pg_pf..tblstsDlyap.strCode) as ApPfLastYear
			on ApPfLastYear.strCode = tblBranches.strCode

			-- sts ar last year
			left join
			(SELECT  
			tblStsApply.strCode,			
			tblBranches.brnDesc,
			sum(tblStsApply.stsApplyAmt) as stsApplyAmt
			FROM         tblBranches
			left join
			tblStsApply on tblStsApply.strCode = tblBranches.strCode
			WHERE     
			tblStsApply.stsApplyDate between '{$dateFromLY}' and '{$dateToLY}'
			and tblStsApply.stsType <> 5
			and tblStsApply.stsPaymentMode = 'C'
			group by 
			tblBranches.brnDesc,
			tblStsApply.strCode) as ArStsLastYear
			on ArStsLastYear.strCode = tblBranches.strCode

			-- da ar last year
			left join
			(SELECT   
			tblStsApply.strCode,
			tblBranches.brnDesc,
			sum(tblStsApply.stsApplyAmt) as stsApplyAmt
			FROM         tblBranches
			left join
			tblStsApply on tblStsApply.strCode = tblBranches.strCode
			WHERE     
			tblStsApply.stsApplyDate between '{$dateFromLY}' and '{$dateToLY}'
			and tblStsApply.stsType = 5
			and tblStsApply.stsPaymentMode = 'C'
			group by 
			tblBranches.brnDesc,
			tblStsApply.strCode) as ArDaLastYear
			on ArDaLastYear.strCode = tblBranches.strCode

			-- pf ar last year
			left join
			(select 
			pg_pf..tblBranches.brnDesc,
			pg_pf..tblstsDlyar.strCode,
			sum(pg_pf..tblstsDlyar.stsApplyAmt) as stsApplyAmt
			from pg_pf..tblBranches
			left join pg_pf..tblstsDlyar on pg_pf..tblstsDlyar.strCode = pg_pf..tblBranches.strCode
			inner join pg_pf..tblFunds on pg_pf..tblstsDlyar.stsrefno = pg_pf..tblFunds.fundNo 
			inner join pg_pf..tblDepartment on pg_pf..tblFunds.minCode = pg_pf..tblDepartment.minCode
			where pg_pf..tblstsDlyar.stsApplyDate between '{$dateFromLY}' and '{$dateToLY}'
			group by pg_pf..tblBranches.brnDesc,pg_pf..tblstsDlyar.strCode) as ArPfLastYear
			on ArPfLastYear.strCode = tblBranches.strCode

			--==( Curr Year )==--

			-- sts ap curr year
			left join
			(SELECT   
			tblStsApply.strCode,
			tblBranches.brnDesc,
			sum(tblStsApply.stsApplyAmt * -1) as stsApplyAmt
			FROM         tblBranches
			left join
			tblStsApply on tblStsApply.strCode = tblBranches.strCode
			WHERE     
			tblStsApply.stsApplyDate between '{$dateFromCY}' and '{$dateToCY}'
			and tblStsApply.stsType <> 5
			and tblStsApply.stsPaymentMode = 'D'
			group by 
			tblBranches.brnDesc,
			tblStsApply.strCode) as ApStsCurrYear
			on ApStsCurrYear.strCode = tblBranches.strCode

			-- da ap curr year
			left join
			(SELECT   
			tblStsApply.strCode,
			tblBranches.brnDesc,
			sum(tblStsApply.stsApplyAmt * -1) as stsApplyAmt
			FROM         tblBranches
			left join
			tblStsApply on tblStsApply.strCode = tblBranches.strCode
			WHERE     
			tblStsApply.stsApplyDate between '{$dateFromCY}' and '{$dateToCY}'
			and tblStsApply.stsType = 5
			and tblStsApply.stsPaymentMode = 'D'
			group by 
			tblBranches.brnDesc,
			tblStsApply.strCode) as ApDaCurrYear
			on ApDaCurrYear.strCode = tblBranches.strCode

			-- pf ap curr year
			left join
			(select 
			pg_pf..tblBranches.brnDesc,
			pg_pf..tblstsDlyap.strCode,
			sum(pg_pf..tblstsDlyap.stsApplyAmt * -1) as stsApplyAmt
			from pg_pf..tblBranches
			left join pg_pf..tblstsDlyap on pg_pf..tblstsDlyap.strCode = pg_pf..tblBranches.strCode
			inner join pg_pf..tblFunds on pg_pf..tblstsDlyap.stsrefno = pg_pf..tblFunds.fundNo 
			inner join pg_pf..tblDepartment on pg_pf..tblFunds.minCode = pg_pf..tblDepartment.minCode
			where pg_pf..tblstsDlyap.stsApplyDate between '{$dateFromCY}' and '{$dateToCY}'
			group by pg_pf..tblBranches.brnDesc,pg_pf..tblstsDlyap.strCode) as ApPfCurrYear
			on ApPfCurrYear.strCode = tblBranches.strCode

			-- sts ar curr year
			left join
			(SELECT   
			tblStsApply.strCode,
			tblBranches.brnDesc,
			sum(tblStsApply.stsApplyAmt) as stsApplyAmt
			FROM         tblBranches
			left join
			tblStsApply on tblStsApply.strCode = tblBranches.strCode
			WHERE     
			tblStsApply.stsApplyDate between '{$dateFromCY}' and '{$dateToCY}'
			and tblStsApply.stsType <> 5
			and tblStsApply.stsPaymentMode = 'C'
			group by 
			tblBranches.brnDesc,
			tblStsApply.strCode) as ArStsCurrYear
			on ArStsCurrYear.strCode = tblBranches.strCode

			-- da ar curr year
			left join
			(SELECT   
			tblStsApply.strCode,
			tblBranches.brnDesc,
			sum(tblStsApply.stsApplyAmt) as stsApplyAmt
			FROM         tblBranches
			left join
			tblStsApply on tblStsApply.strCode = tblBranches.strCode
			WHERE     
			tblStsApply.stsApplyDate between '{$dateFromCY}' and '{$dateToCY}'
			and tblStsApply.stsType = 5
			and tblStsApply.stsPaymentMode = 'C'
			group by 
			tblBranches.brnDesc,
			tblStsApply.strCode) as ArDaCurrYear
			on ArDaCurrYear.strCode = tblBranches.strCode

			-- pf ar last year
			left join
			(select 
			pg_pf..tblBranches.brnDesc,
			pg_pf..tblstsDlyar.strCode,
			sum(pg_pf..tblstsDlyar.stsApplyAmt) as stsApplyAmt
			from pg_pf..tblBranches
			left join pg_pf..tblstsDlyar on pg_pf..tblstsDlyar.strCode = pg_pf..tblBranches.strCode
			inner join pg_pf..tblFunds on pg_pf..tblstsDlyar.stsrefno = pg_pf..tblFunds.fundNo 
			inner join pg_pf..tblDepartment on pg_pf..tblFunds.minCode = pg_pf..tblDepartment.minCode
			where pg_pf..tblstsDlyar.stsApplyDate between '{$dateFromCY}' and '{$dateToCY}'
			group by pg_pf..tblBranches.brnDesc,pg_pf..tblstsDlyar.strCode) as ArPfCurrYear
			on ArPfCurrYear.strCode = tblBranches.strCode

			order by tblBranches.strCode
			";
			
		return $this->getArrRes($this->execQry($sql));
	}
	
}
?>