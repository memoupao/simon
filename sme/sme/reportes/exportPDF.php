<?php 
include("../../includes/constantes.inc.php");

if (! isset($_SESSION)) {
    session_start();
}
require_once (constant('PATH_CLASS') . "Functions.class.php");
require_once (constant('PATH_LIBRERIAS') . 'html2pdf/html2pdf.class.php');
// require_once(constant('PATH_LIBRERIAS').'dompdf/dompdf_config.inc.php');
// require_once(constant('PATH_LIBRERIAS').'tcpdf/config/lang/eng.php');
// require_once(constant('PATH_LIBRERIAS').'tcpdf/tcpdf.php');

$objFunc = new Functions();
$namePDF = $objFunc->__Request('txtname') . ".pdf";
$BodyPDF = $objFunc->__Request('txtcontents');

ob_start();

//$StylesCSS = "<style>" . file_get_contents(constant("PATH_CSS") . "reportes.css") . "</style>";
$contenidoCSS = $objFunc->getContentFromURL(constant("PATH_CSS") . "reportes.css");
$StylesCSS = "<style>" . $contenidoCSS . "</style>";
echo ($StylesCSS);
// $content = "<page>".stripcslashes($BodyPDF)."</page>";
echo ("<page>");
echo (stripcslashes($BodyPDF));
echo ("</page>");
$content = ob_get_clean();
// $content = ob_get_contents();
// ob_end_clean();

// print_r($content);
// exit();
try {
    $html2pdf = new HTML2PDF('L', 'A3', 'es');
    $html2pdf->writeHTML($content, false);
    $html2pdf->Output($namePDF, 'D');
} catch (HTML2PDF_exception $e) {
    
    echo $e;
}
// **************

// $SchemaHTML = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
// <html>
// <head>
// @HEAD
// </head>
// <body>
// @BODY
// </body>
// </html> ' ;

// $StylesCSS = "<style>".file_get_contents(constant("PATH_CSS")."reportes.css")."</style>";
// $SchemaHTML = str_replace("@HEAD", $StylesCSS, $SchemaHTML);
// $SchemaHTML = str_replace("@BODY", stripcslashes($BodyPDF) , $SchemaHTML);

// $old_limit = ini_set("memory_limit", "16M");

// $dompdf = new DOMPDF();
// $dompdf -> set_paper('a3','landscape');
// $dompdf->load_html($SchemaHTML);
// $dompdf->render();

// $dompdf->stream($namePDF);

// exit(0);
// *************************************

// $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
// $pdf->setPrintHeader(false);
// $pdf->setPrintFooter(false);
// $pdf->setLanguageArray($l);
// $pdf->SetFont('times', 'BI', 20);
// $pdf->AddPage();
// $txt = ""
// $pdf->writeHTML($txt, true, 0, true, 0);

// $pdf->Output($namePDF, 'FD');
