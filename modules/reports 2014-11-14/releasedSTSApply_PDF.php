<?php
	session_start();
	include("../../includes/db.inc.php");
	include("../../includes/common.php");
	include("../../includes/pdf/fpdf.php");
	include("reportsObj.php");
	define('FPDF_FONTPATH','../../includes/pdf/font/');
	
class PDF extends FPDF
{
	function Main($arr){
		$this->SetFont('Arial', '', '9');
		$this->AddPage();
		$GTotal = 0;
		$ctr = 0 ;
		foreach($arr as $val){
			$this->SetFont('Arial', '', '9');	
			$this->Cell(11,5,$val['stsRefNo'],0,0,'R');
			$this->Cell(5,5,"",0,0,'L');
			$this->Cell(11,5,$val['stsNo']." - ".$val['stsSeq'],0,0,'R');
			$this->Cell(13,5,"",0,0,'L');
			$this->Cell(22,5,date('m/d/Y',strtotime($val['stsApplyDate'])),0,0,'L');	
			
			if (strlen($val['suppName'])>15)
				$this->Cell(44,5,substr($val['suppName'],0,15)."...",0,0,'L');
			else
				$this->Cell(44,5,$val['suppName'],0,0,'L');	
			
			$this->Cell(30,5,$val['paymentMode'],0,0,'L');
			
			if (strlen($val['dept'])>12)
				$this->Cell(43,5,substr($val['dept'],0,12)."...",0,0,'L');
			else
				$this->Cell(43,5,$val['dept'],0,0,'L');	
				
			$this->Cell(15,5,number_format($val['stsApplyAmt'],2),0,1,'R');
			$ctr++;
		}
			$this->Ln(5);
			$this->SetFont('Arial', '', '7');
			$this->Cell(45,5,'Total '.$ctr. ' of Records',0,1,'L');
			$this->Cell(200,8,'* * * * * End of Report. Nothing Follows * * * * ',0,1,'C');
			$this->SetFont('Arial', '', '9');	
	}
	
	function Header() {
		//"Run Date: ".date("m/d/Y h:iA")
		$this->Cell(55,5,"Released STS (Apply Date)",0,0,'L');
		$this->Cell(90,5,'',0,0,'C');
		$this->Cell(57,5,'Page '.$this->PageNo().'/{nb}',0,1,'R');
		
		$this->Cell(63,5,"As of: ".date('m/d/Y',strtotime($_GET['txtDate'])),0,0,'L');
		$this->Cell(75,5,"",0,0,'C');
		$this->Cell(60,5,'Status: '.$this->status,0,1,'R');	
		
		$this->Cell(70,5,"Store: ".$this->branch,0,0,'L');
		$this->Cell(63,5,"",0,0,'C');
		$this->Cell(64,5,"Run Date: ".date("m/d/Y h:iA"),0,1,'R');
		
		$this->Cell(17,8,'Ref No.','BT',0,'L');
		$this->Cell(19,8,'STS No.','BT',0,'L');
		$this->Cell(35,8,'Application Date','BT',0,'L');
		$this->Cell(35,8,'Supplier','BT',0,'L');
		$this->Cell(35,8,'Payment Mode','BT',0,'L');
		$this->Cell(37,8,'Department','BT',0,'L');
		$this->Cell(15,8,'Amount','BT',1,'L');
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

if($_GET['cmbStatus']=='O'){
	$stat = 'ON QUEUE';	
}else{
	$stat = 'UPLOADED';	
}
$pdf->status = $stat;

$arr = $reportsObj->getReleasedSTSAP($_GET['cmbCompCode'],$_GET['cmbStatus'],$_GET['txtDate']);
$pdf->Main($arr);
$pdf->Output('releasedSTSAP.pdf','D');

?>
