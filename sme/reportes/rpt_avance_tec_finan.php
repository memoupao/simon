<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require (constant("PATH_CLASS") . "BLReportes.class.php");

$objRep = new BLReportes();

$proyecto = $objFunc->__Request('txtCodProy');

$rsMatriz = $objRep->avanceTecnicoPorProyecto($proyecto);



?>
<style>
table.tblBordered {

}

table.tblBordered tbody tr td,
table.tblBordered tfoot tr td,
table.tblBordered thead tr th{
	border: 1px solid #000000;
    padding: 5px
}


</style>


<div id="divBodyAjax" class="TableGrid">
			
			<table width="99%" align="center" cellpadding="0" cellspacing="0" class="tblBordered">
				<thead>
					<tr>
						<th valign="middle" align="center" colspan="5">AVANCE TÉCNICO POR PROYECTO</th>
						<th valign="middle" align="center" colspan="12">Avance Presupuestal</th>
						<th valign="middle" align="center" colspan="6">Avance Técnico </th>
					</tr>
					<tr>
						<th valign="middle" align="center" colspan="5"></th>						
						<th valign="middle" align="center" colspan="6">% Avance acumulado en relación a la meta del Entregable</th>
						<th valign="middle" align="center" colspan="6">% Avance acumulado en relación a la meta total del Proyecto</th>
						<th valign="middle" align="center" colspan="3">% Avance en relación a la meta del Entregable</th>
						<th valign="middle" align="center" colspan="3">% Avance acumulado en relación a la meta total del Proyecto</th>
					</tr>
					<tr>
						<th valign="middle" align="center">CÓDIGO DEL PROYECTO</th>
						<th valign="middle" align="center">Nombre del Proyecto</th>
						<th valign="middle" align="center">Institución Ejecutora</th>
						<th valign="middle" align="center">Fecha de Inicio</th>
						<th valign="middle" align="center">Fecha de Termino</th>
						
						<th valign="middle" align="center">FONDOEMPLEO</th>
						<th valign="middle" align="center">Institución Ejecutora</th>
						<th valign="middle" align="center">Instituciones Asociadas</th>
						<th valign="middle" align="center">Instituciones Colaboradoras</th>
						<th valign="middle" align="center">Beneficiarios</th>
						<th valign="middle" align="center">Total</th>
						
						<th valign="middle" align="center">FONDOEMPLEO</th>
						<th valign="middle" align="center">Institución Ejecutora</th>
						<th valign="middle" align="center">Instituciones Asociadas</th>
						<th valign="middle" align="center">Instituciones Colaboradoras</th>
						<th valign="middle" align="center">Beneficiarios</th>
						<th valign="middle" align="center">Total</th>
						
						
						
						<th valign="middle" align="center">Actividades (%)</th>
						<th valign="middle" align="center">Productos (%)</th>
						<th valign="middle" align="center">Componentes (%)</th>
						
						<th valign="middle" align="center">Actividades (%)</th>
						<th valign="middle" align="center">Productos (%)</th>
						<th valign="middle" align="center">Resultados (%)</th>
						
						
					</tr>
				</thead>

				<tbody class="data" bgcolor="#FFFFFF">
    <?php while ($rw = mysqli_fetch_assoc($rsMatriz)) { ?>
    
    				<tr>
						<td valign="middle" align="center"><?php echo $rw['codigo'];  ?></td>
						<td valign="middle" align="center"><?php echo $rw['titulo'];  ?></td>
						<td valign="middle" align="center"><?php echo $rw['nominst'];  ?></td>
						<td valign="middle" align="center"><?php echo $rw['fecha_inicio'];  ?></td>
						<td valign="middle" align="center"><?php echo $rw['fecha_termino'];  ?></td>
						
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						
					</tr>
        
    <?php }    
    	$rsMatriz->free(); 
    ?> 
    
  				</tbody>
				<tfoot>
					<tr>
						<td valign="middle" align="center">TOTAL</td>
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						<td valign="middle" align="center"></td>
						
					</tr>
				</tfoot>
			</table>
			<br />
		

			
		</div>
