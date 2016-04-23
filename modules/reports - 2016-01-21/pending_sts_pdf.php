<?php
	
session_start();
	include("../../includes/db.inc.php");
	include("../../includes/common.php");
	include("../../includes/pdf/fpdf.php");
	include("agingObj.php");
	define('FPDF_FONTPATH','../../includes/pdf/font/');

class PDF extends FPDF
{
	function Main($arr)
	{
		$this->SetFont('Arial', '', '8');
		$this->AddPage();
		$trigger = 1;
		$trigger2 = 1;
		$recCtr=1;
		$transCtr = 1;
		foreach($arr as $val) {
				
			$this->Cell(20,8,$val['stsRefno'],'0',0,'0');
			$this->Cell(20,8,$val['suppCode'],'0',0,'0');
			$this->Cell(30,8,number_format($val['stsAmt'],2),'0',0,'R');
			$this->Cell(20,8,'','0',0,'L');
			$this->Cell(20,8,$val['stsPaymentMode'],'0',0,'0');
			$this->Cell(30,8,date('m/d/Y',strtotime($val['dateEntered'])),'0',0,'L');
			$this->Cell(25,8,$val['userName'],'0',0,'L');
			$this->Cell(15,8,$val['stsRemarks'],'0',1,'L');
			
			$recCtr++;
		}
		///////////////
		$this->SetFont('Arial', '', '7');
		$this->Cell(200,8,'* * * * * End of Report. Nothing Follows * * * * *',0,1,'C');
		//$this->Cell(40,8,'Trans. Total = '.$transCtr,0,1,'L');
		$this->SetFont('Arial', '', '9');
	}
	
	function Header() {
		$this->Cell(45,5,'Run Date: '.$this->currentDate(),0,0,'L');
		
		$this->Cell(160,5,'Pending STS',0,0,'C');
		$this->Cell(45,5,'Page '.$this->PageNo().'/{nb}',0,1,'R');
		
		$this->Ln(5);
		$this->Cell(20,8,'STS Ref. No','BT',0,'L');
		$this->Cell(30,8,'Supp Code','BT',0,'L');
		$this->Cell(30,8,'STS Amount','BT',0,'L');
		$this->Cell(30,8,'Payment Mode','BT',0,'L');
		$this->Cell(30,8,'Date Entered','BT',0,'L');
		$this->Cell(80,8,'Entered By','BT',0,'L');
		$this->Cell(30,8,'Remarks','BT',1,'L');
	}
	
	function Footer() {
		$prntdBy = "Printed By : ".$_SESSION['sts-fullName'];
		$this->SetY(-15);
		$this->Cell(45,5,$prntdBy,0,0,'L');
	}
	
}

$pdf=new PDF();
$pdf->FPDF($orientation='L',$unit='mm',$format='LETTER');
$pdf->AliasNbPages(); 
$agingObj = new agingObj();
$arr = $agingObj->getPendingSts();
$pdf->Main($arr);
$pdf->Output('peding_sts.pdf','D');

?>
