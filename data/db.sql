CREATE DATABASE easy_complain;

CREATE TABLE `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `fullname` varchar(100) NOT NULL,
    `email` varchar(100) NOT NULL,
    `password` varchar(100) NOT NULL, 
    `is_admin` TINYINT(1) not null default 0,
    `is_active` TINYINT(1) not null default 1, 


) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `feedbacks` (
    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `comment` TEXT NOT NULL,
    `file_path` varchar(200) NOT NULL,
    `user_id` int NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
    

) ENGINE=InnoDB DEFAULT CHARSET=latin1;