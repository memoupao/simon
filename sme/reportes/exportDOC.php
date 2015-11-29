<?php include("../../includes/constantes.inc.php"); ?>
<?php

require_once (constant('PATH_CLASS') . "Functions.class.php");

$objFunc = new Functions();
$nameDOC = $objFunc->__Request('txtname') . ".doc";
$BodyDOC = $objFunc->__Request('txtcontents');

$horizontal = ($objFunc->__Request('horizontal')==='1') ? true : false;


header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Transfer-Encoding: binary");
header("Cache-Control: private", false);
header("Content-Type: application/msword");
header("Content-Disposition: attachment; filename=\"" . basename($nameDOC) . "\";");

$landscape = '';
if ($horizontal) {
	$landscape = 'mso-page-orientation:landscape;';
}

$SchemaHTML = '
                <html xmlns="http://www.w3.org/1999/xhtml"
                      xmlns:o="urn:schemas-microsoft-com:office:office"
                      xmlns="http://www.w3.org/TR/REC-html40">
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <style>
                <!-- /* Style Definitions */
                @page { size:8.5in 11.0in;
                         margin:1.0in 1.25in 1.0in 1.25in ;
                         mso-header-margin:.5in;
                         mso-footer-margin:.5in;
                         mso-paper-source:0;
						 '.$landscape.'							
                       }
                </style>
                @HEAD
                </head>
                <body>
                @BODY
                </body>
                </html> ';

//$StylesCSS = "<style>" . file_get_contents(constant("PATH_CSS") . "reportes.css") . "</style>\n";
//$contentCSS = $objFunc->getContentFromURL(constant("PATH_CSS") . "reportes.css");
$dirCSS = dirname(__FILE__).'/../../css/';
$StylesCSS = "<style>" . file_get_contents($dirCSS."reportes.css") . "</style>";
//$StylesCSS = "<style>" . $contentCSS . "</style>\n";

$SchemaHTML = str_replace("@HEAD", $StylesCSS, $SchemaHTML);
$SchemaHTML = str_replace("@BODY", stripcslashes($BodyDOC), $SchemaHTML);
// echo($Head."\n".$BodyDOC);
echo ($SchemaHTML);
exit(0);
