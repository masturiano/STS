<?
################### INCLUDE FILE #################
	session_start();
	include("../../../includes/db.inc.php");
	include("../../../includes/common.php");
	include("../allowanceObj.php");
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
	var $signatory;
	function Content($arrContract) {

		$compName = $this->compName;
		$date = ($arrContract['dateApproved'] !="") ? date("F j, Y",strtotime($arrContract['dateApproved'])):"";
		$this->Image('../../../images/ppci.jpg',70,8,70);
		$this->Ln(1);
		$this->SetFont('Courier', '', '8');
		$contNo = ($arrContract['contractNo']!="")? $arrContract['contractNo']:"XXXX";
		$Draft = ($arrContract['contractNo']!="")? "":"Draft Only";
		$this->Cell(60,8,$Draft,0,0,'L');
		$this->Cell(130,8,"Agreement Ref. No.: ".$contNo,0,0,'R');
		$this->Ln(14);
		$this->SetFont('Courier', '', '10');
		$this->Ln(13);
		$this->SetFont('Courier', 'B', '10');
		$this->Cell(15,5,"Date: ",0,0,'L');	
		$this->SetFont('Courier', '', '10');
		$this->Cell(100,5,date('M-d-Y'),0,1,'L');	
		
		$this->SetFont('Courier', 'B', '10');
		$this->Cell(30,5,"Vendor Name: ",0,0,'L');	
		$this->SetFont('Courier', '', '10');
		$this->Cell(100,5,$arrContract['suppName'],0,1,'L');
		
		$this->SetFont('Courier', 'B', '10');
		$this->Cell(21,5,"Address: ",0,0,'L');	
		$this->SetFont('Courier', '', '10');
		$this->Cell(100,5,$arrContract['add1'].' '.$arrContract['add2'].' '.$arrContract['add3'],0,1,'L');
		
		$this->SetFont('Courier', 'B', '10');
		$this->Cell(57,5,"Authorized Representative: ",0,0,'L');	
		$this->SetFont('Courier', '', '10');
		$this->Cell(100,5,$arrContract['contactPerson'],0,1,'L');
		
		$this->SetFont('Courier', 'B', '10');
		$this->Cell(23,5,"Position: ",0,0,'L');	
		$this->SetFont('Courier', '', '10');
		$this->Cell(100,5,$arrContract['contactPersonPos'],0,1,'L');
		
		$this->SetFont('Courier', 'B', '10');
		$this->Cell(38,5,"Contract Period: ",0,0,'L');	
		
		if($arrContract['nbrApplication']=='1'){
			$this->SetFont('Courier', '', '10');
			$this->Cell(100,5,date('M d, Y',strtotime($arrContract['applyDate'])). " to " .date('M d, Y',strtotime($arrContract['endDate2'])),0,1,'L');
		}else{
			$this->SetFont('Courier', '', '10');
			$this->Cell(100,5,date('M d, Y',strtotime($arrContract['applyDate'])). " to " .date('M d, Y',strtotime($arrContract['endDate'])),0,1,'L');
		}
		
		$this->SetFont('Courier', 'B', '10');
		$this->Cell(35,5,"Category and Brand: ",0,1,'L');	
		$this->SetFont('Courier', '', '8');
		
		##########################################################
		$header=array('Category','Brand','Period');
		$arrContent = $this->findBrand($arrContract['stsRefno']);
		//Column widths
		$w=array(60,60,45);
		//Header
		$this->Cell(20,6,"",0,0,'L');	
		for($i=0;$i<count($header);$i++)
			$this->Cell($w[$i],7,$header[$i],1,0,'C');
			$this->Ln();
		//Data
		foreach($arrContent as $row){
			$this->Cell(20,6,"",0,0,'L');
			$this->Cell($w[0],6,$row['category'],'LR',0,'L');
			$this->Cell($w[1],6,$row['stsBrandDesc'],'LR',0,'L');
			if($arrContract['nbrApplication']=='1'){
				$this->Cell($w[2],6,date('M Y',strtotime($arrContract['applyDate'])). " - " .date('M Y',strtotime($arrContract['endDate2'])),'LR',0,'C');
			}else{
				$this->Cell($w[2],6,date('M Y',strtotime($arrContract['applyDate'])). " - " .date('M Y',strtotime($arrContract['endDate'])),'LR',0,'C');
			}
			
			$this->Ln();
		}
		//Closure line
		$this->Cell(20,6,"",0,0,'L');
		$this->Cell(array_sum($w),0,'','T');
		##########################################################
		$this->Ln(1);
		$this->SetFont('Courier', 'B', '10');
		$this->Cell(40,5,"Total Rental Fee: ",0,0,'L');	
		$this->SetFont('Courier', '', '10');
		$this->Cell(100,5,"Php ".$arrContract['stsAmt'],0,1,'L');
		
		$this->SetFont('Courier', 'B', '10');
		$this->Cell(35,5,"Mode of Payment: ",0,0,'L');	
		$this->SetFont('Courier', '', '10');
		$this->Cell(100,5,$arrContract['payMode'],0,1,'L');
		
		
		$this->Ln(8);
		$this->SetFont('Courier', 'B', '10');
		$this->Cell(38,5,"Guidlines",0,1,'L');	
		$this->SetFont('Courier', '', '10');
		$this->Ln(1);
		$this->MultiCell(200,6,"		- Supplier is primarily responsible for the design and printing of materials.");
		$this->Ln(1);
		$this->MultiCell(200,6,"		- Sample design and materials must be presented for approval by Puregold,	
				two weeks before the installation.
");
		$this->Ln(1);
		$this->MultiCell(200,6,"		- Any changes on design must be approved by Puregold before implementation.");
		$this->Ln(1);
		$this->MultiCell(200,6,"		- Upon signing the agreement, supplier shall release a check payment on monthly basis.");
	  	$this->Ln(1);
	  	$this->MultiCell(200,6,"		- The agreement may not be terminated during approved duration. Payments 
				made shall not be converted to any other marketing activities.
");
		$this->Ln(2);
		$this->SetFont('Courier', 'B', '10');
		$this->MultiCell(200,6,"* Puregold reserve the right to revise these guidelines at any time.*");
		$this->Ln(15);
		
		
		$this->Cell(70,8,"Conforme:",'0',1,"L");
		$this->Cell(70,8,"Date:",'0',1,"L");
		
	}
	function findBrand($refNo){
		$allowanceObj2 = new allowanceObj();
		return $allowanceObj2->getDistinctCategoryBrand($refNo);
	}
}	
$allowanceObj = new allowanceObj();

$pdf=new PDF();
$pdf->FPDF($orientation='P',$unit='mm',$format='LETTER');	
$pdf->compName = 'Puregold Price Club Inc.';

$arrContractInfo = $allowanceObj->getContractInfo($_GET['refNo']);

$pdf->AddPage();	
$pdf->Content($arrContractInfo);
$pdf->Output('enhancerAgreement.pdf','D');
//$pdf->Output();
?>
