<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
# CLASS NAME
class efdObj extends commonObj {
    
    # GET BRANCHES FOR DROP DOWN
	function getBranches(){
		$sql = "select strCode, cast(strCode as nvarchar)+' - '+brnDesc as brnDesc FROM tblBranches order by strCode";
		return $this->getArrRes($this->execQry($sql));
	}
	
    # GET SUPPLIER FOR DROP DOWN
	function getSupplier(){
		$sql = "SELECT DISTINCT sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppName, sql_mmpgtlib.dbo.APSUPP.ASNUM AS suppCode, CAST(sql_mmpgtlib.dbo.APSUPP.ASNUM AS varchar) + ' - ' + sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppCodeName 
		FROM sql_mmpgtlib.dbo.APSUPP 
		WHERE sql_mmpgtlib.dbo.APSUPP.ASTYPE = 1
		ORDER BY sql_mmpgtlib.dbo.APSUPP.ASNUM
        ";
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
    
    # GET SUPPLIER DETAILS
    function getSupplierDetails($supp_code){
        $sql = "
        SELECT DISTINCT suppCode,suppName FROM (
        SELECT DISTINCT sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppName,sql_mmpgtlib.dbo.APSUPP.ASNUM AS suppCode
        FROM sql_mmpgtlib.dbo.APSUPP 
        WHERE sql_mmpgtlib.dbo.APSUPP.ASTYPE = 1
        AND sql_mmpgtlib.dbo.APSUPP.ASNUM = '{$supp_code}'
        UNION ALL
        SELECT DISTINCT sql_mmneslib.dbo.APSUPP.ASNAME AS suppName,sql_mmneslib.dbo.APSUPP.ASNUM AS suppCode
        FROM sql_mmneslib.dbo.APSUPP 
        WHERE sql_mmneslib.dbo.APSUPP.ASTYPE = 1
        AND sql_mmneslib.dbo.APSUPP.ASNUM = '{$supp_code}') SUPPLIER
        ORDER BY suppCode
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # GET DISTRIBUTION NAME
    function getDistributionName() {
        $sql = "
            SELECT 
                ID,DISTRIBUTION_NAME 
            FROM 
                tblEfdDistribution
            WHERE
                STATUS = 'A'
        "; 
        return $this->getArrRes($this->execQry($sql));   
    }  
    
    # GET SUB DISTRIBUTION NAME
    function getSubDistributionName($id) {
        $sql = "
            SELECT 
                ID,DISTRIBUTION_NAME,SUB_DISTRIBUTION_NAME 
            FROM 
                tblEfdDistributionSub 
            WHERE 
                ID = {$id}
                AND STATUS = 'A'
            ORDER BY 
                ID,SORTING
        "; 
        return $this->getArrRes($this->execQry($sql));   
    } 
    
    # GET DISTRIBUTION NAME SPECIFIC
    function getDistributionNameSpec($id) {
        $sql = "
            SELECT DISTRIBUTION_NAME FROM tblEfdDistribution WHERE ID = '{$id}'
        "; 
        return $this->getSqlAssoc($this->execQry($sql));  
    } 
    
    # GET EFD DETAILS
    function getEfdDetail($arr){
        
        # SET VARIABLE'S
        $distribution_name = $this->getDistributionNameSpec($arr['cmbGroup']);
        $first_day_of_month = date('Y-m-01', strtotime($arr['txtDateFrom']));
        $last_day_of_month = date('Y-m-t', strtotime($arr['txtDateTo']));
        
        # FILTER DATE
        $month_range = "dateRange between '{$first_day_of_month}' and '{$last_day_of_month}'";
        
        # FILTER BRANCH
        if($arr['cmbStore'] == '0'){
            $branch = "";
        }
        else{
            $branch = "AND strCode = '{$arr['cmbStore']}'";
        }
        
        # FILTER VENDOR
        if($arr['cmbSupp'] == '0'){
            $vendor = "";    
        }
        else{
            $vendor = "AND suppCode LIKE '%{$arr['cmbSupp']}'";
        }
        
        # FILTER DISTRIBUTION NAME
        if($distribution_name['DISTRIBUTION_NAME'] == 'ALL'){
            $dis_name = "";   
        }
        else{
            $dis_name = "AND efdNotes LIKE '%{$distribution_name['DISTRIBUTION_NAME']}%'";
        }
        
        # FILTER SUB DISTRIBUTION NAME
        if($arr['cmbSubGroup'] == 'ALL'){
            $sub_dis_name = "";    
        }
        else if($arr['cmbSubGroup'] == 'SubACC' || $arr['cmbSubGroup'] == 'SubACE' || $arr['cmbSubGroup'] == 'SubCHA' || 
        $arr['cmbSubGroup'] == 'SubECO' || $arr['cmbSubGroup'] == 'SubEDI'){
            $sub_dis_name = "
            AND efdNotes NOT LIKE '%ADJ%'
            AND efdNotes NOT LIKE '%DSD%'
            AND efdNotes NOT LIKE '%OpenPO%'
            ";
        }
        else{
            if($arr['cmbSubGroup'] == 'APV'){
                $sub_dis_name = "AND (efdNotes like '%APV%' and efdNotes not like '%DSD_APV%' and efdNotes not like '%OPO_APV%') "; 
            }
            else{
                $sub_dis_name = "AND efdNotes LIKE '%{$arr['cmbSubGroup']}%'";     
            }   
        }
        
        # EXCEL DATA
        $sql = "
            SELECT * FROM (
            SELECT
            invNo, --1
            suppCode, --2
            suppName, --3
            strCode, --4
            shrtName, --5
            poNo, --6
            rcrNo, --7
            rcrDate as dateRange, --8
            rcrAmount as amount, --9
            efdRate, --10
            efdAmount, --11
            efdNotes --12
            FROM tblEfdAce
            union all
            SELECT
            invNo, --1
            suppCode, --2
            '' as suppName, --3
            strCode, --4
            '' as shrtName, --5
            contractNo, --6
            apBatch, --7
            cutOffDate  as dateRange, --8
            payableAmt  as amount, --9
            rate, --10
            efdAmount, --11
            efdNotes --12
            FROM tblEfdEco) DETAILS
            WHERE
            {$month_range}
            {$branch}
            {$vendor}
            {$dis_name}
            {$sub_dis_name}
        "; 
        return $this->getArrRes($this->execQry($sql));   
    } 
    
    # GET EFD SUMMARY ACC
    function getEfdSummaryACC($arr){
        
        # SET VARIABLE'S
        $distribution_name = $this->getDistributionNameSpec($arr['cmbGroup']);
        $first_day_of_month = date('Y-m-01', strtotime($arr['txtDateFrom']));
        $last_day_of_month = date('Y-m-t', strtotime($arr['txtDateTo']));
        
        # FILTER DATE
        $month_range_ace = "rcrDate between '{$first_day_of_month}' AND '{$last_day_of_month}'";
        $month_range_eco = "cutOffDate between '{$first_day_of_month}' AND '{$last_day_of_month}'";
        
        # FILTER BRANCH
        if($arr['cmbStore'] == '0'){
            $branch = "";
        }
        else{
            $branch = "AND strCode = '{$arr['cmbStore']}'";
        }
        
        # FILTER VENDOR
        if($arr['cmbSupp'] == '0'){
            $vendor = "";    
        }
        else{
            $vendor = "AND suppCode LIKE '%{$arr['cmbSupp']}'";
        }
        
        # FILTER DISTRIBUTION NAME
        $dis_name = "AND efdNotes LIKE '%ACC%'";    

        
        # EXCEL DATA
        $sql = "
            SELECT sum(amount) as amount FROM (
                SELECT
                    sum(rcrAmount) amount
                FROM 
                    tblEfdAce
                WHERE
                    {$month_range_ace}
                    {$branch}
                    {$vendor}
                    {$dis_name}
                union all
                SELECT
                    sum(payableAmt)  as amount
                FROM 
                    tblEfdEco
                WHERE
                    {$month_range_eco}
                    {$branch}
                    {$vendor}
                    {$dis_name}
            ) DETAILS
        "; 
        return $this->getSqlAssoc($this->execQry($sql));   
    } 
    
    # GET EFD SUMMARY ACE
    function getEfdSummaryACE($arr){
        
        # SET VARIABLE'S
        $distribution_name = $this->getDistributionNameSpec($arr['cmbGroup']);
        $first_day_of_month = date('Y-m-01', strtotime($arr['txtDateFrom']));
        $last_day_of_month = date('Y-m-t', strtotime($arr['txtDateTo']));
        
        # FILTER DATE
        $month_range_ace = "rcrDate between '{$first_day_of_month}' AND '{$last_day_of_month}'";
        $month_range_eco = "cutOffDate between '{$first_day_of_month}' AND '{$last_day_of_month}'";
        
        # FILTER BRANCH
        if($arr['cmbStore'] == '0'){
            $branch = "";
        }
        else{
            $branch = "AND strCode = '{$arr['cmbStore']}'";
        }
        
        # FILTER VENDOR
        if($arr['cmbSupp'] == '0'){
            $vendor = "";    
        }
        else{
            $vendor = "AND suppCode LIKE '%{$arr['cmbSupp']}'";
        }
        
        # FILTER DISTRIBUTION NAME
        $dis_name = "AND efdNotes LIKE '%ACE%'";    

        
        # EXCEL DATA
        $sql = "
            SELECT sum(amount) as amount FROM (
                SELECT
                    sum(rcrAmount) amount
                FROM 
                    tblEfdAce
                WHERE
                    {$month_range_ace}
                    {$branch}
                    {$vendor}
                    {$dis_name}
                union all
                SELECT
                    sum(payableAmt)  as amount
                FROM 
                    tblEfdEco
                WHERE
                    {$month_range_eco}
                    {$branch}
                    {$vendor}
                    {$dis_name}
            ) DETAILS
        "; 
        return $this->getSqlAssoc($this->execQry($sql));   
    } 
    
    # GET EFD SUMMARY CHA
    function getEfdSummaryCHA($arr){
        
        # SET VARIABLE'S
        $distribution_name = $this->getDistributionNameSpec($arr['cmbGroup']);
        $first_day_of_month = date('Y-m-01', strtotime($arr['txtDateFrom']));
        $last_day_of_month = date('Y-m-t', strtotime($arr['txtDateTo']));
        
        # FILTER DATE
        $month_range_ace = "rcrDate between '{$first_day_of_month}' AND '{$last_day_of_month}'";
        $month_range_eco = "cutOffDate between '{$first_day_of_month}' AND '{$last_day_of_month}'";
        
        # FILTER BRANCH
        if($arr['cmbStore'] == '0'){
            $branch = "";
        }
        else{
            $branch = "AND strCode = '{$arr['cmbStore']}'";
        }
        
        # FILTER VENDOR
        if($arr['cmbSupp'] == '0'){
            $vendor = "";    
        }
        else{
            $vendor = "AND suppCode LIKE '%{$arr['cmbSupp']}'";
        }
        
        # FILTER DISTRIBUTION NAME
        $dis_name = "AND efdNotes LIKE '%CHA%'";    

        
        # EXCEL DATA
        $sql = "
            SELECT sum(amount) as amount FROM (
                SELECT
                    sum(rcrAmount) amount
                FROM 
                    tblEfdAce
                WHERE
                    {$month_range_ace}
                    {$branch}
                    {$vendor}
                    {$dis_name}
                union all
                SELECT
                    sum(payableAmt)  as amount
                FROM 
                    tblEfdEco
                WHERE
                    {$month_range_eco}
                    {$branch}
                    {$vendor}
                    {$dis_name}
            ) DETAILS
        "; 
        return $this->getSqlAssoc($this->execQry($sql));   
    } 
    
    # GET EFD SUMMARY ECO
    function getEfdSummaryECO($arr){
        
        # SET VARIABLE'S
        $distribution_name = $this->getDistributionNameSpec($arr['cmbGroup']);
        $first_day_of_month = date('Y-m-01', strtotime($arr['txtDateFrom']));
        $last_day_of_month = date('Y-m-t', strtotime($arr['txtDateTo']));
        
        # FILTER DATE
        $month_range_ace = "rcrDate between '{$first_day_of_month}' AND '{$last_day_of_month}'";
        $month_range_eco = "cutOffDate between '{$first_day_of_month}' AND '{$last_day_of_month}'";
        
        # FILTER BRANCH
        if($arr['cmbStore'] == '0'){
            $branch = "";
        }
        else{
            $branch = "AND strCode = '{$arr['cmbStore']}'";
        }
        
        # FILTER VENDOR
        if($arr['cmbSupp'] == '0'){
            $vendor = "";    
        }
        else{
            $vendor = "AND suppCode LIKE '%{$arr['cmbSupp']}'";
        }
        
        # FILTER DISTRIBUTION NAME
        $dis_name = "AND efdNotes LIKE '%ECO%'";    

        
        # EXCEL DATA
        $sql = "
            SELECT sum(amount) as amount FROM (
                SELECT
                    sum(rcrAmount) amount
                FROM 
                    tblEfdAce
                WHERE
                    {$month_range_ace}
                    {$branch}
                    {$vendor}
                    {$dis_name}
                union all
                SELECT
                    sum(payableAmt)  as amount
                FROM 
                    tblEfdEco
                WHERE
                    {$month_range_eco}
                    {$branch}
                    {$vendor}
                    {$dis_name}
            ) DETAILS
        "; 
        return $this->getSqlAssoc($this->execQry($sql));   
    } 
    
    # GET EFD SUMMARY EDI
    function getEfdSummaryEDI($arr){
        
        # SET VARIABLE'S
        $distribution_name = $this->getDistributionNameSpec($arr['cmbGroup']);
        $first_day_of_month = date('Y-m-01', strtotime($arr['txtDateFrom']));
        $last_day_of_month = date('Y-m-t', strtotime($arr['txtDateTo']));
        
        # FILTER DATE
        $month_range_ace = "rcrDate between '{$first_day_of_month}' AND '{$last_day_of_month}'";
        $month_range_eco = "cutOffDate between '{$first_day_of_month}' AND '{$last_day_of_month}'";
        
        # FILTER BRANCH
        if($arr['cmbStore'] == '0'){
            $branch = "";
        }
        else{
            $branch = "AND strCode = '{$arr['cmbStore']}'";
        }
        
        # FILTER VENDOR
        if($arr['cmbSupp'] == '0'){
            $vendor = "";    
        }
        else{
            $vendor = "AND suppCode LIKE '%{$arr['cmbSupp']}'";
        }
        
        # FILTER DISTRIBUTION NAME
        $dis_name = "AND efdNotes LIKE '%EDI%'";    

        
        # EXCEL DATA
        $sql = "
            SELECT sum(amount) as amount FROM (
                SELECT
                    sum(rcrAmount) amount
                FROM 
                    tblEfdAce
                WHERE
                    {$month_range_ace}
                    {$branch}
                    {$vendor}
                    {$dis_name}
                union all
                SELECT
                    sum(payableAmt)  as amount
                FROM 
                    tblEfdEco
                WHERE
                    {$month_range_eco}
                    {$branch}
                    {$vendor}
                    {$dis_name}
            ) DETAILS
        "; 
        return $this->getSqlAssoc($this->execQry($sql));   
    } 
    
    function getStsDetail($arr){
        
        # SET VARIABLE'S
        $first_day_of_month = date('Y-m-01', strtotime($arr['txtDateFrom']));
        $last_day_of_month = date('Y-m-t', strtotime($arr['txtDateTo']));
        
        # FILTER EFFICIENCY DISCOUNT ONLY
        $efd_dept_cls_subcls = "(hdr.stsDept = 9 AND hdr.stsCls = 1 AND hdr.stsSubCls = 1)";
        
        # FILTER DATE
        $month_range = "AND hdr.applyDate between '{$first_day_of_month}' and '{$last_day_of_month}'";
        
        # FILTER BRANCH
        if($arr['cmbStore'] == '0'){
            $branch = "";
        }
        else{
            $branch = "AND dtl.strCode = '{$arr['cmbStore']}'";
        }
        
        # FILTER VENDOR
        if($arr['cmbSupp'] == '0'){
            $vendor = "";    
        }
        else{
            $vendor = "AND hdr.suppCode LIKE '%{$arr['cmbSupp']}'";
        }
        
        $sql="
            SELECT
                hdr.stsRefno,
                hdr.suppCode,                            
                mms.asname,                            
                dtl.strCode,                            
                str.brnDesc,                            
                dtl.stsNo,
                hdr.nbrApplication,                            
                dtl.stsAmt,    
                dtl.stsVatAmt,    
                hdr.applyDate,
                hdr.dateEntered,
                hdr.dateApproved,                            
                hdr.stsRemarks,
                case when hdr.stsPaymentMode = 'C' then 'Collection/Check' when hdr.stsPaymentMode = 'D' then 'Invoice Deduction' else hdr.stsPaymentMode end as stsPaymentMode,        
                trt.typeDesc,
                (SELECT     
                    hierarchyDesc
                FROM          
                    tblStsHierarchy
                WHERE      
                    tblStsHierarchy.stsDept = hdr.stsDept AND levelCode = 1) AS dept,
                (SELECT     
                    hierarchyDesc
                FROM          
                    tblStsHierarchy
                WHERE      
                    tblStsHierarchy.stsDept = hdr.stsDept AND tblStsHierarchy.stsCls = hdr.stsCls AND levelCode = 2) AS cls,
                (SELECT     
                    hierarchyDesc
                FROM          
                    tblStsHierarchy
                WHERE      
                    tblStsHierarchy.stsDept = hdr.stsDept 
                    AND tblStsHierarchy.stsCls = hdr.stsCls 
                    AND tblStsHierarchy.stsSubCls = hdr.stsSubCls 
                    AND 
                    levelCode = 3) AS subCls
            FROM
                tblStsHdr hdr
            INNER JOIN 
                tblStsDtl dtl ON dtl.stsRefno = hdr.stsRefno
            INNER JOIN 
                sql_mmpgtlib.dbo.apsupp mms ON mms.asnum = hdr.suppCode
            INNER JOIN 
                tblBranches str ON str.strCode = dtl.strCode
            INNER JOIN     
                tblTransType trt on trt.typeCode = hdr.stsType    
            where 
                {$efd_dept_cls_subcls}
                {$month_range}
                {$branch}
                {$vendor}  
        ";
        return $this->getArrRes($this->execQry($sql));
    } 
    
    function getStsSummary($arr){
        
        # SET VARIABLE'S
        $first_day_of_month = date('Y-m-01', strtotime($arr['txtDateFrom']));
        $last_day_of_month = date('Y-m-t', strtotime($arr['txtDateTo']));
        
        # FILTER EFFICIENCY DISCOUNT ONLY
        $efd_dept_cls_subcls = "(hdr.stsDept = 9 AND hdr.stsCls = 1 AND hdr.stsSubCls = 1)";
        
        # FILTER DATE
        $month_range = "AND hdr.applyDate between '{$first_day_of_month}' and '{$last_day_of_month}'";
        
        # FILTER BRANCH
        if($arr['cmbStore'] == '0'){
            $branch = "";
        }
        else{
            $branch = "AND dtl.strCode = '{$arr['cmbStore']}'";
        }
        
        # FILTER VENDOR
        if($arr['cmbSupp'] == '0'){
            $vendor = "";    
        }
        else{
            $vendor = "AND hdr.suppCode LIKE '%{$arr['cmbSupp']}'";
        }
        
        $sql = "
            SELECT                    
                sum(dtl.stsAmt) as stsAmt    
            FROM            
                tblStsHdr hdr            
            INNER JOIN             
                tblStsDtl dtl ON dtl.stsRefno = hdr.stsRefno            
            INNER JOIN             
                sql_mmpgtlib.dbo.apsupp mms ON mms.asnum = hdr.suppCode            
            INNER JOIN             
                tblBranches str ON str.strCode = dtl.strCode            
            where             
                {$efd_dept_cls_subcls}
                {$month_range}
                {$branch}
                {$vendor} 
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    function getOraDetail($arr){
        
        # SET VARIABLE'S
        $first_day_of_month = date('Y-m-01', strtotime($arr['txtDateFrom']));
        $last_day_of_month = date('Y-m-t', strtotime($arr['txtDateTo']));
        
        # FILTER VENDOR
        if($arr['cmbSupp'] == '0'){
            $vendor = "";    
        }
        else{
            $supplier_number = $arr['cmbSupp'];
            $vendor = "ap_suppliers.SEGMENT1 = ''{$supplier_number}''";
        }
        
        # FILTER BRANCH    
        if($arr['cmbStore'] == '0'){
            $branch = "";
        }
        else{
            $assStoreDet = $this->findStoreDetails($arr['cmbStore']); 
            $short_name = $assStoreDet['BRNSHORTNAME'];           
            $branch = "and ap_supplier_sites_all.VENDOR_SITE_CODE = ''{$short_name}''";
        }
        
        # FILTER DATE
        $month_range = "AND ap_invoices_all.INVOICE_DATE BETWEEN to_date(''{$first_day_of_month}'') AND to_date(''{$last_day_of_month}'')";  
        
        # FILTER SOURCE
        $source = "AND ap_invoices_all.SOURCE = ''PO''";
        
        #FILTER MATCH STATUS FLAG
        $match_status_flag = "AND ap_invoice_distributions_all.MATCH_STATUS_FLAG = ''A''";
        
        # FILTER GL CODE COMBINATIONS
        $gl_code_combinations = "AND gl_code_combinations.SEGMENT7 IN (''60100001'', ''60100002'', ''60100003'', ''60100004'')";
        
        # LINE TYPE GL CODE
        $line_type_type_lookup_code = "AND ap_invoice_distributions_all.LINE_TYPE_LOOKUP_CODE = ''ITEM''";
        
        
        
        # FILTER ORG ID
        $org_id = "AND ap_invoices_all.ORG_ID IN (87,85,133,113,153)";   
        
        # FILTER EFFICIENCY DISCOUNT ONLY
        $efd_dept_cls_subcls = "(hdr.stsDept = 9 AND hdr.stsCls = 1 AND hdr.stsSubCls = 1)";
        
        # CANCELLED AMOUNT
        $cancelled_amount = "and ap_invoices_all.CANCELLED_AMOUNT is null"; 
             
        $sql="
            Select 
                ORA.INVOICE_ID,ORA.SEGMENT1,ORA.VENDOR_NAME,ORA.ORG_ID,ORA.VENDOR_SITE_CODE,
                ORA.INVOICE_NUM,ORA.INVOICE_DATE,ORA.INVOICE_AMOUNT,ORA.SOURCE,
                ORA.DESCRIPTION,ORA.MATCH_STATUS_FLAG,ORA.GL_LINE_CODE,ORA.LINE_AMOUNT,
                TBLBRANCHES.strCode
            from 
                openquery([192.168.200.136],'
                    SELECT 
                        DISTINCT ap_invoices_all.INVOICE_ID,
                        ap_suppliers.SEGMENT1,
                        ap_suppliers.VENDOR_NAME,
                        ap_invoices_all.ORG_ID,
                        ap_supplier_sites_all.VENDOR_SITE_CODE,
                        ap_invoices_all.INVOICE_NUM,
                        ap_invoices_all.INVOICE_DATE,
                        ap_invoices_all.INVOICE_AMOUNT,
                        ap_invoices_all.SOURCE,
                        ap_invoices_all.DESCRIPTION,
                        ap_invoice_distributions_all.MATCH_STATUS_FLAG,
                        gl_code_combinations.SEGMENT7 AS GL_LINE_CODE,
                        ap_invoice_distributions_all.AMOUNT AS LINE_AMOUNT
                FROM 
                    gl_code_combinations
                INNER JOIN 
                    ap_invoice_distributions_all
                    ON ap_invoice_distributions_all.DIST_CODE_COMBINATION_ID = gl_code_combinations.CODE_COMBINATION_ID
                INNER JOIN 
                    ap_invoices_all
                    ON ap_invoice_distributions_all.INVOICE_ID = ap_invoices_all.INVOICE_ID
                INNER JOIN 
                    ap_suppliers
                    ON ap_suppliers.VENDOR_ID = ap_invoices_all.VENDOR_ID
                INNER JOIN 
                    ap_supplier_sites_all
                    ON ap_supplier_sites_all.VENDOR_SITE_ID = ap_invoices_all.VENDOR_SITE_ID
                WHERE 
                    {$vendor}
                    {$branch}
                    {$org_id}
                    {$month_range}
                    {$source}
                    {$match_status_flag}
                    {$gl_code_combinations}
                    {$line_type_type_lookup_code}
                    {$cancelled_amount}
                ORDER BY 
                    ap_invoices_all.ORG_ID,
                    ap_suppliers.SEGMENT1,
                    ap_invoices_all.INVOICE_NUM
            ') ORA   
            LEFT JOIN 
            (SELECT * FROM TBLBRANCHES
            UNION ALL
            SELECT * FROM test_ne_sts..TBLBRANCHES) TBLBRANCHES
            on TBLBRANCHES.brnShortName collate SQL_Latin1_General_CP1_CI_AS = ORA.VENDOR_SITE_CODE collate SQL_Latin1_General_CP1_CI_AS             
        ";
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
                                                     
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);
        return $this->getArrRes($this->execQry($sql));
    } 
    
    function getOraSummary($arr){
        
        # SET VARIABLE'S
        $first_day_of_month = date('Y-m-01', strtotime($arr['txtDateFrom']));
        $last_day_of_month = date('Y-m-t', strtotime($arr['txtDateTo']));
        
        # FILTER VENDOR
        if($arr['cmbSupp'] == '0'){
            $vendor = "";    
        }
        else{
            $supplier_number = $arr['cmbSupp'];
            $vendor = "AND ap_suppliers.SEGMENT1 = ''{$supplier_number}''";
        }
        
        # FILTER BRANCH    
        if($arr['cmbStore'] == '0'){
            $branch = "";
        }
        else{
            $assStoreDet = $this->findStoreDetails($arr['cmbStore']); 
            $short_name = $assStoreDet['BRNSHORTNAME'];           
            $branch = "and ap_supplier_sites_all.VENDOR_SITE_CODE = ''{$short_name}''";
        }
        
        # FILTER DATE
        $month_range = "and ap_invoices_all.INVOICE_DATE BETWEEN to_date(''{$first_day_of_month}'') AND to_date(''{$last_day_of_month}'')";  
        
        # FILTER SOURCE
        $source = "ap_invoices_all.SOURCE = ''PO''";
        
        #FILTER MATCH STATUS FLAG
        $match_status_flag = "AND ap_invoice_distributions_all.MATCH_STATUS_FLAG = ''A''";
        
        # FILTER GL CODE COMBINATIONS
        $gl_code_combinations = "AND gl_code_combinations.SEGMENT7 IN (''60100001'', ''60100002'', ''60100003'', ''60100004'')";
        
        # LINE TYPE GL CODE
        $line_type_type_lookup_code = "AND ap_invoice_distributions_all.LINE_TYPE_LOOKUP_CODE = ''ITEM''";
        
        
        
        # FILTER ORG ID
        $org_id = "AND ap_invoices_all.ORG_ID IN (87,85,133,113,153)";   
        
        # FILTER EFFICIENCY DISCOUNT ONLY
        $efd_dept_cls_subcls = "(hdr.stsDept = 9 AND hdr.stsCls = 1 AND hdr.stsSubCls = 1)";
        
        # CANCELLED AMOUNT
        $cancelled_amount = "and ap_invoices_all.CANCELLED_AMOUNT is null"; 
             
        $sql="
            Select 
                INVOICE_AMOUNT
            from 
                openquery([192.168.200.136],'
                    SELECT 
                        sum(ap_invoices_all.INVOICE_AMOUNT) as INVOICE_AMOUNT
                    FROM 
                        gl_code_combinations
                    INNER JOIN 
                        ap_invoice_distributions_all
                        ON ap_invoice_distributions_all.DIST_CODE_COMBINATION_ID = gl_code_combinations.CODE_COMBINATION_ID
                    INNER JOIN 
                        ap_invoices_all
                        ON ap_invoice_distributions_all.INVOICE_ID = ap_invoices_all.INVOICE_ID
                    INNER JOIN 
                        ap_suppliers
                        ON ap_suppliers.VENDOR_ID = ap_invoices_all.VENDOR_ID
                    INNER JOIN 
                        ap_supplier_sites_all
                        ON ap_supplier_sites_all.VENDOR_SITE_ID = ap_invoices_all.VENDOR_SITE_ID
                    WHERE 
                    {$source}  
                    {$vendor}
                    {$branch}
                    {$org_id}  
                    {$month_range}
                    {$match_status_flag}
                    {$gl_code_combinations}
                    {$line_type_type_lookup_code}
                    {$cancelled_amount}
            ') ORA   
        ";
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
                                                     
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);
        return $this->getSqlAssoc($this->execQry($sql));
    } 
    
    # SIR MIKE COPY
    
    function getRfp($arr){
        if((int)$arr['cmbStore']==0){
            $storeFilt = "";
        }
        else{
            $storeFilt = "AND strCode = '".$arr['cmbStore']."'";    
        }
        
        if((int)$arr['cmbSupp']==0){
            $suppFilt = "";    
        }
        else{
            $suppFilt = "AND suppCode = '".$arr['cmbSupp']."'";    
        }
        
        $sql = "SELECT expNo, strCodeHdr,dbo.tblBranches.brnDesc, suppCode,mms.asname, cut_date, remarks, hdrAmount FROM [dbo].[tblrfp_efd]
INNER JOIN sql_mmpgtlib.dbo.apsupp AS mms ON dbo.tblrfp_efd.suppCode = mms.asnum
INNER JOIN dbo.tblBranches ON dbo.tblrfp_efd.strCodeHdr = dbo.tblBranches.strCode
        WHERE cut_date BETWEEN '".date('m/d/Y',strtotime($arr['txtDateFrom']))."' AND '".date('m/d/Y',strtotime($arr['txtDateTo']))."' $storeFilt $suppFilt;";    
        return $this->getArrRes($this->execQry($sql));
    }
    function getSts($arr){
        if((int)$arr['cmbStore']==0){
            $storeFilt = "";
        }
        else{
            $storeFilt = "AND strCode = '".$arr['cmbStore']."'";
        }
        
        if((int)$arr['cmbSupp']==0){
            $suppFilt = "";
        }
        else{
            $suppFilt = "AND suppCode = '".$arr['cmbSupp']."'";
        }
        $sql="SELECT
        dbo.tblStsHdr.suppCode,
        dbo.tblStsHdr.stsRemarks,
        dbo.tblStsDtl.strCode,
        dbo.tblBranches.brnDesc,
        mms.asname,
        dbo.tblStsDtl.stsAmt,
        dbo.tblStsDtl.stsNo,
        dbo.tblStsHdr.period
        FROM
        dbo.tblStsHdr
        INNER JOIN dbo.tblStsDtl ON dbo.tblStsHdr.stsRefno = dbo.tblStsDtl.stsRefno
        INNER JOIN sql_mmpgtlib.dbo.apsupp AS mms ON dbo.tblStsHdr.suppCode = mms.asnum
        INNER JOIN dbo.tblBranches ON dbo.tblStsDtl.strCode = dbo.tblBranches.strCode
        where (stsDept = 9 OR stsDept = 10) AND period BETWEEN '".date('m/d/Y',strtotime($arr['txtDateFrom']))."' AND '".date('m/d/Y',strtotime($arr['txtDateTo']))."' $storeFilt $suppFilt";
        return $this->getArrRes($this->execQry($sql));
    } 
}
?>