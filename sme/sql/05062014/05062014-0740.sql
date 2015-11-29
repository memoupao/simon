/*
// -------------------------------------------------->
// AQ 2.1 [05-06-2014 07:40]
// Fixed: fn_mnt_planificado_del_entregable
// Cambiado a fn_mnt_planificado_al_entregable
// --------------------------------------------------<
*/

DROP TRIGGER trg_upd_proy;

DELIMITER $$
CREATE TRIGGER trg_upd_proy AFTER UPDATE ON t02_dg_proy
 FOR EACH ROW BEGIN 
    CALL sp_poa_actualiza_presupuesto(NEW.t02_cod_proy, fn_ult_version_proy(NEW.t02_cod_proy));
END $$
DELIMITER ;