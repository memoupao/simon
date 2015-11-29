<?php
/**
 * CticServices
 *
 * Gestiona el mantenimiento de Tasas
 *
 * @package	Admin
 * @author	DA
 * @since	Version 2.0
 * 
 *
 */
include ("../includes/constantes.inc.php");
include ("../includes/validauseradm.inc.php");

$concepto = 'tasa';
$subTitle = 'Mantenimiento de Tasas';

include ("general/mant_head_tasa.php");
?>
<tr>
	<th width="50" height="25">&nbsp;</th>
	<th width="50" spry:sort="numero">Numero</th>
	<th width="200" spry:sort="abreviatura">Nombre de Tasa</th>
	<th width="300" spry:sort="nombre">Valor de Tasa</th>
	<th width="50">&nbsp;</th>
</tr>
</thead>
<tbody class="data">
	<tr class="RowData" spry:repeat="pvLista" spry:setrow="pvLista"
		id="{@id}" spry:select="RowSelected">
		<td nowrap="nowrap"><img src="../img/pencil.gif" title="Editar"
			onclick="btnEditar_Clic('{numero}');" /></td>
		<td>{numero}</td>
		<td>{abreviatura}</td>
		<td>{nombre}</td>
		<td nowrap="nowrap">
			<!-- <img src="../img/bt_elimina.gif" title="Eliminar" onclick="Eliminar('{numero}', '{nombre}');" />-->
		</td>
	</tr>
</tbody>
<tfoot>
	<tr>
		<th colspan="5">
<?php include("general/mant_foot_tasa.php"); ?>
