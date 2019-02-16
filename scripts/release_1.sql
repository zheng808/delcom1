/* part_instance */
ALTER TABLE `part_instance` 
CHANGE `internal_notes` `internal_notes` TEXT NULL;

ALTER TABLE `part_instance` 
CHANGE `unit_cost` `unit_cost` DECIMAL(8,2) NULL DEFAULT 0;

/* part_lot */
ALTER TABLE `part_lot` 
CHANGE `landed_cost` `landed_cost` DECIMAL(8,2) NOT NULL DEFAULT 0;

/* part_variant */
ALTER TABLE `part_variant` 
CHANGE `last_inventory_update` `last_inventory_update` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;


ALTER TABLE `part_variant` 
CHANGE `unit_cost` `unit_cost` DECIMAL(8,2) NULL DEFAULT 0;


/* parts_add_fees */
alter table part_variant
add column shipping_fees decimal(8,2) DEFAULT 0,
add column broker_fees decimal(8,2) DEFAULT 0;

alter table part_instance
add column shipping_fees decimal(8,2) DEFAULT 0,
add column broker_fees decimal(8,2) DEFAULT 0;

/* setAminPass */
select * from sf_guard_user where username = 'admin';


update sf_guard_user 
   set salt = '886c94c3f4bf84074f1186c0a52b82c6',
       password = '95f1654a84f2df0338ddc630e6050f5d0ab954a8'
 where username = 'admin';

 /* workorder_item */
ALTER TABLE `workorder_item` 
CHANGE `amount_paid` `amount_paid` DECIMAL(8,2) NOT NULL DEFAULT 0;




