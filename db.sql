CREATE DATABASE warehouses;
USE warehouses;

CREATE TABLE warehouses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    location VARCHAR(100),
    capacity INT
) ;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description VARCHAR(100),
    cost DECIMAL(10,2),
    space INT
) ;

CREATE TABLE warehouse_stock (
    warehouse_id INT,
    product_id INT,
    quantity INT NOT NULL,
    last_update DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (warehouse_id, product_id),
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
) ;

CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    warehouse_from INT,
    warehouse_to INT,
    quantity INT NOT NULL,
    date_moved DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (warehouse_from) REFERENCES warehouses(id),
    FOREIGN KEY (warehouse_to) REFERENCES warehouses(id)
) ;


INSERT INTO warehouses (name, location, capacity) VALUES
('External', 'Outside System', NULL),
('Central Warehouse', 'Dublin', 1000),
('Regional Warehouse', 'Cork', 500),
('Tralee Warehouse', 'Tralee', 250),
('Killarney Warehouse', 'Killarney', 250);

INSERT INTO products (name, description, cost, space) VALUES
('Computer', 'Desktop Computer', 1000.00, 5),
('Laptop', 'Business Laptop', 700.00, 3),
('Vacuum Cleaner', 'Home Vacuum Cleaner', 300.00, 2);

INSERT INTO warehouse_stock (warehouse_id, product_id, quantity) VALUES
(2, 1, 300),
(3, 2, 100);


INSERT INTO transactions (product_id, warehouse_from, warehouse_to, quantity) VALUES
(1, 1, 2, 100),
(1, 1, 2, 100),
(1, 1, 2, 100),
(2, 1, 3, 200),
(2, 3, 1, 100);



2 ways to solve the problem: 

