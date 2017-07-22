CREATE TABLE `location_details` (
  `id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `user_id` VARCHAR(100) NOT NULL COLLATE utf16_unicode_ci,
  `location` LONGTEXT NOT NULL COLLATE utf16_unicode_ci,
  `time` INT(10) NOT NULL,
  KEY `user_id` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `user_gcm_details`(`user_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf16 COLLATE=utf16_unicode_ci;

CREATE TABLE `location_requests` (
  `id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `asker_id` VARCHAR(100) NOT NULL COLLATE utf16_unicode_ci,
  `user_id` VARCHAR(100) NOT NULL COLLATE utf16_unicode_ci,
  `permit_status` VARCHAR(10) COLLATE utf16_unicode_ci,
  `time` INT(10) NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `user_gcm_details`(`user_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf16 COLLATE=utf16_unicode_ci;

CREATE TABLE `user_gcm_details` (
  `user_id` VARCHAR(100) NOT NULL PRIMARY KEY COLLATE utf16_unicode_ci,
  `gcm` VARCHAR(512) NOT NULL COLLATE utf16_unicode_ci
) ENGINE=INNODB DEFAULT CHARSET=utf16 COLLATE=utf16_unicode_ci;