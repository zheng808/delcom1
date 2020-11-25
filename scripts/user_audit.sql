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




