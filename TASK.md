# Текст задания

Необходимо разработать простой конструктор блюд.

В приложенном архиве дамп базы данных, содержащий исходные данные для
конструктора. В базе содержится две таблицы

* В таблице ingredient_type содержатся типы возможных ингредиентов. Каждому типу
соответствует уникальный 1-буквенный код
* В таблице ingredient хранятся конкретные ингредиенты с ценой

На вход конструктора поступает строка, содержащая коды ингредиентов, которые должны
входит в полученное блюдо. Один ингредиент может быть указан несколько раз. Например,
строка «dcciii» означает блюдо, состоящее из одного теста, двух видов сыра и трёх видов
начинки.

Необходимо сформировать набор всех возможных комбинаций ингредиентов без
повторений ('колбаса' + 'ветчина' и 'ветчина' + 'колбаса' считаются повторениями).
Результатом работу конструктора должен быть JSON-массив, содержащий все возможные
комбинации. Пример вывода для входной строки «dcciii»:

```json
[
  {
    "products": [
      {"type": "Тесто", "value": "Тонкое тесто"},
      {"type": "Сыр", "value": "Моцарелла"},
      {"type": "Начинка", "value": "Ветчина"},
      {"type": "Начинка", "value": "Колбаса"}
    ],
    "price": 215
  },
  {
    "products": [
      {"type": "Тесто", "value": "Тонкое тесто"},
      {"type": "Сыр", "value": "Моцарелла"},
      {"type": "Начинка", "value": "Ветчина"},
      {"type": "Начинка", "value": "Грибы"}
    ],
    "price": 235
  },
  ...
]
```

Конструктор может быть реализован в виде REST-API или в виде консольного приложения.
Платформа для реализации также выбирается на усмотрение соискателя.

# Дамп базы данных

```sql
-- 
-- Disable foreign keys
-- 
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

-- 
-- Set SQL mode
-- 
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 
-- Set character set the client will use to send SQL statements to the server
--
SET NAMES 'utf8mb4';

--
-- Set default database
--
USE test_task;

--
-- Drop table `ingredient`
--
DROP TABLE IF EXISTS ingredient;

--
-- Drop table `ingredient_type`
--
DROP TABLE IF EXISTS ingredient_type;

--
-- Set default database
--
USE test_task;

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

-- 
-- Restore previous SQL mode
-- 
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;

-- 
-- Enable foreign keys
-- 
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
```
