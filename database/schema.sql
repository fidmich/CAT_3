-- Obituary Management Platform
-- Run this file in phpMyAdmin (Import) or via: mysql -u root -p < schema.sql

CREATE DATABASE IF NOT EXISTS obituary_platform
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE obituary_platform;

CREATE TABLE IF NOT EXISTS obituaries (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100) NOT NULL,
    date_of_birth   DATE NOT NULL,
    date_of_death   DATE NOT NULL,
    content         TEXT NOT NULL,
    author          VARCHAR(100) NOT NULL,
    submission_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    slug            VARCHAR(255) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
