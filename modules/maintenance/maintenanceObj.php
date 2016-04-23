<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");

class maintenanceObj extends commonObj {
	
	function checkNewFrOld($oldPass){
		$enOldPass = base64_encode($oldPass);
		$sql = "SELECT userName FROM tblusers WHERE userPass = '$enOldPass' AND userId = '{$_SESSION['sts-userId']}'";
		return $this->getRecCount($this->execQry($sql));
	}
	function changePass($newPass){
		$enNewPass = base64_encode($newPass);
		$sqlAdd = "UPDATE tblusers SET userPass = '$enNewPass' WHERE userId = '{$_SESSION['sts-userId']}'";	
		$trans = $this->beginTran();
		if ($trans) {
			$trans = $this->execQry($sqlAdd);
		}
		if(!$trans){
			$trans = $this->rollbackTran();
			return false;
		} else{
			$trans = $this->commitTran();
			return true;
		}
	}
	function countEnhancer(){
		$sql = "Select count(*) as count From tblEnhancerType WHERE stat = 'A'";	
		return $this->getSqlAssoc($this->execQry($sql));
	}
	function searchEnhancerType($sidx,$sord,$start,$limit,$searchField,$searchString){
		$sql = "SELECT TOP $limit * FROM tblEnhancerType WHERE $searchField =
		'$searchString' AND stat = 'A' AND enhanceType NOT IN (SELECT TOP $start enhanceType FROM tblEnhancerType WHERE $searchField =
		'$searchString' AND stat = 'A' ORDER BY  $sidx $sord) ORDER BY  $sidx $sord";
		return $this->getArrRes($this->execQry($sql));
	}
	function getPaginatedEnhancerType($sidx,$sord,$start,$limit){
		$sql = "SELECT TOP $limit * FROM tblEnhancerType WHERE stat = 'A' AND enhanceType NOT IN (SELECT TOP $start enhanceType FROM tblEnhancerType WHERE stat = 'A' ORDER BY  $sidx $sord) ORDER BY  $sidx $sord";
		return $this->getArrRes($this->execQry($sql));
	}
	function checkIfEnhancerExists($desc){
		$sql = "SELECT * FROM tblEnhancerType WHERE enhanceDesc like '%$desc%'";
		return $this->getRecCount($this->execQry($sql));
	}
	function checkIfEnhancerExistsWId($desc,$id){
		$sql = "SELECT * FROM tblEnhancerType WHERE enhanceDesc like '%$desc%' AND enhanceType != '$id'";
		return $this->getRecCount($this->execQry($sql));
	}
	function addEnhancer($arr){
		$sqlAdd	="INSERT INTO tblEnhancerType (enhanceDesc, stat)
		VALUES ('{$arr['txtEnhancerDesc']}', 'A');";
		return $this->execQry($sqlAdd);
	}
	function enhancerInfo($id){
		$sql = "SELECT * FROM tblEnhancerType WHERE enhanceType = '$id'";	
		return $this->getSqlAssoc($this->execQry($sql));
	}
	function updateEnhancerInfo($arr){
		$sql = "UPDATE tblEnhancerType SET enhanceDesc  = '{$arr['txtEnhancerDesc']}' WHERE enhanceType = '{$arr['hdnEnhancerId']}'";	
		return $this->execQry($sql);
	}
	function deleteEnhancer($id){
		$sqlUpdateDel = "UPDATE tblEnhancerType SET stat = 'D' WHERE enhanceType = '$id'";
		return $this->execQry($sqlUpdateDel);
	}
	
	function countBrand(){
		$sql = "Select count(*) as count From tblBrand WHERE stat = 'A'";	
		return $this->getSqlAssoc($this->execQry($sql));
	}
	function countDisplay(){
		$sql = "Select count(*) as count From tblDisplaySpecs WHERE stat = 'A'";	
		return $this->getSqlAssoc($this->execQry($sql));
	}
	function countDisplayDtl($dispId){
		$sql = "Select count(*) as count From tblDisplaySpecsDtl WHERE status = 'A' AND displaySpecsId = '$dispId'";	
		return $this->getSqlAssoc($this->execQry($sql));
	}
	function searchBrandType($sidx,$sord,$start,$limit,$searchField,$searchString){
		$sql = "SELECT TOP $limit * FROM tblBrand WHERE $searchField =
		'$searchString' AND stat = 'A' AND stsBrand NOT IN (SELECT TOP $start stsBrand FROM tblBrand WHERE $searchField =
		'$searchString' AND stat = 'A' ORDER BY  $sidx $sord) ORDER BY  $sidx $sord";
		return $this->getArrRes($this->execQry($sql));
	}
	function searchDisplayTypeDtl($sidx,$sord,$start,$limit,$searchField,$searchString,$dispId){
		$sql = "SELECT TOP $limit * FROM tblDisplaySpecsDtl WHERE $searchField =
		'$searchString' AND status = 'A' AND displaySpecsId = '$dispId' AND dispDtlId NOT IN (SELECT TOP $start dispDtlId FROM tblDisplaySpecsDtl WHERE $searchField =
		'$searchString' AND status = 'A' AND displaySpecsId = '$dispId' ORDER BY  $sidx $sord) ORDER BY  $sidx $sord";
		return $this->getArrRes($this->execQry($sql));
	}
	function searchDisplayType($sidx,$sord,$start,$limit,$searchField,$searchString){
		$sql = "SELECT TOP $limit * FROM tblDisplaySpecs WHERE $searchField =
		'$searchString' AND stat = 'A' AND displaySpecsId NOT IN (SELECT TOP $start displaySpecsId FROM tblDisplaySpecs WHERE $searchField =
		'$searchString' AND stat = 'A' ORDER BY  $sidx $sord) ORDER BY  $sidx $sord";
		return $this->getArrRes($this->execQry($sql));
	}
	function getPaginatedBrandType($sidx,$sord,$start,$limit){
		$sql = "SELECT TOP $limit * FROM tblBrand WHERE stat = 'A' AND stsBrand NOT IN (SELECT TOP $start stsBrand FROM tblBrand WHERE stat = 'A' ORDER BY  $sidx $sord) ORDER BY  $sidx $sord";
		return $this->getArrRes($this->execQry($sql));
	}
	function getPaginatedDisplayType($sidx,$sord,$start,$limit){
		$sql = "SELECT TOP $limit * FROM tblDisplaySpecs WHERE stat = 'A' AND displaySpecsId NOT IN (SELECT TOP $start displaySpecsId FROM tblDisplaySpecs WHERE stat = 'A' ORDER BY  $sidx $sord) ORDER BY  $sidx $sord";
		return $this->getArrRes($this->execQry($sql));
	}
	function getPaginatedDisplayTypeDtl($sidx,$sord,$start,$limit,$dispId){
		$sql = "SELECT TOP $limit * FROM tblDisplaySpecsDtl WHERE status = 'A' AND displaySpecsId = '$dispId' AND dispDtlId NOT IN (SELECT TOP $start dispDtlId FROM tblDisplaySpecsDtl WHERE status = 'A' AND displaySpecsId = '$dispId' ORDER BY  $sidx $sord) ORDER BY  $sidx $sord";
		return $this->getArrRes($this->execQry($sql));
	}
	function checkIfBrandExists($desc){
		$sql = "SELECT * FROM tblBrand WHERE stsBrandDesc like '%$desc%'";
		return $this->getRecCount($this->execQry($sql));
	}
	function checkIfSpecExists($desc){
		$sql = "SELECT * FROM tblDisplaySpecs WHERE displaySpecsDesc like '%$desc%'";
		return $this->getRecCount($this->execQry($sql));
	}
	function checkIfSpecDtlExists($desc){
		$sql = "SELECT * FROM tblDisplaySpecsDtl WHERE dispDesc like '%$desc%'";
		return $this->getRecCount($this->execQry($sql));
	}
	function checkIfBrandExistsWId($desc,$id){
		$sql = "SELECT * FROM tblBrand WHERE stsBrandDesc like '%$desc%' AND stsBrand != '$id'";
		return $this->getRecCount($this->execQry($sql));
	}
	function checkIfSpecsExistsWId($desc,$id){
		$sql = "SELECT * FROM tblDisplaySpecs WHERE displaySpecsDesc like '$desc' AND displaySpecsId != '$id'";
		return $this->getRecCount($this->execQry($sql));
	}
	function checkIfSpecsDtlExistsWId($desc,$masterId,$dtlId){
		$sql = "SELECT * FROM tblDisplaySpecsDtl WHERE dispDesc like '$desc' AND displaySpecsId != '$masterId' AND dispDtlId = '$dtlId'";
		return $this->getRecCount($this->execQry($sql));
	}
	function addBrand($arr){
		$sqlAdd	="INSERT INTO tblBrand (stsBrandDesc, stat)
		VALUES ('{$arr['txtBrand']}', 'A');";
		return $this->execQry($sqlAdd);
	}
	function addSpecs($arr){
		$sqlAdd	="INSERT INTO tblDisplaySpecs (displaySpecsDesc, stat, createdBy, dateCreated)
		VALUES ('{$arr['txtBrand']}', 'A','".$_SESSION['sts-userId']."', '".date('m/d/Y')."');";
		return $this->execQry($sqlAdd);
	}
	function addSpecsDtl($arr){
		
		$trans = $this->beginTran();
		
		$sqlAdd	="INSERT INTO tblDisplaySpecsDtl (dispDesc, status, createdBy, dateCreated, displaySpecsId) VALUES ('{$arr['txtBrandDtl']}', 'A','".$_SESSION['sts-userId']."', '".date('m/d/Y')."','{$arr['hdnMasterId']}');";
		
		if ($trans) {
			$trans = $this->execQry($sqlAdd);
		}
		if($trans){
			$sql1 = "SELECT SCOPE_IDENTITY() as id";
			$arrId = $this->getSqlAssoc($this->execQry($sql1));
			
			if($arrId['id']!=''){
				$sql = "INSERT INTO tblDispDaDtlStr (strCode, displaySpecsId, dispDtlId, usableTag)  
				SELECT     tblBranches.strCode, tblDisplaySpecsDtl.displaySpecsId, tblDisplaySpecsDtl.dispDtlId, 'Y'
				FROM         tblDisplaySpecsDtl CROSS JOIN
									  tblBranches
				WHERE     (tblDisplaySpecsDtl.dispDtlId = '{$arrId['id']}') AND (tblBranches.brnStat = 'A') ";
				if ($trans) {
					$trans = $this->execQry($sql);
				}
			}
		}
		if(!$trans){
			$trans = $this->rollbackTran();
			return false;
		} else{
			$trans = $this->commitTran();
			return true;
		}
	}
	function brandInfo($id){
		$sql = "SELECT * FROM tblBrand WHERE stsBrand = '$id'";	
		return $this->getSqlAssoc($this->execQry($sql));
	}
	function specsInfo($id){
		$sql = "SELECT * FROM tblDisplaySpecs WHERE displaySpecsId = '$id'";	
		return $this->getSqlAssoc($this->execQry($sql));
	}
	function specsDtlInfo($id,$dtlId){
		$sql = "SELECT * FROM tblDisplaySpecsDtl WHERE displaySpecsId = '$id' AND dispDtlId = '$dtlId'";	
		return $this->getSqlAssoc($this->execQry($sql));
	}
	function updateBrandInfo($arr){
		$sql = "UPDATE tblBrand SET stsBrandDesc  = '{$arr['txtBrand']}' WHERE stsBrand = '{$arr['hdnBrandId']}'";	
		return $this->execQry($sql);
	}
	function updateSpecsInfo($arr){
		$sql = "UPDATE tblDisplaySpecs SET displaySpecsDesc  = '{$arr['txtBrand']}' WHERE displaySpecsId = '{$arr['hdnBrandId']}'";	
		return $this->execQry($sql);
	}
	function updateSpecsDtlInfo($arr){
		$sql = "UPDATE tblDisplaySpecsDtl SET dispDesc = '{$arr['txtBrandDtl']}' WHERE displaySpecsId = '{$arr['hdnMasterId']}' AND dispDtlId = '{$arr['hdnDtlId']}'";
		return $this->execQry($sql);	
	}
	function deleteBrand($id){
		$sqlUpdateDel = "UPDATE tblBrand SET stat = 'D' WHERE stsBrand = '$id'";
		return $this->execQry($sqlUpdateDel);
	}
	function deleteSpecsDtl($id,$dtlId){
		$sqlUpdateDel = "UPDATE tblDisplaySpecsDtl SET status = 'D'  WHERE displaySpecsId = '$id' AND dispDtlId = '$dtlId'";	
		return $this->execQry($sqlUpdateDel);
	}
	function deleteSpecs($id){
		$sqlUpdateDel = "UPDATE tblDisplaySpecs SET stat = 'D' WHERE displaySpecsId = '$id'";
		return $this->execQry($sqlUpdateDel);
	}
	function getBranches(){
		$sql = "SELECT strCode, brnDesc, cast(strCode as nvarchar)+' - '+brnDesc as strCodeName FROM pg_pf..tblbranches order by strCode";	
		return $this->getArrRes($this->execQry($sql));
	}
	function listRentables(){
		$sql = "SELECT * FROM tblDisplaySpecs WHERE stat = 'A'";	
		return $this->getArrRes($this->execQry($sql));
	}
	function getRentableStores($id,$strCode){
		$sql = "SELECT     tblDispDaDtlStr.displaySpecsId, tblDispDaDtlStr.dispDtlId, tblDispDaDtlStr.permanentTag, tblDispDaDtlStr.usableTag, tblDispDaDtlStr.availabilityTag, 
                      tblDisplaySpecsDtl.dispDesc, tblDispDaDtlStr.strCode
FROM         tblDispDaDtlStr INNER JOIN
                      tblDisplaySpecsDtl ON tblDispDaDtlStr.dispDtlId = tblDisplaySpecsDtl.dispDtlId AND tblDispDaDtlStr.displaySpecsId = tblDisplaySpecsDtl.displaySpecsId WHERE tblDispDaDtlStr.displaySpecsId = '$id' AND tblDispDaDtlStr.strCode = '$strCode' AND usableTag = 'Y'";
		return $this->getArrRes($this->execQry($sql));
	}
	function deleteRentablesStr($strCode,$disp){
		$trans = $this->beginTran();
		$sql = "UPDATe tblDispDaDtlStr set permanentTag = NULL, availabilityTag = NULL WHERE strCode = '".$strCode."' AND displaySpecsId = '".$disp."' ";	
		if ($trans) {
			$trans = $this->execQry($sql);
		}
		if(!$trans){
			$trans = $this->rollbackTran();
			return false;
		}else{
			$trans = $this->commitTran();
			return true;
		}
	}
	function updateRentables($arr){
		
		if($this->deleteRentablesStr($arr['hdnStore'],$arr['hdnSpecs'])){
			$trans = $this->beginTran();
			//$arrHdr = $this->getDispStartEnd($arr['refNo']);
			$ctr = 0;
			for($i=0;$i<=(int)$arr['hdRentCtr'];$i++){
				if((int)$arr["switcherRent_$i"]==1	){
					if((int)$arr["switcherPerm_$i"]==1	){
						$perma = "'Y'";
					}else{
						$perma = "NULL";
					}
					
					$sql = "UPDATE tblDispDaDtlStr set permanentTag = $perma, availabilityTag = 'Y' , taggedDate = '".date('m/d/Y')."', taggedBy = '".$_SESSION['sts-userId']."' WHERE strCode = '".$arr['hdnStore']."' AND displaySpecsId = '".$arr['hdnSpecs']."' AND dispDtlId = '".$arr["ckStr_$i"]."' ";	
					$ctr++;
					if ($trans) {
						$trans = $this->execQry($sql);
					}
				}
			}
			if(!$trans){
				$trans = $this->rollbackTran();
				return 0;
			}else{
				$trans = $this->commitTran();
				return $ctr;
			}
		}else{
			return false;	
		}
	}
}
?>