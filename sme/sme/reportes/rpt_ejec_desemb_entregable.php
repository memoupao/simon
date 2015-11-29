<?php 
include("../../includes/constantes.inc.php");
include("../../includes/validauser.inc.php");
require_once (constant('PATH_CLASS') . "BLFE.class.php");
require_once (constant("PATH_CLASS") . "BLMarcoLogico.class.php");

$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
$idProy = $all == 1 ? '*' : $idProy;

$objML = new BLMarcoLogico();
$ML = $objML->GetML($idProy, $idVersion);

if($idProy=='') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
<title>Reporte de Ejecución de Desembolsos</title>

<style>
   .tblDesembolsos{
   }
   .tblDesembolsos thead tr{
   	background: #E9E9E9 !important;
   }
   .tblDesembolsos thead tr th{
   	border: 1px solid #999999;
   	padding: 3px 4px;
   }
   .tblDesembolsos thead tr th:hover{
   	cursor: auto;
   	color: black;
   	text-decoration: none;
   }
   
   .tblDesembolsos tfoot tr td{
   	background-color: white !important;
   	padding: 3px 4px;
   	font-weight: bold;
   }
</style>

</head>

<body>
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php } ?>

<div id="divBodyAjax" class="TableGrid">
	<table cellpadding="0" cellspacing="1" style=" border:1px solid #525E94; display:block; width: 80%;">
		<tr>
			<th style="width: 20%; text-align: left;">CODIGO DEL PROYECTO</th>
			<td style="width: 60%; text-align: left;"><?php echo($ML['t02_cod_proy']);?></td>
			<th style="width:10%; text-align: left;">INICIO</th>
			<td style="width: 10%; text-align: left;"><?php echo($ML['t02_fch_ini']);?></td>
		</tr>
		<tr>
			<th align="left" nowrap="nowrap">DESCRIPCION DEL PROYECTO</th>
			<td align="left"><?php echo($ML['t02_nom_proy']);?></td>
			<th align="left" nowrap="nowrap">TERMINO</th>
			<td align="left"><?php echo($ML['t02_fch_ter']);?></td>
		</tr>
	</table>
	<br/>
<?php 
	$objEjecDesem = new BLFE();

	$rs = $objEjecDesem->getResumenEjecDesembolsos($idProy, $idVersion);
	$numEntregables = mysql_num_rows($rs);

	$data = array();
	$i = 0;
	while ($row = mysql_fetch_array($rs)) {
		$data[$i]['anio'] = $row['anio'];
		$data[$i]['mes'] = $row['mes'];
		$data[$i]['entregable'] = $row['entregable'];
		$data[$i]['periodo'] = $row['periodo'];
		$data[$i]['planeado'] = $row['planeado'];
		$data[$i]['desembolsado'] = $row['desembolsado'];
		$i++;
	}
?>
	<div class="TableGrid" style="width: 80%;">
		<table cellspacing="0" cellpadding="0" border="0" class="tblDesembolsos" width="100%">
			<thead>
				<tr>
					<th width="100" align="center" rowspan="2">RESUMEN</th>									
					<th width="" align="center" colspan="<?php echo $numEntregables; ?>">ENTREGABLES</th>
					<th width="120" align="center" rowspan="2">TOTAL</th>
				</tr>
				<tr>
					<?php 
					$i = 0;
					while ($i < $numEntregables) { ?>
					<th width="95" align="center"><?php echo $data[$i]['entregable']?> <br/><?php echo $data[$i]['periodo']?></th>
					<?php 
						$i++;
					}
					?>
				</tr>
			</thead>
			<tbody class="data">
				<tr>
					<td>PLANEADO</td>
					<?php 
					$i = 0;
					$total_plan = 0;

					while ($i < $numEntregables) { ?>
					<td class="right data-plan" val="<?php echo $data[$i]['planeado']; ?>"><?php echo number_format($data[$i]['planeado'], 2); ?></td>
					<?php 
						$total_plan += round($data[$i]['planeado'], 2);
						$i++;
					}
					?>
					<td class="right"><?php echo number_format($total_plan, 2); ?></td>
				</tr>
				<tr>
					<td>DESEMBOLSADO</td>
					<?php 
					$i = 0;
					$total_desemb = 0;

					while ($i < $numEntregables) { ?>
					<td class="right data-desemb" val="<?php echo $data[$i]['desembolsado']; ?>">
                    	<?php echo number_format($data[$i]['desembolsado'], 2); ?>
					</td>
					<?php
						$total_desemb += round($data[$i]['desembolsado'], 2);
						$i++;
					} ?>
					<td class="right"><?php echo number_format($total_desemb, 2); ?></td>
				</tr>
				
				<tr>
					<td>SALDO</td>
					<?php 
					$i = 0;
					$total_saldo = 0;

					while ($i < $numEntregables) { ?>
					<td class="right data-saldo" val=""></td>
					<?php
						$total_saldo += (round($data[$i]['planeado'], 2) - round($data[$i]['desembolsado'], 2));
						$i++;
					} ?>
					<td class="right"><?php echo number_format($total_saldo, 2); ?></td>
				</tr>
			</tbody>
		</table>
	</div>
	<br />
</div>
<script language="javascript" type="text/javascript">

	$('.right.data-desemb').each(function () {

		var planeado = parseFloat($(this).parent().prev().find('td').eq($(this).index()).attr('val'));
		var desembolsado = parseFloat($(this).attr('val'));
		var saldo = planeado - desembolsado;

		$(this).parent().next().find('td').eq($(this).index()).html(saldo).formatCurrency({symbol: ''});
		$(this).parent().next().find('td').eq($(this).index()).attr('val', saldo);
	});

</script>

<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
</html>
<?php } ?>