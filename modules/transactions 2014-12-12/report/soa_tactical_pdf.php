<?
################### INCLUDE FILE #################
	session_start();
	include("../../../includes/db.inc.php");
	include("../../../includes/common.php");
	include("../tacticalObj.php");
	include("../../../includes/pdf/fpdf.php");
	define('FPDF_FONTPATH','../../../includes/pdf/font/');
	
################ GET TOTAL RECORDS ###############

############################ LETTER/LEGAL PORTRATE TOTAL WIDTH = 200
############################ LETTER LANDSCAPE TOTAL WIDTH = 265
############################ LEGAL LANDSCAPE TOTAL WIDTH = 310
####################### FOOTER LANDSCAPE LETTER AND LEGAL = 180
####################### FOOTER PORTRATE LETTER ONLY       = 260
####################### HEADER 10.0012

class PDF extends FPDF
{
	
	function Content($arrSOA) {
		
		$compName = $this->compName;

		$this->Ln(1);

		$this->SetFont('Helvetica', '', '9');
		$this->Cell(60,4,"",0,0,'L');
		$this->Cell(62,4,"Please present this upon payment",0,1,'R');
		$this->SetFont('Helvetica', '', '10');
		$this->Cell(193,4,"$compName",0,1,"C");
		$this->SetFont('Helvetica', '', '9');
		$this->Cell(60,4,"",0,0,'L');
		$this->Cell(90,4,"Bldg. 1, Tabacalera Compound, No. 900 D. Romualdez St. Paco Manila",0,1,'R');
		
		
		$this->Ln(9);
		$this->SetFont('Helvetica', 'B', '12');
		$this->Cell(197,6,"B I L L I N G   S T A T E M E N T",0,1,'C');
		$this->Ln(10);
		$this->SetFont('Helvetica', '', '8');
		$this->Cell(16,4,"SUPPLIER: ______________________________",0,0,'L');
		$this->SetFont('Helvetica', '', '8');
		$this->Cell(25,4,$arrSOA['asname'],0,1,'L');
		$this->Cell(10,8,"CODE: _________________________",0,0,'L');
		$this->Cell(1,8,$arrSOA['suppCode'],0,1,'L');
		
		$this->SetFont('Helvetica', '', '8');
		$this->Cell(39,4,"BS REF:",1,0,'C',0);
		$this->Cell(39,4,"STATEMENT DATE:",1,0,'C',0);
		$this->Cell(39,4,"GROUP:",1,0,'C',0);
		$this->Cell(39,4,"DUE DATE:",1,0,'C',0);
		$this->Cell(39,4,"TOTAL AMOUNT DUE:",1,1,'C',0);
		
		$this->SetFont('Helvetica', '', '8');
		$this->Cell(39,4,$arrSOA['typePrefix'].$arrSOA['stsRefno'].' - '.$arrSOA['stsSeq'],1,0,'C',0);
		$this->Cell(39,4,date('F d, Y',strtotime($arrSOA['dateEntered'])),1,0,'C',0);
		$this->Cell(39,4,$arrSOA['deptDesc'],1,0,'C',0);
		$this->Cell(39,4,date('F d, Y',strtotime($arrSOA['applyDate'])),1,0,'C',0);
		$this->Cell(39,4,number_format($arrSOA['stsApplyAmt'],2),1,1,'C',0);
		
		$this->Ln(10);
		$this->SetFont('Helvetica', '', '9');
		$this->Cell(25,4,"PARTICULARS: ____________________________________________________________",0,0,'L');
		$this->Cell(30,4,$arrSOA['stsRemarks'],0,1,'L');
		//$arrSOA['stsRemarks']
		
		$this->Ln(11);
		$this->SetFont('Helvetica', 'BI', '8');
		$this->Cell(25,4,"DETAILS:",0,1,'L');
		
		$this->SetFont('Helvetica', '', '8');
		$this->Cell(39,4,"INVOICE NO.",0,0,'L');
		$this->Cell(66,4,"STORE",0,0,'L');
		$this->Cell(30,4,"AMOUNT",0,0,'L');
		$this->Cell(30,4,"VAT AMOUNT",0,0,'L');
		$this->Cell(30,4,"TOTAL AMOUNT",0,1,'L');
		
	}
	
	function Content2($arrSOA2) {
	
		foreach($arrSOA2 as $val){
			$this->SetFont('Helvetica', '', '8');
			$this->Cell(39,4,$val['stsNo'],0,0,'L');
			$this->Cell(66,4,$val['strNam'],0,0,'L');
			$this->Cell(30,4,number_format($val['stsApplyAmt'],2),0,0,'L');
			$this->Cell(30,4,number_format($val['stsVatAmt'],2),0,0,'L');
			$this->Cell(30,4,number_format($val['stsTotAmt'],2),0,1,'L');
			
			$totStsApplyAmt += $val['stsApplyAmt'];
			$totStsVatAmt += $val['stsVatAmt'];
			$stsTotAmt += $val['stsTotAmt'];
		}
		
		$this->SetFont('Helvetica', 'B', '9');
		$this->Cell(105,4,"TOTAL AMOUNT",0,0,'L');
		$this->Cell(30,4,number_format($totStsApplyAmt,2),0,0,'L');
		$this->Cell(30,4,number_format($totStsVatAmt,2),0,0,'L');
		$this->SetFont('Helvetica', 'BU', '9');
		$this->Cell(30,4,number_format($stsTotAmt,2),0,1,'L');
	}
	
	function Content3($arrSOA) {
		
		$this->Ln(14);
		$this->SetFont('Helvetica', 'IB', '7');
		$this->Cell(25,4,"Reminders:",0,1,'L');
		$this->SetFont('Helvetica', 'I', '7');
		$this->MultiCell(195,4,"1. Please examine your Billing Statement immediately. For inquiries please call Merchandising Dept. at 524-4351.",0,1,'L');
		$this->Cell(70,4,"2. For those with EWT, kindly submit your BIR form 2307/2306",0,0,'L');
		$this->SetFont('Helvetica', 'UI', '7');
		$this->Cell(38,4,"within 30 days from the due date,",0,0,'L');
		$this->SetFont('Helvetica', 'I', '7');
		$this->Cell(195,4,"failure to comply, the withholding tax deducted, will be charged",0,1,'L');
		$this->Cell(195,4,"back thru Invoice Deduction, without notice. Please ignore if not applicable.",0,1,'L');
		$this->Cell(73,4,"3. Please settle your account on or before the due date otherwise,",0,0,'L');
		$this->SetFont('Helvetica', 'UI', '7');
		$this->Cell(38,4,"we will apply this account for Invoice Deduction, including VAT amount, if any, after 30 days without further notice.",0,1,'L');
		$this->SetFont('Helvetica', 'I', '7');
		$this->MultiCell(195,4,"4. Please directly handover your payment to authorized PUREGOLD TRS-HO personnel only, ask for Official Receipt or Collection Receipt.",0,1,'L');
		$this->MultiCell(195,4,"5. This is a system generated Billing Statement not requiring manual signature.",0,1,'L');
		
		$this->SetFont('Helvetica', '', '7');
		$this->Cell(195,10,"Prepared by: ".$arrSOA['fullName'],0,1,'L');
		   
	}
	
	function Footer() {
		 //Page number
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	
}	

$tactObj = new tactObj();

$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->FPDF($orientation='P',$unit='mm',$format='LETTER');	
$pdf->compName = 'Puregold Price Club Inc.';

$arrFundsInfo = $tactObj->getSOAInfo($_GET['refNo'],$_GET['seq']);
$arrFundsInfoArr = $tactObj->getSOAInfo2($_GET['refNo'],$_GET['seq']);

$pdf->AddPage();	

$pdf->Content($arrFundsInfo);
$pdf->Content2($arrFundsInfoArr);
$pdf->Content3($arrFundsInfo);
$pdf->Output('SOA.pdf','D');
?>
