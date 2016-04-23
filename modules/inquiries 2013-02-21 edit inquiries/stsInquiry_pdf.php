<?php
	session_start();
	include("../../includes/db.inc.php");
	include("../../includes/common.php");
	include("../../includes/pdf/fpdf.php");
	include("inquiriesObj.php");
	define('FPDF_FONTPATH','../../includes/pdf/font/');
	
class PDF extends FPDF
{
	function Main($arrSTS,$upAmt,$qAmt)
	{
		$this->SetFont('Arial', '', '9');
		$this->AddPage();
		$ctr = 0 ;
		$totExpAmt = 0;
		$this->Ln(3);
		
		$this->Cell(16,9,'STS Ref #:',0,0,'L');
		$this->Cell(65,9,$arrSTS['stsRefno'],0,0,'L');
		$this->Cell(10,9,'STS #:',0,0,'L');
		$this->Cell(68,9,$arrSTS['stsNo'],0,0,'L');
		$this->Cell(17,9,'STS Date#',0,0,'L');
		$this->Cell(16,9,date('m.d.Y',strtotime($arrSTS['dateApproved'])),0,1,'L');
		
		$this->Cell(23,8,'App Start Date:',0,0,'L');
		$this->Cell(58,8,date('m.d.Y',strtotime($arrSTS['applyDate'])),0,0,'L');
		$this->Cell(22,8,'App End Date:',0,0,'L');
		$this->Cell(56,8,date('m.d.Y',strtotime($arrSTS['endDate'])),0,0,'L');
		$this->Cell(26,8,'No of Application:',0,0,'L');
		$this->Cell(0,8,$arrSTS['nbrApplication'],0,1,'L');
		
		$this->Cell(14,8,'Supplier:',0,0,'L');
		$this->Cell(145,8,$arrSTS['suppName'],0,0,'L');
		$this->Cell(22,8,'Supplier Code:',0,0,'L');
		$this->Cell(0,8,$arrSTS['suppCode'],0,1,'L');
		
		$this->Cell(14,8,'Branch:',0,0,'L');
		$this->Cell(67,8,$arrSTS['brnShortDesc'],0,0,'L');
		$this->Cell(19,8,'Contract No:',0,0,'L');
		$this->Cell(59,8,$arrSTS['contractNo'],0,0,'L');
		$this->Cell(20,8,'Department:',0,0,'L');
		$this->Cell(59,8,$arrSTS['hierarchyDesc'],0,1,'L');
		
		$this->Cell(21,8,'STS Amount:',0,0,'L');
		$this->Cell(60,8,number_format($arrSTS['stsAmt'],2),0,0,'L');
		$this->Cell(27,8,'Uploaded Amount:',0,0,'L');
		$this->Cell(51,8,number_format($upAmt,2),0,0,'L');
		$this->Cell(26,8,'Onqueue Amount:',0,0,'L');
		$this->Cell(0,8,number_format($qAmt,2),0,1,'L');
		
		$this->Cell(18,8,'Remarks:',0,0,'L');
		$this->Cell(0,8,$arrSTS['stsRemarks'],0,1,'L');
		
		$this->Cell(27,8,'Mode of Payment:',0,0,'L');
		$this->Cell(132,8,$arrSTS['payMode'],0,0,'L');
		$this->Cell(18,8,'Created By:',0,0,'L');
		$this->Cell(51,8,$arrSTS['fullName'],0,0,'L');
	}
	
	function Header() {
		$this->Cell(165,5,'Run Date: '.$this->currentDate(),0,0,'L');
		$prntdBy = "Printed By : ".$_SESSION['sts-fullName'];
		$this->Cell(45,5,$prntdBy,0,1,'L');
		$this->SetFont('Arial', 'B', '9');
		$this->Cell(195,5,'STS Details',0,0,'C');
		$this->Ln(3);
	}
	
	function Footer() {
		
	}
	
}

$pdf=new PDF();
$pdf->FPDF($orientation='P',$unit='mm',$format='LETTER');
$pdf->AliasNbPages(); 
$inqObj = new inquiriesObj();
$arrSTS = $inqObj->getSTSDet($_GET['stsNo']);
$upAmt = $inqObj->getAmt($_GET['stsNo'],'U','S');
$qAmt = $inqObj->getAmt($_GET['stsNo'],'Q','S');
$pdf->Main($arrSTS,$upAmt,$qAmt);
$pdf->Output('sts_inquiry.pdf','D');

?>
