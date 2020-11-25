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



/* User Audit */
DROP TABLE IF EXISTS user_audit;

DROP TRIGGER IF EXISTS trg_user_update;

CREATE TABLE `user_audit` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(128) DEFAULT NULL,
  `updated_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_column` varchar(128) DEFAULT NULL,
  `old_value` varchar(128) DEFAULT NULL,
  `new_value` varchar(128) DEFAULT NULL,
  `updated_by` varchar(128) DEFAULT 'CURRENT_USER()',
  PRIMARY KEY (`id`)
);


DELIMITER $$    
CREATE TRIGGER `trg_user_update` AFTER UPDATE ON sf_guard_user FOR EACH ROW
    BEGIN   
        if NEW.password != OLD.password then
           insert into user_audit (username,updated_date,updated_by,updated_column,old_value,new_value)
           values (OLD.username, now(),OLD.username,"PASSWORD", OLD.password, NEW.password);
        end if;

        IF NEW.last_login != OLD.last_login THEN 
           insert into `user_audit` (username,updated_date,updated_by,updated_column,old_value,new_value)
           values (OLD.username, now(),OLD.username,'LAST_LOGIN', OLD.last_login, NEW.last_login);
        END IF;
        
        IF NEW.algorithm != OLD.algorithm THEN 
           insert into `user_audit` (username,updated_date,updated_by,updated_column,old_value,new_value)
           values (OLD.username, now(),OLD.username,'ALGORITHM', OLD.algorithm, NEW.algorithm);
        END IF;
        
        IF NEW.salt != OLD.salt THEN 
           insert into `user_audit` (username,updated_date,updated_by,updated_column,old_value,new_value)
           values (OLD.username, now(),OLD.username,'SALT', OLD.salt, NEW.salt);
        END IF;
                
        IF NEW.is_active != OLD.is_active THEN 
           insert into `user_audit` (username,updated_date,updated_by,updated_column,old_value,new_value)
           values (OLD.username, now(),OLD.username,'IS_ACTIVE', OLD.is_active, NEW.is_active);
        END IF;
                
        IF NEW.is_super_admin != OLD.is_super_admin THEN 
           insert into `user_audit` (username,updated_date,updated_by,updated_column,old_value,new_value)
           values (OLD.username, now(),OLD.username,'IS_SUPER_ADMIN', OLD.is_super_admin, NEW.is_super_admin);
        END IF;
    END;$$

DELIMITER ;



