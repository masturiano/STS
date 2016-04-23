<?php
	session_start();
	include("../../includes/db.inc.php");
	include("../../includes/common.php");
	include("../../includes/pdf/fpdf.php");
	include("reportsObj.php");
	define('FPDF_FONTPATH','../../includes/pdf/font/');
	
class PDF extends FPDF
{
	function Main($arr)
	{
		$this->SetFont('Arial', '', '9');
		$this->AddPage();
		$reportsObj = new reportsObj();
			foreach ($arr as $val) {
				$this->SetFont('Arial', '', '9');
					$this->Cell(30,5,' '.$val['InvNo'],0,0,'L');
					$this->Cell(10,5,'',0,0,'L');
					$this->Cell(20,5,number_format($val['stsApplyAmt'],2),0,0,'R');
					$this->Cell(10,5,'',0,0,'L');
					$this->Cell(60,5,' '.substr($val['Supplier'],0,25),0,0,'L');
					$this->Cell(50,5,' '.$val['Store'],0,0,'L');
					$this->Cell(30,5,'',0,0,'L');
					$this->Cell(50,5,' '.$val['hierarchyDesc'],0,0,'L');
					$this->Cell(30,5,date('m/d/Y',strtotime($val['stsApplyDate'])),0,0,'L');
					$this->Cell(10,5,'',0,0,'L');
					$this->Cell(30,5,date('m/d/Y',strtotime($val['uploadDate'])),0,1,'L');
				}
					
			$this->SetFont('Arial', '', '7');
			$this->Cell(258,8,'* * * * * End of Report. Nothing Follows * * * * ',0,1,'C');
			$this->SetFont('Arial', '', '9');
	}
	
	function Header() {
		$this->Cell(45,5,'Run Date: '.$this->currentDate(),0,0,'L');
		if($_GET['cmbTran'] == '1'){
			$type = 'Regular STS';
		}elseif($_GET['cmbTran'] == '2'){
			$type = 'Listing Fee';	
		}elseif($_GET['cmbTran'] == '3'){
			$type = 'Promo Fund';	
		}elseif($_GET['cmbTran'] == '4'){
			$type = 'Shelf Enhancer';	
		}elseif($_GET['cmbTran'] == '5'){
			$type = 'Display Allowance';	
		}else{
			$type = "All STS Type";
		}
			//$_GET['cmbComp']
		$this->Cell(65,5,'',0,0,'L');
		$this->Cell(130,5,'Uploaded STS Transmittal Report From '.$_GET['txtDateFrom'].' to '.$_GET['txtDateTo'],0,0,'C');
		$this->Cell(48,5,'',0,0,'L');
		$this->Cell(35,5,'Page '.$this->PageNo().'/{nb}',0,1,'R');

		$this->Cell(45,5,'ReportID: STS002',0,0,'L');
		$this->Cell(65,5,'',0,0,'L');
		$this->Cell(130,5,$_GET['cmbComp'],0,0,'C');
		$this->Cell(45,5,'',0,0,'L');
		$this->Cell(69,5,'Type : '.$type,0,1,'C');

		$this->Ln(5);
		$this->Cell(40,8,'Invoice No.','BT',0,'L');
		$this->Cell(45,8,'Amount','BT',0,'L');
		$this->Cell(70,8,'Supplier','BT',0,'L');
		$this->Cell(60,8,'Store','BT',0,'L');
		$this->Cell(45,8,'Hierarchy','BT',0,'L');
		$this->Cell(40,8,'Apply Date','BT',0,'L');
		$this->Cell(20,8,'Upload Date','BT','0','L');
		$this->Cell(13,8,'','BT','1','L');
	}
	
	function Footer() {
		$prntdBy = "Printed By : ".strtoupper($_SESSION['sts-fullName']);
		$this->SetY(-15);
		$this->Cell(45,5,$prntdBy,0,0,'L');
	}
	
}

$pdf=new PDF();
$pdf->FPDF($orientation='L',$unit='mm',$format='LEGAL');
$pdf->AliasNbPages(); 
$reportsObj = new reportsObj();
$arrTran = $reportsObj->uploadedTransmittal($_GET['cmbType'], $_GET['cmbTran'], $_GET['cmbComp'],$_GET['txtDateFrom'],$_GET['txtDateTo']);
$pdf->Main($arrTran);
$pdf->Output('transmittal.pdf','D');
?>