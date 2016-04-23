<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
	
class EODobj extends commonObj {
	
	
	function getForAutoApproval(){
		
		$sqlUpdate = "update         tblStsHdr set pendingTag = 'P'
WHERE     (stsStat = 'O') AND (DATEDIFF(day, dateEntered, GETDATE()) >= 6) AND ((suppCode = 0) or stsrefno in (SELECT     tblStsHdr.stsRefno
FROM         tblStsHdr LEFT OUTER JOIN
                      tblStsDtl ON tblStsHdr.stsRefno = tblStsDtl.stsRefno
WHERE     (tblStsDtl.stsRefno IS NULL)))";
		
		//$this->execQry($sqlUpdate);
		
		$sql = "SELECT stsRefno
				FROM tblStsHdr
				WHERE (stsStat = 'O') 
                AND (DATEDIFF(day, dateEntered, GETDATE()) >=6) 
                AND suppCode <> 0 AND (stsRefno IN (SELECT DISTINCT stsRefno FROM tblStsDtl))
                AND (holdingDate is null or (DATEDIFF(day, holdingDate, GETDATE()) >= 1)) ";	
		return $this->getArrRes($this->execQry($sql));
	}
	
	function getSTSType($refNo){
		$sql = "SELECT stsType FROM tblStsHdr where stsRefno = '$refNo'";
		$type = $this->getSqlAssoc($this->execQry($sql));
		return $type['stsType'];
	}
	
	function releaseSTS($refNo){
		$stsType = $this->getSTSType($refNo);
		$stsCount = $this->countSTSDetail($refNo);		
		if($stsCount > 0){
			$sqlGetSTSNo = "exec getLastSTSNo";
			$stsNo = $this->getSqlAssoc($this->execQry($sqlGetSTSNo));
			
			$trans = $this->beginTran();
			$a = $stsNo['stsNo']+$stsCount;
			$sqlUpdateSTSNo = "UPDATE pg_pf..tblStsNo SET stsNo = ".$a." ";
			if ($trans) {
				$trans = $this->execQry($sqlUpdateSTSNo);
			}
			if ($trans){
				$trans = $this->commitTran();
			}
			##############contract number
			if((int)$stsType==5){
				$trans3 = $this->beginTran();
				$sqlGetContractNo = "exec getLastContractNo";
				$contractNo = $this->getSqlAssoc($this->execQry($sqlGetContractNo));
				$newContract = $contractNo['lastContractNo'];
				$b = $newContract+$stsCount;
				$sqlUpdateContractNo = "UPDATE pg_pf..tblContractNo SET lastContractNo = '".$b."' ";
				if ($trans3) {
					$trans3 = $this->execQry($sqlUpdateContractNo);
				}
				if ($trans3){
					$trans3 = $this->commitTran();
				}
			}
			################ end of contract no
			
			
			############################END OF STS
			######################ASSIGNING OF STS NO
			$tempSTSNo = (int)$stsNo['stsNo'];
			$startingSTS = (int)$stsNo['stsNo']+1;
			$arrPar = $this->getParticipants($refNo);
	
			$trans1 = $this->beginTran();
			foreach($arrPar as $val){
				$tempSTSNo++;
				
				$sqlDtl = "UPDATE tblStsDtl set stsNo = '$tempSTSNo', dtlStatus = 'R' WHERE stsRefno = '{$val['stsRefno']}' AND compCode = '{$val['compCode']}' AND strCode = '{$val['strCode']}';";
				if ($trans1) {
					$trans1 = $this->execQry($sqlDtl);
				}
				if((int)$stsType==5){
					$newContract++;
					$sqlDaDtl = "UPDATE tblStsDaDetail SET stsNo  = '$tempSTSNo', contractNo = '$newContract' WHERE stsRefno = '{$val['stsRefno']}' AND strCode =  '{$val['strCode']}'";
					if ($trans1) {
						$trans1 = $this->execQry($sqlDaDtl);
						
					}
				}
			}
			################ END OF STSNO
			
			############### UPDATE HEADER
			
			$sqlManager = "SELECT  top 1  userId
			FROM         tblUsers
			WHERE     (grpCode IN
				(SELECT     tblStsHdr.grpCode
						 FROM          tblStsHdr
						 WHERE      stsrefno = '".$refNo."')) AND (isManager = 'Y') AND userStat = 'A'";
			$approvedBy = $this->getSqlAssoc($this->execQry($sqlManager));
			
			$sqlUpdateHeader = "UPDATE tblStsHdr SET stsStartNo = '$startingSTS', stsEndNo = '$tempSTSNo', approvedBy = '".$approvedBy['userId']."', dateApproved = '".date('m/d/Y')."', stsStat = 'R' WHERE stsRefNo = '".$refNo."';";
			if((int)$stsType==2){
				$this->generateListingBatch($refNo);
			}
			if ($trans1){
				$trans1 = $this->execQry($sqlUpdateHeader);
			}
			if(!$trans1){
				$trans1 = $this->rollbackTran();
			}else{
				$trans1 = $this->commitTran();
				//$noRefNo .= $arr['refNo_'.$i].",";
			}
		}else{
			//$errorRefNo .= $arr['refNo_'.$i].",";
		}
		############### END OF UPDATE HEADER
			
	}
	
	function generateListingBatch($refNo){
		$getToExport = "SELECT     tblStsDtl.strCode, tblStsDtl.stsAmt, tblStsDtl.stsNo, tblStsHdr.stsRemarks, tblStsDtl.stsRefno
					FROM         tblStsDtl INNER JOIN
                      tblStsHdr ON tblStsDtl.stsRefno = tblStsHdr.stsRefno WHERE tblStsDtl.stsRefno = '$refNo'";
			$arrContent = $this->getArrRes($this->execQry($getToExport));
			
			$sqlGetLastBatch = "SELECT listFeeNo FROM tblListingFeeNo";
			$oldBatch = $this->getSqlAssoc($this->execQry($sqlGetLastBatch));
			$newBatch = (int)$oldBatch['listFeeNo']+1;//new voucher number
			
			$trans = $this->beginTran();
			
			$qryUpdateApNo = "UPDATE tblListingFeeNo SET listFeeNo = ".$newBatch." ";
			if ($trans){
				$trans = $this->execQry($qryUpdateApNo);
			}
			$listBatchNo = sprintf("%09s", $newBatch)."X";
			
			$gmt = time() + (8 * 60 * 60);
			$todayTime = date("His");
			$datefileMD = date("md");
			$datefileY = date("y");
			
			$fileLocMMSApEnt = "../../exportfiles/listingFee/".trim("STU").$datefileMD.$datefileY."_".$todayTime.$SECONDS.".CSV"; 
			$fileNameMMSApEnt=trim("STU").$datefileMD.$datefileY."_".$todayTime.$SECONDS.".CSV"; 
			
			foreach($arrContent as $val){
				$mmsApEntContent = $mmsApEntContent.
					$listBatchNo.",". ###STS Batch
					sprintf("%010s", $val['stsNo']).",". ###STS #
					$val['strCode'].",". ### STore Code
					"0,". ### Blank
					substr(str_replace(",","-",$val['stsRemarks']),0,30).",". ### Remarks
					$val['stsAmt']. ### Amount
					"\r\n";
				
			}
			$sqlReUpdateHeader = "UPDATE tblStsHdr SET stsRemarks = '".$listBatchNo." ".$val['stsRemarks']."' WHERE stsRefNo = '$refNo';";
			if ($trans){
				$trans = $this->execQry($sqlReUpdateHeader);
			}	
			if(!$trans){
				$trans = $this->rollbackTran();
			}else{
				$trans = $this->commitTran();
			}
			if (file_exists($fileNameMMSApEnt)) {
				unlink($fileLocMMSApEnt);
			} 
			$mmsApEntHandler = fopen($fileLocMMSApEnt, "x");
						
			fwrite($mmsApEntHandler, $mmsApEntContent);
			fclose($mmsApEntHandler);		
			
			$ftp_server = "192.168.200.100";  
			$ftp_user_name = "dtsuser"; 
			$ftp_user_pass = "dtsuser"; 
			$conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server");        
						
			$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die("You do not have access to this ftp server!");   
			
			$destination_file = "/pgtflr/APMFLR/Unprocess/".$fileNameMMSApEnt;
			$upload = ftp_put($conn_id, $destination_file, $fileLocMMSApEnt, FTP_BINARY);  // upload the file
			
			ftp_close($conn_id); 
			return true;	
	}
	function countSTSDetail($refNo){
		$sql = "SELECT * From tblStsDtl Where stsRefno = '$refNo'";
		return  $this->getRecCount($this->execQry($sql));
	}
	function getParticipants($refNo){
		$sql = "SELECT * FROM tblStsDtl WHERE stsRefno = '$refNo'";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function ExtractTblSTShdr() {
		$sql = "Exec sts_test.dbo.sts_EOD2";
		//$sql = "Exec sts_EOD2";
		//return $this->execQry($sql);
		return mssql_query($sql);
	}
	function ExtractAPAR(){
		//$sql = "Exec sts_extractTransacAPAR";
		//return  $this->execQry($sql);
	//	mssql_select_db("sts_test");
		$sql = "Exec sts_test.dbo.sts_extractTransacAPAR";
		return mssql_query($sql);
	}
	function getStsNo(){
		$trans = $this->beginTran();
		$qryGetToApNo = "SELECT stscontrolno from sts..controlno";	
		$oldSTS = $this->getSqlAssoc($this->execQry($qryGetToApNo));
		$newAP = (int)$oldSTS['stscontrolno']+435;
		$qryUpdateApNo = "UPDATE sts..controlno SET stscontrolno = ".$newAP." ";
		if ($trans){
			$trans = $this->execQry($qryUpdateApNo);
		}
		if(!$trans){
			$trans = $this->rollbackTran();
			return "Error";
		}else{
			$trans = $this->commitTran();
			return (int)$oldSTS['stscontrolno'];
		}
	}
	function uploadToOracle(){
			
			$ctr2 = 0;
			$arrCompAp = $this->getDistinctCompCodeInAP();
			
			$trans = $this->beginTran();
			
			
			if(count($arrCompAp)>0){
				foreach($arrCompAp as $valAp){
					$totDetailAmt = 0;
					#### Batch Number
					$qryGetToApNo = "SELECT apBatchNo FROM tblApBatchNo";
					$oldAP = $this->getSqlAssoc($this->execQry($qryGetToApNo));
					$newAP = (int)$oldAP['apBatchNo']+1;//new voucher number
					
					$qryUpdateApNo = "UPDATE tblApBatchNo SET apBatchNo = ".$newAP." ";
					if ($trans){
						$trans = $this->execQry($qryUpdateApNo);
					}
					
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
					
					$sql = "SELECT     tblStsDlyAp.stsNo, tblStsDlyAp.stsSeq, tblStsDlyAp.stsApplyDate, tblStsDlyAp.stsActualDate, tblStsDlyAp.suppCode, tblStsDlyAp.stsApplyAmt, tblStsDlyAp.compCode, 
                      tblBranches.brnShortName, tblBranches.businessLine, tblBranches.compCode as compCodeHO,tblStsHdr.applyDate, tblStsDlyAp.strCode, tblStsHierarchy.glMajor, 
                      tblStsHierarchy.glMinor, tblStsHierarchy.hierarchyDesc, tblStsDlyAp.stsType, tblStsHierarchy.shortDesc, ISNULL(tblStsHdr.stsRemarks, tblStsDlyAp.stsRemarks) AS stsRemarks
FROM         tblStsDlyAp left JOIN
                      tblStsHdr ON tblStsDlyAp.stsRefno = tblStsHdr.stsRefno INNER JOIN
                      tblBranches ON tblStsDlyAp.strCode = tblBranches.strCode INNER JOIN
                      tblStsHierarchy ON tblStsDlyAp.stsDept = tblStsHierarchy.stsDept AND tblStsDlyAp.stsCls = tblStsHierarchy.stsCls AND 
                      tblStsDlyAp.stsSubCls = tblStsHierarchy.stsSubCls
					WHERE tblStsDlyAp.compCode = '{$valAp['compCode']}' AND (tblStsDlyAp.suppCode NOT IN
                          (SELECT     ASNUM
                            FROM          sql_mmpgtlib..APSUPP
                            WHERE      (ASNAME LIKE '%NTBU%')) OR
                      tblStsDlyAp.suppCode NOT IN
                          (SELECT     ADNUM
                            FROM          sql_mmpgtlib..APSUPA
                            WHERE      (ADSTS = 'I')))
					";
					
					
					################# ORACLE TEXT FILE UPLOAD
					
					if($valAp['compCode']==700){
                        $fileFolder = "PGJR";
                        $fileCode = "PJ";    
                        $newCompCode = 700;
                    }elseif( ($valAp['compCode']>=101 && $valAp['compCode']<=105) || ($valAp['compCode']>800 && $valAp['compCode']<809) || $valAp['compCode']==809){
                        $fileFolder = "PPCI";
                        $fileCode = "PG";
                        if(($valAp['compCode']>800 && $valAp['compCode']<809) || ($valAp['compCode']==809)){
                            $newCompCode = 101;
                        }else{
                            $newCompCode = $valAp['compCode'];
                        }
                    }elseif($valAp['compCode']== 810){
                        $fileFolder = "DCI";
                        $fileCode = "DI";
                        $newCompCode = 810;
                    }elseif($valAp['compCode']==811){
                        $fileFolder = "FLS";
                        $fileCode = "FL";
                        $newCompCode = 811;
                    }
                    else{
                        $fileFolder = "PC";
                        $fileCode = "PC";    
                        $newCompCode = 302;
                    }    
					$file_path="../../exportfiles/$fileFolder/AP/$fileCode".$datefileMD.$datefileY."_".$todayTime.".401"; 
					$file_name2="$fileCode".$datefileMD.$datefileY."_".$todayTime.".401";
					/*if (file_exists($file_path)) {
						unlink($file_path);
					} */
					$handle2 = fopen($file_path, "a");
					
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
							$contents .= $prefix.$valCon['stsNo']."-".$valCon['stsSeq']."|";
							$contents .= "DEBIT|";
							$contents .= date("d-M-Y",strtotime($valCon['stsApplyDate']))."|";
							$contents .= $valCon['suppCode']."|";
							$contents .= $valCon['brnShortName']."|";
							$contents .= $valCon['stsApplyAmt']."|";
							$contents .= $valCon['hierarchyDesc']." ".substr(preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $valCon['stsRemarks']),0,100)."|";
							$contents .= date("d-M-Y",strtotime($valCon['stsApplyDate']))."|";
							$contents .= date("d-M-Y",strtotime($valCon['stsApplyDate']))."|";
							$contents .= date("d-M-Y")."|";
							$contents .= "STS|";
							$contents .= "1|";
							$contents .= $valCon['stsApplyAmt']."|";
							$contents .= "ITEM|";
							$contents .= "|";
							$contents .= "|";
							$contents .= "|";
							$contents .= "|";
							$contents .= "|";
							$contents .= "|";
							$contents .= "|";
							$contents .= "|";
							$contents .= "|";
							$contents .= "|";
							$contents .= $newCompCode."|";
							$contents .= $valCon['brnShortName']."|";
							$contents .= $valCon['businessLine']."|";
							$contents .= $department."|"; ##department
							$contents .= "0|"; ##section
							$contents .= $accountMajor."|"; ##major
							$contents .= $accountMajor."|"; ##minor
							$contents .= $valCon['stsApplyAmt']."|"; 
							$contents .= "XX|";
							$contents .= "|";
							$contents .= "|";
							$contents .= "|";
							$contents .= "PHP|";
							$contents .= date("d-M-Y",strtotime($valCon['stsApplyDate']))."|";
							$contents .= "|";
							$contents .= $file_name2."|\r\n";
						
						##### mike sept 12 2012
						if( $valCon['strCode']=='101' || $valCon['strCode']=='102' || $valCon['strCode']=='103' || $valCon['strCode']=='104' || $valCon['strCode']=='105') 
							$mmsCompCode = $valCon['strCode'];
						else{
							$mmsCompCode = 	$valCon['compCodeHO'];
						}
							
					}
					
                    
						fwrite($handle2, $contents);
						fclose($handle2) ;
						unset($contents);
					
					$sqlUpdateAp = "UPDATE tblStsDlyAp SET status = 'A', uploadDate = '".date('Y-m-d')."', uploadApFile = '".$datefileMD.$datefileY."_".$todayTime.$SECONDS."', apBatch = '$apBatchNo' WHERE compCode = '{$valAp['compCode']}' AND (suppCode NOT IN
                          (SELECT     ASNUM
                            FROM          sql_mmpgtlib..APSUPP
                            WHERE      (ASNAME LIKE '%NTBU%')) OR
                      suppCode NOT IN
                          (SELECT     ADNUM
                            FROM          sql_mmpgtlib..APSUPA
                            WHERE      (ADSTS = 'I')))";
					$sqlInsertAp = "INSERT INTO tblStsDlyApHist SELECT * FROM tblstsdlyap WHERE compCode = '{$valAp['compCode']}' AND (suppCode NOT IN
                          (SELECT     ASNUM
                            FROM          sql_mmpgtlib..APSUPP
                            WHERE      (ASNAME LIKE '%NTBU%')) OR
                      suppCode NOT IN
                          (SELECT     ADNUM
                            FROM          sql_mmpgtlib..APSUPA
                            WHERE      (ADSTS = 'I')))";
					$sqlDelAp = "DELETE FROM tblStsDlyAp WHERE compCode = '{$valAp['compCode']}' AND (suppCode NOT IN
                          (SELECT     ASNUM
                            FROM          sql_mmpgtlib..APSUPP
                            WHERE      (ASNAME LIKE '%NTBU%')) OR
                      suppCode NOT IN
                          (SELECT     ADNUM
                            FROM          sql_mmpgtlib..APSUPA
                            WHERE      (ADSTS = 'I')))";
					
					if ($trans){
						$trans = $this->execQry($sqlUpdateAp);
					}
					if ($trans){
						$trans = $this->execQry($sqlInsertAp);
					}
					if ($trans){
						$trans = $this->execQry($sqlDelAp);
					}
					
				}
			}
			
			###############AR
			$arrCompAr = $this->getDistinctCompCodeInAR();
			
			if(count($arrCompAr)>0){
				
				foreach($arrCompAr as $valAr){
					$totDetailAmt = 0;
					#### Batch Number
					$qryGetToApNo = "SELECT apBatchNo FROM tblApBatchNo";
					$oldAP = $this->getSqlAssoc($this->execQry($qryGetToApNo));
					$newAP = (int)$oldAP['apBatchNo']+1;//new voucher number
					
					$qryUpdateApNo = "UPDATE tblApBatchNo SET apBatchNo = ".$newAP." ";
					if ($trans){
						$trans = $this->execQry($qryUpdateApNo);
					}
					
					$tempApNo = sprintf("%05s", $newAP);
					$arBatchNo =  sprintf("%06s", $newAP);
					//$arBatchNo = $apBatchNo;
						
					$mmsApEntContent="";
					$mmsApEntIwt="";
					$mmsIDstContent="";
					$mmsAPHeadContent="";
					$mmsApEntHandler = "";
					$mmsApIwtHandler = "";
					$mmsApIDstHandler = "";
					$mmsApHeadHandler = "";
								
					$sqlAr = "SELECT     tblStsDlyAr.stsNo, tblStsDlyAr.stsSeq, tblStsDlyAr.stsApplyDate, tblStsDlyAr.stsActualDate, tblStsDlyAr.suppCode, tblStsDlyAr.stsApplyAmt, tblStsDlyAr.compCode, 
                      tblStsHierarchy.glMajor, tblStsHierarchy.glMinor, tblBranches.brnShortName, tblBranches.businessLine, tblBranches.compCodeHO, tblStsHdr.applyDate,  tblStsHierarchy.hierarchyDesc, tblStsDlyAr.strCode, tblStsDlyAr.stsType, tblStsDlyAr.stsVatAmt, tblStsDlyAr.stsEwtAmt, tblBranches.ewtLoc,tblStsHierarchy.shortDesc, ISNULL(tblStsHdr.stsRemarks, tblStsDlyAr.stsRemarks) AS stsRemarks, tblStsHdr.vatTag, tblStsHierarchy.orGlMajor, tblStsHierarchy.orGlMinor,tblStsHierarchy.wOr
FROM         tblStsDlyAr LEFT OUTER JOIN
                      tblStsHdr ON tblStsDlyAr.stsRefno = tblStsHdr.stsRefno INNER JOIN
                       tblStsHierarchy ON tblStsDlyAr.stsDept = tblStsHierarchy.stsDept AND tblStsDlyAr.stsCls = tblStsHierarchy.stsCls AND 
                      tblStsDlyAr.stsSubCls = tblStsHierarchy.stsSubCls INNER JOIN
                      tblBranches ON tblStsDlyAr.strCode = tblBranches.strCode
					WHERE (tblstsdlyar.compCode = '{$valAr['compCode']}') AND (tblStsDlyAr.suppCode NOT IN
                          (SELECT     suppCode
                            FROM          check_AR)) AND (tblStsDlyAr.suppCode NOT IN
                          (SELECT     ASNUM
                            FROM          sql_mmpgtlib..APSUPP
                            WHERE      (ASNAME LIKE '%NTBU%')) OR
                      tblStsDlyAr.suppCode NOT IN
                          (SELECT     ADNUM
                            FROM          sql_mmpgtlib..APSUPA
                            WHERE      (ADSTS = 'I')))
					";
					
					if($valAr['compCode']==700){
                        $fileFolder = "PGJR";
                        $fileCode = "PJ";    
                        $newCompCode = 700;
                    }elseif( ($valAr['compCode']>=101 && $valAr['compCode']<=105) || ($valAr['compCode']>800 && $valAr['compCode']<809) || $valAr['compCode']==809){
                        $fileFolder = "PPCI";
                        $fileCode = "PG";
                        if(($valAr['compCode']>800 && $valAr['compCode']<809) || ($valAr['compCode']==809)){
                            $newCompCode = 101;
                        }else{
                            $newCompCode = $valAr['compCode'];
                        }
                    }elseif($valAr['compCode']== 810){
                        $fileFolder = "DCI";
                        $fileCode = "DI";
                        $newCompCode = 810;
                    }elseif($valAr['compCode'] == 811){
                        $fileFolder = "FLS";
                        $fileCode = "FL";
                        $newCompCode = 811;
                    }
                    else{
                        $fileFolder = "PC";
                        $fileCode = "PC";    
                        $newCompCode = 302;
                    }
					
					$gmt = time() + (8 * 60 * 60);
					$todayTime = date("His");
					$datefileMD = date("md");
					$datefileY = date("y");
					
					$file_path="../../exportfiles/$fileFolder/AR/$fileCode".$datefileMD.$datefileY."_".$todayTime.".H01"; 
					$file_name2="$fileCode".$datefileMD.$datefileY."_".$todayTime.".H01";
					
					/*if (file_exists($file_path)) {
						unlink($file_path);
					} */
					$handle2 = fopen($file_path, "a");
					
					$arrContent = $this->getArrRes($this->execQry($sqlAr));
					
					$ctr=0;
					
					foreach($arrContent as $valCon){
						
						if($valCon['vatTag']=='Y'){
							if($valCon['wOr']=='Y'){
								$glMajor = $valCon['glMajor'];
								$glMinor = $valCon['glMinor'];
							}else{
								$glMajor = $valCon['orGlMajor'];
								$glMinor = $valCon['orGlMinor'];
							}
							
						}else{
							$glMajor = $valCon['glMajor'];
							$glMinor = $valCon['glMinor'];
						}
						
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
						
							$ctr++;
							$contents .= $prefix.$valCon['stsNo']."-".$valCon['stsSeq']."|";
							$contents .= date("d-M-Y",strtotime($valCon['stsApplyDate']))."|";
							$contents .= "STS".$glMajor.$glMinor."|";
							$contents .= $valCon['brnShortName']."|";
							$contents .= $custCode['ASRCUS']."|";
							$contents .= $valCon['brnShortName']."|";
							$contents .= "1|";
							$contents .= $valCon['businessLine']."|";
							$contents .= "STS|";
							$contents .= date("d-M-Y",strtotime($valCon['stsApplyDate']))."|";
							$contents .= $prefix.$valCon['stsNo']."-".$valCon['stsSeq']."|";
							$contents .= $prefix.$valCon['stsNo']."-".$valCon['stsSeq']." ".$valCon['shortDesc']." ".substr(preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $valCon['stsRemarks']),0,100)."|";
							$contents .= "1|";
							$contents .= $valCon['stsApplyAmt']."|";
							$contents .= "|";
							$contents .= "PHP|";
							$contents .= "0|";
							$contents .= $file_name2."|\r\n";
							
							##########################  vat and ewt text file generation for oracle dec 19 2013
							if((int)$valCon['stsVatAmt']!='' || (float)$valCon['stsVatAmt']!=0){
								$contents .= $prefix.$valCon['stsNo']."-".$valCon['stsSeq']."VAT|";
								$contents .= date("d-M-Y",strtotime($valCon['stsApplyDate']))."|";
								$contents .= "ST/DA/PF-VAT|";
								$contents .= $valCon['brnShortName']."|";
								$contents .= $custCode['ASRCUS']."|";
								$contents .= $valCon['brnShortName']."|";
								$contents .= "1|";
								$contents .= $valCon['businessLine']."|";
								$contents .= "STS|";
								$contents .= date("d-M-Y",strtotime($valCon['stsApplyDate']))."|";
								$contents .= $prefix.$valCon['stsNo']."-".$valCon['stsSeq']."VAT|";
								$contents .= $prefix.$valCon['stsNo']."-".$valCon['stsSeq']."VAT|";
								$contents .= "1|";
								$contents .= $valCon['stsVatAmt']."|";
								$contents .= "|";
								$contents .= "PHP|";
								$contents .= "0|";
								$contents .= $file_name2."|\r\n";
								
								
								####disabled by ma'am linda Dec 23 2013
								/*$contents .= $prefix.$valCon['stsNo']."-".$valCon['stsSeq']."EWT|";
								$contents .= date("d-M-Y",strtotime($valCon['stsApplyDate']))."|";
								$contents .= "STSEWT|";
								$contents .= $valCon['brnShortName']."|";
								$contents .= $custCode['ASRCUS']."|";
								$contents .= $valCon['brnShortName']."|";
								$contents .= "1|";
								$contents .= $valCon['businessLine']."|";
								$contents .= "STS|";
								$contents .= date("d-M-Y",strtotime($valCon['stsApplyDate']))."|";
								$contents .= $prefix.$valCon['stsNo']."-".$valCon['stsSeq']."EWT|";
								$contents .= $prefix.$valCon['stsNo']."-".$valCon['stsSeq']."EWT|";
								$contents .= "1|";
								$contents .= $valCon['stsEwtAmt']."|";
								$contents .= "|";
								$contents .= "PHP|";
								$contents .= $prefix.$valCon['stsNo']."-".$valCon['stsSeq']."|";
								$contents .= $file_name2."|\r\n";*/
							}
						}
						$stsApply = 0;
		
					fwrite($handle2, $contents);
					
					fclose($handle2);
					unset($contents);
                    
                    // old backup by mydel
                    /*
					############### APHEAD
					if((string)$mmsCompCode =='809'){
					$mmsAPHeadContent = $mmsAPHeadContent.
						"ARZ039,". ##"ARZ039"
						"MBALIG2,". ##user
						"STS WEB ".$arBatchNo.",". ## Remarks
						$ctr.",". ## Total Records
						$totDetailAmt.",". ##Total Amt
						$ctr.",". ## Total Actual record
						$totDetailAmt.",". ##Total actual Amt
						"N,". ##with error?
						$subLedger2[0].",".  ### HO Location
						$subLedger2[1].",". ## subledger
						"1,". ## BTSTAT
						"0,". ## BTCOD
						$arBatchNo."". ## AR BatchNo
						"\r\n";
					}
                    */
                   
					$sqlUpdateAr = "UPDATE tblstsdlyar SET status = 'A', uploadDate = '".date('Y-m-d')."', uploadArFile = '".$datefileMD.$datefileY."_".$todayTime.$SECONDS."', arBatch = '$arBatchNo' WHERE compCode = '{$valAr['compCode']}'  AND (tblStsDlyAr.suppCode NOT IN
                          (SELECT     suppCode
                            FROM          check_AR)) AND (tblStsDlyAr.suppCode NOT IN
                          (SELECT     ASNUM
                            FROM          sql_mmpgtlib..APSUPP
                            WHERE      (ASNAME LIKE '%NTBU%')) OR
                      tblStsDlyAr.suppCode NOT IN
                          (SELECT     ADNUM
                            FROM          sql_mmpgtlib..APSUPA
                            WHERE      (ADSTS = 'I')))";
					$sqlInsertAr = "INSERT INTO tblstsdlyarhist SELECT * FROM tblstsdlyar WHERE compCode = '{$valAr['compCode']}' AND (tblStsDlyAr.suppCode NOT IN
                          (SELECT     suppCode
                            FROM          check_AR)) AND (tblStsDlyAr.suppCode NOT IN
                          (SELECT     ASNUM
                            FROM          sql_mmpgtlib..APSUPP
                            WHERE      (ASNAME LIKE '%NTBU%')) OR
                      		tblStsDlyAr.suppCode NOT IN
                          (SELECT     ADNUM
                            FROM          sql_mmpgtlib..APSUPA
                            WHERE      (ADSTS = 'I')))";
					$sqlDelAr = "DELETE FROM tblstsdlyar WHERE compCode = '{$valAr['compCode']}' AND (tblStsDlyAr.suppCode NOT IN
                          (SELECT     suppCode
                            FROM          check_AR)) AND (tblStsDlyAr.suppCode NOT IN
                          (SELECT     ASNUM
                            FROM          sql_mmpgtlib..APSUPP
                            WHERE      (ASNAME LIKE '%NTBU%')) OR
                      tblStsDlyAr.suppCode NOT IN
                          (SELECT     ADNUM
                            FROM          sql_mmpgtlib..APSUPA
                            WHERE      (ADSTS = 'I')))";
					
					if ($trans){
						$trans = $this->execQry($sqlUpdateAr);
					}
					if ($trans){
						$trans = $this->execQry($sqlInsertAr);
					}
					if ($trans){
						$trans = $this->execQry($sqlDelAr);
					}
				}
			}
			if(!$trans){
				$trans = $this->rollbackTran();
				return false;
			}else{
				$trans = $this->commitTran();
				return true;
			}
		}
		function getDistinctCompCodeInAP(){
			$sql = "SELECT DISTINCT compCode FROM tblstsdlyap order by compCode;";	
			return $this->getArrRes($this->execQry($sql));
		}
		
		function getDistinctCompCodeInAR(){
			$sql = "SELECT DISTINCT compCode FROM tblstsdlyar  order by compCode;";	
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