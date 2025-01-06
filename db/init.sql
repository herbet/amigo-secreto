CREATE DATABASE IF NOT EXISTS amigo_secreto;

USE amigo_secreto;

CREATE TABLE `users` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `groups` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    created_by INT NOT NULL,
    draw_completed TINYINT(1) DEFAULT 0,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE `participants` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    user_id INT DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE `group_members` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT NOT NULL,
    participant_id INT NOT NULL,
    secret_friend_id INT DEFAULT NULL,
    FOREIGN KEY (group_id) REFERENCES groups(id),
    FOREIGN KEY (participant_id) REFERENCES participants(id),
    FOREIGN KEY (secret_friend_id) REFERENCES participants(id)
);


