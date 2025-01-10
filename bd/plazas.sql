-- ESTO YA NO SE USO PORQUE O FUNCIONÓ SE USO COMO ALGORITMO

DELIMITER //
CREATE PROCEDURE asignar_plaza()
BEGIN
    DECLARE ultimo_historial INT;
    DECLARE plaza INT;
    DECLARE done INT DEFAULT FALSE;
    DECLARE user_rfc VARCHAR(20);
    DECLARE user_puesto INT;
    DECLARE cur CURSOR FOR
        SELECT u.RFC, e.id_puesto
        FROM Usuario u
        INNER JOIN Empleado e ON u.RFC = e.RFC
        WHERE u.estado = 'alta' AND u.categoria = 'user';

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN cur;

    read_loop: LOOP
        FETCH cur INTO user_rfc, user_puesto;
        IF done THEN
            LEAVE read_loop;
        END IF;

        -- Buscar una plaza disponible
        SELECT id_plaza
        INTO plaza
        FROM Plaza
        WHERE id_puesto = user_puesto
        AND RFC IS NULL
        AND YEAR(elaboracion) = YEAR(CURDATE())
        LIMIT 1;

        UPDATE Plaza
        SET RFC = user_rfc
        WHERE id_puesto = user_puesto
        AND RFC IS NULL
        AND YEAR(elaboracion) = YEAR(CURDATE())
        LIMIT 1;

        -- Buscar el id_historial_plaza y almacenarlo en la variable
        SELECT id_historial_plaza
        INTO ultimo_historial
        FROM Historial_Plaza
        WHERE fecha_fin IS NULL
        AND YEAR(elaboracion) = YEAR(CURDATE()) - 1
        ORDER BY id_historial_plaza DESC
        LIMIT 1;

        -- Actualizar Historial_Plaza usando la variable
        IF ultimo_historial IS NOT NULL THEN
            UPDATE Historial_Plaza
            SET fecha_fin = CONCAT(YEAR(CURDATE()) - 1, '-12-31')
            WHERE id_historial_plaza = ultimo_historial;
        
        -- Insertar nuevo registro en Historial_Plaza
            INSERT INTO Historial_Plaza (RFC, id_plaza, fecha_inicio) VALUES(user_rfc, plaza, CONCAT(YEAR(CURDATE()), '-01-01'));
        END IF;


    END LOOP;

    CLOSE cur;
END //

DELIMITER ;


CALL asignar_plaza();

 -- Limpiar plazas antiguas
UPDATE Plaza SET RFC = NULL WHERE YEAR(elaboracion) = YEAR(CURDATE()) - 1;

-------------------------------------------------------------------------------------------------

-- PARA SABER QUE EMPLEADOS FALTAN DE PLAZA
SELECT Usuario.RFC, (SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) as PUESTO,
(SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) as DEPARTAMENTO 
FROM Usuario JOIN Empleado ON Empleado.RFC = Usuario.RFC WHERE Usuario.categoria = 'user' AND Usuario.estado = 'alta' AND (SELECT RFC FROM Plaza WHERE RFC = Usuario.RFC LIMIT 1) IS NULL

-------------------------------------------------------------------------------------------------

CREATE DATABASE cnomina CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


UPDATE Historial_Plaza hp
JOIN Plaza p ON hp.RFC = p.RFC
SET hp.id_plaza = p.id_plaza
WHERE YEAR(hp.elaboracion) = 2025
AND hp.fecha_fin IS NULL
AND p.RFC IS NOT NULL;
