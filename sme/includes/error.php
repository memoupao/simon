<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Monitoreo FONDOEMPLEO</title>
<link href="../css/template.css" rel="stylesheet" media="all" />
</head>

<body>
	<div class="contenttext">
<?php
$Error = (! isset($_GET['error']) ? "" : $_GET['error']);
?>

<?php if($Error=='construction'){ ?>
<input name="" type="image" src="../img/enconstruccion.gif" />
<?php

} else {
    if ($Error == "") {
        $Error = 'Pagina en Mantenimiento';
    }
    echo ("<div>" . $Error . "</div>");
}
?>
</div>
</body>
</html>