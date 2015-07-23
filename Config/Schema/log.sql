CREATE TABLE `{prefix}logs` (
	`id` varchar(36) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`model_alias` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`model_data` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`model_options` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`model_action` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`request_user_agent` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`request_client_ip` varchar(45) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`request_method` varchar(45) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`request_referer` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`request_url` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`auth_user` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`created` datetime DEFAULT NULL,	PRIMARY KEY  (`id`),
	UNIQUE KEY `id_UNIQUE` (`id`)) 	DEFAULT CHARSET=latin1,
	COLLATE=latin1_swedish_ci,
	ENGINE=InnoDB;