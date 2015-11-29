<?php
$subTitle = "Mantenimiento de Bancos";
$concepto = "banco";
include ("general/mant_head.php");
?>
<tr>
	<th width="50" height="25">&nbsp;</th>
	<th width="50" spry:sort="numero">Numero</th>
	<th width="200" spry:sort="abreviatura">Abreviatura</th>
	<th width="300" spry:sort="nombre">Nombre</th>
	<th width="50">&nbsp;</th>
</tr>
</thead>
<tbody class="data">
	<tr class="RowData" spry:repeat="pvLista" spry:setrow="pvLista"
		id="{@id}" spry:select="RowSelected">
		<td nowrap="nowrap"><img src="../img/pencil.gif" title="Editar"
			onclick="btnEditar_Clic('{numero}');" /></td>
		<td>{numero}</td>
		<td>{nombre}</td>
		<td>{nombre_lar}</td>
		<td nowrap="nowrap"><img src="../img/bt_elimina.gif" title="Eliminar"
			onclick="Eliminar('{numero}', '{nombre}');" /></td>
	</tr>
</tbody>
<tfoot>
	<tr>
		<th colspan="5">
<?php include("general/mant_foot.php"); ?>