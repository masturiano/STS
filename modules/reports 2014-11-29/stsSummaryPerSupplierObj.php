<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class stsSummaryPerSupplierObj extends commonObj {

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
	
	function stsSummPerSupplierDtl($dtFrom,$dtTo){
	
		$time = strtotime("-1 year", time());
		$dateFromLY = date("Y-m-d", strtotime("$dtFrom -1 year", time()));
		$dateToLY = date("Y-m-d", strtotime("$dtTo -1 year", time()));
		
		$dateFromCY = date("Y-m-d", strtotime($dtFrom));
		$dateToCY = date("Y-m-d", strtotime($dtTo));

			$sql .= "
			select 
			sql_mmpgtlib.dbo.APSUPP.asnum,
			sql_mmpgtlib.dbo.APSUPP.asname,
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


			from sql_mmpgtlib.dbo.APSUPP

			-- sts ap last year
			left join
			(select 
			sql_mmpgtlib.dbo.APSUPP.asnum,
			sql_mmpgtlib.dbo.APSUPP.asname,
			tblStsApply.suppCode,
			sum(tblStsApply.stsApplyAmt * -1) as stsApplyAmt
			from sql_mmpgtlib.dbo.APSUPP
			left join
			tblStsApply on tblStsApply.suppCode = sql_mmpgtlib.dbo.APSUPP.asnum
			where tblStsApply.stsApplyDate between '{$dateFromLY}' and '{$dateToLY}'
			and tblStsApply.stsType <> 5
			and tblStsApply.stsPaymentMode = 'D'
			and sql_mmpgtlib.dbo.APSUPP.astype = 1
			group by
			sql_mmpgtlib.dbo.APSUPP.asnum,
			sql_mmpgtlib.dbo.APSUPP.asname,
			tblStsApply.suppCode) as ApStsLastYear
			on ApStsLastYear.asnum = sql_mmpgtlib.dbo.APSUPP.asnum

			-- da ap last year
			left join
			(select 
			sql_mmpgtlib.dbo.APSUPP.asnum,
			sql_mmpgtlib.dbo.APSUPP.asname,
			tblStsApply.suppCode,
			sum(tblStsApply.stsApplyAmt * -1) as stsApplyAmt
			from sql_mmpgtlib.dbo.APSUPP
			left join
			tblStsApply on tblStsApply.suppCode = sql_mmpgtlib.dbo.APSUPP.asnum
			where tblStsApply.stsApplyDate between '{$dateFromLY}' and '{$dateToLY}'
			and tblStsApply.stsType = 5
			and tblStsApply.stsPaymentMode = 'D'
			and sql_mmpgtlib.dbo.APSUPP.astype = 1
			group by
			sql_mmpgtlib.dbo.APSUPP.asnum,
			sql_mmpgtlib.dbo.APSUPP.asname,
			tblStsApply.suppCode) as ApDaLastYear
			on ApDaLastYear.asnum = sql_mmpgtlib.dbo.APSUPP.asnum

			-- pf ap last year
			left join
			(select 
			sql_mmpgtlib.dbo.APSUPP.asnum,
			sql_mmpgtlib.dbo.APSUPP.asname,
			sum(pg_pf..tblstsDlyap.stsApplyAmt * -1) as stsApplyAmt
			from sql_mmpgtlib.dbo.APSUPP
			left join pg_pf..tblstsDlyap on pg_pf..tblstsDlyap.suppCode = sql_mmpgtlib.dbo.APSUPP.asnum
			inner join pg_pf..tblFunds on pg_pf..tblstsDlyap.stsrefno = pg_pf..tblFunds.fundNo 
			inner join pg_pf..tblDepartment on pg_pf..tblFunds.minCode = pg_pf..tblDepartment.minCode
			where pg_pf..tblstsDlyap.stsApplyDate between '{$dateFromLY}' and '{$dateToLY}'
			group by sql_mmpgtlib.dbo.APSUPP.asnum,sql_mmpgtlib.dbo.APSUPP.asname) as ApPfLastYear
			on ApPfLastYear.asnum = sql_mmpgtlib.dbo.APSUPP.asnum

			-- sts ar last year
			left join
			(select 
			sql_mmpgtlib.dbo.APSUPP.asnum,
			sql_mmpgtlib.dbo.APSUPP.asname,
			tblStsApply.suppCode,
			sum(tblStsApply.stsApplyAmt) as stsApplyAmt
			from sql_mmpgtlib.dbo.APSUPP
			left join
			tblStsApply on tblStsApply.suppCode = sql_mmpgtlib.dbo.APSUPP.asnum
			where tblStsApply.stsApplyDate between '{$dateFromLY}' and '{$dateToLY}'
			and tblStsApply.stsType <> 5
			and tblStsApply.stsPaymentMode = 'C'
			and sql_mmpgtlib.dbo.APSUPP.astype = 1
			group by
			sql_mmpgtlib.dbo.APSUPP.asnum,
			sql_mmpgtlib.dbo.APSUPP.asname,
			tblStsApply.suppCode) as ArStsLastYear
			on ArStsLastYear.asnum = sql_mmpgtlib.dbo.APSUPP.asnum

			-- da ar last year
			left join
			(select 
			sql_mmpgtlib.dbo.APSUPP.asnum,
			sql_mmpgtlib.dbo.APSUPP.asname,
			tblStsApply.suppCode,
			sum(tblStsApply.stsApplyAmt) as stsApplyAmt
			from sql_mmpgtlib.dbo.APSUPP
			left join
			tblStsApply on tblStsApply.suppCode = sql_mmpgtlib.dbo.APSUPP.asnum
			where tblStsApply.stsApplyDate between '{$dateFromLY}' and '{$dateToLY}'
			and tblStsApply.stsType = 5
			and tblStsApply.stsPaymentMode = 'C'
			and sql_mmpgtlib.dbo.APSUPP.astype = 1
			group by
			sql_mmpgtlib.dbo.APSUPP.asnum,
			sql_mmpgtlib.dbo.APSUPP.asname,
			tblStsApply.suppCode) as ArDaLastYear
			on ArDaLastYear.asnum = sql_mmpgtlib.dbo.APSUPP.asnum

			-- pf ar last year
			left join
			(select 
			sql_mmpgtlib.dbo.APSUPP.asnum,
			sql_mmpgtlib.dbo.APSUPP.asname,
			sum(pg_pf..tblstsDlyar.stsApplyAmt) as stsApplyAmt
			from sql_mmpgtlib.dbo.APSUPP
			left join pg_pf..tblstsDlyar on pg_pf..tblstsDlyar.suppCode = sql_mmpgtlib.dbo.APSUPP.asnum
			inner join pg_pf..tblFunds on pg_pf..tblstsDlyar.stsrefno = pg_pf..tblFunds.fundNo 
			inner join pg_pf..tblDepartment on pg_pf..tblFunds.minCode = pg_pf..tblDepartment.minCode
			where pg_pf..tblstsDlyar.stsApplyDate between  '{$dateFromLY}' and '{$dateToLY}'
			group by sql_mmpgtlib.dbo.APSUPP.asnum,sql_mmpgtlib.dbo.APSUPP.asname) as ArPfLastYear
			on ArPfLastYear.asnum = sql_mmpgtlib.dbo.APSUPP.asnum

			--==( Curr Year )==--

			-- sts ap curr year
			left join
			(select 
			sql_mmpgtlib.dbo.APSUPP.asnum,
			sql_mmpgtlib.dbo.APSUPP.asname,
			tblStsApply.suppCode,
			sum(tblStsApply.stsApplyAmt * -1) as stsApplyAmt
			from sql_mmpgtlib.dbo.APSUPP
			left join
			tblStsApply on tblStsApply.suppCode = sql_mmpgtlib.dbo.APSUPP.asnum
			where tblStsApply.stsApplyDate between '{$dateFromCY}' and '{$dateToCY}'
			and tblStsApply.stsType <> 5
			and tblStsApply.stsPaymentMode = 'D'
			and sql_mmpgtlib.dbo.APSUPP.astype = 1
			group by
			sql_mmpgtlib.dbo.APSUPP.asnum,
			sql_mmpgtlib.dbo.APSUPP.asname,
			tblStsApply.suppCode) as ApStsCurrYear
			on ApStsCurrYear.asnum = sql_mmpgtlib.dbo.APSUPP.asnum

			-- da ap curr year
			left join
			(select 
			sql_mmpgtlib.dbo.APSUPP.asnum,
			sql_mmpgtlib.dbo.APSUPP.asname,
			tblStsApply.suppCode,
			sum(tblStsApply.stsApplyAmt * -1) as stsApplyAmt
			from sql_mmpgtlib.dbo.APSUPP
			left join
			tblStsApply on tblStsApply.suppCode = sql_mmpgtlib.dbo.APSUPP.asnum
			where tblStsApply.stsApplyDate between '{$dateFromCY}' and '{$dateToCY}'
			and tblStsApply.stsType = 5
			and tblStsApply.stsPaymentMode = 'D'
			and sql_mmpgtlib.dbo.APSUPP.astype = 1
			group by
			sql_mmpgtlib.dbo.APSUPP.asnum,
			sql_mmpgtlib.dbo.APSUPP.asname,
			tblStsApply.suppCode) as ApDaCurrYear
			on ApDaCurrYear.asnum = sql_mmpgtlib.dbo.APSUPP.asnum

			-- pf ap curr year
			left join
			(select 
			sql_mmpgtlib.dbo.APSUPP.asnum,
			sql_mmpgtlib.dbo.APSUPP.asname,
			sum(pg_pf..tblstsDlyap.stsApplyAmt * -1) as stsApplyAmt
			from sql_mmpgtlib.dbo.APSUPP
			left join pg_pf..tblstsDlyap on pg_pf..tblstsDlyap.suppCode = sql_mmpgtlib.dbo.APSUPP.asnum
			inner join pg_pf..tblFunds on pg_pf..tblstsDlyap.stsrefno = pg_pf..tblFunds.fundNo 
			inner join pg_pf..tblDepartment on pg_pf..tblFunds.minCode = pg_pf..tblDepartment.minCode
			where pg_pf..tblstsDlyap.stsApplyDate between '{$dateFromCY}' and '{$dateToCY}'
			group by sql_mmpgtlib.dbo.APSUPP.asnum,sql_mmpgtlib.dbo.APSUPP.asname) as ApPfCurrYear
			on ApPfCurrYear.asnum = sql_mmpgtlib.dbo.APSUPP.asnum

			-- sts ar curr year
			left join
			(select 
			sql_mmpgtlib.dbo.APSUPP.asnum,
			sql_mmpgtlib.dbo.APSUPP.asname,
			tblStsApply.suppCode,
			sum(tblStsApply.stsApplyAmt) as stsApplyAmt
			from sql_mmpgtlib.dbo.APSUPP
			left join
			tblStsApply on tblStsApply.suppCode = sql_mmpgtlib.dbo.APSUPP.asnum
			where tblStsApply.stsApplyDate between '{$dateFromCY}' and '{$dateToCY}'
			and tblStsApply.stsType <> 5
			and tblStsApply.stsPaymentMode = 'C'
			and sql_mmpgtlib.dbo.APSUPP.astype = 1
			group by
			sql_mmpgtlib.dbo.APSUPP.asnum,
			sql_mmpgtlib.dbo.APSUPP.asname,
			tblStsApply.suppCode) as ArStsCurrYear
			on ArStsCurrYear.asnum = sql_mmpgtlib.dbo.APSUPP.asnum

			-- da ar curr year
			left join
			(select 
			sql_mmpgtlib.dbo.APSUPP.asnum,
			sql_mmpgtlib.dbo.APSUPP.asname,
			tblStsApply.suppCode,
			sum(tblStsApply.stsApplyAmt) as stsApplyAmt
			from sql_mmpgtlib.dbo.APSUPP
			left join
			tblStsApply on tblStsApply.suppCode = sql_mmpgtlib.dbo.APSUPP.asnum
			where tblStsApply.stsApplyDate between '{$dateFromCY}' and '{$dateToCY}'
			and tblStsApply.stsType = 5
			and tblStsApply.stsPaymentMode = 'C'
			and sql_mmpgtlib.dbo.APSUPP.astype = 1
			group by
			sql_mmpgtlib.dbo.APSUPP.asnum,
			sql_mmpgtlib.dbo.APSUPP.asname,
			tblStsApply.suppCode) as ArDaCurrYear
			on ArDaCurrYear.asnum = sql_mmpgtlib.dbo.APSUPP.asnum

			-- pf ar curr year
			left join
			(select 
			sql_mmpgtlib.dbo.APSUPP.asnum,
			sql_mmpgtlib.dbo.APSUPP.asname,
			sum(pg_pf..tblstsDlyar.stsApplyAmt) as stsApplyAmt
			from sql_mmpgtlib.dbo.APSUPP
			left join pg_pf..tblstsDlyar on pg_pf..tblstsDlyar.suppCode = sql_mmpgtlib.dbo.APSUPP.asnum
			inner join pg_pf..tblFunds on pg_pf..tblstsDlyar.stsrefno = pg_pf..tblFunds.fundNo 
			inner join pg_pf..tblDepartment on pg_pf..tblFunds.minCode = pg_pf..tblDepartment.minCode
			where pg_pf..tblstsDlyar.stsApplyDate between '{$dateFromCY}' and '{$dateToCY}'
			group by sql_mmpgtlib.dbo.APSUPP.asnum,sql_mmpgtlib.dbo.APSUPP.asname) as ArPfCurrYear
			on ArPfCurrYear.asnum = sql_mmpgtlib.dbo.APSUPP.asnum

			order by sql_mmpgtlib.dbo.APSUPP.asnum
			";
			
		return $this->getArrRes($this->execQry($sql));
	}
	
}
?>