CREATE DATABASE IF NOT EXISTS ctf;
USE ctf;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50),
    password VARCHAR(50)
);

CREATE TABLE flags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    flag VARCHAR(255)
);

INSERT INTO users (login, password)
VALUES ('admin', 'netvoyadmin123');

INSERT INTO flags (flag)
VALUES ('h3x1514{d0nt_7ry_t0_l0g1n_b4d_sql1}');

