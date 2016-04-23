<?
################### INCLUDE FILE #################
	session_start();
	include("../../../includes/db.inc.php");
	include("../../../includes/common.php");
	include("../transObj.php");
	include("../../../includes/pdf/fpdf.php");
	define('FPDF_FONTPATH','../../../includes/pdf/font/');

class PDF extends FPDF
{

	function Content() {
		$transObj = new transObj();
		$compName = $this->compName;
		
		$arrStsPar = $transObj->getRegStsParticipants($_GET['refNo']);
		
		$this->SetFont('Arial', '', '6');
		
		##########################################################
		
		//Column widths
		$w=array(30,50,35);
		
		//Data
		foreach($arrStsPar as $row){
			$this->Cell(40,6,"",0,0,'L');
			$this->Cell($w[0],6,$row['stsNo'],'LR',0,'C');
			$this->Cell($w[1],6,$row['strCode']." - ".$row['brnShortDesc'],'LR',0,'L');
			$this->Cell($w[2],6,number_format($row['stsAmt'],2),'LR',0,'R');
			$this->Ln();
		}
		//Closure line
		$arrSTS = $transObj->getSTSPrint($_GET['refNo']);
		$this->Cell(40,6,"",0,0,'L');
		$this->Cell(array_sum($w),0,'','T');
		##########################################################
		$this->Ln(10);
		$this->Cell(75,5,'Supplier Conforme: ',0,0,'L');
		$this->Cell(80,5,'Prepared By:',0,0,'L');
		$this->Cell(35,5,'Approved By: ',0,1,'L');
		$this->SetFont('Arial', 'U', '8');
		$this->Cell(75,5,'___________________ ',0,0,'L');
		$this->Cell(80,5,''.$arrSTS['enteredBy'],0,0,'L');
		$this->Cell(35,5,''.$arrSTS['approvedBy'],0,1,'L');
		
	}
	function Header() {
		$transObj = new transObj();
		
		$arrSTS = $transObj->getSTSPrint($_GET['refNo']);
		if(trim($arrSTS['approvedBy']) != ''){
			$count = $transObj->checkIfPrinted($_GET['refNo']);
			if( (int)$count > 0){
				$transObj->tagPrinted($_GET['refNo']);
			}else{
				$transObj->tagRePrinted($_GET['refNo']);
			}
		}
		
		$date = ($arrSTS['dateApproved'] !="") ? date("F j, Y",strtotime($arrSTS['dateApproved'])):"";
		$this->Image('../../../images/ppci.jpg',70,8,70);
		$this->Ln(1);
		$this->SetFont('Arial', '', '8');
		$this->Cell(57,5,'Page '.$this->PageNo().'/{nb}',0,1,'L');
		//$Draft = ($arrSTS['contractNo']!="")? "":"Draft Only";
		$this->Cell(60,8,"",0,0,'L');
		
		$this->Ln(14);
		$this->SetFont('Arial', '', '9');
		
		$this->SetFont('Arial', '', '9');
		if($arrSTS['dateApproved']!='') 
			$aDate = date('M d, Y',strtotime($arrSTS['dateApproved']));
		else
			$aDate = "-";
		
		$this->Cell(25,5,'DATE: ',0,0,'L');
		$this->Cell(20,5,$aDate,0,0,'L');
		$this->Cell(75,5,"",0,0,'C');
		$this->Cell(38,5,'REF NO.: ',0,0,'L');
		$this->Cell(20,5,$arrSTS['stsRefno'],0,1,'L');
		
		$this->Cell(25,5,'SUPPLIER: ',0,0,'L');
		$this->Cell(20,5,$arrSTS['suppName'],0,0,'L');
		$this->Cell(75,5,"",0,0,'C');
		$this->Cell(38,5,'GROUP: ',0,0,'L');
		$this->Cell(20,5,$arrSTS['grpDesc'],0,1,'L');
		
		
		$this->Cell(25,5,'TRANDEPT: ',0,0,'L');
		$this->Cell(20,5,$arrSTS['Dept'],0,0,'L');
		
		$this->Cell(75,5,"",0,0,'C');
		$this->Cell(38,5,'TRANCLASS: ',0,0,'L');
		$this->Cell(20,5,substr($arrSTS['Class'],0,18),0,1,'L');
		
		
		if( $arrSTS['stsType'] =='1')
			$sclass = $arrSTS['SClass'];
		else
			$sclass = " - ";
		$this->Cell(25,5,'SUBCLASS: ',0,0,'L');
		$this->Cell(20,5,$sclass,0,0,'L');
		$this->Cell(75,5,"",0,0,'C');
		$this->Cell(38,5,'AMOUNT: ',0,0,'L');
		$this->Cell(20,5,number_format($arrSTS['stsAmt'],2),0,1,'L');
		
		if($arrSTS['nbrApplication']=='1'){
			$effectivity =  date('M d, Y',strtotime($arrSTS['applyDate']));
		}else{
			$effectivity = date('M d, Y',strtotime($arrSTS['applyDate']))." to ".date('M d, Y',strtotime($arrSTS['endDate']));
		}
		$this->Cell(25,5,'EFFECTIVITY: ',0,0,'L');
		$this->Cell(20,5,$effectivity,0,0,'L');
		$this->Cell(75,5,"",0,0,'C');
		$this->Cell(38,5,'MODE OF PAYMENT: ',0,0,'L');
		$this->Cell(20,5,$arrSTS['paymode'],0,1,'L');

		$this->Cell(25,5,'DETAILS:',0,0,'L');
		$this->Cell(20,5,$arrSTS['stsRemarks'],0,1,'L');
		
		$this->Ln(3);
		$w=array(30,50,35);
		//Header
		$header=array('STS No','Store','Amount');
		$this->Cell(40,6,"",0,0,'L');	
		for($i=0;$i<count($header);$i++)
			$this->Cell($w[$i],7,$header[$i],1,0,'C');
			$this->Ln();
	}
}	

$pdf=new PDF();
$pdf->AliasNbPages(); 
$pdf->FPDF($orientation='P',$unit='mm',$format='LETTER');	
$pdf->compName = 'Puregold Price Club Inc.';

$pdf->AddPage();	
$pdf->Content();
$pdf->Output('STS.pdf','D');
//$pdf->Output();
?>
