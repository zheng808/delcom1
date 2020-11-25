ALTER TABLE `workorder_item` 
CHANGE `amount_paid` `amount_paid` DECIMAL(8,2) NOT NULL DEFAULT 0;

ALTER TABLE `deltamarine`.`workorder_item` 
ADD COLUMN `task_code` VARCHAR(6) NOT NULL DEFAULT 'FFFFFF' AFTER `color_code`;
