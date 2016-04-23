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
				
				$this->Cell(37,8,$val['grpDesc'],'0',0,'0');
				$this->Cell(5,8,$val['transact'],'0',0,'R');
				$this->Cell(5,8,'','0',0,'L');
				$this->Cell(28,8,number_format($val['amount'],2),'0',0,'R');
				$this->Cell(29,8,number_format($val['over30'],2),'0',0,'R');
				$this->Cell(29,8,number_format($val['over60'],2),'0',0,'R');
				$this->Cell(28,8,number_format($val['over90'],2),'0',0,'R');
				$this->Cell(12,8,'','0',0,'L');
				$this->Cell(20,8,'','B',1,'L');
				
				$tran += $val['transact'];
				$totFund += $val['amount'];
				$tot30 += $val['over30'];
				$tot60 += $val['over60'];
				$tot90 += $val['over90'];
				
				$recCtr++;
			}
		$this->SetFont('Arial', 'B', '8');
		$this->Cell(37,8,'Total','0',0,'0');
		$this->Cell(5,8,$tran,'0',0,'R');
		$this->Cell(5,8,'','0',0,'L');
		$this->Cell(28,8,number_format($totFund,2),'0',0,'R');
		$this->Cell(29,8,number_format($tot30,2),'0',0,'R');
		$this->Cell(29,8,number_format($tot60,2),'0',0,'R');
		$this->Cell(28,8,number_format($tot90,2),'0',0,'R');
		///////////////
		$this->SetFont('Arial', '', '7');
		$this->Cell(200,8,'* * * * * End of Report. Nothing Follows * * * * *',0,1,'C');
		//$this->Cell(40,8,'Trans. Total = '.$transCtr,0,1,'L');
		$this->SetFont('Arial', '', '9');
	}
	
	function Header() {
		if($_GET['type']=="DA"){
			$typ = "DAC ";	
		}else{
			$typ = "STS ";	
		}
		if($_GET['payMode']=="D"){
			$mode = "INVOICE DEDUCTION ";	
		}else{
			$mode = "COLLECTION ";	
		}
		$this->Cell(45,5,'Run Date: '.$this->currentDate(),0,0,'L');
		$this->Cell(110,5,$typ .'Aging Summary '.$mode,0,0,'C');
		$this->Cell(45,5,'Page '.$this->PageNo().'/{nb}',0,1,'R');
		
		$this->Ln(5);
		$this->Cell(30,8,'Group','BT',0,'L');
		$this->Cell(32,8,'# of Transact','BT',0,'L');
		$this->Cell(29,8,'Current','BT',0,'L');
		$this->Cell(29,8,'Over 30','BT',0,'L');
		$this->Cell(29,8,'Over 60','BT',0,'L');
		$this->Cell(29,8,'Over 90','BT',0,'L');
		$this->Cell(21,8,'Remarks','BT',1,'L');
	}
	
	function Footer() {
		$prntdBy = "Printed By : ".$_SESSION['username'];
		$this->SetY(-15);
		$this->Cell(45,5,$prntdBy,0,0,'L');
	}
	
}

$pdf=new PDF();
$pdf->FPDF($orientation='P',$unit='mm',$format='LETTER');
$pdf->AliasNbPages(); 
$agingObj = new agingObj();
$arr = $agingObj->getAgingStsSumm($_GET['type'],$_GET['payMode']);
$pdf->Main($arr);
$pdf->Output('aging_sts_summ.pdf','D');

?>
