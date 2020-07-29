

/* reset tax override flags on 10798 */
update workorder wo, workorder_item woi, workorder_expense woe
   set woe.pst_override_flg = 'N'
where wo.id = woi.`workorder_id`
  and woi.id = woe.`workorder_item_id`
  and wo.pst_exempt = 0 
  and woe.taxable_pst > 0  and woe.pst_override_flg = 'Y';

update workorder wo, workorder_item woi, part_instance pi
   set pi.pst_override_flg = 'N'
where wo.id = woi.`workorder_id`
  and woi.id = pi.`workorder_item_id`
  and wo.pst_exempt = 0 
  and pi.taxable_pst > 0  and pi.pst_override_flg = 'Y';


/* Clean-up Taxes on WO 10538 */
update workorder wo, workorder_item woi, part_instance pi
   set pi.taxable_pst = 0
 where wo.id = 10538
  and wo.id = woi.workorder_id
  and woi.id = pi.workorder_item_id
  and pi.taxable_pst > 0
  and pi.pst_override_flg = 'N'
  and wo.pst_exempt = 1;

update workorder wo, workorder_item woi, part_instance pi
   set pi.taxable_gst = 0
 where wo.id = 10538
  and wo.id = woi.workorder_id
  and woi.id = pi.workorder_item_id
  and pi.taxable_gst > 0
  and pi.gst_override_flg = 'N'
  and wo.gst_exempt = 1;

/* add audit tables */
/* add audit for workorder */
DROP TABLE IF EXISTS workorder_audit;

DROP TRIGGER IF EXISTS trg_workorder_update;

CREATE TABLE workorder_audit (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  workorder_id int(11),
  updated_date datetime DEFAULT CURRENT_TIMESTAMP,
  updated_by varchar(128) DEFAULT 'CURRENT_USER()',
  updated_column varchar(128) DEFAULT NULL,
  old_value varchar(128) DEFAULT NULL,
  new_value varchar(128) DEFAULT NULL,
  PRIMARY KEY (id)
);


DELIMITER $$    
CREATE TRIGGER trg_workorder_update AFTER UPDATE ON workorder FOR EACH ROW
BEGIN   
    if NEW.status != OLD.status then
        insert into workorder_audit (workorder_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'status', OLD.status, NEW.status);
    end if;

    IF NEW.customer_id != OLD.customer_id THEN 
        insert into workorder_audit (workorder_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'customer_id', OLD.customer_id, NEW.customer_id);
    END IF;

    IF NEW.customer_boat_id != OLD.customer_boat_id THEN 
        insert into workorder_audit (workorder_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'customer_boat_id', OLD.customer_boat_id, NEW.customer_boat_id);
    END IF;

    IF NEW.haulout_date != OLD.haulout_date THEN 
        insert into workorder_audit (workorder_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'haulout_date', OLD.haulout_date, NEW.haulout_date);
    END IF;

    IF NEW.haulin_date != OLD.haulin_date THEN 
        insert into workorder_audit (workorder_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'haulin_date', OLD.haulin_date, NEW.haulin_date);
    END IF;

    IF NEW.started_on != OLD.started_on THEN 
        insert into workorder_audit (workorder_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'started_on', OLD.started_on, NEW.started_on);
    END IF;

    IF NEW.completed_on != OLD.completed_on THEN 
        insert into workorder_audit (workorder_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'completed_on', OLD.completed_on, NEW.completed_on);
    END IF;

    IF NEW.hst_exempt != OLD.hst_exempt THEN 
        insert into workorder_audit (workorder_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'hst_exempt', OLD.hst_exempt, NEW.hst_exempt);
    END IF;

    IF NEW.pst_exempt != OLD.pst_exempt THEN 
        insert into workorder_audit (workorder_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'pst_exempt', OLD.pst_exempt, NEW.pst_exempt);
    END IF;

    IF NEW.gst_exempt != OLD.gst_exempt THEN 
        insert into workorder_audit (workorder_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'gst_exempt', OLD.gst_exempt, NEW.gst_exempt);
    END IF;

    IF NEW.shop_supplies_surcharge != OLD.shop_supplies_surcharge THEN 
        insert into workorder_audit (workorder_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'shop_supplies_surcharge', OLD.shop_supplies_surcharge, NEW.shop_supplies_surcharge);
    END IF;

    IF NEW.moorage_surcharge != OLD.moorage_surcharge THEN 
        insert into workorder_audit (workorder_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'moorage_surcharge', OLD.moorage_surcharge, NEW.moorage_surcharge);
    END IF;

    IF NEW.moorage_surcharge_amt != OLD.moorage_surcharge_amt THEN 
        insert into workorder_audit (workorder_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'moorage_surcharge_amt', OLD.moorage_surcharge_amt, NEW.moorage_surcharge_amt);
    END IF;

    IF NEW.exemption_file != OLD.exemption_file THEN 
        insert into workorder_audit (workorder_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'exemption_file', OLD.exemption_file, NEW.exemption_file);
    END IF;

    IF NEW.canada_entry_num != OLD.canada_entry_num THEN 
        insert into workorder_audit (workorder_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'canada_entry_num', OLD.canada_entry_num, NEW.canada_entry_num);
    END IF;

    IF NEW.canada_entry_date != OLD.canada_entry_date THEN 
        insert into workorder_audit (workorder_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'canada_entry_date', OLD.canada_entry_date, NEW.canada_entry_date);
    END IF;

    IF NEW.usa_entry_num != OLD.usa_entry_num THEN 
        insert into workorder_audit (workorder_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'usa_entry_num', OLD.usa_entry_num, NEW.usa_entry_num);
    END IF;

    IF NEW.usa_entry_date != OLD.usa_entry_date THEN 
        insert into workorder_audit (workorder_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'usa_entry_date', OLD.usa_entry_date, NEW.usa_entry_date);
    END IF;
    
END;$$

DELIMITER ;

/* add audit for part_instance */
DROP TABLE IF EXISTS part_instance_audit;

DROP TRIGGER IF EXISTS trg_part_instance_update;

CREATE TABLE part_instance_audit (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  part_instance_id int(11),
  updated_date datetime DEFAULT CURRENT_TIMESTAMP,
  updated_by varchar(128) DEFAULT 'CURRENT_USER()',
  updated_column varchar(128) DEFAULT NULL,
  old_value varchar(128) DEFAULT NULL,
  new_value varchar(128) DEFAULT NULL,
  PRIMARY KEY (id)
);

 
DELIMITER $$    
CREATE TRIGGER trg_part_instance_update AFTER UPDATE ON part_instance FOR EACH ROW
BEGIN   
    if NEW.quantity != OLD.quantity then
        insert into part_instance_audit (part_instance_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'quantity', OLD.quantity, NEW.quantity);
    end if;

    if NEW.unit_price != OLD.unit_price then
        insert into part_instance_audit (part_instance_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'unit_price', OLD.unit_price, NEW.unit_price);
    end if;

    if NEW.unit_cost != OLD.unit_cost then
        insert into part_instance_audit (part_instance_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'unit_cost', OLD.unit_cost, NEW.unit_cost);
    end if;

    if NEW.taxable_hst != OLD.taxable_hst then
        insert into part_instance_audit (part_instance_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'taxable_hst', OLD.taxable_hst, NEW.taxable_hst);
    end if;

    if NEW.taxable_pst != OLD.taxable_pst then
        insert into part_instance_audit (part_instance_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'taxable_pst', OLD.taxable_pst, NEW.taxable_pst);
    end if;

    if NEW.taxable_gst != OLD.taxable_gst then
        insert into part_instance_audit (part_instance_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'taxable_gst', OLD.taxable_gst, NEW.taxable_gst);
    end if;

    if NEW.enviro_levy != OLD.enviro_levy then
        insert into part_instance_audit (part_instance_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'enviro_levy', OLD.enviro_levy, NEW.enviro_levy);
    end if;

    if NEW.battery_levy != OLD.battery_levy then
        insert into part_instance_audit (part_instance_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'battery_levy', OLD.battery_levy, NEW.battery_levy);
    end if;

    if NEW.estimate != OLD.estimate then
        insert into part_instance_audit (part_instance_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'estimate', OLD.estimate, NEW.estimate);
    end if;

    if NEW.allocated != OLD.allocated then
        insert into part_instance_audit (part_instance_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'allocated', OLD.allocated, NEW.allocated);
    end if;

    if NEW.date_used != OLD.date_used then
        insert into part_instance_audit (part_instance_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'date_used', OLD.date_used, NEW.date_used);
    end if;

    if NEW.shipping_fees != OLD.shipping_fees then
        insert into part_instance_audit (part_instance_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'shipping_fees', OLD.shipping_fees, NEW.shipping_fees);
    end if;

    if NEW.broker_fees != OLD.broker_fees then
        insert into part_instance_audit (part_instance_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'broker_fees', OLD.broker_fees, NEW.broker_fees);
    end if;

    if NEW.pst_override_flg != OLD.pst_override_flg then
        insert into part_instance_audit (part_instance_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'pst_override_flg', OLD.pst_override_flg, NEW.pst_override_flg);
    end if;

    if NEW.gst_override_flg != OLD.gst_override_flg then
        insert into part_instance_audit (part_instance_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'gst_override_flg', OLD.gst_override_flg, NEW.gst_override_flg);
    end if;

    if NEW.enviro_override_flg != OLD.enviro_override_flg then
        insert into part_instance_audit (part_instance_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'enviro_override_flg', OLD.enviro_override_flg, NEW.enviro_override_flg);
    end if;

END;$$

DELIMITER ;

/* add audit for part_instance */
DROP TABLE IF EXISTS workorder_expense_audit;

DROP TRIGGER IF EXISTS trg_workorder_expense_update;

CREATE TABLE workorder_expense_audit (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  workorder_expense_id int(11),
  updated_date datetime DEFAULT CURRENT_TIMESTAMP,
  updated_by varchar(128) DEFAULT 'CURRENT_USER()',
  updated_column varchar(128) DEFAULT NULL,
  old_value varchar(128) DEFAULT NULL,
  new_value varchar(128) DEFAULT NULL,
  PRIMARY KEY (id)
);

 
DELIMITER $$    
CREATE TRIGGER trg_workorder_expense_update AFTER UPDATE ON workorder_expense FOR EACH ROW
BEGIN   
    if NEW.cost != OLD.cost then
        insert into workorder_expense_audit (workorder_expense_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'cost', OLD.cost, NEW.cost);
    end if;

    if NEW.price != OLD.price then
        insert into workorder_expense_audit (workorder_expense_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'price', OLD.price, NEW.price);
    end if;

    if NEW.invoice != OLD.invoice then
        insert into workorder_expense_audit (workorder_expense_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'invoice', OLD.invoice, NEW.invoice);
    end if;

    if NEW.taxable_hst != OLD.taxable_hst then
        insert into workorder_expense_audit (workorder_expense_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'taxable_hst', OLD.taxable_hst, NEW.taxable_hst);
    end if;

    if NEW.taxable_pst != OLD.taxable_pst then
        insert into workorder_expense_audit (workorder_expense_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'taxable_pst', OLD.taxable_pst, NEW.taxable_pst);
    end if;

    if NEW.taxable_gst != OLD.taxable_gst then
        insert into workorder_expense_audit (workorder_expense_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'taxable_gst', OLD.taxable_gst, NEW.taxable_gst);
    end if;

    if NEW.pst_override_flg != OLD.pst_override_flg then
        insert into workorder_expense_audit (workorder_expense_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'pst_override_flg', OLD.pst_override_flg, NEW.pst_override_flg);
    end if;

    if NEW.gst_override_flg != OLD.gst_override_flg then
        insert into workorder_expense_audit (workorder_expense_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'gst_override_flg', OLD.gst_override_flg, NEW.gst_override_flg);
    end if;

    if NEW.sub_contractor_flg != OLD.sub_contractor_flg then
        insert into workorder_expense_audit (workorder_expense_id,updated_date,updated_by,updated_column,old_value,new_value)
        values (OLD.id, now(),CURRENT_USER,'sub_contractor_flg', OLD.sub_contractor_flg, NEW.sub_contractor_flg);
    end if;

END;$$

DELIMITER ;


/* remove view inventory */
DROP VIEW IF EXISTS inventory_view;

/* update version numbers */
update system_settings set value = '1.2.11' where code = 'APP_VERSION';
update system_settings set value = '11' where code = 'DB_VERSION';
update system_settings set value = 'a' where code = 'EXT_VERSION';