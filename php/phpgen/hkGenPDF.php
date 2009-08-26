<?





require_once(dirname(__FILE__)."/lib/fpdf/fpdf.php");
require_once(dirname(__FILE__)."/lib/fpdi/fpdi.php");

/*
 * Setup the template
 *
 */

// $filename = dirname(__FILE__)."/templates/template.pdf";

// $pdf = new FPDI();
// $pdf->AddPage();


// $pdf->setSourceFile($filename);

// $tplIdx = $pdf->importPage(1);

// $pdf->useTemplate($tplIdx);

class PDF extends FPDF
{
//Page header
function Header()
{
    //Logo
    $this->Image(dirname(__FILE__).'/images/logo.png',10,8,33);
    //Arial bold 15
    $this->SetFont('Helvetica','B',8);
    //Move to the right
   
    //Title
    $theTitle = "Financieel overzicht van " .
                "dd-mm-eejj" .
                " tot en met " .
                "dd-mm-eejj" .
                ". Dit is pagina " .
                $this->PageNo() .
                " van {nb}";
    $this->Cell(190,10,$theTitle,0,0,'R');
    $this->Ln(3);
    $this->Cell(190,10,'Periode van: dd-mm-eejj',0,0,'R');
    $this->Ln(3);
    $this->Cell(190,10,'        tot: dd-mm-eejj',0,0,'R');
    $this->Ln(3);
    $this->Cell(190,10,'Blad       : '.$this->PageNo().'#{nb}',0,0,'L');
    //Line break
    $this->Ln(20);
}

//Page footer
function Footer()
{
    //Position at 1.5 cm from bottom
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}

//Instanciation of inherited class
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
for($i=1;$i<=40;$i++)
    $pdf->Cell(0,10,'Printing line number '.$i,0,1);
$pdf->Output();
?>



// MSIE hacks. Need this to be able to download the file over https
// All kudos to http://in2.php.net/manual/en/function.header.php#74736
//header('Cache-Control: maxage=3600'); //Adjust maxage appropriately
//header('Pragma: public');


$outFile   = "out.pdf";
$pdf->Output($outFile, 'D');

?>