<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
	
class extractObj extends commonObj {
    
    # Ace
    
    # TRUNCATE TABLE tblEfdAceTemp 
    function truncTempAce(){
        $sql = "
        truncate table tblEfdAceTemp
        "; 
        return $this->execQry($sql);
    }
    
    # BULK INSERT TO TABLE tblEfdAceTemp 
    function bulkInsertAce($fileName){
        
        $directory="C:\wamp\www\STS\importfiles\efd_ace_data\\";
        
        $sql = "
        BULK INSERT [pg_sts].[dbo].[tblEfdAceTemp]
        FROM '$directory$fileName' 
        WITH 
        (FIELDTERMINATOR = ',',
        ROWTERMINATOR = '\n'
        )
        ";    
        return $this->execQry($sql);
    }
    
    # DELETE INVALID INVOICE NUMBER
    function deleteHeaderAce(){
        $sql = "
        delete from tblEfdAceTemp where invNo like '%Number%'
        "; 
        return $this->execQry($sql);
    }
    
    # DELETE MONTH
    function deleteMonthAce($month){
        $sql = "
        delete from tblEfdAce where month = '$month'
        "; 
        return $this->execQry($sql);
    }
    
    # UPDATE BLANK FIELD
    function updateBlankFieldAce(){
        $sql = "
        BEGIN
            update 
                    tblEfdAceTemp
                    set rcrAmount = '0'
            where rcrAmount = ''
        END
        BEGIN
            update 
                    tblEfdAceTemp
                    set efdRate = '0'
            where efdRate = ''
        END
        BEGIN
            update 
                    tblEfdAceTemp
                    set efdAmount = '0'
            where efdAmount = ''
        END
        "; 
        return $this->execQry($sql);
    }
    
    function insertTblEfdAce($month){
        $sql = "
        insert into tblEfdAce(invNo,suppCode,suppName,strCode,shrtName,poNo,rcrNo,rcrDate,rcrAmount,efdRate,efdAmount,efdNotes,month)
        select ltrim(rtrim(invNo)),ltrim(rtrim(suppCode)),ltrim(rtrim(suppName)),ltrim(rtrim(strCode)),ltrim(rtrim(shrtName)),ltrim(rtrim(poNo)),ltrim(rtrim(rcrNo)),ltrim(rtrim(rcrDate)),ltrim(rtrim(rcrAmount)),ltrim(rtrim(efdRate)),ltrim(rtrim(efdAmount)),replace(ltrim(rtrim(efdNotes)),' ',''),'{$month}' from [pg_sts].[dbo].[tblEfdAceTemp]
        ";
        return $this->execQry($sql);
    }
    
    # Eco
    
    function truncTempEco(){
        $sql = "
        truncate table tblEfdEcoTemp
        "; 
        return $this->execQry($sql);
    }
    
    function bulkInsertEco($fileName){
        
        $directory="C:\wamp\www\STS\importfiles\efd_eco_data\\";
        
        $sql = "
        BULK INSERT [pg_sts].[dbo].[tblEfdEcoTemp]
        FROM '$directory$fileName' 
        WITH 
        (FIELDTERMINATOR = ',',
        ROWTERMINATOR = '\n'
        )
        ";    
        return $this->execQry($sql);
    }
    
    function deleteHeaderEco(){
        $sql = "
        delete from tblEfdEcoTemp where invNo like '%Number%'
        "; 
        return $this->execQry($sql);
    }
    
    # DELETE MONTH
    function deleteMonthEco($month){
        $sql = "
        delete from tblEfdEco where month = '$month'
        "; 
        return $this->execQry($sql);
    }
    
    # UPDATE BLANK FIELD
    function updateBlankFieldEco(){
        $sql = "
        BEGIN
            update 
                tblEfdEcoTemp
                set payableAmt = '0'
            where payableAmt = ''
        END
        BEGIN
            update 
                tblEfdEcoTemp
                set rate = '0'
            where rate = ''
        END
        BEGIN
            update 
                tblEfdEcoTemp
                set efdAmount = '0'
            where efdAmount = ''
        END
        "; 
        return $this->execQry($sql);
    }
    
    function insertTblEfdEco($month){
        $sql = "
        insert into tblEfdEco(invNo,suppCode,strCode,contractNo,apBatch,cutOffDate,payableAmt,rate,efdAmount,efdNotes,month)
        select ltrim(rtrim(invNo)),ltrim(rtrim(suppCode)),ltrim(rtrim(strCode)),ltrim(rtrim(contractNo)),ltrim(rtrim(apBatch)),ltrim(rtrim(cutOffDate)),ltrim(rtrim(payableAmt)),ltrim(rtrim(rate)),ltrim(rtrim(efdAmount)),replace(ltrim(rtrim(efdNotes)),' ',''),'{$month}' from [pg_sts].[dbo].[tblEfdEcoTemp]
        ";
        return $this->execQry($sql);
    }
    
    /*
	function checkFileName($fileName){
		$sql = "SELECT * from tblEfdFileName WHERE efdFileName = '$fileName'";
		return  $this->getRecCount($this->execQry($sql));	
	}
	function insertFileName($fileName){
		$sql = "INSERT into tblEfdFileName  (efdFileName) VALUES ('$fileName')";
		return $this->execQry($sql);	
	}
    */
}