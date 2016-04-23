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
                      tblUsers ON tblStsHdr.enteredBy = tblUsers.userId LEFT JOIN
                      sql_mmpgtlib.dbo.APSUPP ON tblStsHdr.suppCode = sql_mmpgtlib.dbo.APSUPP.ASNUM
WHERE     (tblStsHdr.stsStat = 'O') AND (DATEDIFF(day, CONVERT(datetime, CONVERT(varchar,tblStsHdr.dateEntered, 101)), GETDATE()) > 30) $filter AND stsPaymentMode = '$payMode' AND tblStsHdr.stsRefno in (SELECT distinct stsRefno FROM tblStsDtl) ORDER BY tblGroup.grpDesc ";
		return $this->getArrRes($this->execQry($sql));	  
	}
	function getAgingStsSumm($type,$payMode){
		if($type=="DA"){
			$filter = "AND hdr.stsType = 5";	
		}else{
			$filter = "AND hdr.stsType <> 5";	
		}
		if($type=="DA"){
			$filter2 = "AND stsType = 5";	
		}else{
			$filter2 = "AND stsType <> 5";	
		}
	
		$sql = "SELECT     COUNT(hdr.stsRefno) AS transact, tblGroup.grpDesc, SUM(hdr.stsAmt) AS amount,
                          (SELECT     SUM(b.stsAmt)
                            FROM          tblStsHdr b
                            WHERE      stsStat = 'O' AND (DATEDIFF(day, CONVERT(datetime, CONVERT(varchar, b.dateEntered, 101)), GETDATE()) BETWEEN 31 AND 60) AND 
                                                   b.grpCode = hdr.grpCode $filter2  AND stsPaymentMode = '$payMode'  AND b.stsRefno IN
                                                       (SELECT DISTINCT stsRefno
                                                         FROM          tblStsDtl)) AS over30,
                          (SELECT     SUM(stsAmt)
                            FROM          tblStsHdr a
                            WHERE      stsStat = 'O' AND (DATEDIFF(day, CONVERT(datetime, CONVERT(varchar, a.dateEntered, 101)), GETDATE()) BETWEEN 61 AND 90) AND 
                                                   a.grpCode = hdr.grpCode $filter2  AND stsPaymentMode = '$payMode'  AND a.stsRefno IN
                                                       (SELECT DISTINCT stsRefno
                                                         FROM          tblStsDtl)) AS over60,
                          (SELECT     SUM(stsAmt)
                            FROM          tblStsHdr c
                            WHERE      stsStat = 'O' AND (DATEDIFF(day, CONVERT(datetime, CONVERT(varchar, c.dateEntered, 101)), GETDATE()) > 90) AND c.grpCode = hdr.grpCode  
                                                  $filter2  AND stsPaymentMode = '$payMode'  AND c.stsRefno IN
                                                       (SELECT DISTINCT stsRefno
                                                         FROM          tblStsDtl)) AS over90,
						 (SELECT     SUM(d.stsAmt)
                            FROM          tblStsHdr d
                            WHERE      stsStat = 'O' AND (DATEDIFF(day, CONVERT(datetime, CONVERT(varchar, d.dateEntered, 101)), GETDATE()) <=30) AND 
                                                   d.grpCode = hdr.grpCode $filter2  AND stsPaymentMode = '$payMode'  AND d.stsRefno IN
                                                       (SELECT DISTINCT stsRefno
                                                         FROM          tblStsDtl)) AS current1
FROM         tblStsHdr hdr INNER JOIN
                      tblGroup ON hdr.grpCode = tblGroup.grpCode
WHERE     (hdr.stsStat = 'O') $filter AND stsPaymentMode = '$payMode'  AND hdr.stsRefno in (SELECT distinct stsRefno FROM tblStsDtl)
GROUP BY tblGroup.grpDesc, hdr.grpCode
ORDER BY tblGroup.grpDesc";	
	return $this->getArrRes($this->execQry($sql));	  
	}
}