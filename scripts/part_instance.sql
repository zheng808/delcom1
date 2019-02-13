ALTER TABLE `part_instance` 
CHANGE `internal_notes` `internal_notes` TEXT NULL;

ALTER TABLE `part_instance` 
CHANGE `unit_cost` `unit_cost` DECIMAL(8,2) NULL DEFAULT 0;
