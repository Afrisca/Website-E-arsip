CREATE DATABASE IF NOT EXISTS e_arsip;
USE e_arsip;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','pegawai') NOT NULL
);

INSERT INTO users (username, password, role) VALUES
('admin1', SHA2('adminpass', 256), 'admin'),
('pegawai1', SHA2('pegawaipass', 256), 'pegawai');
