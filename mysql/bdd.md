# Queries used

## Create database

```sql

CREATE TABLE T_PRODUIT(
id INT PRIMARY KEY AUTO_INCREMENT,
name VARCHAR(50),
description TEXT,
prix DOUBLE,
date_creation DATETIME
);

```

## CRUD operations

### Create a new product

```sql

INSERT INTO T_PRODUIT (  name,   description,   prix,  date_creation)
               VALUES ( :name,  :description,  :prix,     :date)

```

### Read all products

```sql

SELECT \* FROM T_PRODUIT ORDER BY date_creation DESC

```

### Read a product by id

```sql

SELECT \* FROM T_PRODUIT WHERE id = :id

```

### Update a product

```sql

UPDATE T_PRODUIT SET name = :name, description = :description, prix = :prix WHERE id = :id

```

### Delete a product

```sql

DELETE FROM T_PRODUIT WHERE id = :id

```

## Drop table

```sql

DROP TABLE T_PRODUIT

```

## Reset table

```sql

TRUNCATE TABLE T_PRODUIT

```

## Get multiples ids

```sql
SELECT * FROM table
WHERE id IN (1, 2, 3, 4, 5)
```
