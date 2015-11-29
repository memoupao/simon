<?php
/**
 * CticServices
 *
 * Gestiona los mantenimientos de conceptos como por ejemplo:
 * Bancos, Monedas, Lineas.
 *
 * @package	Admin
 * @author	AQ
 * @since	Version 2.0
 *
 * @param	string $_GET('item') Nombre del concepto a procesar.
 *
 */
include ("../includes/constantes.inc.php");
include ("../includes/validauseradm.inc.php");

$concepto = $objFunc->__GET('item');
// -------------------------------------------------->
// AQ 2.0 [21-10-2013 16:30]
// Casos agregados: tipo_anexo y tipo_cuenta
//
// DA 2.0 [21-10-2013 16:27]
// Casos agregados: lineas y tasas
//
switch ($concepto) {
    case 'banco':
        $subTitle = "Mantenimiento de Bancos";
        break;
    case 'moneda':
        $subTitle = "Mantenimiento de Monedas";
        break;
    case 'tipo_anexo':
        $subTitle = "Mantenimiento de Tipos de Anexo";
        break;
    case 'tipo_cuenta':
        $subTitle = "Mantenimiento de Tipos de Cuenta";
        break;
    case 'tasa':
        $subTitle = "Mantenimiento de Tasas";
        break;
    case 'linea':
        $subTitle = "Mantenimiento de Lineas";
        break;
}
// --------------------------------------------------<

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
		<td>{abreviatura}</td>
		<td>{nombre}</td>
		<td nowrap="nowrap"><img src="../img/bt_elimina.gif" title="Eliminar"
			onclick="Eliminar('{numero}', '{nombre}');" /></td>
	</tr>
</tbody>
<tfoot>
	<tr>
		<th colspan="5">
<?php include("general/mant_foot.php"); ?>
