<?php
	session_start();
	include("../../includes/db.inc.php");
	include("../../includes/common.php");
	include("../../includes/pdf/fpdf.php");
	include("reportsObj.php");
	define('FPDF_FONTPATH','../../includes/pdf/font/');
	
class PDF extends FPDF
{
	
	function Main()
	{
		$this->SetFont('Arial', '', '9');
		$this->AddPage();
		$ctr = 0 ;
		$reportsObj = new reportsObj();
			$this->Ln(3);
		
				$arrH = $reportsObj->stsSummaryUnapproved($this->tran,$this->dtFrom,$this->dtTo,$this->group,$this->suppCode);
				foreach ($arrH as $valH) {
					$ctr++;
					$this->Cell(15,5,$valH['stsRefno'],0,0,'L');
					$this->Cell(20,5,$valH['nbrApplication'],0,0,'L');
					$this->Cell(50,5,$valH['strCode']."-".$valH['brnDesc'],0,0,'L');
					$this->Cell(25,5,$valH['suppCode']."-".substr($valH['suppName'],0,17),0,0,'L');
					$this->Cell(10,5,'',0,0,'L');
					$this->Cell(40,5,number_format($valH['stsAmt'],2),0,0,'R');
					$this->Cell(4,5,'',0,0,'L');
					$this->Cell(20,5,date('m/d/Y',strtotime($valH['dateEntered'])),0,0,'L');
					$this->Cell(30,5,$valH['userName'],0,0,'L');
					$this->Cell(30,5,$valH['payMode'],0,0,'L');
					$this->Cell(35,5,substr($valH['dept'],0,4)."-".substr($valH['cls'],0,4)."-".substr($valH['subCls'],0,4),0,0,'L');
					$this->Cell(58,5,substr($valH['stsRemarks'],0,45),0,1,'L');
					$grandAmt += $valH['stsAmt'];
					$this->SetFont('Arial', '', '9');
			}
				
				$this->SetFont('Arial', 'B', '9');
				$this->Cell(90,5,'',0,0,'L');
				$this->Cell(30,5,"Grand Total",0,0,'R');
				$this->Cell(40,5,number_format($grandAmt,2),0,1,'R');
				$this->SetFont('Arial', '', '9');
				$this->Cell(25,5,'Number of record: '.$ctr,0,1,'L');
			
					
			$this->SetFont('Arial', '', '7');
			$this->Cell(288,8,'* * * * * End of Report. Nothing Follows * * * * ',0,1,'C');
			$this->SetFont('Arial', '', '9');
	}
	
	function Header() {
		
		$this->Cell(65,5,'Run Date: '.$this->currentDate(),0,0,'L');
		if($_GET['cmbTran'] == '1'){
			$type = 'Regular STS';
		}elseif($_GET['cmbTran'] == '2'){
			$type = 'Listing Fee';	
		}elseif($_GET['cmbTran'] == '4'){
			$type = 'Shelf Enhancer';	
		}elseif($_GET['cmbTran'] == '4'){
			$type = 'Display Allowance';	
		}else{
			$type = "All STS Type";
		}
		$this->Cell(198,5,'Unapproved STS Summary Report',0,0,'C');
		$this->Cell(35,5,'Group: '.$this->grpName,0,1,'R');
		$this->Cell(65,5,'ReportID: STS005',0,0,'L');
		$this->Cell(198,5,$type.': '.$_GET['txtDateFrom'].' - '.$_GET['txtDateTo'],0,0,'C');
		$this->Cell(35,5,'Page '.$this->PageNo().'/{nb}',0,1,'R');
		$this->Ln(5);
		$this->Cell(20,8,'STS Ref','BT',0,'L');
		$this->Cell(35,8,'STS No-App','BT',0,'L');
		$this->Cell(45,8,'Store','BT',0,'L');
		$this->Cell(43,8,'Vendor','BT',0,'L');
		$this->Cell(17,8,'Amount','BT',0,'L');
		$this->Cell(25,8,'Date Entered','BT',0,'L');
		$this->Cell(28,8,'Entered By','BT',0,'L');
		$this->Cell(28,8,'Mode of Payment','BT',0,'L');
		$this->Cell(45,8,'Transaction Type','BT',0,'L');
		$this->Cell(50,8,'Remarks','BT',1,'L');
		
	}
	function Footer() {
		$prntdBy = "Printed By : ".$_SESSION['sts-fullName'];
		$this->SetY(-15);
		$this->Cell(45,5,$prntdBy,0,0,'L');
	}
	
}

$pdf=new PDF();
$pdf->FPDF($orientation='L',$unit='mm',$format='LEGAL');
$pdf->AliasNbPages(); 
$reportsObj = new reportsObj();
$pdf->tran = $_GET['cmbTran'];
$pdf->dtFrom = $_GET['txtDateFrom'];
$pdf->dtTo = $_GET['txtDateTo'];
$pdf->group = $_SESSION['sts-grpCode'];
$pdf->suppCode = $_GET['cmbSupp'];
$grpName = $reportsObj->findGroupName($_SESSION['sts-grpCode']);
$pdf->grpName = $grpName;
$pdf->Main();
$pdf->Output('unapproved_STS_Summary.pdf','D');

?>
