<?
################### INCLUDE FILE #################
	session_start();
	include("../../../includes/db.inc.php");
	include("../../../includes/common.php");
	include("../daObj.php");
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

		$daObj = new daObj();
		$arrComp = $daObj->getMaxComp($_GET['refNo'],$_GET['strCode']);
		$compName = $this->compName;
		$date = ($arrContract['dateApproved'] !="") ? date("F j, Y",strtotime($arrContract['dateApproved'])):"";
		$cancelDate = ($arrContract['cancelDate'] !="") ? date("F j, Y",strtotime($arrContract['cancelDate'])):"";
		$effectivityDate = ($arrContract['effectivityDate'] !="") ? date("F j, Y",strtotime($arrContract['effectivityDate'])):"";
		
		if($arrComp == 809){
			$this->Image('../../../images/compE.jpg',100,8,20);
		}else{
			$this->Image('../../../images/ppci.jpg',70,8,70);
		}
		//$this->Image('../../../images/parco.jpg',86,8,50);
		//$this->Image('../../../images/ppci.jpg',70,8,70);
		$this->Ln(9);
		$this->SetFont('Arial', '', '11');
		$contNo = ($arrContract['stsNo']!="")? $arrContract['contractNo']:"XXXX";
		$Draft = ($arrContract['stsNo']!="")? "TSD ".$arrContract['stsNo']:"Draft Only";
		if($arrContract['dtlStatus']=='C'){
			$dtlStatusP = "CANCELLED: ".$cancelDate;
			$effectivityDateP =  "Effectivity Date: ".$effectivityDate;
		}else{
			$dtlStatusP = "";
		}
		
		$this->Cell(190,8,$dtlStatusP,0,1,'R');
		$this->Cell(190,8,$effectivityDateP,0,1,'R');
		
		$this->Cell(60,8,$Draft,0,0,'L');
		
		$this->Cell(130,8,"Contract No.: ".$contNo,0,1,'R');
		$this->Ln(2);
		$this->SetFont('Arial', 'B', '13');
		
		$this->Cell(200,7,$arrContract['brnDesc'],0,1,'C');
		
		
		
		//$this->Ln(14);
	
		$this->SetFont('Arial', '', '11');
		$this->Ln(8);
		$this->SetFont('Arial', 'B', '12');
		$this->Cell(65,5,"Date: ",0,0,'L');	
		$this->SetFont('Arial', '', '11');
		$this->Cell(100,5,date('M-d-Y'),0,1,'L');	
		
		$this->SetFont('Arial', 'B', '12');
		$this->Cell(65,5,"Company Name: ",0,0,'L');	
		$this->SetFont('Arial', '', '11');
		$this->Cell(100,5,$arrContract['suppName'],0,1,'L');
		
		$this->SetFont('Arial', 'B', '12');
		$this->Cell(65,5,"Address: ",0,0,'L');	
		$this->SetFont('Arial', '', '11');
		$this->Cell(100,5,$arrContract['add1'].' '.$arrContract['add2'].' '.$arrContract['add3'],0,1,'L');
		
		$this->SetFont('Arial', 'B', '12');
		$this->Cell(65,5,"Authorized Representative: ",0,0,'L');	
		$this->SetFont('Arial', '', '11');
		$this->Cell(100,5,$arrContract['contactPerson'],0,1,'L');
		
		$this->SetFont('Arial', 'B', '12');
		$this->Cell(65,5,"Position: ",0,0,'L');	
		$this->SetFont('Arial', '', '11');
		$this->Cell(100,5,$arrContract['contactPersonPos'],0,1,'L');
		
		$this->SetFont('Arial', 'B', '12');
		$this->Cell(65,5,"Contract Period: ",0,0,'L');	
		
		if($arrContract['nbrApplication']=='1'){
			$this->SetFont('Arial', '', '11');
			$this->Cell(100,5,date('M d, Y',strtotime($arrContract['applyDate'])). " to " .date('M d, Y',strtotime($arrContract['endDate'])),0,1,'L');
		}else{
			$this->SetFont('Arial', '', '11');
			$this->Cell(100,5,date('M d, Y',strtotime($arrContract['applyDate'])). " to " .date('M d, Y',strtotime($arrContract['endDate'])),0,1,'L');
		}
		
		$this->SetFont('Arial', 'B', '12');
		$this->Cell(65,5,"Product Group: ",0,0,'L');	
		$this->SetFont('Arial', '', '11');
		$this->Cell(100,5,$arrContract['grpDesc'],0,1,'L');
		
		$this->SetFont('Arial', 'B', '12');
		$this->Cell(65,5,"Display Type: ",0,0,'L');	
		$this->SetFont('Arial', '', '11');
		$this->Cell(100,5,$arrContract['displayTypeDesc'],0,1,'L');
		
		$this->SetFont('Arial', 'B', '12');
		$this->Cell(65,5,"Brand Name: ",0,0,'L');	
		$this->SetFont('Arial', '', '11');
		$this->Cell(100,5,$arrContract['brand'],0,1,'L');
		
		$this->SetFont('Arial', 'B', '12');
		$this->Cell(65,5,"Location: ",0,0,'L');	
		$this->SetFont('Arial', '', '11');
		$this->Cell(100,5,$arrContract['location'],0,1,'L');
		
		$this->SetFont('Arial', 'B', '12');
		$this->Cell(65,5,"Size Specifications: ",0,0,'L');	
		$this->SetFont('Arial', '', '11');
		$this->Cell(100,5,$arrContract['sizeSpecsDesc'],0,1,'L');
		
		$this->SetFont('Arial', 'B', '12');
		$this->Cell(65,5,"Display Specifications: ",0,0,'L');	
		$this->SetFont('Arial', '', '11');
		$this->Cell(100,5,$arrContract['displaySpecsDesc'],0,1,'L');
		
		$this->Ln(1);
		$this->SetFont('Arial', 'B', '12');
		$this->Cell(65,5,"Monthly Trade Support Discount ",0,1,'L');	
		$this->Cell(65,5,"     per Unit: ",0,0,'L');	
		$this->SetFont('Arial', '', '11');
		$this->Cell(100,5,"Php ".number_format($arrContract['perUnitAmt'],2),0,1,'L');
		
		$this->Ln(1);
		$this->SetFont('Arial', 'B', '12');
		$this->Cell(65,5,"Number of Availed Unit: ",0,0,'L');	
		$this->SetFont('Arial', '', '11');
		$this->Cell(100,5,$arrContract['noUnits'],0,1,'L');
		
		$this->Ln(1);
		$this->SetFont('Arial', 'B', '12');
		$this->Cell(65,5,"Total Trade Support Discount ",0,1,'L');	
		$this->Cell(65,5,"     per Month: ",0,0,'L');	
		$this->Cell(100,5,"Php ".number_format($arrContract['stsAmtDa'],2),0,1,'L');
		
		$this->SetFont('Arial', 'B', '12');
		$this->Cell(65,5,"Mode of Payment: ",0,0,'L');	
		$this->SetFont('Arial', '', '11');
		$this->Cell(100,5,$arrContract['payMode'],0,1,'L');
		
		$this->SetFont('Arial', 'B', '12');
		$this->Cell(65,5,"Remarks: ",0,0,'L');	
		$this->SetFont('Arial', '', '11');
		$this->Cell(100,5,$arrContract['daRemarks'],0,1,'L');
		
		$this->Ln(3);
		$this->SetFont('Arial', 'B', '12');
		$this->Cell(38,5,"Other Terms and Conditions",0,1,'L');	
		$this->SetFont('Arial', '', '11');
		$this->Ln(1);
		$this->MultiCell(200,4,"1. Above amount is a conditional sales discount granted  for marketing support activities for the purpose of increasing sales. ");
		$this->Ln(1);
		$this->MultiCell(200,4,"2. The company agrees to maintain cleanliness, orderliness, attractive and appealing visual standards in the display area.");
		$this->Ln(1);
		$this->MultiCell(200,4,"3. Only items currently carried by Puregold in its product mix should be included in the display. New items to feature and/or for inclusion should have the prior approval of Merchandising Department and coordination with the Store Manager.");
		$this->Ln(1);
		$this->MultiCell(200,4,"4. Product signages must be in consonance and harmony with the Puregold product tags and signages. Other merchandising and advertising collaterals should have the prior approval of Merchandising and coordination with the Store Manager");
	  	$this->Ln(1);
	  	$this->MultiCell(200,4,"5. This agreement may be terminated at any time by either of the parties, provided that a written notice is served thirty (30) days before the requested termination date.");
		$this->Ln(1);
		$this->MultiCell(200,4,"6. This agreement is renewable at the end of the agreement period in favor of the company. However, Puregold reserves the right to grant the display area to the other interested parties, if the company fails to confirm the new agreement thirty (30) days before the current agreement period.");
		$this->Ln(2);
		
		$this->Ln(3);
		
		$this->SetFont('Arial', '', '9');
		$this->Cell(70,8,"Prepared by: ".$arrContract['fullName'],'0',0,"L");
		$this->Cell(60,8,"Approved by: ".$arrContract['approvedBy'],'0',0,"L");
		$this->Cell(70,8,"Conforme: __________________________",'0',0,"R");
		//$this->Cell(180,8,"",'0',0,"L");
		
	}
	
}	
$daObj = new daObj();

$pdf=new PDF();
$pdf->FPDF($orientation='P',$unit='mm',$format='LETTER');	
$pdf->compName = 'Puregold Price Club Inc.';

$arrContractInfo = $daObj->getDaContract($_GET['refNo'],$_GET['strCode']);

$pdf->AddPage();	
$pdf->Content($arrContractInfo);
$pdf->Output('enhancerAgreement.pdf','D');
//$pdf->Output();
?>
