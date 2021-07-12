
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

#-----------------------------------------------------------------------------
#-- part_category
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `part_category`;


CREATE TABLE `part_category`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255)  NOT NULL,
	`lft` INTEGER  NOT NULL,
	`rgt` INTEGER  NOT NULL,
	`scope` INTEGER,
	PRIMARY KEY (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- part
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `part`;


CREATE TABLE `part`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`part_category_id` INTEGER,
	`name` VARCHAR(255)  NOT NULL,
	`description` TEXT,
	`has_serial_number` TINYINT default 0 NOT NULL,
	`is_multisku` TINYINT default 0 NOT NULL,
	`manufacturer_id` INTEGER,
	`active` TINYINT default 1 NOT NULL,
	`origin` VARCHAR(255),
	PRIMARY KEY (`id`),
	INDEX `part_FI_1` (`part_category_id`),
	CONSTRAINT `part_FK_1`
		FOREIGN KEY (`part_category_id`)
		REFERENCES `part_category` (`id`),
	INDEX `part_FI_2` (`manufacturer_id`),
	CONSTRAINT `part_FK_2`
		FOREIGN KEY (`manufacturer_id`)
		REFERENCES `manufacturer` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- part_option
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `part_option`;


CREATE TABLE `part_option`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`part_id` INTEGER  NOT NULL,
	`name` VARCHAR(255)  NOT NULL,
	`is_color` TINYINT default 0 NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `part_option_FI_1` (`part_id`),
	CONSTRAINT `part_option_FK_1`
		FOREIGN KEY (`part_id`)
		REFERENCES `part` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- part_option_value
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `part_option_value`;


CREATE TABLE `part_option_value`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`part_variant_id` INTEGER  NOT NULL,
	`part_option_id` INTEGER  NOT NULL,
	`value` VARCHAR(255)  NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `part_option_value_FI_1` (`part_variant_id`),
	CONSTRAINT `part_option_value_FK_1`
		FOREIGN KEY (`part_variant_id`)
		REFERENCES `part_variant` (`id`),
	INDEX `part_option_value_FI_2` (`part_option_id`),
	CONSTRAINT `part_option_value_FK_2`
		FOREIGN KEY (`part_option_id`)
		REFERENCES `part_option` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- part_variant
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `part_variant`;


CREATE TABLE `part_variant`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`part_id` INTEGER  NOT NULL,
	`is_default_variant` TINYINT  NOT NULL,
	`manufacturer_sku` VARCHAR(255),
	`internal_sku` VARCHAR(255),
	`use_default_units` TINYINT default 0 NOT NULL,
	`units` VARCHAR(6),
	`use_default_costing` TINYINT default 0 NOT NULL,
	`cost_calculation_method` VARCHAR(7) default '' NOT NULL,
	`unit_cost` DECIMAL(8,2),
	`use_default_pricing` TINYINT default 0 NOT NULL,
	`unit_price` DECIMAL(8,2),
	`markup_amount` DECIMAL(8,2),
	`markup_percent` INTEGER,
	`taxable_hst` TINYINT default 1 NOT NULL,
	`taxable_gst` TINYINT default 1 NOT NULL,
	`taxable_pst` TINYINT default 1 NOT NULL,
	`enviro_levy` DECIMAL(8,2),
	`battery_levy` DECIMAL(8,2),
	`use_default_dimensions` TINYINT default 0 NOT NULL,
	`shipping_weight` DECIMAL(8,3),
	`shipping_width` DECIMAL(8,3),
	`shipping_height` DECIMAL(8,3),
	`shipping_depth` DECIMAL(8,3),
	`shipping_volume` DECIMAL(8,3),
	`use_default_inventory` TINYINT default 0 NOT NULL,
	`track_inventory` TINYINT default 1 NOT NULL,
	`minimum_on_hand` DECIMAL(8,3) default 0 NOT NULL,
	`maximum_on_hand` DECIMAL(8,3),
	`current_on_hand` DECIMAL(8,3) default 0 NOT NULL,
	`current_on_hold` DECIMAL(8,3) default 0 NOT NULL,
	`current_on_order` DECIMAL(8,3) default 0 NOT NULL,
	`location` VARCHAR(255),
	`last_inventory_update` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `part_variant_FI_1` (`part_id`),
	CONSTRAINT `part_variant_FK_1`
		FOREIGN KEY (`part_id`)
		REFERENCES `part` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- part_supplier
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `part_supplier`;


CREATE TABLE `part_supplier`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`part_variant_id` INTEGER,
	`supplier_id` INTEGER,
	`supplier_sku` VARCHAR(255),
	`notes` TEXT,
	PRIMARY KEY (`id`),
	INDEX `part_supplier_FI_1` (`part_variant_id`),
	CONSTRAINT `part_supplier_FK_1`
		FOREIGN KEY (`part_variant_id`)
		REFERENCES `part_variant` (`id`),
	INDEX `part_supplier_FI_2` (`supplier_id`),
	CONSTRAINT `part_supplier_FK_2`
		FOREIGN KEY (`supplier_id`)
		REFERENCES `supplier` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- part_photo
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `part_photo`;


CREATE TABLE `part_photo`
(
	`part_id` INTEGER,
	`part_variant_id` INTEGER,
	`photo_id` INTEGER,
	`is_primary` TINYINT default 1 NOT NULL,
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (`id`),
	INDEX `part_photo_FI_1` (`part_id`),
	CONSTRAINT `part_photo_FK_1`
		FOREIGN KEY (`part_id`)
		REFERENCES `part` (`id`),
	INDEX `part_photo_FI_2` (`part_variant_id`),
	CONSTRAINT `part_photo_FK_2`
		FOREIGN KEY (`part_variant_id`)
		REFERENCES `part_variant` (`id`),
	INDEX `part_photo_FI_3` (`photo_id`),
	CONSTRAINT `part_photo_FK_3`
		FOREIGN KEY (`photo_id`)
		REFERENCES `photo` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- part_file
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `part_file`;


CREATE TABLE `part_file`
(
	`part_id` INTEGER,
	`part_variant_id` INTEGER,
	`file_id` INTEGER,
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (`id`),
	INDEX `part_file_FI_1` (`part_id`),
	CONSTRAINT `part_file_FK_1`
		FOREIGN KEY (`part_id`)
		REFERENCES `part` (`id`),
	INDEX `part_file_FI_2` (`part_variant_id`),
	CONSTRAINT `part_file_FK_2`
		FOREIGN KEY (`part_variant_id`)
		REFERENCES `part_variant` (`id`),
	INDEX `part_file_FI_3` (`file_id`),
	CONSTRAINT `part_file_FK_3`
		FOREIGN KEY (`file_id`)
		REFERENCES `file` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- barcode
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `barcode`;


CREATE TABLE `barcode`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`value` VARCHAR(255)  NOT NULL,
	`default_symbology` VARCHAR(8),
	`part_variant_id` INTEGER,
	`part_supplier_id` INTEGER,
	PRIMARY KEY (`id`),
	INDEX `barcode_FI_1` (`part_variant_id`),
	CONSTRAINT `barcode_FK_1`
		FOREIGN KEY (`part_variant_id`)
		REFERENCES `part_variant` (`id`),
	INDEX `barcode_FI_2` (`part_supplier_id`),
	CONSTRAINT `barcode_FK_2`
		FOREIGN KEY (`part_supplier_id`)
		REFERENCES `part_supplier` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- subpart
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `subpart`;


CREATE TABLE `subpart`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`parent_id` INTEGER  NOT NULL,
	`child_id` INTEGER  NOT NULL,
	`child_quantity` DECIMAL(8,3)  NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `subpart_FI_1` (`parent_id`),
	CONSTRAINT `subpart_FK_1`
		FOREIGN KEY (`parent_id`)
		REFERENCES `part_variant` (`id`),
	INDEX `subpart_FI_2` (`child_id`),
	CONSTRAINT `subpart_FK_2`
		FOREIGN KEY (`child_id`)
		REFERENCES `part_variant` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- supplier
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `supplier`;


CREATE TABLE `supplier`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`wf_crm_id` INTEGER  NOT NULL,
	`account_number` VARCHAR(127),
	`credit_limit` DECIMAL(8,2),
	`net_days` INTEGER default 0 NOT NULL,
	`hidden` TINYINT default 0 NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `supplier_FI_1` (`wf_crm_id`),
	CONSTRAINT `supplier_FK_1`
		FOREIGN KEY (`wf_crm_id`)
		REFERENCES `wf_crm` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- manufacturer
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `manufacturer`;


CREATE TABLE `manufacturer`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`wf_crm_id` INTEGER  NOT NULL,
	`hidden` TINYINT default 0 NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `manufacturer_FI_1` (`wf_crm_id`),
	CONSTRAINT `manufacturer_FK_1`
		FOREIGN KEY (`wf_crm_id`)
		REFERENCES `wf_crm` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- supplier_order
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `supplier_order`;


CREATE TABLE `supplier_order`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`supplier_id` INTEGER,
	`purchase_order` VARCHAR(127),
	`notes` TEXT,
	`date_ordered` DATETIME,
	`date_expected` DATETIME,
	`date_received` DATETIME,
	`finalized` TINYINT default 0 NOT NULL,
	`approved` TINYINT default 0 NOT NULL,
	`sent` TINYINT default 0 NOT NULL,
	`received_some` TINYINT default 0 NOT NULL,
	`received_all` TINYINT default 0 NOT NULL,
	`invoice_id` INTEGER,
	PRIMARY KEY (`id`),
	INDEX `supplier_order_FI_1` (`supplier_id`),
	CONSTRAINT `supplier_order_FK_1`
		FOREIGN KEY (`supplier_id`)
		REFERENCES `supplier` (`id`),
	INDEX `supplier_order_FI_2` (`invoice_id`),
	CONSTRAINT `supplier_order_FK_2`
		FOREIGN KEY (`invoice_id`)
		REFERENCES `invoice` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- customer_order
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `customer_order`;


CREATE TABLE `customer_order`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`customer_id` INTEGER,
	`finalized` TINYINT default 0 NOT NULL,
	`approved` TINYINT default 0 NOT NULL,
	`sent_some` TINYINT default 0 NOT NULL,
	`sent_all` TINYINT default 0 NOT NULL,
	`invoice_per_shipment` TINYINT default 0 NOT NULL,
	`invoice_id` INTEGER,
	`date_ordered` DATETIME,
	`hst_exempt` TINYINT default 0 NOT NULL,
	`gst_exempt` TINYINT default 0 NOT NULL,
	`pst_exempt` TINYINT default 0 NOT NULL,
	`for_rigging` TINYINT default 0 NOT NULL,
	`discount_pct` TINYINT default 0 NOT NULL,
	`po_num` VARCHAR(127),
	`boat_name` VARCHAR(127),
	PRIMARY KEY (`id`),
	INDEX `customer_order_FI_1` (`customer_id`),
	CONSTRAINT `customer_order_FK_1`
		FOREIGN KEY (`customer_id`)
		REFERENCES `customer` (`id`),
	INDEX `customer_order_FI_2` (`invoice_id`),
	CONSTRAINT `customer_order_FK_2`
		FOREIGN KEY (`invoice_id`)
		REFERENCES `invoice` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- customer_return
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `customer_return`;


CREATE TABLE `customer_return`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`customer_order_id` INTEGER,
	`invoice_id` INTEGER,
	`date_returned` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `customer_return_FI_1` (`customer_order_id`),
	CONSTRAINT `customer_return_FK_1`
		FOREIGN KEY (`customer_order_id`)
		REFERENCES `customer_order` (`id`),
	INDEX `customer_return_FI_2` (`invoice_id`),
	CONSTRAINT `customer_return_FK_2`
		FOREIGN KEY (`invoice_id`)
		REFERENCES `invoice` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- customer_return_item
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `customer_return_item`;


CREATE TABLE `customer_return_item`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`customer_return_id` INTEGER,
	`customer_order_item_id` INTEGER,
	`part_instance_id` INTEGER,
	PRIMARY KEY (`id`),
	INDEX `customer_return_item_FI_1` (`customer_return_id`),
	CONSTRAINT `customer_return_item_FK_1`
		FOREIGN KEY (`customer_return_id`)
		REFERENCES `customer_return` (`id`),
	INDEX `customer_return_item_FI_2` (`customer_order_item_id`),
	CONSTRAINT `customer_return_item_FK_2`
		FOREIGN KEY (`customer_order_item_id`)
		REFERENCES `customer_order_item` (`id`),
	INDEX `customer_return_item_FI_3` (`part_instance_id`),
	CONSTRAINT `customer_return_item_FK_3`
		FOREIGN KEY (`part_instance_id`)
		REFERENCES `part_instance` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- supplier_order_item
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `supplier_order_item`;


CREATE TABLE `supplier_order_item`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`supplier_order_id` INTEGER,
	`part_variant_id` INTEGER,
	`quantity_requested` DECIMAL(8,3) default 0 NOT NULL,
	`quantity_completed` DECIMAL(8,3) default 0 NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `supplier_order_item_FI_1` (`supplier_order_id`),
	CONSTRAINT `supplier_order_item_FK_1`
		FOREIGN KEY (`supplier_order_id`)
		REFERENCES `supplier_order` (`id`),
	INDEX `supplier_order_item_FI_2` (`part_variant_id`),
	CONSTRAINT `supplier_order_item_FK_2`
		FOREIGN KEY (`part_variant_id`)
		REFERENCES `part_variant` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- customer_order_item
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `customer_order_item`;


CREATE TABLE `customer_order_item`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`customer_order_id` INTEGER,
	`part_instance_id` INTEGER,
	`quantity_completed` DECIMAL(8,3) default 0 NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `customer_order_item_FI_1` (`customer_order_id`),
	CONSTRAINT `customer_order_item_FK_1`
		FOREIGN KEY (`customer_order_id`)
		REFERENCES `customer_order` (`id`),
	INDEX `customer_order_item_FI_2` (`part_instance_id`),
	CONSTRAINT `customer_order_item_FK_2`
		FOREIGN KEY (`part_instance_id`)
		REFERENCES `part_instance` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- part_lot
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `part_lot`;


CREATE TABLE `part_lot`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`part_variant_id` INTEGER,
	`supplier_order_item_id` INTEGER,
	`quantity_received` DECIMAL(8,3) default 0 NOT NULL,
	`quantity_remaining` DECIMAL(8,3) default 0 NOT NULL,
	`received_date` DATETIME  NOT NULL,
	`landed_cost` DECIMAL(8,2)  NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `part_lot_FI_1` (`part_variant_id`),
	CONSTRAINT `part_lot_FK_1`
		FOREIGN KEY (`part_variant_id`)
		REFERENCES `part_variant` (`id`),
	INDEX `part_lot_FI_2` (`supplier_order_item_id`),
	CONSTRAINT `part_lot_FK_2`
		FOREIGN KEY (`supplier_order_item_id`)
		REFERENCES `supplier_order_item` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- part_instance
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `part_instance`;


CREATE TABLE `part_instance`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`part_variant_id` INTEGER,
	`custom_name` VARCHAR(255),
	`custom_origin` VARCHAR(255),
	`quantity` DECIMAL(8,3)  NOT NULL,
	`unit_price` DECIMAL(8,2)  NOT NULL,
	`unit_cost` DECIMAL(8,2),
	`taxable_hst` DECIMAL(8,2) default 0 NOT NULL,
	`taxable_gst` DECIMAL(8,2) default 0 NOT NULL,
	`taxable_pst` DECIMAL(8,2) default 0 NOT NULL,
	`enviro_levy` DECIMAL(8,2) default 0 NOT NULL,
	`battery_levy` DECIMAL(8,2) default 0 NOT NULL,
	`supplier_order_item_id` INTEGER,
	`workorder_item_id` INTEGER,
	`workorder_invoice_id` INTEGER,
	`added_by` INTEGER,
	`estimate` TINYINT default 0 NOT NULL,
	`allocated` TINYINT default 0 NOT NULL,
	`delivered` TINYINT default 0 NOT NULL,
	`serial_number` VARCHAR(255),
	`date_used` DATETIME,
	`is_inventory_adjustment` TINYINT default 0 NOT NULL,
	`internal_notes` TEXT,
	PRIMARY KEY (`id`),
	INDEX `part_instance_FI_1` (`part_variant_id`),
	CONSTRAINT `part_instance_FK_1`
		FOREIGN KEY (`part_variant_id`)
		REFERENCES `part_variant` (`id`),
	INDEX `part_instance_FI_2` (`supplier_order_item_id`),
	CONSTRAINT `part_instance_FK_2`
		FOREIGN KEY (`supplier_order_item_id`)
		REFERENCES `supplier_order_item` (`id`),
	INDEX `part_instance_FI_3` (`workorder_item_id`),
	CONSTRAINT `part_instance_FK_3`
		FOREIGN KEY (`workorder_item_id`)
		REFERENCES `workorder_item` (`id`),
	INDEX `part_instance_FI_4` (`workorder_invoice_id`),
	CONSTRAINT `part_instance_FK_4`
		FOREIGN KEY (`workorder_invoice_id`)
		REFERENCES `invoice` (`id`),
	INDEX `part_instance_FI_5` (`added_by`),
	CONSTRAINT `part_instance_FK_5`
		FOREIGN KEY (`added_by`)
		REFERENCES `employee` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- shipment
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `shipment`;


CREATE TABLE `shipment`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`carrier` VARCHAR(64),
	`tracking_number` VARCHAR(127),
	`date_shipped` DATETIME  NOT NULL,
	`invoice_id` INTEGER,
	PRIMARY KEY (`id`),
	INDEX `shipment_FI_1` (`invoice_id`),
	CONSTRAINT `shipment_FK_1`
		FOREIGN KEY (`invoice_id`)
		REFERENCES `invoice` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- shipment_item
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `shipment_item`;


CREATE TABLE `shipment_item`
(
	`shipment_id` INTEGER,
	`customer_order_item_id` INTEGER,
	`quantity` DECIMAL(8,3),
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (`id`),
	INDEX `shipment_item_FI_1` (`shipment_id`),
	CONSTRAINT `shipment_item_FK_1`
		FOREIGN KEY (`shipment_id`)
		REFERENCES `shipment` (`id`),
	INDEX `shipment_item_FI_2` (`customer_order_item_id`),
	CONSTRAINT `shipment_item_FK_2`
		FOREIGN KEY (`customer_order_item_id`)
		REFERENCES `customer_order_item` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- workorder
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `workorder`;


CREATE TABLE `workorder`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`customer_id` INTEGER,
	`customer_boat_id` INTEGER,
	`workorder_category_id` INTEGER,
	`status` VARCHAR(15)  NOT NULL,
	`summary_color` VARCHAR(6) default '' NOT NULL,
	`summary_notes` VARCHAR(255),
	`haulout_date` DATETIME,
	`haulin_date` DATETIME,
	`created_on` DATETIME,
	`started_on` DATETIME,
	`completed_on` DATETIME,
	`hst_exempt` TINYINT default 0 NOT NULL,
	`gst_exempt` TINYINT default 0 NOT NULL,
	`pst_exempt` TINYINT default 0 NOT NULL,
	`customer_notes` TEXT,
	`internal_notes` TEXT,
	`for_rigging` TINYINT default 0 NOT NULL,
	`shop_supplies_surcharge` DECIMAL(5,2) default 0,
	`moorage_surcharge` DECIMAL(5,2) default 0,
	`moorage_surcharge_amt` DECIMAL(8,2) default 0,
	PRIMARY KEY (`id`),
	KEY `workorder_I_1`(`status`),
	INDEX `workorder_FI_1` (`customer_id`),
	CONSTRAINT `workorder_FK_1`
		FOREIGN KEY (`customer_id`)
		REFERENCES `customer` (`id`),
	INDEX `workorder_FI_2` (`customer_boat_id`),
	CONSTRAINT `workorder_FK_2`
		FOREIGN KEY (`customer_boat_id`)
		REFERENCES `customer_boat` (`id`),
	INDEX `workorder_FI_3` (`workorder_category_id`),
	CONSTRAINT `workorder_FK_3`
		FOREIGN KEY (`workorder_category_id`)
		REFERENCES `workorder_category` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- workorder_category
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `workorder_category`;


CREATE TABLE `workorder_category`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255),
	PRIMARY KEY (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- workorder_item
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `workorder_item`;


CREATE TABLE `workorder_item`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`workorder_id` INTEGER  NOT NULL,
	`label` VARCHAR(255),
	`lft` INTEGER  NOT NULL,
	`rgt` INTEGER  NOT NULL,
	`owner_company` INTEGER,
	`labour_estimate` DECIMAL(8,2),
	`labour_actual` DECIMAL(8,2),
	`other_estimate` DECIMAL(8,2),
	`other_actual` DECIMAL(8,2),
	`part_estimate` DECIMAL(8,2),
	`part_actual` DECIMAL(8,2),
	`amount_paid` DECIMAL(8,2),
	`completed` TINYINT default 0 NOT NULL,
	`completed_by` INTEGER,
	`completed_date` DATETIME,
	`customer_notes` TEXT,
	`internal_notes` TEXT,
	`color_code` VARCHAR(6) default '' NOT NULL,
	`task_code` VARCHAR(6) default '' NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `workorder_item_FI_1` (`workorder_id`),
	CONSTRAINT `workorder_item_FK_1`
		FOREIGN KEY (`workorder_id`)
		REFERENCES `workorder` (`id`)
		ON DELETE CASCADE,
	INDEX `workorder_item_FI_2` (`completed_by`),
	CONSTRAINT `workorder_item_FK_2`
		FOREIGN KEY (`completed_by`)
		REFERENCES `employee` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- workorder_item_billable
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `workorder_item_billable`;


CREATE TABLE `workorder_item_billable`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`workorder_item_id` INTEGER,
	`manufacturer_id` INTEGER,
	`supplier_id` INTEGER,
	`manufacturer_parts_percent` TINYINT default 0 NOT NULL,
	`manufacturer_labour_percent` TINYINT default 0 NOT NULL,
	`supplier_parts_percent` TINYINT default 0 NOT NULL,
	`supplier_labour_percent` TINYINT default 0 NOT NULL,
	`in_house_parts_percent` TINYINT default 0 NOT NULL,
	`in_house_labour_percent` TINYINT default 0 NOT NULL,
	`customer_parts_percent` TINYINT default 100 NOT NULL,
	`customer_labour_percent` TINYINT default 100 NOT NULL,
	`recurse` TINYINT default 1 NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `workorder_item_billable_FI_1` (`workorder_item_id`),
	CONSTRAINT `workorder_item_billable_FK_1`
		FOREIGN KEY (`workorder_item_id`)
		REFERENCES `workorder_item` (`id`),
	INDEX `workorder_item_billable_FI_2` (`manufacturer_id`),
	CONSTRAINT `workorder_item_billable_FK_2`
		FOREIGN KEY (`manufacturer_id`)
		REFERENCES `manufacturer` (`id`),
	INDEX `workorder_item_billable_FI_3` (`supplier_id`),
	CONSTRAINT `workorder_item_billable_FK_3`
		FOREIGN KEY (`supplier_id`)
		REFERENCES `supplier` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- workorder_expense
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `workorder_expense`;


CREATE TABLE `workorder_expense`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`workorder_item_id` INTEGER,
	`workorder_invoice_id` INTEGER,
	`label` VARCHAR(255)  NOT NULL,
	`customer_notes` TEXT,
	`internal_notes` TEXT,
	`cost` DECIMAL(8,2),
	`estimate` TINYINT default 0 NOT NULL,
	`price` DECIMAL(8,2),
	`origin` VARCHAR(255),
	`taxable_hst` DECIMAL(8,2) default 0 NOT NULL,
	`taxable_gst` DECIMAL(8,2) default 0 NOT NULL,
	`taxable_pst` DECIMAL(8,2) default 0 NOT NULL,
	`created_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `workorder_expense_FI_1` (`workorder_item_id`),
	CONSTRAINT `workorder_expense_FK_1`
		FOREIGN KEY (`workorder_item_id`)
		REFERENCES `workorder_item` (`id`),
	INDEX `workorder_expense_FI_2` (`workorder_invoice_id`),
	CONSTRAINT `workorder_expense_FK_2`
		FOREIGN KEY (`workorder_invoice_id`)
		REFERENCES `invoice` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- workorder_item_photo
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `workorder_item_photo`;


CREATE TABLE `workorder_item_photo`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`workorder_item_id` INTEGER,
	`photo_id` INTEGER,
	`created_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `workorder_item_photo_FI_1` (`workorder_item_id`),
	CONSTRAINT `workorder_item_photo_FK_1`
		FOREIGN KEY (`workorder_item_id`)
		REFERENCES `workorder_item` (`id`),
	INDEX `workorder_item_photo_FI_2` (`photo_id`),
	CONSTRAINT `workorder_item_photo_FK_2`
		FOREIGN KEY (`photo_id`)
		REFERENCES `photo` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- workorder_item_file
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `workorder_item_file`;


CREATE TABLE `workorder_item_file`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`workorder_item_id` INTEGER,
	`file_id` INTEGER,
	`created_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `workorder_item_file_FI_1` (`workorder_item_id`),
	CONSTRAINT `workorder_item_file_FK_1`
		FOREIGN KEY (`workorder_item_id`)
		REFERENCES `workorder_item` (`id`),
	INDEX `workorder_item_file_FI_2` (`file_id`),
	CONSTRAINT `workorder_item_file_FK_2`
		FOREIGN KEY (`file_id`)
		REFERENCES `file` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- workorder_invoice
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `workorder_invoice`;


CREATE TABLE `workorder_invoice`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`workorder_id` INTEGER,
	`invoice_id` INTEGER,
	`is_estimate` TINYINT default 0 NOT NULL,
	`created_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `workorder_invoice_FI_1` (`workorder_id`),
	CONSTRAINT `workorder_invoice_FK_1`
		FOREIGN KEY (`workorder_id`)
		REFERENCES `workorder` (`id`),
	INDEX `workorder_invoice_FI_2` (`invoice_id`),
	CONSTRAINT `workorder_invoice_FK_2`
		FOREIGN KEY (`invoice_id`)
		REFERENCES `invoice` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- workorder_payment
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `workorder_payment`;


CREATE TABLE `workorder_payment`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`workorder_id` INTEGER,
	`supplier_id` INTEGER,
	`manufacturer_id` INTEGER,
	`amount` DECIMAL(8,2),
	`created_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `workorder_payment_FI_1` (`workorder_id`),
	CONSTRAINT `workorder_payment_FK_1`
		FOREIGN KEY (`workorder_id`)
		REFERENCES `workorder` (`id`),
	INDEX `workorder_payment_FI_2` (`supplier_id`),
	CONSTRAINT `workorder_payment_FK_2`
		FOREIGN KEY (`supplier_id`)
		REFERENCES `supplier` (`id`),
	INDEX `workorder_payment_FI_3` (`manufacturer_id`),
	CONSTRAINT `workorder_payment_FK_3`
		FOREIGN KEY (`manufacturer_id`)
		REFERENCES `manufacturer` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- employee
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `employee`;


CREATE TABLE `employee`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`wf_crm_id` INTEGER  NOT NULL,
	`guard_user_id` INTEGER,
	`payrate` DECIMAL(8,2),
	`hidden` TINYINT default 0 NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `employee_FI_1` (`wf_crm_id`),
	CONSTRAINT `employee_FK_1`
		FOREIGN KEY (`wf_crm_id`)
		REFERENCES `wf_crm` (`id`),
	INDEX `employee_FI_2` (`guard_user_id`),
	CONSTRAINT `employee_FK_2`
		FOREIGN KEY (`guard_user_id`)
		REFERENCES `sf_guard_user` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- timelog
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `timelog`;


CREATE TABLE `timelog`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`employee_id` INTEGER,
	`workorder_item_id` INTEGER,
	`workorder_invoice_id` INTEGER,
	`labour_type_id` INTEGER,
	`nonbill_type_id` INTEGER,
	`custom_label` VARCHAR(128),
	`rate` DECIMAL(5,2)  NOT NULL,
	`start_time` DATETIME,
	`end_time` DATETIME,
	`payroll_hours` DECIMAL(5,2)  NOT NULL,
	`billable_hours` DECIMAL(5,2)  NOT NULL,
	`cost` DECIMAL(8,2),
	`taxable_hst` DECIMAL(8,2) default 0 NOT NULL,
	`taxable_gst` DECIMAL(8,2) default 0 NOT NULL,
	`taxable_pst` DECIMAL(8,2) default 0 NOT NULL,
	`employee_notes` TEXT,
	`admin_notes` TEXT,
	`admin_flagged` TINYINT default 0 NOT NULL,
	`estimate` TINYINT default 0 NOT NULL,
	`approved` TINYINT default 0 NOT NULL,
	`created_at` DATETIME,
	`updated_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `timelog_FI_1` (`employee_id`),
	CONSTRAINT `timelog_FK_1`
		FOREIGN KEY (`employee_id`)
		REFERENCES `employee` (`id`),
	INDEX `timelog_FI_2` (`workorder_item_id`),
	CONSTRAINT `timelog_FK_2`
		FOREIGN KEY (`workorder_item_id`)
		REFERENCES `workorder_item` (`id`),
	INDEX `timelog_FI_3` (`workorder_invoice_id`),
	CONSTRAINT `timelog_FK_3`
		FOREIGN KEY (`workorder_invoice_id`)
		REFERENCES `invoice` (`id`),
	INDEX `timelog_FI_4` (`labour_type_id`),
	CONSTRAINT `timelog_FK_4`
		FOREIGN KEY (`labour_type_id`)
		REFERENCES `labour_type` (`id`),
	INDEX `timelog_FI_5` (`nonbill_type_id`),
	CONSTRAINT `timelog_FK_5`
		FOREIGN KEY (`nonbill_type_id`)
		REFERENCES `nonbill_type` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- labour_type
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `labour_type`;


CREATE TABLE `labour_type`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255),
	`hourly_rate` DECIMAL(8,2),
	`active` TINYINT default 1 NOT NULL,
	PRIMARY KEY (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- nonbill_type
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `nonbill_type`;


CREATE TABLE `nonbill_type`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255)  NOT NULL,
	PRIMARY KEY (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- customer
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `customer`;


CREATE TABLE `customer`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`wf_crm_id` INTEGER  NOT NULL,
	`guard_user_id` INTEGER,
	`pst_number` VARCHAR(255),
	`hidden` TINYINT default 0 NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `customer_FI_1` (`wf_crm_id`),
	CONSTRAINT `customer_FK_1`
		FOREIGN KEY (`wf_crm_id`)
		REFERENCES `wf_crm` (`id`),
	INDEX `customer_FI_2` (`guard_user_id`),
	CONSTRAINT `customer_FK_2`
		FOREIGN KEY (`guard_user_id`)
		REFERENCES `sf_guard_user` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- customer_boat
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `customer_boat`;


CREATE TABLE `customer_boat`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`customer_id` INTEGER,
	`serial_number` VARCHAR(128),
	`make` VARCHAR(255),
	`model` VARCHAR(255),
	`name` VARCHAR(255),
	`registration` VARCHAR(255),
	`notes` TEXT,
	`fire_date` TIMESTAMP
	PRIMARY KEY (`id`),
	INDEX `customer_boat_FI_1` (`customer_id`),
	CONSTRAINT `customer_boat_FK_1`
		FOREIGN KEY (`customer_id`)
		REFERENCES `customer` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- invoice
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `invoice`;


CREATE TABLE `invoice`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`receivable` TINYINT default 1 NOT NULL,
	`customer_id` INTEGER,
	`supplier_id` INTEGER,
	`manufacturer_id` INTEGER,
	`subtotal` DECIMAL(8,2) default 0 NOT NULL,
	`shipping` DECIMAL(8,2) default 0 NOT NULL,
	`hst` DECIMAL(8,2) default 0 NOT NULL,
	`gst` DECIMAL(8,2) default 0 NOT NULL,
	`pst` DECIMAL(8,2) default 0 NOT NULL,
	`enviro_levy` DECIMAL(8,2) default 0 NOT NULL,
	`battery_levy` DECIMAL(8,2) default 0 NOT NULL,
	`duties` DECIMAL(8,2) default 0 NOT NULL,
	`total` DECIMAL(8,2) default 0 NOT NULL,
	`issued_date` DATETIME,
	`payable_date` DATETIME,
	`archived` TINYINT default 0 NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `invoice_FI_1` (`customer_id`),
	CONSTRAINT `invoice_FK_1`
		FOREIGN KEY (`customer_id`)
		REFERENCES `customer` (`id`),
	INDEX `invoice_FI_2` (`supplier_id`),
	CONSTRAINT `invoice_FK_2`
		FOREIGN KEY (`supplier_id`)
		REFERENCES `supplier` (`id`),
	INDEX `invoice_FI_3` (`manufacturer_id`),
	CONSTRAINT `invoice_FK_3`
		FOREIGN KEY (`manufacturer_id`)
		REFERENCES `manufacturer` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- payment
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `payment`;


CREATE TABLE `payment`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`customer_order_id` INTEGER,
	`workorder_id` INTEGER,
	`amount` DECIMAL(8,2) default 0 NOT NULL,
	`tendered` DECIMAL(8,2) default 0 NOT NULL,
	`change` DECIMAL(8,2) default 0 NOT NULL,
	`payment_method` VARCHAR(128),
	`payment_details` VARCHAR(255),
	`created_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `payment_FI_1` (`customer_order_id`),
	CONSTRAINT `payment_FK_1`
		FOREIGN KEY (`customer_order_id`)
		REFERENCES `customer_order` (`id`),
	INDEX `payment_FI_2` (`workorder_id`),
	CONSTRAINT `payment_FK_2`
		FOREIGN KEY (`workorder_id`)
		REFERENCES `workorder` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- photo
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `photo`;


CREATE TABLE `photo`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`filename` VARCHAR(255),
	`caption` TEXT,
	PRIMARY KEY (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- file
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `file`;


CREATE TABLE `file`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`filename` VARCHAR(255),
	`description` TEXT,
	PRIMARY KEY (`id`)
)Type=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
