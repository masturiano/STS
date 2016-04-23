<?php
	session_start();
	include("../../../includes/db.inc.php");
	include("../../../includes/common.php");
	include("../allowanceObj.php");
	include("../../../includes/pdf/fpdf.php");
	define('FPDF_FONTPATH','../../../includes/pdf/font/');
	
class PDF extends FPDF
{
	var $arrEvent;
	var $arrBranch;
	var $arrStrFunds;
	function Main($arrBrand)
	{
		$this->SetFont('Courier', '', '10');
		$this->AddPage();
		$ctr = 0 ;
		$allowanceObj = new allowanceObj();
			$this->Ln(3);
			foreach ($arrBrand as $valBrand) {
				$this->SetFont('Courier', 'B', '10');
				$this->Cell(20,5,'Category: ',0,0,'L');
				$this->SetFont('Courier', '', '10');
				$this->Cell(10,5,$valBrand['category'],0,1,'L');
				
				$this->SetFont('Courier', 'B', '10');
				$this->Cell(20,5,'Brand: ',0,0,'L');
				$this->SetFont('Courier', '', '10');
				$this->Cell(10,5,$valBrand['stsBrandDesc'],0,1,'L');
				
				$this->SetFont('Courier', '', '10');
				$arrPar = $allowanceObj->getBrandParticipants($valBrand['stsRefno'],$valBrand['brandCode']);
				/*foreach ($arrPar as $valH) {
					
					$this->Cell(10,5,' '.$valH['brnDesc'],0,0,'L');
					$this->Cell(40,5,'',0,0,'L');
					$this->Cell(25,5,$valH['enhanceDesc'],0,1,'L');
					$this->SetFont('Courier', '', '10');
				}
				*/
				$header=array('Store','Shelf Enhancer');
				
				//Column widths
				$w=array(80,60);
				//Header
				$this->Cell(30,6,"",0,0,'L');	
				for($i=0;$i<count($header);$i++)
					$this->Cell($w[$i],7,$header[$i],1,0,'C');
					$this->Ln();
				//Data
				foreach($arrPar as $row){
					$this->Cell(30,6,"",0,0,'L');
					$this->Cell($w[0],6,$row['brnDesc'],'LR',0,'L');
					$this->Cell($w[1],6,$row['enhanceDesc'],'LR',1,'L');
					//$this->Ln();
				}
				//Closure line
				$this->Cell(30,6,"",0,0,'L');
				$this->Cell(array_sum($w),0,'','T');
				##########################################3
				$this->Ln(3);
			}
				
				
			$this->SetFont('Arial', '', '7');
			//$this->Cell(238,8,'* * * * * End of Report. Nothing Follows * * * * ',0,1,'C');
			$this->SetFont('Arial', '', '9');
	}
	
	function Header() {
		$this->SetFont('Courier', 'B', '10');
		$this->Cell(25,5,'',0,0,'L');
		$cont = ($this->contractNo['contractNo']!="")? $this->contractNo['contractNo']:"XXXX";
		$this->Cell(158,5,'Agreement Reference Number: '.$cont,0,1,'C');
		$this->Cell(25,5,'Run Date: '.$this->currentDate(),0,0,'L');
		$this->Cell(158,5,'',0,0,'C');
		$this->Cell(15,5,'Page '.$this->PageNo().'/{nb}',0,1,'R');
		$this->Ln(5);
		/*$this->Cell(20,8,'Ref. No.','BT',0,'L');
		$this->Cell(35,8,'Vendor','BT',0,'L');
		$this->Cell(23,8,'Amount','BT',0,'L');
		$this->Cell(25,8,'Date Ent.','BT',0,'L');
		$this->Cell(37,8,'App. Start Date-No.','BT',0,'L');
		$this->Cell(35,8,'Mode of Payment','BT',0,'L');
		$this->Cell(40,8,'Contract No.','BT',0,'L');
		$this->Cell(45,8,'Remarks','BT',1,'L');*/
		
	}
	/*function Footer() {
		$prntdBy = "Printed By : ".$_SESSION['sts-fullName'];
		$this->SetY(-15);
		$this->Cell(45,5,$prntdBy,0,0,'L');
	}*/
	
}

$pdf=new PDF();
$pdf->FPDF($orientation='P',$unit='mm',$format='LETTER');
$pdf->AliasNbPages(); 
$allowanceObj = new allowanceObj();
$pdf->contractNo = $allowanceObj->getSTSInfo($_GET['refNo']);
$arrTran = $allowanceObj->getDistinctCategoryBrand($_GET['refNo']);
$pdf->Main($arrTran);
$pdf->Output('enhancerAttachment.pdf','D');

?>
