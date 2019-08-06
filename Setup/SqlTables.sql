CREATE TABLE `users` (
	`id`                        int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name`                      varchar(255) CHARACTER SET utf8
	COLLATE utf8_unicode_ci                               DEFAULT NULL,
	`username`                  varchar(255) CHARACTER SET utf8
	COLLATE utf8_unicode_ci                               DEFAULT NULL,
	`email`                     varchar(255) CHARACTER SET utf8
	COLLATE utf8_unicode_ci                               DEFAULT NULL,
	`force_reset_password`      tinyint(1)       NOT NULL DEFAULT '0',
	`password_hash`             varchar(255) CHARACTER SET utf8
	COLLATE utf8_unicode_ci                      NOT NULL,
	`activation_token`          varchar(64)               DEFAULT NULL,
	`activation_hash`           varchar(255) CHARACTER SET utf8
	COLLATE utf8_unicode_ci                               DEFAULT NULL,
	`password_reset_hash`       varchar(64) CHARACTER SET utf8
	COLLATE utf8_unicode_ci                               DEFAULT NULL,
	`password_reset_expires_at` datetime                  DEFAULT NULL,
	`is_active`                 tinyint(1)       NOT NULL DEFAULT '0',
	`created_at`                timestamp        NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at`                timestamp        NOT NULL DEFAULT CURRENT_TIMESTAMP
	ON UPDATE CURRENT_TIMESTAMP,
	`deleted_at`                timestamp        NULL     DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
	UNIQUE KEY `username_UNIQUE` (`username`),
	UNIQUE KEY `password_reset_hash` (`password_reset_hash`),
	UNIQUE KEY `activation_hash` (`activation_hash`),
	UNIQUE KEY `activation_token_UNIQUE` (`activation_token`)
)
	ENGINE = InnoDB
	AUTO_INCREMENT = 1
	DEFAULT CHARSET = utf8
	COLLATE = utf8_unicode_ci;



CREATE TABLE `remembered_logins` (
  `token_hash` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `expires_at` datetime NOT NULL,
  PRIMARY KEY (`token_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `last_login` (
	`id`         bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`user_id`    int(10) UNSIGNED    NOT NULL,
	`last_time`  timestamp           NULL             DEFAULT CURRENT_TIMESTAMP,
	`browser`    varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	`location`   varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	`ip_address` varchar(14) COLLATE utf8_unicode_ci  DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `id_UNIQUE` (`id`)
)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8
	COLLATE = utf8_unicode_ci;