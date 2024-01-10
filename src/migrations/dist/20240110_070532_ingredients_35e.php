<?php

namespace migrations;

require_once(__DIR__ . '/../lib.inc.php');

if (defined('APPLY')) goto APPLY;
if (defined('ROLLBACK')) goto ROLLBACK;

goto END;

APPLY:

// ingredients
Db()->query(<<<'SQL'
--
-- Create table `ingredient_type`
--
CREATE TABLE ingredient_type (
  id INT NOT NULL AUTO_INCREMENT,
  title VARCHAR(255) DEFAULT NULL,
  code CHAR(1) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 4,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_0900_ai_ci;

--
-- Create table `ingredient`
--
CREATE TABLE ingredient (
  id INT NOT NULL AUTO_INCREMENT,
  type_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  price DECIMAL(19, 2) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 10,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_0900_ai_ci;

--
-- Create foreign key
--
ALTER TABLE ingredient 
  ADD CONSTRAINT FK_ingredient_type_id FOREIGN KEY (type_id)
    REFERENCES ingredient_type(id);

-- 
-- Dumping data for table ingredient_type
--
INSERT INTO ingredient_type VALUES
(1, 'Тесто', 'd'),
(2, 'Сыр', 'c'),
(3, 'Начинка', 'i');

-- 
-- Dumping data for table ingredient
--
INSERT INTO ingredient VALUES
(1, 1, 'Тонкое тесто', 100.00),
(2, 1, 'Пышное тесто', 110.00),
(3, 1, 'Ржаное тесто', 150.00),
(4, 2, 'Моцарелла', 50.00),
(5, 2, 'Рикотта', 70.00),
(6, 3, 'Колбаса', 30.00),
(7, 3, 'Ветчина', 35.00),
(8, 3, 'Грибы', 50.00),
(9, 3, 'Томаты', 10.00);
SQL
);

goto END;

ROLLBACK:

// Rollback ingredients
Db()->query(<<<'SQL'
--
-- Drop table `ingredient`
--
DROP TABLE IF EXISTS ingredient;

--
-- Drop table `ingredient_type`
--
DROP TABLE IF EXISTS ingredient_type;
SQL
);

goto END;

END:
