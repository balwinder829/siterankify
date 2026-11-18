CREATE TABLE IF NOT EXISTS `contact_submissions` (
  `contact_submission_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `source` VARCHAR(32) NOT NULL DEFAULT 'contact',
  `name` VARCHAR(64) NOT NULL,
  `email` VARCHAR(320) NOT NULL,
  `subject` VARCHAR(128) NOT NULL,
  `message` TEXT NOT NULL,
  `ip` VARCHAR(45) NULL DEFAULT NULL,
  `user_agent` VARCHAR(512) NULL DEFAULT NULL,
  `is_read` TINYINT(1) NOT NULL DEFAULT 0,
  `datetime` DATETIME NOT NULL,
  PRIMARY KEY (`contact_submission_id`),
  KEY `user_id` (`user_id`),
  KEY `email` (`email`),
  KEY `source` (`source`),
  KEY `datetime` (`datetime`),
  KEY `is_read` (`is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


