<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class monthlyStsSummaryPerSupplierObj extends commonObj {

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
	
	function stsSummPerSupllierDtlLasYear($dtFrom,$dtTo,$arrMonth){
	
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
		
		if($separateLastYearFrom == 2014 and $separateLastYearTo == 2014){
			$lastYear = 'tblStsSupplierSummaryLy';
		}
		if($separateYearFrom != 2014 and $separateLastYearTo != 2014){
			$dispNoRec = 'where asnum = 0';
		}
			
		$sql .= "
		select asnum,asname,";
		for($i=$separateMonthFrom;$i<=$separateMonthTo;$i++) {
			$i = $i * 1;
			$sql .= "
			sts{$i},da{$i},pf{$i},
			";
		}
		$sql .= "
		
		'' as blank
		from tblStsSupplierSummaryLy
		$dispNoRec
		";

		return $this->getArrRes($this->execQry($sql));
	}
	
	function stsSummPerSupllierDtlCurYear($dtFrom,$dtTo,$arrMonth){
	
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
		
		if($separateYearFrom == 2015 and $separateLastYearTo == 2015){
			$currYear = 'tblStsSupplierSummary';
		}
		if($separateYearFrom != 2015 and $separateLastYearTo != 2015){
			$dispNoRec = 'where asnum = 0';
		}
			
		$sql .= "
		select asnum,asname,";
		for($i=$separateMonthFrom;$i<=$separateMonthTo;$i++) {
			$i = $i * 1;
			$sql .= "
			sts{$i},da{$i},pf{$i},
			";
		}
		$sql .= "
		
		'' as blank
		from tblStsSupplierSummary
		$dispNoRec
		";

		return $this->getArrRes($this->execQry($sql));
	}
	
}
?>