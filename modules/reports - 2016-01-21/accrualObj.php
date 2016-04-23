<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class accrualObj extends commonObj {
	
	function accrualDtl($dtFrom){
	
			$curFirstDay = date("Y/m/d", strtotime($dtFrom));
			$curLastDay = date("Y/m/d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m',strtotime($dtFrom)).'/01/'.date('Y',strtotime($dtFrom)).' 00:00:00'))));
			
			$nextMonth = date("Y/m/d", strtotime('+1 month',strtotime(date('m',strtotime($dtFrom)).'/01/'.date('Y',strtotime($dtFrom)).' 00:00:00')));
			
			/*
			$sql = "
			SELECT     
			dbo.tblStsDlyApHist.*, 
			dbo.tblStsHierarchy.glMajor AS glMajor, 
			dbo.tblStsHierarchy.glMinor AS glMinor
			FROM         
			dbo.tblStsDlyApHist 
			INNER JOIN
            dbo.tblStsHierarchy ON dbo.tblStsDlyApHist.stsDept = dbo.tblStsHierarchy.stsDept 
			AND dbo.tblStsDlyApHist.stsCls = dbo.tblStsHierarchy.stsCls 
			AND dbo.tblStsDlyApHist.stsSubCls = dbo.tblStsHierarchy.stsSubCls
			WHERE     
			(dbo.tblStsDlyApHist.stsApplyDate BETWEEN '{$curFirstDay}' AND '{$curLastDay}') AND (dbo.tblStsDlyApHist.uploadDate >= '{$nextMonth}'
			AND tblStsDlyApHist.uploadDate not between  '11/7/2014' AND  '11/11/2014')
			order by stsRefno,stsNo,stsSeq,strCode,suppCode,stsApplyAmt
			";
			*/
			
			$sql = "
			SELECT     
			dbo.tblStsAccrualHist.*, 
			dbo.tblStsHierarchy.glMajor AS glMajor, 
			dbo.tblStsHierarchy.glMinor AS glMinor
			FROM         
			dbo.tblStsAccrualHist 
			INNER JOIN
			dbo.tblStsHierarchy ON dbo.tblStsAccrualHist.stsDept = dbo.tblStsHierarchy.stsDept 
			AND dbo.tblStsAccrualHist.stsCls = dbo.tblStsHierarchy.stsCls 
			AND dbo.tblStsAccrualHist.stsSubCls = dbo.tblStsHierarchy.stsSubCls
			WHERE     
			(dbo.tblStsAccrualHist.stsApplyDate BETWEEN '{$curFirstDay}' AND '{$curLastDay}') AND (dbo.tblStsAccrualHist.uploadDate >= '{$nextMonth}')
			order by stsRefno,stsNo,stsSeq,strCode,suppCode,stsApplyAmt
			";
			
		return $this->getArrRes($this->execQry($sql));
	}
	
	function checkAccruMonth($dateMY){
			$sql="
				SELECT     *
				FROM         tblStsAccrualMonth
				where accruMonth = '{$dateMY}'
			";
			return $this->execQry($sql);
	}
	
	function insertTxtFile($dtFrom){
	
			$curFirstDay = date("Y/m/d", strtotime($dtFrom));
			$curLastDay = date("Y/m/d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m',strtotime($dtFrom)).'/01/'.date('Y',strtotime($dtFrom)).' 00:00:00'))));
			
			$nextMonth = date("Y/m/d", strtotime('+1 month',strtotime(date('m',strtotime($dtFrom)).'/01/'.date('Y',strtotime($dtFrom)).' 00:00:00')));
			
			$sql="
			insert into tblStsAccrualTemp(stsNo,stsSeq,stsRefno,compCode,strCode,suppCode,stsType,stsDept,stsCls,stsSubCls,grpCode,stsApplyAmt,stsApplyDate,stsActualDate,
			stsPaymentMode,uploadDate,uploadApRef,uploadApFile,status,apBatch,stsRemarks,glMajor,glMinor)
			SELECT     
			dbo.tblStsDlyApHist.stsNo,dbo.tblStsDlyApHist.stsSeq,dbo.tblStsDlyApHist.stsRefno,dbo.tblStsDlyApHist.compCode,dbo.tblStsDlyApHist.strCode,dbo.tblStsDlyApHist.suppCode,
			dbo.tblStsDlyApHist.stsType,dbo.tblStsDlyApHist.stsDept,dbo.tblStsDlyApHist.stsCls,dbo.tblStsDlyApHist.stsSubCls,dbo.tblStsDlyApHist.grpCode,dbo.tblStsDlyApHist.stsApplyAmt,
			dbo.tblStsDlyApHist.stsApplyDate,dbo.tblStsDlyApHist.stsActualDate,
			dbo.tblStsDlyApHist.stsPaymentMode,dbo.tblStsDlyApHist.uploadDate,dbo.tblStsDlyApHist.uploadApRef,dbo.tblStsDlyApHist.uploadApFile,dbo.tblStsDlyApHist.status,
			dbo.tblStsDlyApHist.apBatch,dbo.tblStsDlyApHist.stsRemarks,
			dbo.tblStsHierarchy.glMajor AS glMajor, 
			dbo.tblStsHierarchy.glMinor AS glMinor
			FROM         
			dbo.tblStsDlyApHist 
			INNER JOIN
            dbo.tblStsHierarchy ON dbo.tblStsDlyApHist.stsDept = dbo.tblStsHierarchy.stsDept 
			AND dbo.tblStsDlyApHist.stsCls = dbo.tblStsHierarchy.stsCls 
			AND dbo.tblStsDlyApHist.stsSubCls = dbo.tblStsHierarchy.stsSubCls
			WHERE     
			(dbo.tblStsDlyApHist.stsApplyDate BETWEEN '{$curFirstDay}' AND '{$curLastDay}') AND (dbo.tblStsDlyApHist.uploadDate >= '{$nextMonth}')
			order by stsRefno,stsNo,stsSeq,strCode,suppCode,stsApplyAmt
			";
		return $this->execQry($sql);
	}
	
	function getTxtFileName($dtFrom){
	
			$curFirstDay = date("Y/m/d", strtotime($dtFrom));
			$curLastDay = date("Y/m/d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m',strtotime($dtFrom)).'/01/'.date('Y',strtotime($dtFrom)).' 00:00:00'))));
			
			$nextMonth = date("Y/m/d", strtotime('+1 month',strtotime(date('m',strtotime($dtFrom)).'/01/'.date('Y',strtotime($dtFrom)).' 00:00:00')));
			
			$sql="
			SELECT     
			DISTINCT dbo.tblStsDlyApHist.compCode
			FROM         
			dbo.tblStsDlyApHist 
			INNER JOIN
            dbo.tblStsHierarchy ON dbo.tblStsDlyApHist.stsDept = dbo.tblStsHierarchy.stsDept 
			AND dbo.tblStsDlyApHist.stsCls = dbo.tblStsHierarchy.stsCls 
			AND dbo.tblStsDlyApHist.stsSubCls = dbo.tblStsHierarchy.stsSubCls
			WHERE     
			(dbo.tblStsDlyApHist.stsApplyDate BETWEEN '{$curFirstDay}' AND '{$curLastDay}') AND (dbo.tblStsDlyApHist.uploadDate >= '{$nextMonth}')
			";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function getTxtFileContents($dtFrom){
	
			$curFirstDay = date("Y/m/d", strtotime($dtFrom));
			$curLastDay = date("Y/m/d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m',strtotime($dtFrom)).'/01/'.date('Y',strtotime($dtFrom)).' 00:00:00'))));
			
			$nextMonth = date("Y/m/d", strtotime('+1 month',strtotime(date('m',strtotime($dtFrom)).'/01/'.date('Y',strtotime($dtFrom)).' 00:00:00')));
			
			//summary
			/*
			$sql="
			SELECT     
			dbo.tblStsAccrualTemp.compCode,
			dbo.tblStsAccrualTemp.strCode,
			dbo.tblStsAccrualTemp.suppCode,
			dbo.tblStsAccrualTemp.stsDept,
			dbo.tblStsAccrualTemp.stsCls,
			dbo.tblStsAccrualTemp.stsSubCls,
			sum(dbo.tblStsAccrualTemp.stsApplyAmt * -1) as stsApplyAmt,
			dbo.tblStsHierarchy.glMajor, 
			dbo.tblStsHierarchy.glMinor
			FROM         
			dbo.tblStsAccrualTemp 
			INNER JOIN
			dbo.tblStsHierarchy ON dbo.tblStsAccrualTemp.stsDept = dbo.tblStsHierarchy.stsDept 
			AND dbo.tblStsAccrualTemp.stsCls = dbo.tblStsHierarchy.stsCls 
			AND dbo.tblStsAccrualTemp.stsSubCls = dbo.tblStsHierarchy.stsSubCls
			WHERE     
			(dbo.tblStsAccrualTemp.stsApplyDate BETWEEN '{$curFirstDay}' AND '{$curLastDay}') AND (dbo.tblStsAccrualTemp.uploadDate >= '{$nextMonth}')
			group by 
			dbo.tblStsAccrualTemp.compCode,
			dbo.tblStsAccrualTemp.strCode,
			dbo.tblStsAccrualTemp.suppCode,
			dbo.tblStsAccrualTemp.stsDept,
			dbo.tblStsAccrualTemp.stsCls,
			dbo.tblStsAccrualTemp.stsSubCls,
			dbo.tblStsHierarchy.glMajor, 
			dbo.tblStsHierarchy.glMinor
			order by 
			dbo.tblStsAccrualTemp.strCode,
			dbo.tblStsAccrualTemp.suppCode
			";
			*/
			
			$sql="
			SELECT     
			cast(tblTransType.typePrefix as nvarchar)+cast(stsNo as nvarchar)+'-'+cast(stsSeq as nvarchar) as invoice,   
			dbo.tblStsAccrualTemp.compCode,
			dbo.tblStsAccrualTemp.strCode,
			dbo.tblStsAccrualTemp.suppCode,
			dbo.tblStsAccrualTemp.stsDept,
			dbo.tblStsAccrualTemp.stsCls,
			dbo.tblStsAccrualTemp.stsSubCls,
			dbo.tblStsAccrualTemp.stsApplyAmt * -1 as stsApplyAmt,
			dbo.tblStsHierarchy.glMajor, 
			dbo.tblStsHierarchy.glMinor
			FROM         
			dbo.tblStsAccrualTemp 
			INNER JOIN
			dbo.tblStsHierarchy ON dbo.tblStsAccrualTemp.stsDept = dbo.tblStsHierarchy.stsDept 
			AND dbo.tblStsAccrualTemp.stsCls = dbo.tblStsHierarchy.stsCls 
			AND dbo.tblStsAccrualTemp.stsSubCls = dbo.tblStsHierarchy.stsSubCls
			left join tblTransType on tblTransType.typeCode = dbo.tblStsAccrualTemp.stsType
			WHERE     
			(dbo.tblStsAccrualTemp.stsApplyDate BETWEEN '{$curFirstDay}' AND '{$curLastDay}') AND (dbo.tblStsAccrualTemp.uploadDate >= '{$nextMonth}')
			order by 
			dbo.tblStsAccrualTemp.strCode,
			dbo.tblStsAccrualTemp.suppCode
			";
			
			//not summarized
			/*
			$sql="
			SELECT     
			dbo.tblStsDlyApHist.stsNo,dbo.tblStsDlyApHist.stsSeq,dbo.tblStsDlyApHist.stsRefno,dbo.tblStsDlyApHist.compCode,dbo.tblStsDlyApHist.strCode,dbo.tblStsDlyApHist.suppCode,
			dbo.tblStsDlyApHist.stsType,dbo.tblStsDlyApHist.stsDept,dbo.tblStsDlyApHist.stsCls,dbo.tblStsDlyApHist.stsSubCls,dbo.tblStsDlyApHist.grpCode,dbo.tblStsDlyApHist.stsApplyAmt,
			dbo.tblStsDlyApHist.stsApplyDate,dbo.tblStsDlyApHist.stsActualDate,
			dbo.tblStsDlyApHist.stsPaymentMode,dbo.tblStsDlyApHist.uploadDate,dbo.tblStsDlyApHist.uploadApRef,dbo.tblStsDlyApHist.uploadApFile,dbo.tblStsDlyApHist.status,
			dbo.tblStsDlyApHist.apBatch,dbo.tblStsDlyApHist.stsRemarks,
			dbo.tblStsHierarchy.glMajor AS glMajor, 
			dbo.tblStsHierarchy.glMinor AS glMinor
			FROM         
			dbo.tblStsDlyApHist 
			INNER JOIN
            dbo.tblStsHierarchy ON dbo.tblStsDlyApHist.stsDept = dbo.tblStsHierarchy.stsDept 
			AND dbo.tblStsDlyApHist.stsCls = dbo.tblStsHierarchy.stsCls 
			AND dbo.tblStsDlyApHist.stsSubCls = dbo.tblStsHierarchy.stsSubCls
			WHERE     
			(dbo.tblStsDlyApHist.stsApplyDate BETWEEN '{$curFirstDay}' AND '{$curLastDay}') AND (dbo.tblStsDlyApHist.uploadDate >= '{$nextMonth}')
			order by stsRefno,stsNo,stsSeq,strCode,suppCode,stsApplyAmt
			";
			*/
		return $this->getArrRes($this->execQry($sql));
	}
	
	function insertAccruMonth($dtFrom) {
			$sql="
				insert into tblStsAccrualMonth values('{$dtFrom}')
			";
			return $this->execQry($sql);
	}
	
	function getStoreDtl($store) {
			$sql="
				SELECT     brnShortName,businessLine
				FROM         tblBranches
				WHERE     (strCode = '{$store}')
			";
			return $this->getSqlAssoc($this->execQry($sql));
	}
	
	function createTxtFile($dtFrom) {
	
			$arrFileName = $this->getTxtFileName($dtFrom);
			
			//foreach($arrFileName as $valFileName){
				
				//$fileName = $valFileName['filename'];
				//$fileName = $valFileName['compCode'];
				
				
				$runDate = date('d-M-Y');
				$date = date('ymd');
				$time = date('his');
				
				
				$this->insertAccruMonth($dtFrom);
				$arrFileCont = $this->getTxtFileContents($dtFrom);
				
				//Junior F01
				
				$fileNameJr = "PJ{$date}_{$time}.F01";
				
				$fContJr = "";
				
				foreach($arrFileCont as $valFileContJr){
				
					if($valFileContJr['compCode']==700){
					
					$newCompCode = 700;
						
						$storeShort = $this->getStoreDtl($valFileContJr['strCode']);
						
						//$fContJr .= trim(date('d-M-Y',strtotime($valFileContJr['stsApplyDate'])))."|"; 					##acctDate
						$fContJr .= trim(date('d-M-Y',strtotime($runDate)))."|"; 					##acctDate
						$fContJr .= trim("DEBIT")."|";					##acctType
						$fContJr .= trim($newCompCode)."|";					##compCode
						$fContJr .= trim($storeShort['brnShortName'])."|";					##storeShort
						$fContJr .= trim($storeShort['businessLine'])."|"; 				##businessLine
						$fContJr .= trim("0")."|";					##department
						$fContJr .= trim("0")."|";					##section
						$fContJr .= trim($newCompCode."305101")."|"; 					##acctCode
						$fContJr .= trim($newCompCode."305101")."|"; 						##acctCode
						$fContJr .= trim("0")."|";					##DTL_QUANTITY
						$fContJr .= trim("0")."|";					##DTL_VAT_CODE
						$fContJr .= "|";					##DTL_CURRENCY
						$fContJr .= trim("STS Accrual Current ".$dtFrom)."|";					##journalName
						$fContJr .= trim("Accrual")."|"; 						##catgryName
						$fContJr .= trim("PHP")."|"; 						##currCode
						$fContJr .= trim($valFileContJr['stsApplyAmt'])."|"; 						##amount
						$fContJr .= trim($valFileContJr['invoice'])."|"; 						##sourceRef
						$fContJr .= "|"; 					##
						$fContJr .= "|"; 					##
						$fContJr .= "|"; 					##
						$fContJr .= "|"; 					##
						$fContJr .= "|"; 					##
						$fContJr .= trim($fileNameJr)."|"; 						##fileName
						$fContJr .= "\r\n";
						
						//$fContJr .= trim(date('d-M-Y',strtotime($valFileContJr['stsApplyDate'])))."|"; 					##acctDate
						$fContJr .= trim(date('d-M-Y',strtotime($runDate)))."|"; 					##acctDate
						$fContJr .= trim("CREDIT")."|";					##acctType
						$fContJr .= trim($newCompCode)."|";					##compCode
						$fContJr .= trim($storeShort['brnShortName'])."|";					##storeShort
						$fContJr .= trim($storeShort['businessLine'])."|"; 				##businessLine
						$fContJr .= trim("0")."|";					##department
						$fContJr .= trim("0")."|";					##section
						$fContJr .= trim($newCompCode.$valFileContJr['glMajor'].$valFileContJr['glMinor'])."|"; 					##acctCode
						$fContJr .= trim($newCompCode.$valFileContJr['glMajor'].$valFileContJr['glMinor'])."|"; 						##acctCode
						$fContJr .= trim("0")."|";					##DTL_QUANTITY
						$fContJr .= trim("0")."|";					##DTL_VAT_CODE
						$fContJr .= "|";					##DTL_CURRENCY
						$fContJr .= trim("STS Accrual Current ".$dtFrom)."|";					##journalName
						$fContJr .= trim("Accrual")."|"; 						##catgryName
						$fContJr .= trim("PHP")."|"; 						##currCode
						$fContJr .= trim($valFileContJr['stsApplyAmt'])."|"; 						##amount
						$fContJr .= trim($valFileContJr['invoice'])."|"; 						##sourceRef
						$fContJr .= "|"; 					##
						$fContJr .= "|"; 					##
						$fContJr .= "|"; 					##
						$fContJr .= "|"; 					##
						$fContJr .= "|"; 					##
						$fContJr .= trim($fileNameJr)."|"; 						##fileName
						$fContJr .= "\r\n";
					
					}
				}
				
				$destiFoldrJr = "../../exportfiles/accrual/PJ/".$fileNameJr; 
		
				if (file_exists($destiFoldrJr)) {
					unlink($destiFoldrJr);
				}
				
				$handleFromFileNameJr = fopen ($destiFoldrJr, "x");
				
				fwrite($handleFromFileNameJr, $fContJr);
				fclose($handleFromFileNameJr) ;
				
				//Junior M01
				
				$fileNameJr2 = "PJ{$date}_{$time}.M01";
				
				$fContJr2 = "";
				
				foreach($arrFileCont as $valFileContJr2){
				
					if($valFileContJr2['compCode']==700){
					
					$newCompCode = 700;
						
						$storeShort = $this->getStoreDtl($valFileContJr2['strCode']);
						
						$fContJr2 .= trim(date('d-M-Y',strtotime($valFileContJr2['stsApplyDate'])))."|"; 					##acctDate
						//$fContJr2 .= trim(date('d-M-Y',strtotime($runDate)))."|"; 					##acctDate
						$fContJr2 .= trim("DEBIT")."|";					##acctType
						$fContJr2 .= trim($newCompCode)."|";					##compCode
						$fContJr2 .= trim($storeShort['brnShortName'])."|";					##storeShort
						$fContJr2 .= trim($storeShort['businessLine'])."|"; 				##businessLine
						$fContJr2 .= trim("0")."|";					##department
						$fContJr2 .= trim("0")."|";					##section
						$fContJr2 .= trim($newCompCode."305101")."|"; 					##acctCode
						$fContJr2 .= trim($newCompCode."305101")."|"; 						##acctCode
						$fContJr2 .= trim("0")."|";					##DTL_QUANTITY
						$fContJr2 .= trim("0")."|";					##DTL_VAT_CODE
						$fContJr2 .= "|";					##DTL_CURRENCY
						$fContJr2 .= trim("STS Accrual Current ".$dtFrom)."|";					##journalName
						$fContJr2 .= trim("Accrual")."|"; 						##catgryName
						$fContJr2 .= trim("PHP")."|"; 						##currCode
						$fContJr2 .= trim($valFileContJr2['stsApplyAmt'])."|"; 						##amount
						$fContJr2 .= trim($valFileContJr2['invoice'])."|";  						##sourceRef
						$fContJr2 .= "|"; 					##
						$fContJr2 .= "|"; 					##
						$fContJr2 .= "|"; 					##
						$fContJr2 .= "|"; 					##
						$fContJr2 .= "|"; 					##
						$fContJr2 .= trim($fileNameJr2)."|"; 						##fileName
						$fContJr2 .= "\r\n";
						
						$fContJr2 .= trim(date('d-M-Y',strtotime($valFileContJr2['stsApplyDate'])))."|"; 					##acctDate
						//$fContJr2 .= trim(date('d-M-Y',strtotime($runDate)))."|"; 					##acctDate
						$fContJr2 .= trim("CREDIT")."|";					##acctType
						$fContJr2 .= trim($newCompCode)."|";					##compCode
						$fContJr2 .= trim($storeShort['brnShortName'])."|";					##storeShort
						$fContJr2 .= trim($storeShort['businessLine'])."|"; 				##businessLine
						$fContJr2 .= trim("0")."|";					##department
						$fContJr2 .= trim("0")."|";					##section
						$fContJr2 .= trim($newCompCode.$valFileContJr2['glMajor'].$valFileContJr2['glMinor'])."|"; 					##acctCode
						$fContJr2 .= trim($newCompCode.$valFileContJr2['glMajor'].$valFileContJr2['glMinor'])."|"; 						##acctCode
						$fContJr2 .= trim("0")."|";					##DTL_QUANTITY
						$fContJr2 .= trim("0")."|";					##DTL_VAT_CODE
						$fContJr2 .= "|";					##DTL_CURRENCY
						$fContJr2 .= trim("STS Accrual Current ".$dtFrom)."|";					##journalName
						$fContJr2 .= trim("Accrual")."|"; 						##catgryName
						$fContJr2 .= trim("PHP")."|"; 						##currCode
						$fContJr2 .= trim($valFileContJr2['stsApplyAmt'])."|"; 						##amount
						$fContJr2 .= trim($valFileContJr2['invoice'])."|"; 						##sourceRef
						$fContJr2 .= "|"; 					##
						$fContJr2 .= "|"; 					##
						$fContJr2 .= "|"; 					##
						$fContJr2 .= "|"; 					##
						$fContJr2 .= "|"; 					##
						$fContJr2 .= trim($fileNameJr2)."|"; 						##fileName
						$fContJr2 .= "\r\n";
					
					}
				}
				
				$destiFoldrJr2 = "../../exportfiles/accrual/PJ/".$fileNameJr2; 
		
				if (file_exists($destiFoldrJr2)) {
					unlink($destiFoldrJr2);
				}
				
				$handleFromFileNameJr2 = fopen ($destiFoldrJr2, "x");
				
				fwrite($handleFromFileNameJr2, $fContJr2);
				fclose($handleFromFileNameJr2) ;
				
				//PPCI F01
				
				$fileNamePpci = "PG{$date}_{$time}.F01";
				
				$fContPpci = "";
				
				
				foreach($arrFileCont as $valFileContPpci){
				
					if(($valFileContPpci['compCode']>=101 && $valFileContPpci['compCode']<=105) || ($valFileContPpci['compCode']>800 && $valFileContPpci['compCode']<809)){
					
					$newCompCode = 101;
						
						$storeShort = $this->getStoreDtl($valFileContPpci['strCode']);
						
						$fContPpci .= trim(date('d-M-Y',strtotime($valFileContPpci['stsApplyDate'])))."|"; 					##acctDate
						//$fContPpci .= trim(date('d-M-Y',strtotime($runDate)))."|"; 					##acctDate
						$fContPpci .= trim("DEBIT")."|";					##acctType
						$fContPpci .= trim($newCompCode)."|";					##compCode
						$fContPpci .= trim($storeShort['brnShortName'])."|";					##storeShort
						$fContPpci .= trim($storeShort['businessLine'])."|"; 				##businessLine
						$fContPpci .= trim("0")."|";					##department
						$fContPpci .= trim("0")."|";					##section
						$fContPpci .= trim($newCompCode."305101")."|"; 					##acctCode
						$fContPpci .= trim($newCompCode."305101")."|"; 					##acctCode
						$fContPpci .= trim("0")."|";					##DTL_QUANTITY
						$fContPpci .= trim("0")."|";					##DTL_VAT_CODE
						$fContPpci .= "|";					##DTL_CURRENCY
						$fContPpci .= trim("STS Accrual Current ".$dtFrom)."|";					##journalName
						$fContPpci .= trim("Accrual")."|"; 						##catgryName
						$fContPpci .= trim("PHP")."|"; 						##currCode
						$fContPpci .= trim($valFileContPpci['stsApplyAmt'])."|"; 						##amount
						$fContPpci .= trim($valFileContPpci['invoice'])."|"; 						##sourceRef
						$fContPpci .= "|"; 					##
						$fContPpci .= "|"; 					##
						$fContPpci .= "|"; 					##
						$fContPpci .= "|"; 					##
						$fContPpci .= "|"; 					##
						$fContPpci .= trim($fileNamePpci)."|"; 						##fileName
						$fContPpci .= "\r\n";
						
						$fContPpci .= trim(date('d-M-Y',strtotime($valFileContPpci['stsApplyDate'])))."|"; 					##acctDate
						//$fContPpci .= trim(date('d-M-Y',strtotime($runDate)))."|"; 					##acctDate
						$fContPpci .= trim("CREDIT")."|";					##acctType
						$fContPpci .= trim($newCompCode)."|";					##compCode
						$fContPpci .= trim($storeShort['brnShortName'])."|";					##storeShort
						$fContPpci .= trim($storeShort['businessLine'])."|"; 				##businessLine
						$fContPpci .= trim("0")."|";					##department
						$fContPpci .= trim("0")."|";					##section
						$fContPpci .= trim($newCompCode.$valFileContPpci['glMajor'].$valFileContPpci['glMinor'])."|"; 					##acctCode
						$fContPpci .= trim($newCompCode.$valFileContPpci['glMajor'].$valFileContPpci['glMinor'])."|"; 						##acctCode
						$fContPpci .= trim("0")."|";					##DTL_QUANTITY
						$fContPpci .= trim("0")."|";					##DTL_VAT_CODE
						$fContPpci .= "|";					##DTL_CURRENCY
						$fContPpci .= trim("STS Accrual Current ".$dtFrom)."|";					##journalName
						$fContPpci .= trim("Accrual")."|"; 						##catgryName
						$fContPpci .= trim("PHP")."|"; 						##currCode
						$fContPpci .= trim($valFileContPpci['stsApplyAmt'])."|"; 						##amount
						$fContPpci .= trim($valFileContPpci['invoice'])."|"; 						##sourceRef
						$fContPpci .= "|"; 					##
						$fContPpci .= "|"; 					##
						$fContPpci .= "|"; 					##
						$fContPpci .= "|"; 					##
						$fContPpci .= "|"; 					##
						$fContPpci .= trim($fileNamePpci)."|"; 						##fileName
						$fContPpci .= "\r\n";
					}
				}
				
				$destiFoldrPpci = "../../exportfiles/accrual/PG/".$fileNamePpci; 
		
				if (file_exists($destiFoldrPpci)) {
					unlink($destiFoldrPpci);
				}
				
				$handleFromFileNamePpci = fopen ($destiFoldrPpci, "x");
				
				fwrite($handleFromFileNamePpci, $fContPpci);
				fclose($handleFromFileNamePpci) ;
				
				//PPCI M01
				
				$fileNamePpci2 = "PG{$date}_{$time}.M01";
				
				$fContPpci2 = "";
				
				
				foreach($arrFileCont as $valFileContPpci2){
				
					if(($valFileContPpci2['compCode']>=101 && $valFileContPpci2['compCode']<=105) || ($valFileContPpci2['compCode']>800 && $valFileContPpci2['compCode']<809)){
					
					$newCompCode = 101;
						
						$storeShort = $this->getStoreDtl($valFileContPpci2['strCode']);
						
						$fContPpci2 .= trim(date('d-M-Y',strtotime($valFileContPpci2['stsApplyDate'])))."|"; 					##acctDate
						//$fContPpci2 .= trim(date('d-M-Y',strtotime($runDate)))."|"; 					##acctDate
						$fContPpci2 .= trim("DEBIT")."|";					##acctType
						$fContPpci2 .= trim($newCompCode)."|";					##compCode
						$fContPpci2 .= trim($storeShort['brnShortName'])."|";					##storeShort
						$fContPpci2 .= trim($storeShort['businessLine'])."|"; 				##businessLine
						$fContPpci2 .= trim("0")."|";					##department
						$fContPpci2 .= trim("0")."|";					##section
						$fContPpci2 .= trim($newCompCode."305101")."|"; 					##acctCode
						$fContPpci2 .= trim($newCompCode."305101")."|"; 					##acctCode
						$fContPpci2 .= trim("0")."|";					##DTL_QUANTITY
						$fContPpci2 .= trim("0")."|";					##DTL_VAT_CODE
						$fContPpci2 .= "|";					##DTL_CURRENCY
						$fContPpci2 .= trim("STS Accrual Current ".$dtFrom)."|";					##journalName
						$fContPpci2 .= trim("Accrual")."|"; 						##catgryName
						$fContPpci2 .= trim("PHP")."|"; 						##currCode
						$fContPpci2 .= trim($valFileContPpci2['stsApplyAmt'])."|"; 						##amount
						$fContPpci2 .= trim($valFileContPpci2['invoice'])."|"; 						##sourceRef
						$fContPpci2 .= "|"; 					##
						$fContPpci2 .= "|"; 					##
						$fContPpci2 .= "|"; 					##
						$fContPpci2 .= "|"; 					##
						$fContPpci2 .= "|"; 					##
						$fContPpci2 .= trim($fileNamePpci2)."|"; 						##fileName
						$fContPpci2 .= "\r\n";
						
						$fContPpci2 .= trim(date('d-M-Y',strtotime($valFileContPpci2['stsApplyDate'])))."|"; 					##acctDate
						//$fContPpci2 .= trim(date('d-M-Y',strtotime($runDate)))."|"; 					##acctDate
						$fContPpci2 .= trim("CREDIT")."|";					##acctType
						$fContPpci2 .= trim($newCompCode)."|";					##compCode
						$fContPpci2 .= trim($storeShort['brnShortName'])."|";					##storeShort
						$fContPpci2 .= trim($storeShort['businessLine'])."|"; 				##businessLine
						$fContPpci2 .= trim("0")."|";					##department
						$fContPpci2 .= trim("0")."|";					##section
						$fContPpci2 .= trim($newCompCode.$valFileContPpci2['glMajor'].$valFileContPpci2['glMinor'])."|"; 					##acctCode
						$fContPpci2 .= trim($newCompCode.$valFileContPpci2['glMajor'].$valFileContPpci2['glMinor'])."|"; 						##acctCode
						$fContPpci2 .= trim("0")."|";					##DTL_QUANTITY
						$fContPpci2 .= trim("0")."|";					##DTL_VAT_CODE
						$fContPpci2 .= "|";					##DTL_CURRENCY
						$fContPpci2 .= trim("STS Accrual Current ".$dtFrom)."|";					##journalName
						$fContPpci2 .= trim("Accrual")."|"; 						##catgryName
						$fContPpci2 .= trim("PHP")."|"; 						##currCode
						$fContPpci2 .= trim($valFileContPpci2['stsApplyAmt'])."|"; 						##amount
						$fContPpci2 .= trim($valFileContPpci2['invoice'])."|"; 						##sourceRef
						$fContPpci2 .= "|"; 					##
						$fContPpci2 .= "|"; 					##
						$fContPpci2 .= "|"; 					##
						$fContPpci2 .= "|"; 					##
						$fContPpci2 .= "|"; 					##
						$fContPpci2 .= trim($fileNamePpci2)."|"; 						##fileName
						$fContPpci2 .= "\r\n";
					}
				}
				
				$destiFoldrPpci2 = "../../exportfiles/accrual/PG/".$fileNamePpci2; 
		
				if (file_exists($destiFoldrPpci2)) {
					unlink($destiFoldrPpci2);
				}
				
				$handleFromFileNamePpci2 = fopen ($destiFoldrPpci2, "x");
				
				fwrite($handleFromFileNamePpci2, $fContPpci2);
				fclose($handleFromFileNamePpci2) ;
				
				//Company E F01
				
				$fileNameCoe = "PE{$date}_{$time}.F01";
				
				$fContCoe = "";
				
				
				foreach($arrFileCont as $valFileContCoe){
				
					if($valFileContCoe['compCode']==809){
					
					$newCompCode = 809;
						
						$storeShort = $this->getStoreDtl($valFileContCoe['strCode']);
						
						$fContCoe .= trim(date('d-M-Y',strtotime($valFileContCoe['stsApplyDate'])))."|"; 					##acctDate
						//$fContCoe .= trim(date('d-M-Y',strtotime($runDate)))."|"; 					##acctDate
						$fContCoe .= trim("DEBIT")."|";					##acctType
						$fContCoe .= trim($newCompCode)."|";					##compCode
						$fContCoe .= trim($storeShort['brnShortName'])."|";					##storeShort
						$fContCoe .= trim($storeShort['businessLine'])."|"; 				##businessLine
						$fContCoe .= trim("0")."|";					##department
						$fContCoe .= trim("0")."|";					##section
						$fContCoe .= trim($newCompCode."305101")."|"; 					##acctCode
						$fContCoe .= trim($newCompCode."305101")."|"; 					##acctCode
						$fContCoe .= trim("0")."|";					##DTL_QUANTITY
						$fContCoe .= trim("0")."|";					##DTL_VAT_CODE
						$fContCoe .= "|";					##DTL_CURRENCY
						$fContCoe .= trim("STS Accrual Current ".$dtFrom)."|";					##journalName
						$fContCoe .= trim("Accrual")."|"; 						##catgryName
						$fContCoe .= trim("PHP")."|"; 						##currCode
						$fContCoe .= trim($valFileContCoe['stsApplyAmt'])."|"; 						##amount
						$fContCoe .= trim($valFileContCoe['invoice'])."|";  						##sourceRef
						$fContCoe .= "|"; 					##
						$fContCoe .= "|"; 					##
						$fContCoe .= "|"; 					##
						$fContCoe .= "|"; 					##
						$fContCoe .= "|"; 					##
						$fContCoe .= trim($fileNameCoe)."|"; 						##fileName
						$fContCoe .= "\r\n";
						
						$fContCoe .= trim(date('d-M-Y',strtotime($valFileContCoe['stsApplyDate'])))."|"; 					##acctDate
						//$fContCoe .= trim(date('d-M-Y',strtotime($runDate)))."|"; 					##acctDate
						$fContCoe .= trim("CREDIT")."|";					##acctType
						$fContCoe .= trim($newCompCode)."|";					##compCode
						$fContCoe .= trim($storeShort['brnShortName'])."|";					##storeShort
						$fContCoe .= trim($storeShort['businessLine'])."|"; 				##businessLine
						$fContCoe .= trim("0")."|";					##department
						$fContCoe .= trim("0")."|";					##section
						$fContCoe .= trim($newCompCode.$valFileContCoe['glMajor'].$valFileContCoe['glMinor'])."|"; 					##acctCode
						$fContCoe .= trim($newCompCode.$valFileContCoe['glMajor'].$valFileContCoe['glMinor'])."|"; 						##acctCode
						$fContCoe .= trim("0")."|";					##DTL_QUANTITY
						$fContCoe .= trim("0")."|";					##DTL_VAT_CODE
						$fContCoe .= "|";					##DTL_CURRENCY
						$fContCoe .= trim("STS Accrual Current ".$dtFrom)."|";					##journalName
						$fContCoe .= trim("Accrual")."|"; 						##catgryName
						$fContCoe .= trim("PHP")."|"; 						##currCode
						$fContCoe .= trim($valFileContCoe['stsApplyAmt'])."|"; 						##amount
						$fContCoe .= trim($valFileContCoe['invoice'])."|"; 						##sourceRef
						$fContCoe .= "|"; 					##
						$fContCoe .= "|"; 					##
						$fContCoe .= "|"; 					##
						$fContCoe .= "|"; 					##
						$fContCoe .= "|"; 					##
						$fContCoe .= trim($fileNameCoe)."|"; 						##fileName
						$fContCoe .= "\r\n";
					
					}
				}
				
				$destiFoldrCoe = "../../exportfiles/accrual/PE/".$fileNameCoe; 
		
				if (file_exists($destiFoldrCoe)) {
					unlink($destiFoldrCoe);
				}
				
				$handleFromFileNameCoe = fopen ($destiFoldrCoe, "x");
				
				fwrite($handleFromFileNameCoe, $fContCoe);
				fclose($handleFromFileNameCoe) ;
				
				//Company E M01
				
				$fileNameCoe2 = "PE{$date}_{$time}.M01";
				
				$fContCoe2 = "";
				
				
				foreach($arrFileCont as $valFileContCoe2){
				
					if($valFileContCoe2['compCode']==809){
					
					$newCompCode = 809;
						
						$storeShort = $this->getStoreDtl($valFileContCoe2['strCode']);
						
						$fContCoe2 .= trim(date('d-M-Y',strtotime($valFileContCoe2['stsApplyDate'])))."|"; 					##acctDate
						//$fContCoe2 .= trim(date('d-M-Y',strtotime($runDate)))."|"; 					##acctDate
						$fContCoe2 .= trim("DEBIT")."|";					##acctType
						$fContCoe2 .= trim($newCompCode)."|";					##compCode
						$fContCoe2 .= trim($storeShort['brnShortName'])."|";					##storeShort
						$fContCoe2 .= trim($storeShort['businessLine'])."|"; 				##businessLine
						$fContCoe2 .= trim("0")."|";					##department
						$fContCoe2 .= trim("0")."|";					##section
						$fContCoe2 .= trim($newCompCode."305101")."|"; 					##acctCode
						$fContCoe2 .= trim($newCompCode."305101")."|"; 					##acctCode
						$fContCoe2 .= trim("0")."|";					##DTL_QUANTITY
						$fContCoe2 .= trim("0")."|";					##DTL_VAT_CODE
						$fContCoe2 .= "|";					##DTL_CURRENCY
						$fContCoe2 .= trim("STS Accrual Current ".$dtFrom)."|";					##journalName
						$fContCoe2 .= trim("Accrual")."|"; 						##catgryName
						$fContCoe2 .= trim("PHP")."|"; 						##currCode
						$fContCoe2 .= trim($valFileContCoe2['stsApplyAmt'])."|"; 						##amount
						$fContCoe2 .= trim($valFileContCoe2['invoice'])."|"; 						##sourceRef
						$fContCoe2 .= "|"; 					##
						$fContCoe2 .= "|"; 					##
						$fContCoe2 .= "|"; 					##
						$fContCoe2 .= "|"; 					##
						$fContCoe2 .= "|"; 					##
						$fContCoe2 .= trim($fileNameCoe2)."|"; 						##fileName
						$fContCoe2 .= "\r\n";
						
						$fContCoe2 .= trim(date('d-M-Y',strtotime($valFileContCoe2['stsApplyDate'])))."|"; 					##acctDate
						//$fContCoe2 .= trim(date('d-M-Y',strtotime($runDate)))."|"; 					##acctDate
						$fContCoe2 .= trim("CREDIT")."|";					##acctType
						$fContCoe2 .= trim($newCompCode)."|";					##compCode
						$fContCoe2 .= trim($storeShort['brnShortName'])."|";					##storeShort
						$fContCoe2 .= trim($storeShort['businessLine'])."|"; 				##businessLine
						$fContCoe2 .= trim("0")."|";					##department
						$fContCoe2 .= trim("0")."|";					##section
						$fContCoe2 .= trim($newCompCode.$valFileContCoe2['glMajor'].$valFileContCoe2['glMinor'])."|"; 					##acctCode
						$fContCoe2 .= trim($newCompCode.$valFileContCoe2['glMajor'].$valFileContCoe2['glMinor'])."|"; 						##acctCode
						$fContCoe2 .= trim("0")."|";					##DTL_QUANTITY
						$fContCoe2 .= trim("0")."|";					##DTL_VAT_CODE
						$fContCoe2 .= "|";					##DTL_CURRENCY
						$fContCoe2 .= trim("STS Accrual Current ".$dtFrom)."|";					##journalName
						$fContCoe2 .= trim("Accrual")."|"; 						##catgryName
						$fContCoe2 .= trim("PHP")."|"; 						##currCode
						$fContCoe2 .= trim($valFileContCoe2['stsApplyAmt'])."|"; 						##amount
						$fContCoe2 .= trim($valFileContCoe2['invoice'])."|"; 						##sourceRef
						$fContCoe2 .= "|"; 					##
						$fContCoe2 .= "|"; 					##
						$fContCoe2 .= "|"; 					##
						$fContCoe2 .= "|"; 					##
						$fContCoe2 .= "|"; 					##
						$fContCoe2 .= trim($fileNameCoe2)."|"; 						##fileName
						$fContCoe2 .= "\r\n";
					
					}
				}
				
				$destiFoldrCoe2 = "../../exportfiles/accrual/PE/".$fileNameCoe2; 
		
				if (file_exists($destiFoldrCoe2)) {
					unlink($destiFoldrCoe2);
				}
				
				$handleFromFileNameCoe2 = fopen ($destiFoldrCoe2, "x");
				
				fwrite($handleFromFileNameCoe2, $fContCoe2);
				fclose($handleFromFileNameCoe2) ;
				
				//Puregold Subic F01
				
				$fileNameSubic = "PC{$date}_{$time}.F01";
				
				$fContSubic = "";
				
				
				foreach($arrFileCont as $valFileContSubic){
				
					if($valFileContSubic['compCode']==302){
					
					$newCompCode = 302;
						
						$storeShort = $this->getStoreDtl($valFileContSubic['strCode']);
						
						$fContSubic .= trim(date('d-M-Y',strtotime($valFileContSubic['stsApplyDate'])))."|"; 					##acctDate
						//$fContSubic .= trim(date('d-M-Y',strtotime($runDate)))."|"; 					##acctDate
						$fContSubic .= trim("DEBIT")."|";					##acctType
						$fContSubic .= trim($newCompCode)."|";					##compCode
						$fContSubic .= trim($storeShort['brnShortName'])."|";					##storeShort
						$fContSubic .= trim($storeShort['businessLine'])."|"; 				##businessLine
						$fContSubic .= trim("0")."|";					##department
						$fContSubic .= trim("0")."|";					##section
						$fContSubic .= trim($newCompCode."305101")."|"; 					##acctCode
						$fContSubic .= trim($newCompCode."305101")."|"; 					##acctCode
						$fContSubic .= trim("0")."|";					##DTL_QUANTITY
						$fContSubic .= trim("0")."|";					##DTL_VAT_CODE
						$fContSubic .= "|";					##DTL_CURRENCY
						$fContSubic .= trim("STS Accrual Current ".$dtFrom)."|";					##journalName
						$fContSubic .= trim("Accrual")."|"; 						##catgryName
						$fContSubic .= trim("PHP")."|"; 						##currCode
						$fContSubic .= trim($valFileContSubic['stsApplyAmt'])."|"; 						##amount
						$fContSubic .= trim($valFileContSubic['invoice'])."|"; 						##sourceRef
						$fContSubic .= "|"; 					##
						$fContSubic .= "|"; 					##
						$fContSubic .= "|"; 					##
						$fContSubic .= "|"; 					##
						$fContSubic .= "|"; 					##
						$fContSubic .= trim($fileNameSubic)."|"; 						##fileName
						$fContSubic .= "\r\n";
						
						$fContSubic .= trim(date('d-M-Y',strtotime($valFileContSubic['stsApplyDate'])))."|"; 					##acctDate
						//$fContSubic .= trim(date('d-M-Y',strtotime($runDate)))."|"; 					##acctDate
						$fContSubic .= trim("CREDIT")."|";					##acctType
						$fContSubic .= trim($newCompCode)."|";					##compCode
						$fContSubic .= trim($storeShort['brnShortName'])."|";					##storeShort
						$fContSubic .= trim($storeShort['businessLine'])."|"; 				##businessLine
						$fContSubic .= trim("0")."|";					##department
						$fContSubic .= trim("0")."|";					##section
						$fContSubic .= trim($newCompCode.$valFileContSubic['glMajor'].$valFileContSubic['glMinor'])."|"; 					##acctCode
						$fContSubic .= trim($newCompCode.$valFileContSubic['glMajor'].$valFileContSubic['glMinor'])."|"; 						##acctCode
						$fContSubic .= trim("0")."|";					##DTL_QUANTITY
						$fContSubic .= trim("0")."|";					##DTL_VAT_CODE
						$fContSubic .= "|";					##DTL_CURRENCY
						$fContSubic .= trim("STS Accrual Current ".$dtFrom)."|";					##journalName
						$fContSubic .= trim("Accrual")."|"; 						##catgryName
						$fContSubic .= trim("PHP")."|"; 						##currCode
						$fContSubic .= trim($valFileContSubic['stsApplyAmt'])."|"; 						##amount
						$fContSubic .= trim($valFileContSubic['invoice'])."|"; 						##sourceRef
						$fContSubic .= "|"; 					##
						$fContSubic .= "|"; 					##
						$fContSubic .= "|"; 					##
						$fContSubic .= "|"; 					##
						$fContSubic .= "|"; 					##
						$fContSubic .= trim($fileNameSubic)."|"; 						##fileName
						$fContSubic .= "\r\n";
					
					}
				}
				
				$destiFoldrSubic = "../../exportfiles/accrual/PC/".$fileNameSubic; 
		
				if (file_exists($destiFoldrSubic)) {
					unlink($destiFoldrSubic);
				}
				
				$handleFromFileNameSubic = fopen ($destiFoldrSubic, "x");
				
				fwrite($handleFromFileNameSubic, $fContSubic);
				fclose($handleFromFileNameSubic) ;
				
				//Puregold Subic M01
				
				$fileNameSubic2 = "PC{$date}_{$time}.M01";
				
				$fContSubic2 = "";
				
				
				foreach($arrFileCont as $valFileContSubic2){
				
					if($valFileContSubic2['compCode']==302){
					
					$newCompCode = 302;
						
						$storeShort = $this->getStoreDtl($valFileContSubic2['strCode']);
						
						$fContSubic2 .= trim(date('d-M-Y',strtotime($valFileContSubic2['stsApplyDate'])))."|"; 					##acctDate
						//$fContSubic2 .= trim(date('d-M-Y',strtotime($runDate)))."|"; 					##acctDate
						$fContSubic2 .= trim("DEBIT")."|";					##acctType
						$fContSubic2 .= trim($newCompCode)."|";					##compCode
						$fContSubic2 .= trim($storeShort['brnShortName'])."|";					##storeShort
						$fContSubic2 .= trim($storeShort['businessLine'])."|"; 				##businessLine
						$fContSubic2 .= trim("0")."|";					##department
						$fContSubic2 .= trim("0")."|";					##section
						$fContSubic2 .= trim($newCompCode."305101")."|"; 					##acctCode
						$fContSubic2 .= trim($newCompCode."305101")."|"; 					##acctCode
						$fContSubic2 .= trim("0")."|";					##DTL_QUANTITY
						$fContSubic2 .= trim("0")."|";					##DTL_VAT_CODE
						$fContSubic2 .= "|";					##DTL_CURRENCY
						$fContSubic2 .= trim("STS Accrual Current ".$dtFrom)."|";					##journalName
						$fContSubic2 .= trim("Accrual")."|"; 						##catgryName
						$fContSubic2 .= trim("PHP")."|"; 						##currCode
						$fContSubic2 .= trim($valFileContSubic2['stsApplyAmt'])."|"; 						##amount
						$fContSubic2 .= trim($valFileContSubic2['invoice'])."|";						##sourceRef
						$fContSubic2 .= "|"; 					##
						$fContSubic2 .= "|"; 					##
						$fContSubic2 .= "|"; 					##
						$fContSubic2 .= "|"; 					##
						$fContSubic2 .= "|"; 					##
						$fContSubic2 .= trim($fileNameSubic2)."|"; 						##fileName
						$fContSubic2 .= "\r\n";
						
						$fContSubic2 .= trim(date('d-M-Y',strtotime($valFileContSubic2['stsApplyDate'])))."|"; 					##acctDate
						//$fContSubic2 .= trim(date('d-M-Y',strtotime($runDate)))."|"; 					##acctDate
						$fContSubic2 .= trim("CREDIT")."|";					##acctType
						$fContSubic2 .= trim($newCompCode)."|";					##compCode
						$fContSubic2 .= trim($storeShort['brnShortName'])."|";					##storeShort
						$fContSubic2 .= trim($storeShort['businessLine'])."|"; 				##businessLine
						$fContSubic2 .= trim("0")."|";					##department
						$fContSubic2 .= trim("0")."|";					##section
						$fContSubic2 .= trim($newCompCode.$valFileContSubic2['glMajor'].$valFileContSubic2['glMinor'])."|"; 					##acctCode
						$fContSubic2 .= trim($newCompCode.$valFileContSubic2['glMajor'].$valFileContSubic2['glMinor'])."|"; 						##acctCode
						$fContSubic2 .= trim("0")."|";					##DTL_QUANTITY
						$fContSubic2 .= trim("0")."|";					##DTL_VAT_CODE
						$fContSubic2 .= "|";					##DTL_CURRENCY
						$fContSubic2 .= trim("STS Accrual Current ".$dtFrom)."|";					##journalName
						$fContSubic2 .= trim("Accrual")."|"; 						##catgryName
						$fContSubic2 .= trim("PHP")."|"; 						##currCode
						$fContSubic2 .= trim($valFileContSubic2['stsApplyAmt'])."|"; 						##amount
						$fContSubic2 .= trim($valFileContSubic2['invoice'])."|";						##sourceRef
						$fContSubic2 .= "|"; 					##
						$fContSubic2 .= "|"; 					##
						$fContSubic2 .= "|"; 					##
						$fContSubic2 .= "|"; 					##
						$fContSubic2 .= "|"; 					##
						$fContSubic2 .= trim($fileNameSubic2)."|"; 						##fileName
						$fContSubic2 .= "\r\n";
					
					}
				}
				
				$destiFoldrSubic2 = "../../exportfiles/accrual/PC/".$fileNameSubic2; 
		
				if (file_exists($destiFoldrSubic2)) {
					unlink($destiFoldrSubic2);
				}
				
				$handleFromFileNameSubic2 = fopen ($destiFoldrSubic2, "x");
				
				fwrite($handleFromFileNameSubic2, $fContSubic2);
				fclose($handleFromFileNameSubic2) ;
				
				
			//}
	
	}
	
	function insertAccruHist($dtFrom){
	
			$curFirstDay = date("Y/m/d", strtotime($dtFrom));
			$curLastDay = date("Y/m/d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m',strtotime($dtFrom)).'/01/'.date('Y',strtotime($dtFrom)).' 00:00:00'))));
			
			$nextMonth = date("Y/m/d", strtotime('+1 month',strtotime(date('m',strtotime($dtFrom)).'/01/'.date('Y',strtotime($dtFrom)).' 00:00:00')));
			
			$sql="
			insert into tblStsAccrualHist(stsNo,stsSeq,stsRefno,compCode,strCode,suppCode,stsType,stsDept,stsCls,stsSubCls,grpCode,stsApplyAmt,stsApplyDate,stsActualDate,
			stsPaymentMode,uploadDate,uploadApRef,uploadApFile,status,apBatch,stsRemarks,glMajor,glMinor)
			SELECT     
			dbo.tblStsAccrualTemp.stsNo,dbo.tblStsAccrualTemp.stsSeq,dbo.tblStsAccrualTemp.stsRefno,dbo.tblStsAccrualTemp.compCode,dbo.tblStsAccrualTemp.strCode,dbo.tblStsAccrualTemp.suppCode,
			dbo.tblStsAccrualTemp.stsType,dbo.tblStsAccrualTemp.stsDept,dbo.tblStsAccrualTemp.stsCls,dbo.tblStsAccrualTemp.stsSubCls,dbo.tblStsAccrualTemp.grpCode,dbo.tblStsAccrualTemp.stsApplyAmt,
			dbo.tblStsAccrualTemp.stsApplyDate,dbo.tblStsAccrualTemp.stsActualDate,
			dbo.tblStsAccrualTemp.stsPaymentMode,dbo.tblStsAccrualTemp.uploadDate,dbo.tblStsAccrualTemp.uploadApRef,dbo.tblStsAccrualTemp.uploadApFile,dbo.tblStsAccrualTemp.status,
			dbo.tblStsAccrualTemp.apBatch,dbo.tblStsAccrualTemp.stsRemarks,
			dbo.tblStsHierarchy.glMajor AS glMajor, 
			dbo.tblStsHierarchy.glMinor AS glMinor
			FROM         
			dbo.tblStsAccrualTemp 
			INNER JOIN
            dbo.tblStsHierarchy ON dbo.tblStsAccrualTemp.stsDept = dbo.tblStsHierarchy.stsDept 
			AND dbo.tblStsAccrualTemp.stsCls = dbo.tblStsHierarchy.stsCls 
			AND dbo.tblStsAccrualTemp.stsSubCls = dbo.tblStsHierarchy.stsSubCls
			WHERE     
			(dbo.tblStsAccrualTemp.stsApplyDate BETWEEN '{$curFirstDay}' AND '{$curLastDay}') AND (dbo.tblStsAccrualTemp.uploadDate >= '{$nextMonth}')
			order by stsRefno,stsNo,stsSeq,strCode,suppCode,stsApplyAmt
			";
		return $this->execQry($sql);
	}
	
	function truncAccruTemp(){
	
			
			$sql="
			TRUNCATE TABLE tblStsAccrualTemp
			";
			
		return $this->execQry($sql);
	}
	
}
?>