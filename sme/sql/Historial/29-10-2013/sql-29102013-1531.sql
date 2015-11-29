/* Correccion al momento de registrar o editar en datos generales del proyecto */
alter table `t02_dg_proy` change `t02_pres_eje` `t02_pres_eje` double default '0' NOT NULL comment 'Presupuesto de la Intitucion ejecutora';
