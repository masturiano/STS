<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class wtcObj extends commonObj {
	
	function wtcDtl(){
	                    
		$sql = "
		    select 
                VENDOR_NO,VENDOR,CATEGORY,ACTIVITY,STORE_CODE,STORE_NAME,NET_REBATES,INVOICE_NO
            from WTC_2015
		";
		return $this->getArrRes($this->execQry($sql));
	}   
}
?>