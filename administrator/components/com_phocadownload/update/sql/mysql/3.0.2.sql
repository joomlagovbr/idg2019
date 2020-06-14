ALTER TABLE `#__phocadownload` ADD COLUMN `token` char(64) default NULL;
ALTER TABLE `#__phocadownload` ADD COLUMN `tokenhits` int(11) NOT NULL default 0;

-- BEGIN PROCEDURE
-- DELIMITER $$
-- CREATE PROCEDURE AlterTable()
-- BEGIN
--     DECLARE _count INT;
-- 	DECLARE _count2 INT;
-- 	
--    SET _count = ( SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '#__phocadownload' AND COLUMN_NAME = 'token' );
--    IF _count = 0 THEN ALTER TABLE `#__phocadownload` ADD COLUMN `token` char(64) default NULL;
-- 	END IF;
-- 	
--     SET _count2 = ( SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '#__phocadownload' AND COLUMN_NAME = 'tokenhits' );
--     IF _count2 = 0 THEN ALTER TABLE `#__phocadownload` ADD COLUMN `tokenhits` int(11) NOT NULL default 0;
-- 	END IF;
-- END $$
-- DELIMITER ;
-- CALL AlterTable();
-- DROP PROCEDURE IF EXISTS AlterTable;
-- END PROCEDURE

