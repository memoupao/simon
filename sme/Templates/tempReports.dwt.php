<?php include("../includes/constantes.inc.php"); ?>
<?php include("../includes/validauser.inc.php"); ?>
<?php

require (constant("PATH_CLASS") . "BLReportes.class.php");
// $objFunc->xx
// $objRep = new BlReportes

?>


<?php if($objFunc->__QueryString()=="") { ?>
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
<div id="divBodyAjax" class="TableGrid">
			<!-- TemplateBeginEditable name="BodyAjax" -->
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
					<tr>
						<td align="left" valign="middle">&nbsp;</td>
					</tr>
					<tr>
						<td align="left" valign="middle">&nbsp;</td>
					</tr>
				</tbody>
				<tfoot>
				</tfoot>
			</table>
			<script language="javascript" type="text/javascript">
</script>
			<!-- TemplateEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
</html>
<?php } ?>