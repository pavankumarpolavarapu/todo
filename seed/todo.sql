USE todo;

CREATE TABLE `users` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `first_name` varchar(255),
  `mid_name` varchar(255),
  `last_name` varchar(255),
  `email_id` varchar(255),
  `mobile_num` varchar(255),
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `categories` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `name` varchar(255)
);

CREATE TABLE `tasks` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `category_id` int,
  `name` varchar(255),
  `due_date` timestamp DEFAULT now(),
  `completed` smallint DEFAULT 0,
  `completed_date` timestamp NULL,
  `remind_date` timestamp NULL,
  `created_at` timestamp DEFAULT now(),
  `updated_at` timestamp NULL ON UPDATE CURRENT_TIMESTAMP
);

ALTER TABLE `tasks` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
ALTER TABLE `tasks` ADD FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
ALTER TABLE `categories` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
