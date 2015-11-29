DROP PROCEDURE IF EXISTS sp_sel_concursos;

DELIMITER $$
CREATE PROCEDURE sp_sel_concursos()

BEGIN
  SELECT num_conc as numero,
     anio_conc as anio,
     nom_conc as nombre,
     abr_conc as abreviado,
     coment_conc as comentario
    FROM adm_concursos
   ORDER BY num_conc ASC ;
END $$
DELIMITER ;