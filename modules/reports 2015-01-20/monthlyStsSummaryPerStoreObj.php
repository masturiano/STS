<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class monthlyStsSummaryPerStoreObj extends commonObj {

	function lastOfMonth($date) {
		return date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m',strtotime($date)).'/01/'.date('Y',strtotime($date)).' 00:00:00'))));
	}

	function getCountMonth($dtFrom,$dtTo){
		
		$dateFrom = date("Y-m-d", strtotime($dtFrom));
		$dateTo = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m',strtotime($dtTo)).'/01/'.date('Y',strtotime($dtTo)).' 00:00:00'))));
		
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
		$dateTo = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m',strtotime($dtTo)).'/01/'.date('Y',strtotime($dtTo)).' 00:00:00'))));

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
	
	function stsSummPerStoreDtlLasYear($dtFrom,$dtTo,$arrMonth){
	
		$dateFrom = date("Y-m-d", strtotime($dtFrom));
		$dateTo = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m',strtotime($dtTo)).'/01/'.date('Y',strtotime($dtTo)).' 00:00:00'))));
		
		//$monthFrom = substr($dtFrom,0,3);
		//$yearFrom = substr($dtFrom,-4);
		//$lastYearFrom = $yearFrom - 1;
		//$monthTo = substr($dtTo,0,3);
		//$yearTo = substr($dtTo,-4);
		//$lastYearTo = $yearTo - 1;
		
		$separateDateFrom =  date("d", strtotime($dtFrom));
		$separateMonthFrom =  date("m", strtotime($dtFrom));
		$separateYearFrom =  date("Y", strtotime($dtFrom));
		$separateLastYearFrom = $separateYearFrom - 1;
		
		$separateDateTo =  date("d", strtotime($dateTo));
		$separateMonthTo =  date("m", strtotime($dateTo));
		$separateYearTo =  date("Y", strtotime($dateTo));
		$separateLastYearTo = $separateYearTo - 1;
		
		$monthToNum = array('Jan'=>'1','Feb'=>'2','Mar'=>'3','Apr'=>'4','May'=>'5','Jun'=>'6','Jul'=>'7','Aug'=>'8','Sep'=>'9','Oct'=>'10','Nov'=>'11','Dec'=>'12');
		$monthGroupYear  =  substr($dtTo,-4);
		$monthGroupLAstYear  =  $monthGroupYear - 1;
		
		$monthGroup = $monthToNum[$monthTo].' '.$monthGroupYear;
		
		if($separateLastYearFrom == 2013 and $separateLastYearTo == 2013){
			$lastYear = 'tblStsStoreSummaryLy';
		}
		if($separateYearFrom != 2013 and $separateLastYearTo != 2013){
			$dispNoRec = 'where strCode = 0';
		}
			
		$sql .= "
		select strCode,brnDesc,";
		for($i=$separateMonthFrom;$i<=$separateMonthTo;$i++) {
			$i = $i * 1;
			$sql .= "
			sts{$i},da{$i},pf{$i},
			";
		}
		$sql .= "
		
		'' as blank
		from tblStsStoreSummaryLy
		$dispNoRec
		";

		return $this->getArrRes($this->execQry($sql));
	}
	
	function stsSummPerStoreDtlCurYear($dtFrom,$dtTo,$arrMonth){
	
		$dateFrom = date("Y-m-d", strtotime($dtFrom));
		$dateTo = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m',strtotime($dtTo)).'/01/'.date('Y',strtotime($dtTo)).' 00:00:00'))));
		
		//$monthFrom = substr($dtFrom,0,3);
		//$yearFrom = substr($dtFrom,-4);
		//$lastYearFrom = $yearFrom - 1;
		//$monthTo = substr($dtTo,0,3);
		//$yearTo = substr($dtTo,-4);
		//$lastYearTo = $yearTo - 1;
		
		$separateDateFrom =  date("d", strtotime($dtFrom));
		$separateMonthFrom =  date("m", strtotime($dtFrom));
		$separateYearFrom =  date("Y", strtotime($dtFrom));
		$separateLastYearFrom = $separateYearFrom - 1;
		
		$separateDateTo =  date("d", strtotime($dateTo));
		$separateMonthTo =  date("m", strtotime($dateTo));
		$separateYearTo =  date("Y", strtotime($dateTo));
		$separateLastYearTo = $separateYearTo - 1;
		
		$monthToNum = array('Jan'=>'1','Feb'=>'2','Mar'=>'3','Apr'=>'4','May'=>'5','Jun'=>'6','Jul'=>'7','Aug'=>'8','Sep'=>'9','Oct'=>'10','Nov'=>'11','Dec'=>'12');
		$monthGroupYear  =  substr($dtTo,-4);
		$monthGroupLAstYear  =  $monthGroupYear - 1;
		
		$monthGroup = $monthToNum[$monthTo].' '.$monthGroupYear;
		
		if($separateYearFrom == 2014 and $separateLastYearTo == 2014){
			$currYear = 'tblStsStoreSummary';
		}
		if($separateYearFrom != 2014 and $separateLastYearTo != 2014){
			$dispNoRec = 'where strCode = 0';
		}
			
		$sql .= "
		select strCode,brnDesc,";
		for($i=$separateMonthFrom;$i<=$separateMonthTo;$i++) {
			$i = $i * 1;
			$sql .= "
			sts{$i},da{$i},pf{$i},
			";
		}
		$sql .= "
		
		'' as blank
		from tblStsStoreSummary
		$dispNoRec
		";

		return $this->getArrRes($this->execQry($sql));
	}
	
}
?>