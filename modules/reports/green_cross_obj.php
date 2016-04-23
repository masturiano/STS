<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
# CLASS NAME
class greenCrossObj extends commonObj {
    
    # GET BRANCHES FOR DROP DOWN
	function getBranches(){
		$sql = "select strCode, cast(strCode as nvarchar)+' - '+brnDesc as brnDesc FROM tblBranches order by strCode";
		return $this->getArrRes($this->execQry($sql));
	}
    
    # GET STORE DETAILS
    function findStoreDetails($store_code){
        $sql = "
        SELECT STRCODE,BRNDESC,BRNSHORTDESC,BRNSHORTNAME 
        FROM TBLBRANCHES
        WHERE STRCODE = '{$store_code}'
        union all
        SELECT STRCODE,BRNDESC,BRNSHORTDESC,BRNSHORTNAME 
        FROM ne_sts..TBLBRANCHES
        WHERE STRCODE = '{$store_code}'
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # GET GREEN CROSS DETAIL
    function getSummary($year,$store_code){
        
        # FILTER DATE
        $year = "YEAR = '{$year}'";
        
        # FILTER BRANCH
        if($store_code == '0'){
            $branch = "";
        }
        else{
            $branch = "AND LOC = '{$store_code}'";
        }
        
        # EXCEL DATA
        $sql = "
            SELECT 
                SUM(RCR_AMOUNT) as SUM_RCR_AMOUNT,
                CAST(ROUND(SUM(RCR_AMOUNT)/1.12,2) AS DECIMAL(18,2)) AS EX_VAT,
                CAST(ROUND((SUM(RCR_AMOUNT)/1.12)*1.04,2) AS DECIMAL(18,2)) AS GROSS_BEF_DISC,
                CAST(ROUND(((SUM(RCR_AMOUNT)/1.12)*1.04)-(SUM(RCR_AMOUNT)/1.12),2) AS DECIMAL(18,2)) AS FOUR_PERC_DISC,
                CAST(ROUND((((SUM(RCR_AMOUNT)/1.12)*1.04)-(SUM(RCR_AMOUNT)/1.12))/(SUM(RCR_AMOUNT)/1.12),2) AS DECIMAL(18,2)) AS RATE_IN,
                (SUM(RCR_AMOUNT)/1.12)*0.01 AS EDI,
                CAST(ROUND(((((SUM(RCR_AMOUNT)/1.12)*1.04)-(SUM(RCR_AMOUNT)/1.12))/4),2) AS DECIMAL(18,2)) AS TO_CHECK,
                CAST(ROUND(CAST(ROUND(((((SUM(RCR_AMOUNT)/1.12)*1.04)-(SUM(RCR_AMOUNT)/1.12))/4),2) AS DECIMAL(18,2)) - ((SUM(RCR_AMOUNT)/1.12)*0.01),2) AS DECIMAL(18,2)) AS VAR,
                    CASE 
                        WHEN MONTH = 01 THEN 'JAN'
                        WHEN MONTH = 02 THEN 'FEB'
                        WHEN MONTH = 03 THEN 'MAR'
                        WHEN MONTH = 04 THEN 'APR'
                        WHEN MONTH = 05 THEN 'MAY'
                        WHEN MONTH = 06 THEN 'JUN'
                        WHEN MONTH = 07 THEN 'JUL'
                        WHEN MONTH = 08 THEN 'AUG'
                        WHEN MONTH = 09 THEN 'SEP'
                        WHEN MONTH = 10 THEN 'OCT'
                        WHEN MONTH = 11 THEN 'NOV'
                        WHEN MONTH = 12 THEN 'DEC'
                    END AS MONTH_NAME,
                MONTH,
                YEAR
            FROM 
                tblEfdGreenCross
            WHERE
                {$year}
                {$branch}
            GROUP BY    
                MONTH,YEAR
            ORDER BY
                MONTH,YEAR    
        "; 
        return $this->getArrRes($this->execQry($sql));   
    } 
}
?>