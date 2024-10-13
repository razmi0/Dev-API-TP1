# Queries used

CREATE TABLE T_PRODUIT(
id INT PRIMARY KEY AUTO_INCREMENT,
name VARCHAR(50),
description TEXT,
prix INT,
date_creation DATETIME
);

INSERT INTO T_PRODUIT (name, description, prix, date_creation)
VALUES ('Gaming Console PlayStation 5', 'Next-generation gaming console with 4K resolution.', 499, '2024-10-08 12:45:55');

DELETE FROM T_PRODUIT WHERE id = :id

SELECT \* FROM T_PRODUIT WHERE id = :id

SELECT \* FROM T_PRODUIT ORDER BY date_creation DESC

UPDATE T_PRODUIT SET name = :name, description = :description, prix = :prix WHERE id = :id

INSERT INTO T_PRODUIT (name, description, prix, date_creation) VALUES (:name, :description, :prix, :date)
