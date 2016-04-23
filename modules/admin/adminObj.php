<?

$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
	
class adminObj extends commonObj {
	
	function countUsers(){
		$sql = "Select count(*) as count From tblUsers WHERE userStat = 'A'";	
		return $this->getSqlAssoc($this->execQry($sql));
	}
	function searchUsers($sidx,$sord,$start,$limit,$searchField,$searchString){
		
		$sql = "SELECT TOP $limit tblUsers.*, tblGroup.grpDesc AS prodName FROM tblUsers INNER JOIN tblGroup ON(tblUsers.grpCode = tblGroup.grpCode) WHERE $searchField =
		'$searchString' AND userStat = 'A' AND tblUsers.userId NOT IN (SELECT TOP $start userId FROM tblUsers WHERE $searchField = '$searchString' AND  userStat = 'A' 
		ORDER BY  $sidx $sord) ORDER BY  $sidx $sord ";
		return $this->getArrRes($this->execQry($sql));
	}
	function getPaginatedUsers($sidx,$sord,$start,$limit){
		$sql = "SELECT TOP $limit tblUsers.*, tblGroup.grpDesc AS prodName FROM tblUsers INNER JOIN tblGroup ON(tblUsers.grpCode = tblGroup.grpCode) WHERE userStat = 'A'
		AND tblUsers.userId NOT IN (SELECT TOP $start userId FROM tblUsers WHERE userStat = 'A' ORDER BY  $sidx $sord)
		ORDER BY  $sidx $sord ";
		return $this->getArrRes($this->execQry($sql));
	}
	function getGroups(){
		$sql = "SELECT * FROM tblGroup";
		return $this->getArrRes($this->execQry($sql));	
	}
	function checkIfUnameExists($username){
		$sql = "SELECT * FROM tblusers WHERE userName = '$username'";
		return $this->getRecCount($this->execQry($sql));
	}
	function addUser($arr){
		
        $userId = $this->generatedUserId();
        
        $encodedPass = base64_encode($arr['txtUserName']);
		
        $sqlAdd	="INSERT INTO tblusers (userId, grpCode, userName, userPass, userLevel, dateEnt, userStat, fullName, strCode)
		VALUES ('{$userId['userId']}','{$arr['cmbDepartment']}', '{$arr['txtUserName']}', '$encodedPass', '2', '".date('Y-m-d')."', 'A', '{$arr['txtFullName']}', '{$arr['cmbStr']}');";
		
        $sqlAddNE ="INSERT INTO ne_sts..tblusers (userId, grpCode, userName, userPass, userLevel, dateEnt, userStat, fullName, strCode)
        VALUES ('{$userId['userId']}','{$arr['cmbDepartment']}', '{$arr['txtUserName']}', '$encodedPass', '2', '".date('Y-m-d')."', 'A', '{$arr['txtFullName']}', '{$arr['cmbStr']}');";
        
        $sqlGetGrpCode = "select grpCode from tblusers WHERE userName='{$arr['txtUserName']}'";
        
        if($this->execQry($sqlAdd)){
            if($minCode = $this->getSqlAssoc($this->execQry($sqlGetGrpCode))){
            $sqlGetModule = "select modules from tblUsersModule WHERE minCode = {$minCode['grpCode']}";
            if($modules = $this->getSqlAssoc($this->execQry($sqlGetGrpCode))){
                $sqlUpdateModule = "Update tblusers set pages=(select modules from tblUsersModule WHERE minCode = {$minCode['grpCode']}) WHERE userName='{$arr['txtUserName']}'";
                    if($this->execQry($sqlUpdateModule)){
                        #NE Copy
                        if($this->execQry($sqlAddNE)){
                            if($minCode = $this->getSqlAssoc($this->execQry($sqlGetGrpCode))){
                                $sqlGetModule = "select modules from tblUsersModule WHERE minCode = {$minCode['grpCode']}";  
                                if($modules = $this->getSqlAssoc($this->execQry($sqlGetGrpCode))){
                                    $sqlUpdateModuleNE = "Update ne_sts..tblusers set pages=(select modules from tblUsersModule WHERE minCode = {$minCode['grpCode']}) WHERE userName='{$arr['txtUserName']}'";    
                                    return $this->execQry($sqlUpdateModuleNE);
                                }  
                            }    
                        }            
                    }        
                }    
            }    
        }    
      
    }
	function userInfo($userId){
		$sql = "SELECT * FROM tblusers WHERE userId = '$userId';";	
		return $this->getSqlAssoc($this->execQry($sql));	
	}
	function UpdateUserInfo($arr){
		$sqlUpdate = "UPDATE tblusers SET fullName = '{$arr['txtFullName']}', grpCode = '{$arr['cmbDepartment']}', strCode = '{$arr['cmbStr']}' WHERE userId = '{$arr['hdnUserId']}'";
		return $this->execQry($sqlUpdate);
	}
	function deleteUser($userId){
		$sqlUpdateDel = "UPDATE tblusers SET userStat = 'D' WHERE userId = '$userId'";
		return $this->execQry($sqlUpdateDel);
	}
	function resetPassword($userId){
		$sql = "SELECT userName from tblusers WHERE userId = '$userId'";
		$userName = $this->getSqlAssoc($this->execQry($sql));	
		$newPass = base64_encode($userName['userName']);
		$sqlInsert = "UPDATE tblusers SET userPass= '$newPass' WHERE userId = '$userId'";
		return $this->execQry($sqlInsert);
	}
	function ModuleAccess($pages,$userId){
		$sqlModuleAccess = "Update tblusers set pages='$pages' WHERE userId='$userId'";
		return $this->execQry($sqlModuleAccess);
	}
	function getBranches(){
		$sql = "SELECT * FROM tblBranches";	
		return $this->getArrRes($this->execQry($sql));
	}
    function generatedUserId(){
        $sql = "select max(userId)+1 as userId from tblusers";    
        return $this->getSqlAssoc($this->execQry($sql));    
    }
}
?>