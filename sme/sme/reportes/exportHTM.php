<?php include("../../includes/constantes.inc.php"); ?>
<?php

require_once (constant('PATH_CLASS') . "Functions.class.php");

$objFunc = new Functions();
$nameDOC = $objFunc->__Request('txtname') . ".html";
$BodyDOC = $objFunc->__Request('txtcontents');
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Transfer-Encoding: binary");
header("Cache-Control: private", false);
// header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
// header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=\"" . basename($nameDOC) . "\";");

$SchemaHTML = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml" >
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                @HEAD
                </head>
                <body>
                @BODY
                </body>
                </html> ';

$StylesCSS = "<style>" . file_get_contents(constant("PATH_CSS") . "reportes.css") . "</style>\n";

$StylesCSS = str_replace("overflow:hidden;", " ", $StylesCSS);

$SchemaHTML = str_replace("@HEAD", $StylesCSS, $SchemaHTML);
$SchemaHTML = str_replace("@BODY", stripcslashes($BodyDOC), $SchemaHTML);
// echo($Head."\n".$BodyDOC);
echo ($SchemaHTML);

exit(0);

?>