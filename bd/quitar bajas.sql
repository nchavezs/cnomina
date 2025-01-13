CREATE TEMPORARY TABLE RFCs (
    rfc VARCHAR(20)
);

INSERT INTO RFCs (rfc) VALUES 
('NEFJ951204N41'), ('GATJ910307HQ1'), ('GAH0911020E51'), 
('RIRS7012282NA'), ('CAGX0410291P3'), ('SAGA801006AL2'),
('BEVA680311J97'), ('HEJL910527RW7'), ('OENA8010173V3'),
('GURE9607212Y9'), ('LILA671126RY2'), ('FAJL930706BK1'),
('PEMJ970312K19'), ('GISM9308135L9'), ('BUOL790907IL7'),
('MOAG750518H52'), ('CACC540508GA2'), ('GOMG861218SG5'),
('OERP051125NJ3'), ('VAHA9511175S4'), ('MOGL581227IFA'),
('GAVE8802119E2'), ('MAPA011115QQ6'), ('AOSC7403189H9'),
('GUAA041021HI5'), ('MEMN6808263S6'), ('CAOM550209LZ6'),
('RIJD5803097N0'), ('TAGB7002157Q8'), ('AASA020713BA6'),
('RULG870103MYA'), ('GUFI790315QP7'), ('SOSA610826NZ5'),
('GUDI7305257B9'), ('MOMG6210272F5'), ('VAVL920503731'),
('GAGR860826LU0');

DELIMITER //

CREATE PROCEDURE ActualizarDatos()
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE rfc_actual VARCHAR(20);
    DECLARE cur CURSOR FOR SELECT rfc FROM RFCs;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN cur;

    bucle: LOOP
        FETCH cur INTO rfc_actual;
        IF done THEN
            LEAVE bucle;
        END IF;

        -- Operación 1: Eliminar la última baja
        DELETE FROM Baja
        WHERE id_baja = (
            SELECT id_baja
            FROM (SELECT id_baja FROM Baja WHERE rfc = rfc_actual ORDER BY id_baja DESC LIMIT 1) AS Subconsulta
        );

        -- Operación 2: Actualizar el estado del usuario
        UPDATE Usuario 
        SET estado = 'alta' 
        WHERE rfc = rfc_actual;

        -- Operación 3: Actualizar Historial_Plaza
        UPDATE Historial_Plaza 
        SET fecha_fin = NULL 
        WHERE id_historial_plaza = (
            SELECT id_historial_plaza
            FROM (SELECT id_historial_plaza FROM Historial_Plaza WHERE rfc = rfc_actual ORDER BY id_historial_plaza DESC LIMIT 1) AS Subconsulta
        );

        -- Operación 4: Actualizar Plaza
        UPDATE Plaza 
        SET rfc = rfc_actual
        WHERE id_plaza = (
            SELECT id_plaza
            FROM (SELECT id_plaza FROM Historial_Plaza WHERE rfc = rfc_actual ORDER BY id_historial_plaza DESC LIMIT 1) AS Subconsulta
        );

        DELETE FROM Historial WHERE rfc = rfc_actual AND tipo = "baja" ORDER BY id_historial DESC LIMIT 1;
    END LOOP;

    CLOSE cur;
END;
//

DELIMITER ;

CALL ActualizarDatos();