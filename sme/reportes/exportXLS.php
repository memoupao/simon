<?php 
include("../../includes/constantes.inc.php"); 

if (! isset($_SESSION)) {
    session_start();
}

require_once (constant('PATH_CLASS') . "Functions.class.php");

$objFunc = new Functions();
$nameFile = $objFunc->__Request('txtname');
$nameXLS = $nameFile . ".xls";
$BodyXLS = $objFunc->__Request('txtcontents');
$BodyXLS = utf8_decode($BodyXLS);


    header("Pragma: public"); // required
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private", false); // required for certain browsers
    header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
    header("Content-Disposition: attachment; filename=\"" . basename($nameXLS) . "\";");
    header("Content-Transfer-Encoding: binary");

    $SchemaHTML = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
                <head>
                    <!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>'.$nameFile.'</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->
                @HEAD
                </head>
                <body>
                @BODY
                </body>
                </html>';

    //$StylesCSS = "<style>" . file_get_contents(constant("PATH_CSS") . "reportes.css") . "</style>";
    $dirCSS = dirname(__FILE__).'/../../css/';      
    $StylesCSS = "<style>" . file_get_contents($dirCSS."reportes.css") . "</style>";
    $SchemaHTML = str_replace("@HEAD", $StylesCSS, $SchemaHTML);    
    $SchemaHTML = str_replace("@BODY", stripcslashes($BodyXLS), $SchemaHTML);
    
    echo $SchemaHTML;
