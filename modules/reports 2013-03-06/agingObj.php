<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class agingObj extends commonObj {
	
	function getAgingStsDetail($type,$payMode){
		if($type=="DA"){
			$filter = "AND stsType = 5";	
		}else{
			$filter = "AND stsType <> 5";	
		}
		$sql = "SELECT     tblStsHdr.stsRefno, tblStsHdr.dateEntered, tblGroup.grpDesc, tblUsers.fullName, tblStsHdr.stsAmt, sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppName
FROM         tblStsHdr INNER JOIN
                      tblGroup ON tblStsHdr.grpCode = tblGroup.grpCode INNER JOIN
                      tblUsers ON tblStsHdr.enteredBy = tblUsers.userId INNER JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM
WHERE     (tblStsHdr.stsStat = 'O') AND (DATEDIFF(day, tblStsHdr.dateEntered, GETDATE()) > 30) $filter AND stsPaymentMode = '$payMode'  ";
		return $this->getArrRes($this->execQry($sql));	  
	}
	function getAgingStsSumm($type,$payMode){
		if($type=="DA"){
			$filter = "AND m.stsType = 5";	
		}else{
			$filter = "AND m.stsType <> 5";	
		}
		if($type=="DA"){
			$filter2 = "AND stsType = 5";	
		}else{
			$filter2 = "AND stsType <> 5";	
		}
		$sql = "SELECT     COUNT(m.stsRefno) AS transact, tblGroup.grpDesc, SUM(m.stsAmt) AS amount,
                          (SELECT     SUM(b.stsAmt)
                            FROM          tblStsHdr b
                            WHERE      stsStat = 'O' AND (DATEDIFF(day, dateEntered, GETDATE()) BETWEEN 31 AND 60) AND b.grpCode = m.grpCode $filter2) AS over30,
                          (SELECT     SUM(stsAmt)
                            FROM          tblStsHdr a
                            WHERE      stsStat = 'O' AND (DATEDIFF(day, dateEntered, GETDATE()) BETWEEN 61 AND 90) AND a.grpCode = m.grpCode $filter2) AS over60,
                          (SELECT     SUM(stsAmt)
                            FROM          tblStsHdr c
                            WHERE      stsStat = 'O' AND (DATEDIFF(day, dateEntered, GETDATE()) > 90) AND c.grpCode = m.grpCode $filter2) AS over90
FROM         tblStsHdr m INNER JOIN
                      tblGroup ON m.grpCode = tblGroup.grpCode
WHERE     (m.stsStat = 'O') $filter AND stsPaymentMode = '$payMode' 
GROUP BY tblGroup.grpDesc, m.grpCode
ORDER BY tblGroup.grpDesc";	
	return $this->getArrRes($this->execQry($sql));	  
	}
}