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
//Load data
function LoadData($file)
{
    //* TODO: set this function so it reads from the DB :
    //*       set this function so it reads XML-streams :
    //*       
    //*       read header info too!!!
    
    
    //Read basic CSV file lines
    $lines=file($file);
    $data=array();
    foreach($lines as $line)
        $data[]=explode(';',chop($line));
    return $data;
}
//Page header
function Header()
{
    //Logo
    
    //Arial bold 15
    $this->SetFont('Helvetica','B',8);
    //Move to the right
   
    //Repeat on every page start
    $this->Cell(190,10,"Overzicht",0,0,'R');
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





function FancyTable($header,$data)
{
    //Colors, line width and bold font
    $this->SetFillColor(199,112,45);
    $this->SetTextColor(255);
    $this->SetDrawColor(200,149,109);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    //Header
    $w=array(62,43,43,43);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'L',true);
    $this->Ln();
    //Color and font restoration
    $this->SetFillColor(246,214,189);
    $this->SetTextColor(0);
    $this->SetFont('');
    //Data
    $fill=false;
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
        $this->Cell($w[1],6,'€ '.money_format('%(#10i',$row[1]),'LR',0,'L',$fill);
        $this->Cell($w[2],6,'€ '.money_format('%(#10i',$row[2]),'LR',0,'R',$fill);
        $this->Cell($w[3],6,'€ '.money_format('%(#10i',$row[3]),'LR',0,'R',$fill);
        $this->Ln();
        $fill=!$fill;
    }
    // Blanc then totals
    $this->Cell($w[0],6,'','LR',0,'L',$fill);
    $this->Cell($w[1],6,'','LR',0,'L',$fill);
    $this->Cell($w[2],6,'','LR',0,'R',$fill);
    $this->Cell($w[3],6,'','LR',0,'R',$fill);
    $this->Ln();
    $this->SetFillColor(201,87,0);
    $this->SetTextColor(16);
   
    $this->Cell($w[0],6,'TOTAAL','LRT',0,'R',$fill);
    $this->Cell($w[1],6,'€ '.money_format('%(#10i',232323.65),'LRT',0,'L',false);
    $this->Cell($w[2],6,'€ '.money_format('%(#10i',232323.65),'LRT',0,'R',false);
    $this->Cell($w[3],6,'€ '.money_format('%(#10i',232323.65),'LRT',0,'R',false);
    $this->Ln();
    
    $this->Cell(array_sum($w),0,'','T');
}
}

$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Courier','',12);
// 40 lines per page? or less coz landscape


$header        = array('Financiele Groep','Netto Bedrag','BTW','Totaal');

$data=$pdf->LoadData('000000001.data');



$pdf->FancyTable($header,$data);
$pdf->Output();




// MSIE hacks. Need this to be able to download the file over https
// All kudos to http://in2.php.net/manual/en/function.header.php#74736
//header('Cache-Control: maxage=3600'); //Adjust maxage appropriately
//header('Pragma: public');


$outFile   = "out.pdf";
$pdf->Output($outFile, 'D');

?>