ALTER TABLE `part_variant` 
CHANGE `last_inventory_update` `last_inventory_update` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;


ALTER TABLE `part_variant` 
CHANGE `unit_cost` `unit_cost` DECIMAL(8,2) NULL DEFAULT 0;
