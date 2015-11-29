<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<?php
$objFunc->SetTitle("Búsqueda de Proyectos");
$evaluar = $_GET['evaljs'];
?>

<meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
<meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo($objFunc->Title);?></title>
<link href="../../../css/template.css" rel="stylesheet" media="all" />
<script src="<?php echo(PATH_JS);?>general.js" type="text/javascript"></script>
<script src="../../../SpryAssets/xpath.js" type="text/javascript"></script>
<script src="../../../SpryAssets/SpryData.js" type="text/javascript"></script>
<script src="../../../SpryAssets/SpryPagedView.js"
	type="text/javascript"></script>

<SCRIPT src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"
	type=text/javascript></SCRIPT>

<script type="text/javascript">
var dsproyectos = null ;
var pvProyectos = null;
var pvProyectosPagedInfo = null;
Loadproyectos();
function Loadproyectos()
{
	var xmlurl = "../datos/process.xml.php?action=<?php echo(md5("buscar"));?>&idInst=<?php echo($ObjSession->IdInstitucion);?>";
	dsproyectos = new Spry.Data.XMLDataSet(xmlurl, "proyectos/rowdata", {useCache: true});

	pvProyectos	= new Spry.Data.PagedView(dsproyectos, { pageSize: 10});
	pvProyectosPagedInfo = pvProyectos.getPagingInfo();
}

</script>


</head>
<body>
	<form id="FormData" method="post"
		enctype="application/x-www-form-urlencoded"
		action="<?php echo($_SERVER['PHP_SELF']);?>">
		<div id="mainContent" style="width: 580px;">
			<div id="toolbar" style="height: 8px;">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="11%" height="25" valign="middle">Buscar:</td>
						<td width="30%" valign="top"><input name="txtBuscaproyecto"
							type="text" class="Boton" id="txtBuscaproyecto"
							style="text-align: center;" title="Nombre del Proyecto" size="35"
							onkeyup="StartFilterTimer();" /><br /></td>
						<td width="5%" valign="middle">&nbsp;&nbsp;<input type="image"
							src="../../../img/btnRecuperar.gif" width="16" height="16"
							onclick="dsproyectos.loadData(); return false;"
							title="Actualizar Datos" /></td>
						<td width="5%" valign="middle"></td>
						<td width="49%" valign="middle"><span>Mostrar</span> <select
							name="cbopageSize" id="cbopageSize"
							onchange="pvProyectos.setPageSize(parseInt(this.value));"
							style="width: 60px">
								<option value="5">5 Reg.</option>
								<option value="10" selected="selected">10 Reg.</option>
								<option value="20">20 Reg.</option>
								<option value="30">30 Reg.</option>
								<option value="50">50 Reg.</option>
						</select> <span>Ir a </span> <span
							spry:region="pvProyectosPagedInfo" style="width: 75px;"> <select
								name="cboFilter" id="cboFilter"
								onchange="pvProyectos.goToPage(parseInt(this.value));"
								spry:repeatchildren="pvProyectosPagedInfo" style="width: 60px">
									<option
										spry:if="({ds_PageNumber} == pvProyectos.getCurrentPage())"
										value="{ds_PageNumber}" selected="selected">Pag.
										{ds_PageNumber}</option>
									<option
										spry:if="({ds_PageNumber} != pvProyectos.getCurrentPage())"
										value="{ds_PageNumber}">Pag. {ds_PageNumber}</option>
							</select>
						</span></td>
					</tr>
				</table>
			</div>
			<div class="TableGrid" spry:region="pvProyectos dsproyectos">
				<p spry:state="loading" align="center">
					<img src="../../../img/indicator.gif" width="16" height="16" /><br>
					Cargando...
				</p>
				<table width="580" border="0" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th width="115" height="26" align="center" spry:sort="ejecutor">EJECUTOR</th>
							<th width="59" align="center" spry:sort="codigo">CODIGO</th>
							<th width="38" align="center" spry:sort="exp">EXP</th>
							<th width="20" align="center" spry:sort="vs">VS</th>
							<th width="335" align="center" spry:sort="nombre">DESCRIPCION DEL
								PROYECTO</th>
						</tr>
					</thead>
					<tbody class="data" bgcolor="#FFFFFF">
						<tr class="RowData" spry:repeat="pvProyectos"
							spry:setrow="pvProyectos" id="{@id}" spry:select="RowSelected">
							<td align="left">{ejecutor}</td>
							<td align="center"><A href="javascript:Seleccionarproyecto();"
								title="Seleccionar Proyecto">{codigo}</A></td>
							<td align="center">{exp}</td>
							<td align="center">{vs}</td>
							<td align="left">{nombre}</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<th width="115">&nbsp;</th>
							<th width="59" align="right">&nbsp;</th>
							<th colspan="3" align="right"><input type="button" class="Boton"
								title="Ir a la Primera Pagina"
								onclick="pvProyectos.firstPage();" value="&lt;&lt;" /> <input
								type="button" class="Boton" title="Pagina Anterior"
								onclick="pvProyectos.previousPage();" value="&lt;" /> <input
								type="button" class="Boton" title="Pagina Siguiente"
								onclick="pvProyectos.nextPage();" value="&gt;" /> <input
								type="button" class="Boton" title="Ir a la Ultima Pagina"
								onclick="pvProyectos.lastPage();" value="&gt;&gt;" /></th>
						</tr>
					</tfoot>
				</table>
				<input type="hidden" id="txtEval" name="txtEval"
					value="<?php echo("".$evaluar);?>" />
			</div>

			<script language="javascript" type="text/javascript">

function Seleccionarproyecto()
{
	var txtevaluar = $('#txtEval').val();
	var rowid = dsproyectos.getCurrentRowID()
	var row = dsproyectos.getRowByID(rowid);
	if(row)
	{
		try
		{
			$("#<?php echo($_GET['ctrl_idinst']);?>", window.opener.document).val(row.t01_id_inst);
			$("#<?php echo($_GET['ctrl_idproy']);?>", window.opener.document).val(row.codigo);
			$("#<?php echo($_GET['ctrl_idversion']);?>", window.opener.document).val("1");
			$("#<?php echo($_GET['ctrl_ejecutor']);?>", window.opener.document).val(htmlEncode(row.ejecutor));
			$("#<?php echo($_GET['ctrl_proyecto']);?>", window.opener.document).val(htmlEncode(row.nombre));
			if(txtevaluar!="")
			{
				var evaluar = "window.opener." + txtevaluar + "();" ;
				eval(evaluar);
			}
		}
		catch(ex)
		{
			try
			{
				$("#<?php echo($_GET['ctrl_idinst']);?>", window.parent.document).val(row.t01_id_inst);
				$("#<?php echo($_GET['ctrl_idproy']);?>", window.parent.document).val(row.codigo);
				$("#<?php echo($_GET['ctrl_idversion']);?>", window.parent.document).val("1");
				$("#<?php echo($_GET['ctrl_ejecutor']);?>", window.parent.document).val(htmlEncode(row.ejecutor));
				$("#<?php echo($_GET['ctrl_proyecto']);?>", window.parent.document).val(htmlEncode(row.nombre));
				window.parent.HideBusqueda();
				if(txtevaluar!="")
				{
					var evaluar = "window.parent." + txtevaluar + "();" ;
					eval(evaluar) ;
				}
				return;
			}
			catch(ex)
			{
			}
		}

		window.close();
		return;
	}
	else
	{ alert("Error al Seleccionar el Proyecto !!!"); }
}
</script>
			<script language="JavaScript" type="text/javascript">
<!--
function FilterData()
{
var tf = document.getElementById("txtBuscaproyecto");
if (!tf.value)
{
	dsproyectos.filter(null);
	return;
}

var regExpStr = tf.value;
//regExpStr = "^" + regExpStr;
var regExp = new RegExp(regExpStr, "i");
var filterFunc = function(ds, row, rowNumber)
{
	var str = row["nombre"]+"|"+row["ejecutor"]+"|"+row["codigo"] ;
	if (str && str.search(regExp) != -1)
		return row;
	return null;
};
dsproyectos.filter(filterFunc);
}

function StartFilterTimer()
{
if (StartFilterTimer.timerID)
	clearTimeout(StartFilterTimer.timerID);
	StartFilterTimer.timerID = setTimeout(function() { StartFilterTimer.timerID = null; FilterData(); }, 100);
}

-->

</script>
		</div>

	</form>
</body>
</html>
