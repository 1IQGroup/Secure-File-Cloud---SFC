
DROP USER IF EXISTS 'jakub'@'localhost';
CREATE USER 'jakub'@'localhost' IDENTIFIED BY 'KrAHw#.!6W';
GRANT ALL PRIVILEGES ON projekt_chmura.* TO 'jakub'@'localhost';
FLUSH PRIVILEGES;