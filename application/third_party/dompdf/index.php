<?php
error_reporting(1);
ini_set('display_errors', 1);
require_once 'autoload.inc.php';

// https://github.com/dompdf/dompdf
// https://github.com/dompdf/dompdf/wiki/Usage
// https://github.com/dompdf/dompdf/wiki/Using-Dompdf-in-CodeIgniter-3.x
// https://ourcodeworld.com/articles/read/687/how-to-configure-a-header-and-footer-in-dompdf
// https://github.com/dompdf/dompdf/issues/1190


$html='<html>
    <head>
        <style>
		
			@font-face {
			  font-family: "Mangal";
			  font-style: normal;
			  font-weight: normal;
			  src: url(mangal.ttf) format(\'truetype\');
			}
            /** 
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
            @page {
                margin: 0cm 0cm;
            }
            /** Define now the real margins of every page in the PDF **/
            body {
                margin-top: 2cm;
                margin-left: 2cm;
                margin-right: 2cm;
                margin-bottom: 2cm;
            }
			
			main{
				font-family: Mangal, sans-serif;
				font-size:12px;
			}
			
            /** Define the header rules **/
            header {
                position: fixed;
                top: 0cm;
                left: 0cm;
                right: 0cm;
                height: 2cm;
                background-color: #f400b8;
                color: white;
                line-height: 1.5cm;
            }
			
			.header_img{
				margin-top: 0.4cm;
				margin-left: 2cm;
				text-align:left;
				width:20%;
			}
			
			.pdf_heading{
				font-size: 24px;
				font-family: sans-serif;
			}

            /** Define the footer rules **/
            footer {
                position: fixed; 
                bottom: 0cm; 
                left: 0cm; 
                right: 0cm;
                height: 2cm;

                /** Extra personal styles **/
                background-color: #f400b8;
                color: white;
                text-align: center;
                line-height: 1.5cm;
            }
			.pn_location{
				margin-right: 2cm;
				float:right;
			}
			
			.page-number:after { 
				content: counter(page);
			}
			
        </style>
		
		
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
	</head>
	
    <body>
        <!-- Define header and footer blocks before your content -->
        <header>
           <img src="'.FCPATH.'assets\images\logo_extra.png" class="header_img" /> <span class="pdf_heading">Digital India Awards 2018</span>
        </header>

        <footer>
            National Informatics Centre, MeitY <span class="page-number pn_location">Page </span>
        </footer>
        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
            <h1>Heading 1</h1>
			
			 <h2>Heading 2</h2>
			 
			 <h3>Heading 3</h3>
			
			 संयुक्त राष्ट्र के महासचिव ने राष्ट्रपति से मुलाकात की
			<p>
                Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>Content content conetnt<br/>
          
		  
		  <!--
		  <p style="page-break-after: always;">
		  Page 1
		  </p>
		  
		  <p style="page-break-after: never;">
              Page 2
            </p> -->
			
        </main>
    </body>
</html>
';

//echo $html; die;

/*
	
$html='<table width="100%" border="1">
  <tr>
    <td width="50%">A</td>
    <td width="50%">B</td>
  </tr>
  <tr>
    <td width="50%">xxxx</td>
    <td width="50%">yyyy</td>
  </tr>
</table>
';
*/

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();



$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
//$dompdf->stream();

$font = $dompdf->getFontMetrics()->get_font("helvetica", "bold");
$dompdf->getCanvas()->page_text(500, 18, "Page {PAGE_NUM} of {PAGE_COUNT}", $font, 10, array(0,0,0));
$dompdf->stream("dompdf_out.pdf", array("Attachment" => false));

?>