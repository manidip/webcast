<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'/third_party/dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;

class Dom_pdf{

  public function generate($html, $filename='', $stream=TRUE, $paper = 'A4', $orientation = "portrait")
  {
	// instantiate and use the dompdf class
	
	
	
	
    $dompdf = new Dompdf();
	//$dompdf->set_option('isRemoteEnabled', TRUE);
	
	//echo "<pre>";
	
	//print_r($dompdf->getOptions()); die;
	
	//$dompdf->set_option('defaultFont', 'Courier');
	
	# or
	/*
	$options = new Options();
$options->set('defaultFont', 'Courier');
$dompdf = new Dompdf($options);
*/
	
    $dompdf->loadHtml($html);
	
	//echo ($html); die;
	
    $dompdf->setPaper($paper, $orientation);
	
	// Render the HTML as PDF
    $dompdf->render();
	
    if ($stream) {
		
		
		//$font = $dompdf->getFontMetrics()->get_font("helvetica", "bold");
		//$dompdf->getCanvas()->page_text(500, 18, "Page {PAGE_NUM} of {PAGE_COUNT}", $font, 10, array(0,0,0));
		
        $dompdf->stream($filename.".pdf", array("Attachment" => false));
		
    } else {
        
		
		// to save generated pdf
		
		$pdf_content=$dompdf->output();
		return $pdf_content;
    }
  }
}