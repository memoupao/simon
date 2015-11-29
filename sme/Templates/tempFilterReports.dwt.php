<?php include("../includes/constantes.inc.php"); ?>
<?php include("../includes/validauser.inc.php"); ?>
<?php

$NoInclude = true;
require (constant("PATH_CLASS") . "BLReportes.class.php");
// $objRep = new BlReportes

?>
<?php if(!$NoInclude) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- TemplateBeginEditable name="doctitle" -->
<title>REPORTES</title>
<!-- TemplateEndEditable -->
<script language="javascript" type="text/javascript"
	src="../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<link href="../css/reportes.css" rel="stylesheet" type="text/css"
	media="all" />
<!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
</head>

<body>
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php } ?>
<style>
.Filter {
	font-size: 10px;
	font: Arial, Helvetica, sans-serif;
	padding: 3px;
	overflow: hidden;
}

.Filter fieldset {
	border: 1px solid #2A3F55;
}

.Filter fieldset legend {
	color: #2A3F55;
	font-weight: bold;
}

.Filter select {
	font-size: 10px;
	color: navy;
	font-weight: normal;
}

.Filter .Field {
	color: #000080;
	font-weight: bold;
	font-size: 11px;
	padding: 4px;
}

.Filter .Button {
	padding: 2px;
	padding-bottom: 3px;
	padding-top: 3px;
	padding-left: 8px;
	padding-right: 8px;
	background-color: #EEE;
	border: solid 1px #999;
	cursor: pointer;
}
</style>
		<!-- TemplateBeginEditable name="BodyAjax" -->
		<div id="divBodyAjax" class="Filter">
			<table width="750" cellpadding="0" cellspacing="0">
				<thead>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
					<tr>
						<td align="left" valign="middle"><br></td>
					</tr>
					<tr>
						<td align="left" valign="middle"><br></td>
					</tr>
					<tr>
						<td align="left" valign="middle"><br></td>
					</tr>
				</tbody>
				<tfoot>
				</tfoot>
			</table>
			<script language="javascript" type="text/javascript">
</script>
		</div>
		<!-- TemplateEndEditable -->
<?php if(!$NoInclude) { ?>
</form>
</body>
</html>
<?php } ?>