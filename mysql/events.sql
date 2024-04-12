DROP EVENT IF EXISTS delete_expired_records_event;
DROP EVENT IF EXISTS delete_expired_records_event2;
DROP EVENT IF EXISTS delete_expired_records_event3;

DELIMITER //
--usuwanie zawartości z zablokowanymi ip
CREATE EVENT IF NOT EXISTS delete_expired_records_event
ON SCHEDULE EVERY 1 MINUTE -- Sprawdzanie co minutę, czy istnieją rekordy do usunięcia
DO
BEGIN
    DECLARE done BOOLEAN DEFAULT FALSE;
    DECLARE id_to_delete INT;
    DECLARE cur CURSOR FOR 
        SELECT id
        FROM blokada
        WHERE TIMESTAMPDIFF(MINUTE, czas, NOW()) >= 20
        ORDER BY czas ASC;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    OPEN cur;
    
    read_loop: LOOP
        FETCH cur INTO id_to_delete;
        IF done THEN
            LEAVE read_loop;
        END IF;
        
        DELETE FROM blokada WHERE id = id_to_delete;
    END LOOP;
    
    CLOSE cur;
    
END//
--usuwanie zawartości z kodami do rejestracji i hasłem
CREATE EVENT IF NOT EXISTS delete_expired_records_event2
ON SCHEDULE EVERY 1 MINUTE -- Sprawdzanie co minutę, czy istnieją rekordy do usunięcia
DO
BEGIN
    DECLARE done BOOLEAN DEFAULT FALSE;
    DECLARE id_to_delete INT;
    DECLARE cur CURSOR FOR 
        SELECT id
        FROM rejestracja
        WHERE TIMESTAMPDIFF(MINUTE, czas, NOW()) >= 60
        ORDER BY czas ASC;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    OPEN cur;
    
    read_loop: LOOP
        FETCH cur INTO id_to_delete;
        IF done THEN
            LEAVE read_loop;
        END IF;
        
        DELETE FROM rejestracja WHERE id = id_to_delete;
    END LOOP;
    
    CLOSE cur;
    
END//
--usuwanie zawartości z zablokowaną możliwością wysyłania emaila do hasła i rejestracji
CREATE EVENT IF NOT EXISTS delete_expired_records_event3
ON SCHEDULE EVERY 1 MINUTE -- Sprawdzanie co minutę, czy istnieją rekordy do usunięcia
DO
BEGIN
    DECLARE done BOOLEAN DEFAULT FALSE;
    DECLARE id_to_delete INT;
    DECLARE cur CURSOR FOR 
        SELECT id
        FROM blokada2
        WHERE TIMESTAMPDIFF(MINUTE, czas, NOW()) >= 60
        ORDER BY czas ASC;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    OPEN cur;
    
    read_loop: LOOP
        FETCH cur INTO id_to_delete;
        IF done THEN
            LEAVE read_loop;
        END IF;
        
        DELETE FROM blokada WHERE id = id_to_delete;
    END LOOP;
    
    CLOSE cur;
    
END//

DELIMITER ;
