<?php
ob_start();

echo ($_SERVER["DOCUMENT_ROOT"]);
echo ($_SERVER["PHP_SELF"]);

echo ("<pre>");
print_r($_REQUEST);
print_r($_SERVER);
print_r($_SESSION);
echo ("</pre>");
$body = ob_get_contents();
ob_end_clean();

// -------------------------------------------------->
// DA 2.0 [18-01-2014 17:49]
// Aquí va la descripción de la modificación
//mail("cristhian_80@hotmail.com", "Probando el Cron de Linux", "Probando el Cron de Linux - Body \n $body");
// --------------------------------------------------<

?>