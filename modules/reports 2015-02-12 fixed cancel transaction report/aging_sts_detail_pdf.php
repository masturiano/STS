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
				
			if($trigger == 1){
				$this->SetFont('Arial', 'B', '8');
				$this->Cell(40,8,$val['suppName'],'0',1,'0');
				$this->SetFont('Arial', '', '8');
				$trigger = 0;
			}
			$this->Cell(70,8,$val['stsRefno'],'0',0,'0');
			$this->Cell(30,8,date('m/d/Y',strtotime($val['dateEntered'])),'0',0,'0');
			$this->Cell(30,8,$val['grpDesc'],'0',0,'0');
			$this->Cell(30,8,$val['fullName'],'0',0,'L');
			$this->Cell(15,8,number_format($val['stsAmt'],2),'0',1,'R');
			
			$tot += $val['stsAmt'];
			
			if($val['suppName'] != $arr[$recCtr]['suppName']){
				$trigger=1;
			}
			$recCtr++;
		}
		$this->SetFont('Arial', 'B', '8');
		$this->Cell(70,8,'','0',0,'0');
		$this->Cell(30,8,'','0',0,'0');
		$this->Cell(30,8,'','0',0,'0');
		$this->Cell(30,8,'Total','0',0,'L');
		$this->Cell(15,8,number_format($tot,2),'0',1,'R');
		unset($totDocAmt);
		///////////////
		$this->SetFont('Arial', '', '7');
		$this->Cell(200,8,'* * * * * End of Report. Nothing Follows * * * * *',0,1,'C');
		//$this->Cell(40,8,'Trans. Total = '.$transCtr,0,1,'L');
		$this->SetFont('Arial', '', '9');
	}
	
	function Header() {
		$this->Cell(45,5,'Run Date: '.$this->currentDate(),0,0,'L');
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
		$this->Cell(110,5,$typ.'Created with Over 30 Days '.$mode,0,0,'C');
		$this->Cell(45,5,'Page '.$this->PageNo().'/{nb}',0,1,'R');
		
		$this->Ln(5);
		$this->Cell(70,8,'STS Ref. No','BT',0,'L');
		$this->Cell(30,8,'Date Created','BT',0,'L');
		$this->Cell(30,8,'Group','BT',0,'L');
		$this->Cell(30,8,'Prepared By','BT',0,'L');
		$this->Cell(30,8,'Amount','BT',1,'L');
	}
	
	function Footer() {
		$prntdBy = "Printed By : ".$_SESSION['sts-fullName'];
		$this->SetY(-15);
		$this->Cell(45,5,$prntdBy,0,0,'L');
	}
	
}

$pdf=new PDF();
$pdf->FPDF($orientation='P',$unit='mm',$format='LETTER');
$pdf->AliasNbPages(); 
$agingObj = new agingObj();
$arr = $agingObj->getAgingStsDetail($_GET['type'],$_GET['payMode']);
$pdf->Main($arr);
$pdf->Output('aging_sts_details.pdf','D');

?>
