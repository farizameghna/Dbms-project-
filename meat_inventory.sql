-- SQL file to create database and tables for Meat Inventory
CREATE DATABASE IF NOT EXISTS meat_inventory;
USE meat_inventory;

CREATE TABLE IF NOT EXISTS livestock (
  id INT AUTO_INCREMENT PRIMARY KEY,
  animal VARCHAR(100),
  date DATE,
  qty INT,
  farm VARCHAR(150),
  status VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS processing (
  id INT AUTO_INCREMENT PRIMARY KEY,
  batch VARCHAR(50),
  animal VARCHAR(100),
  cut VARCHAR(100),
  date DATE,
  operator VARCHAR(100),
  status VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS storage (
  id INT AUTO_INCREMENT PRIMARY KEY,
  unit VARCHAR(100),
  temp FLOAT,
  humidity FLOAT,
  status VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS stock (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product VARCHAR(150),
  batch VARCHAR(50),
  qty FLOAT,
  unit VARCHAR(100),
  expiry DATE
);

CREATE TABLE IF NOT EXISTS fifo (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product VARCHAR(150),
  batch VARCHAR(50),
  entry_date DATE,
  expiry DATE,
  rotation VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS sensor (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sensor VARCHAR(50),
  location VARCHAR(150),
  temp VARCHAR(50),
  humidity VARCHAR(50),
  last_update DATETIME
);

CREATE TABLE IF NOT EXISTS yields (
  id INT AUTO_INCREMENT PRIMARY KEY,
  batch VARCHAR(50),
  total_weight VARCHAR(50),
  meat VARCHAR(50),
  trimmings VARCHAR(50),
  offal VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS recall (
  id INT AUTO_INCREMENT PRIMARY KEY,
  batch VARCHAR(50),
  issue VARCHAR(255),
  date DATE,
  status VARCHAR(100)
);

-- sample data
INSERT INTO livestock (animal, date, qty, farm, status) VALUES ('Beef','2025-08-01',20,'Farm A','Processed');
INSERT INTO processing (batch, animal, cut, date, operator, status) VALUES ('P001','Beef','Ribeye','2025-08-02','John','Done');
INSERT INTO storage (unit, temp, humidity, status) VALUES ('Freezer A',-18,65,'Normal');
INSERT INTO stock (product, batch, qty, unit, expiry) VALUES ('Beef - Ribeye','P001',180,'Freezer A','2025-09-15');
INSERT INTO fifo (product, batch, entry_date, expiry, rotation) VALUES ('Pork','P002','2025-07-25','2025-08-20','FEFO');
INSERT INTO sensor (sensor, location, temp, humidity, last_update) VALUES ('S1','Freezer A','-18C','68%','2025-08-03 12:45:00');
INSERT INTO yields (batch, total_weight, meat, trimmings, offal) VALUES ('P001','200kg','180kg','15kg','5kg');
INSERT INTO recall (batch, issue, date, status) VALUES ('P002','Temp breach','2025-08-01','Recalled');
