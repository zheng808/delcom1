
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

#-----------------------------------------------------------------------------
#-- wf_crm
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `wf_crm`;


CREATE TABLE `wf_crm`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`tree_left` INTEGER,
	`tree_right` INTEGER,
	`parent_node_id` INTEGER,
	`tree_id` INTEGER,
	`department_name` VARCHAR(255),
	`first_name` VARCHAR(255),
	`middle_name` VARCHAR(255),
	`last_name` VARCHAR(255),
	`salutation` VARCHAR(64),
	`titles` VARCHAR(255),
	`job_title` VARCHAR(255),
	`alpha_name` VARCHAR(255),
	`email` VARCHAR(255),
	`work_phone` VARCHAR(64),
	`mobile_phone` VARCHAR(64),
	`home_phone` VARCHAR(64),
	`fax` VARCHAR(64),
	`homepage` VARCHAR(255),
	`private_notes` TEXT,
	`public_notes` TEXT,
	`is_company` TINYINT default 0 NOT NULL,
	`is_in_addressbook` TINYINT default 1 NOT NULL,
	`created_at` DATETIME,
	`updated_at` DATETIME,
	PRIMARY KEY (`id`),
	KEY `alpha_name`(`alpha_name`),
	KEY `nested_set_left`(`tree_left`),
	KEY `nested_set_right`(`tree_right`),
	KEY `nested_set_id`(`tree_id`),
	KEY `email`(`email`),
	INDEX `wf_crm_FI_1` (`parent_node_id`),
	CONSTRAINT `wf_crm_FK_1`
		FOREIGN KEY (`parent_node_id`)
		REFERENCES `wf_crm` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- wf_crm_category_ref
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `wf_crm_category_ref`;


CREATE TABLE `wf_crm_category_ref`
(
	`crm_id` INTEGER  NOT NULL,
	`category_id` INTEGER  NOT NULL,
	PRIMARY KEY (`crm_id`,`category_id`),
	CONSTRAINT `wf_crm_category_ref_FK_1`
		FOREIGN KEY (`crm_id`)
		REFERENCES `wf_crm` (`id`)
		ON DELETE CASCADE,
	INDEX `wf_crm_category_ref_FI_2` (`category_id`),
	CONSTRAINT `wf_crm_category_ref_FK_2`
		FOREIGN KEY (`category_id`)
		REFERENCES `wf_crm_category` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- wf_crm_category
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `wf_crm_category`;


CREATE TABLE `wf_crm_category`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`tree_left` INTEGER,
	`tree_right` INTEGER,
	`tree_id` INTEGER,
	`parent_node_id` INTEGER,
	`private_name` VARCHAR(255)  NOT NULL,
	`public_name` VARCHAR(255)  NOT NULL,
	`is_subscribable` TINYINT default 0 NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `wf_crm_category_FI_1` (`parent_node_id`),
	CONSTRAINT `wf_crm_category_FK_1`
		FOREIGN KEY (`parent_node_id`)
		REFERENCES `wf_crm_category` (`id`)
		ON DELETE SET NULL
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- wf_crm_address
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `wf_crm_address`;


CREATE TABLE `wf_crm_address`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`crm_id` INTEGER,
	`type` VARCHAR(255),
	`line1` VARCHAR(255),
	`line2` VARCHAR(255),
	`city` VARCHAR(128),
	`region` VARCHAR(128),
	`postal` VARCHAR(16),
	`country` VARCHAR(2)  NOT NULL,
	PRIMARY KEY (`id`),
	KEY `country`(`country`),
	KEY `region`(`country`, `region`),
	INDEX `wf_crm_address_FI_1` (`crm_id`),
	CONSTRAINT `wf_crm_address_FK_1`
		FOREIGN KEY (`crm_id`)
		REFERENCES `wf_crm` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- wf_crm_correspondence
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `wf_crm_correspondence`;


CREATE TABLE `wf_crm_correspondence`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`wf_crm_id` INTEGER,
	`received` TINYINT default 1 NOT NULL,
	`method` VARCHAR(16)  NOT NULL,
	`subject` VARCHAR(128),
	`message` TEXT,
	`whendone` DATETIME  NOT NULL,
	`is_new` TINYINT default 1 NOT NULL,
	PRIMARY KEY (`id`),
	KEY `new`(`is_new`),
	KEY `person`(`wf_crm_id`),
	CONSTRAINT `wf_crm_correspondence_FK_1`
		FOREIGN KEY (`wf_crm_id`)
		REFERENCES `wf_crm` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
