<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
	
class EODobj extends commonObj {
	
	
	function uploadToOracle($dtFrom,$dtTo){
			
			$ctr2 = 0;
			 $arrCompAp = $this->getDistinctCompCodeInAP();
			
			
			
			
			 if(count($arrCompAp)>0){
				 foreach($arrCompAp as $valAp){
					 $totDetailAmt = 0;
					 #### Batch Number
					
					
					 $tempApNo = sprintf("%05s", $newAP);
					 $apBatchNo = "ST".$tempApNo;
					
					
					 $gmt = time() + (8 * 60 * 60);
					 $todayTime = date("His");
					 $datefileMD = date("md");
					 $datefileY = date("y");
					 $mmsApEntContent="";
					 $mmsApEntIwt="";
					 $mmsIDstContent="";
					 $mmsAPHeadContent="";
					 $mmsApEntHandler = "";
					 $mmsApIwtHandler = "";
					 $mmsApIDstHandler = "";
					 $mmsApHeadHandler = "";
					
					 $sql = "SELECT     tblStsDlyApHist.stsNo, tblStsDlyApHist.stsSeq, tblStsDlyApHist.stsApplyDate, tblStsDlyApHist.stsActualDate, tblStsDlyApHist.suppCode, tblStsDlyApHist.stsApplyAmt, tblStsDlyApHist.compCode, 
                      tblBranches.brnShortName, tblBranches.businessLine, tblBranches.compCode as compCodeHO,tblStsHdr.applyDate, tblStsDlyApHist.strCode, tblStsHierarchy.glMajor, 
                      tblStsHierarchy.glMinor, tblStsHierarchy.hierarchyDesc, tblStsDlyApHist.stsType
FROM         tblStsDlyApHist left JOIN
                      tblStsHdr ON tblStsDlyApHist.stsRefno = tblStsHdr.stsRefno INNER JOIN
                      tblBranches ON tblStsDlyApHist.strCode = tblBranches.strCode INNER JOIN
                      tblStsHierarchy ON tblStsDlyApHist.stsDept = tblStsHierarchy.stsDept AND tblStsDlyApHist.stsCls = tblStsHierarchy.stsCls AND 
                      tblStsDlyApHist.stsSubCls = tblStsHierarchy.stsSubCls
					WHERE 
					(tblStsDlyApHist.compCode = '{$valAp['compCode']}')
					AND (tblStsDlyApHist.uploadDate BETWEEN '{$dtFrom}' AND '{$dtTo}')
					AND (tblStsDlyApHist.suppCode NOT IN
                          (SELECT     ASNUM
                            FROM          sql_mmpgtlib..APSUPP
                            WHERE      (ASNAME LIKE '%NTBU%')) OR
                    tblStsDlyApHist.suppCode NOT IN
                          (SELECT     ADNUM
                            FROM          sql_mmpgtlib..APSUPA
                            WHERE      (ADSTS = 'I')))
					";
					
					 if($valAp['compCode']==700){
						$fileFolder = "PGJR";
						$fileCode = "PJ";	
						$newCompCode = 700;
					}elseif( ($valAp['compCode']>=101 && $valAp['compCode']<=105) || ($valAp['compCode']>800 && $valAp['compCode']<809)){
						$fileFolder = "PPCI";
						$fileCode = "PG";
						if(($valAp['compCode']>800 && $valAp['compCode']<809)){
							$newCompCode = 101;
						}else{
							$newCompCode = $valAp['compCode'];
						}
					}elseif($valAp['compCode']==809){
						$fileFolder = "PE";
						$fileCode = "PE";
						$newCompCode = 809;
					}else{
						$fileFolder = "PC";
						$fileCode = "PC";	
						$newCompCode = 302;
					}	
					 ################# ORACLE TEXT FILE UPLOAD
					
					 $arrContent = $this->getArrRes($this->execQry($sql));
					 $ctr=0;
					
					 foreach($arrContent as $valCon){
						 $ctr++;
						
						if((int)$valCon['stsType'] == 1){
							if(($valCon['glMajor'] >= 700 && $valCon['glMajor'] <= 999)&&($valCon['glMinor'] >= 101 && $valCon['glMinor'] <=123)){
								$department = $valCon['glMinor'];
								$accountMajor = $newCompCode.$valCon['glMajor']."000";	
							}else{
								$department = "0";
								$accountMajor = $newCompCode.$valCon['glMajor'].$valCon['glMinor'];
							}
						}else{
							$department = "0";
							$accountMajor = $newCompCode.$valCon['glMajor'].$valCon['glMinor'];
						}
						
						if($valCon['stsType']=='3' || $valCon['stsType']=='7' || $valCon['stsType']=='8'){
							$prefix = 'PF';
						}elseif($valCon['stsType']=='5'){
							$prefix = 'DA';
						}else{
							$prefix = 'ST';
						}
						
						 ################ Oracle Text File Upload
						 
						 $sql = "INSERT into ORA..dtsloop_x  VALUES ('".$prefix.$valCon['stsNo']."-".$valCon['stsSeq']."',
						  'DEBIT',
								 '".date("d-M-Y",strtotime($valCon['stsApplyDate']))."',
								 '".$valCon['suppCode']."',
								 '".$valCon['brnShortName']."',
								 '".$valCon['stsApplyAmt']."',
								 '".$valCon['hierarchyDesc']."',
								 '".date("d-M-Y",strtotime($valCon['stsApplyDate']))."',
								 '".date("d-M-Y",strtotime($valCon['stsApplyDate']))."',
								 '".date("d-M-Y")."',
								 'STS',
								 '1',
								 '".$valCon['stsApplyAmt']."',
								 'ITEM',
								 '',
								 '',
								 '',
								 '',
								 '',
								 '',
								 '',
								 '',
								 '',
								 '',
								 '".$newCompCode."',
								 '".$valCon['brnShortName']."',
								 '".$valCon['businessLine']."',
								 '".$department."',
								 '0',
								 '".$accountMajor."',
								 '".$accountMajor."',
								 '".$valCon['stsApplyAmt']."',
								 'XX',
								 '',
								 '',
								 '',
								 'PHP',
								 '".date('d-M-Y',strtotime($valCon['stsApplyDate']))."',
								 '',
								 '".$fileCode.$valCon['uploadApFile']."',''
						 )";
						
						 $this->execQry($sql);		
					 }
				 }
			 }
			
			###############AR
			$arrCompAr = $this->getDistinctCompCodeInAR();
			
			if(count($arrCompAr)>0){
				
				foreach($arrCompAr as $valAr){
					
								
					$sqlAr = "SELECT     tblStsDlyArHist.stsNo, tblStsDlyArHist.stsSeq, tblStsDlyArHist.stsApplyDate, tblStsDlyArHist.stsActualDate, tblStsDlyArHist.suppCode, tblStsDlyArHist.stsApplyAmt, tblStsDlyArHist.compCode, 
                      tblStsHierarchy.glMajor, tblStsHierarchy.glMinor, tblBranches.brnShortName, tblBranches.businessLine, tblBranches.compCodeHO, tblStsHdr.applyDate,  tblStsHierarchy.hierarchyDesc, tblStsDlyArHist.strCode, tblStsDlyArHist.stsType, tblStsDlyArHist.stsVatAmt, tblStsDlyArHist.stsEwtAmt, tblBranches.ewtLoc
FROM         tblStsDlyArHist LEFT OUTER JOIN
                      tblStsHdr ON tblStsDlyArHist.stsRefno = tblStsHdr.stsRefno INNER JOIN
                       tblStsHierarchy ON tblStsDlyArHist.stsDept = tblStsHierarchy.stsDept AND tblStsDlyArHist.stsCls = tblStsHierarchy.stsCls AND 
                      tblStsDlyArHist.stsSubCls = tblStsHierarchy.stsSubCls INNER JOIN
                      tblBranches ON tblStsDlyArHist.strCode = tblBranches.strCode
					WHERE (tblStsDlyArHist.compCode = '{$valAr['compCode']}') 
					AND (tblStsDlyArHist.uploadDate BETWEEN '{$dtFrom}' AND '{$dtTo}')
					AND (tblStsDlyArHist.suppCode NOT IN
                          (SELECT     suppCode
                            FROM          check_AR)) AND (tblStsDlyArHist.suppCode NOT IN
                          (SELECT     ASNUM
                            FROM          sql_mmpgtlib..APSUPP
                            WHERE      (ASNAME LIKE '%NTBU%')) OR
                      tblStsDlyArHist.suppCode NOT IN
                          (SELECT     ADNUM
                            FROM          sql_mmpgtlib..APSUPA
                            WHERE      (ADSTS = 'I')))
					";
					
					if($valAp['compCode']==700){
						$fileFolder = "PGJR";
						$fileCode = "PJ";	
						$newCompCode = 700;
					}elseif( ($valAr['compCode']>=101 && $valAr['compCode']<=105) || ($valAr['compCode']>800 && $valAr['compCode']<809)){
						$fileFolder = "PPCI";
						$fileCode = "PG";
						if(($valAr['compCode']>800 && $valAr['compCode']<809)){
							$newCompCode = 101;
						}else{
							$newCompCode = $valAr['compCode'];
						}
					}elseif($valAr['compCode']==809){
						$fileFolder = "PE";
						$fileCode = "PE";
						$newCompCode = 809;
					}else{
						$fileFolder = "PC";
						$fileCode = "PC";	
						$newCompCode = 302;
					}
					
					
					
					$arrContent = $this->getArrRes($this->execQry($sqlAr));
					
					$ctr=0;
					foreach($arrContent as $valCon){
						
						$qryGetToArCtr = "SELECT arCtr FROM tblArCtr";
						$oldArCtr = $this->getSqlAssoc($this->execQry($qryGetToArCtr));
						$newArCtr = (int)$oldArCtr['arCtr']+1;//new voucher number
						
						$qryUpdateArCtr = "UPDATE tblArCtr SET arCtr = ".$newArCtr." ";
						if ($trans){
							$trans = $this->execQry($qryUpdateArCtr);
						}
						
						if($valCon['stsType']=='3' || $valCon['stsType']=='7' || $valCon['stsType']=='8'){
							$prefix = 'PF';
						}elseif($valCon['stsType']=='5'){
							$prefix = 'DA';
						}else{
							$prefix = 'ST';
						}
						

						if( $valCon['strCode']=='101' || $valCon['strCode']=='102' || $valCon['strCode']=='103' || $valCon['strCode']=='104' || $valCon['strCode']=='105') 
							$mmsCompCode = $valCon['strCode'];
						else{
							$mmsCompCode = 	$valCon['compCode'];
						}
						$subLedger = $this->getSubledger($mmsCompCode);
						$subLedger2 = explode(",",$subLedger);
						$getCustCode = "SELECT ASRCUS FROM sql_mmpgtlib..APSREB WHERE ASNUM = '{$valCon['suppCode']}'";
						$custCode = $this->getSqlAssoc($this->execQry($getCustCode));
						
						$sqlAR = "insert into ORA..ar_invoice    VALUES (
							
							'".$prefix.$valCon['stsNo']."-".$valCon['stsSeq']."',
							'".date("d-M-Y",strtotime($valCon['stsApplyDate']))."',
							'"."STS".$valCon['glMajor'].$valCon['glMinor']."',
							'".$valCon['brnShortName']."',
							'".$custCode['ASRCUS']."',
							'".$valCon['brnShortName']."',
							'".'1'."',
							'".$valCon['businessLine']."',
							'".'STS'."',
							'".date("d-M-Y",strtotime($valCon['stsApplyDate']))."',
							'".$prefix.$valCon['stsNo']."-".$valCon['stsSeq']."',
							'".$prefix.$valCon['stsNo']."-".$valCon['stsSeq']."',
							'".'1'."',
							'".$valCon['stsApplyAmt']."',
							'".'PHP'."',
							'".''."',
							'".'0'."',
							'".$fileCode.$valCon['uploadArFile']."',''
						)";
						$this->execQry($sqlAR);		
						if((int)$valCon['stsVatAmt']!='' || (float)$valCon['stsVatAmt']!=0){
							$sqlAR2 = "insert into ORA..ar_invoice    VALUES (
							'".$prefix.$valCon['stsNo']."-".$valCon['stsSeq']."VAT',
							'".date("d-M-Y",strtotime($valCon['stsApplyDate']))."',
							'".'ST/DA/PF-VAT'."',
							'".$valCon['brnShortName']."',
							'".$custCode['ASRCUS']."',
							'".$valCon['brnShortName']."',
							'".'1'."',
							'".$valCon['businessLine']."',
							'".'STS'."',
							'".date("d-M-Y",strtotime($valCon['stsApplyDate']))."',
							'".$prefix.$valCon['stsNo']."-".$valCon['stsSeq']."VAT|"."',
							'".$prefix.$valCon['stsNo']."-".$valCon['stsSeq']."VAT|"."',
							'".'1'."',
							'".$valCon['stsApplyAmt']."',
							'".'PHP'."',
							'".''."',
							'".'0'."',
							'".$fileCode.$valCon['uploadArFile']."',''
							)";
							$this->execQry($sqlAR2);
						}
					}
				}
			}
			
		}
		function getDistinctCompCodeInAP(){
			$sql = "SELECT DISTINCT compCode FROM tblStsDlyApHist order by compCode;";	
			return $this->getArrRes($this->execQry($sql));
		}
		
		function getDistinctCompCodeInAR(){
			$sql = "SELECT DISTINCT compCode FROM tblStsDlyArHist  order by compCode;";	
			return $this->getArrRes($this->execQry($sql));
		}
		
		function getAPARLastNo($field,$table,$compCode){
			$sql = "SELECT $field FROM $table WHERE stsComp = '$compCode'";
			$No = $this->getSqlAssoc($this->execQry($sql));
			return $No["$field"];
		}
		function getSubledger($compCode){
			 $sql = "SELECT  TOP 1   *
				FROM         tblARZCTL
				WHERE     CTLGLC = '$compCode'";	
			$No = $this->getSqlAssoc($this->execQry($sql));
			return $No["CTLHDO"].",".$No["CTLENT"];
		}
}
	
?>