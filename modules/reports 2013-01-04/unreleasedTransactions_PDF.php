<?php
	session_start();
	include("../../includes/db.inc.php");
	include("../../includes/common.php");
	include("../../includes/pdf/fpdf.php");
	include("reportsObj.php");
	define('FPDF_FONTPATH','../../includes/pdf/font/');
	
class PDF extends FPDF{
	
	function Main($arr){
		$this->SetFont('Arial', '', '9');
		$this->AddPage();
		$GTotal = 0;
		$ctr = 0 ;
		$trigger1 = $trigger2 = $compTotal = 0;
		$ctrAhead = 1;
		foreach($arr as $val) {
			
			$this->SetFont('Arial', '', '9');	
			
			if($trigger1==0){
				if($val['stsComp']=='1001'){
					$branch = 'Duty Free Clark';	
				}else{
					$branch = 'Duty Free Subic';	
				}
				$this->Cell(9,5,$branch,0,1,'L');
				$trigger1=1;
			}
			if($trigger2==0){
				$grpName = $this->findGrp($val['grpEntered']);
				$this->Cell(9,5,$grpName,0,1,'L');
				$trigger2=1;	
			}
			$this->Cell(9,5,$val['stsRefNo'],0,0,'R');
			$this->Cell(5,5,"",0,0,'L');
			$this->Cell(20,5,date('m/d/Y',strtotime($val['applyDate'])),0,0,'L');	
			$this->Cell(22,5,date('m/d/Y',strtotime($val['endDate'])),0,0,'L');	
			
			if (strlen($val['suppName'])>15)
				$this->Cell(40,5,substr($val['suppName'],0,15)."...",0,0,'L');
			else
				$this->Cell(40,5,$val['suppName'],0,0,'L');	
			
			$this->Cell(40,5,$val['paymentMode'],0,0,'L');
			
			if (strlen($val['dept'])>12)
				$this->Cell(42,5,substr($val['dept'],0,12)."...",0,0,'L');
			else
				$this->Cell(42,5,$val['dept'],0,0,'L');	
			$this->Cell(15,5,$val['suppCurr']." ".number_format($val['stsAmt'],2),0,1,'R');
	
			if($val['stsComp'] != $arr[$ctrAhead]['stsComp']){
				$trigger1=0;
				$trigger2=0;
			}
			if($val['grpEntered'] != $arr[$ctrAhead]['grpEntered']){
				$trigger2=0;
			}
			$ctrAhead++;
			$ctr++;
		}
			$this->Ln(5);
			$this->SetFont('Arial', '', '7');
			$this->Cell(45,5,'Total '.$ctr. ' of Records',0,1,'L');
			$this->Cell(200,8,'* * * * * End of Report. Nothing Follows * * * * ',0,1,'C');
			$this->SetFont('Arial', '', '9');	
	}
	function findGrp($grp){
		$reportsObj = new reportsObj();	
		$grpName =  $reportsObj->getProdGrpName($grp);
		return $grpName['prodName'];
	}
	function Header() {
		//"Run Date: ".date("m/d/Y h:iA")
		$this->Cell(55,5,"Un-Released STS Transactions",0,0,'L');
		$this->Cell(90,5,'',0,0,'C');
		$this->Cell(57,5,'Page '.$this->PageNo().'/{nb}',0,1,'R');
		
		$this->Cell(63,5,"From ".date('m/d/Y',strtotime($_GET['txtDateFrom']))." to ".date('m/d/Y',strtotime($_GET['txtDateTo'])),0,0,'L');
		$this->Cell(75,5,"",0,0,'C');
		$this->Cell(60,5,'',0,1,'R');	
		
		$this->Cell(70,5,"",0,0,'L');
		$this->Cell(63,5,"",0,0,'C');
		$this->Cell(64,5,"Run Date: ".date("m/d/Y h:iA"),0,1,'R');
		
		$this->Cell(15,8,'Ref No.','BT',0,'L');
		$this->Cell(21,8,'Start Date','BT',0,'L');
		$this->Cell(32,8,'End Date','BT',0,'L');
		$this->Cell(35,8,'Supplier','BT',0,'L');
		$this->Cell(35,8,'Payment Mode','BT',0,'L');
		$this->Cell(37,8,'Department','BT',0,'L');
		$this->Cell(19,8,'Amount','BT',1,'L');
	
	}
	
	function Footer() {
		$prntdBy = "Printed By : ".$_SESSION['sts-fullName'];
		$this->SetY(-15);
		$this->Cell(45,5,$prntdBy,0,0,'L');
	}
}

$pdf=new PDF();
$pdf->FPDF($orientation='P',$unit='mm',$format='LEGAL');
$pdf->AliasNbPages(); 
$reportsObj = new reportsObj();
if($_GET['cmbCompCode']=='1001'){
	$branch = 'Duty Free Clark';	
}else{
	$branch = 'Duty Free Subic';	
}
$pdf->branch = $branch;
$grpName =  $reportsObj->getProdGrpName($_GET['cmbProdGrp']);
$pdf->grpName = $grpName['prodName'];
$arr = $reportsObj->getUnreleasedSTS($_GET['txtDateFrom'],$_GET['txtDateTo']);
$pdf->Main($arr);
//$pdf->Output();
$pdf->Output('unreleasedSTS.pdf','D');

?>
