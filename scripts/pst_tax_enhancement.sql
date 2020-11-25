/*
* Reset Workorder summary colours for new Tax categories
*/
/* Full Tax - Set colour GREEN */
update workorder set summary_color = '33DD33' where pst_exempt = 0 and gst_exempt = 0;

/* No Tax - Set colour RED */
update workorder set summary_color = 'FF3333' where pst_exempt = 1 and gst_exempt = 1;

/* PST Tax - Set colour ORANGE */
update workorder set summary_color = 'FFA500' where pst_exempt = 0 and gst_exempt = 1;

/* GST Tax - Set colour BLUE */
update workorder set summary_color = '0000FF' where pst_exempt = 1 and gst_exempt = 0;

/*
* Add subcontractor flag columns
*/
alter table part_instance add column sub_contractor_flg varchar(1) DEFAULT 'N';

alter table workorder_expense add column sub_contractor_flg varchar(1) DEFAULT 'N';

/*
* Add system_settings table
*/
create table system_settings (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(24) NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` varchar(255) NULL,
  `value` varchar(255) NOT NULL,
  `updated_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_by` varchar(128) DEFAULT 'CURRENT_USER()',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
);

insert into system_settings (code, name, description, value, updated_by)
values ('DB_VERSION','Database Version','Current version number of the Delcom Database','5','SYSTEM');

insert into system_settings (code, name, description, value, updated_by)
values ('APP_VERSION','Application Version','Current version number of the Delcom Application','1.2.6','SYSTEM');

insert into system_settings (code, name, description, value, updated_by)
values ('EXT_VERSION','Extension Version','Current extension version number of the Delcom Application','PST','SYSTEM');




/*
* Add enviro_taxable flag column
*/
alter table part_instance add column enviro_taxable_flg varchar(1) DEFAULT 'N';

update system_settings set value = '1.2.7' where code = 'APP_VERSION';

update system_settings set value = '06' where code = 'DB_VERSION';



/*
* Add tax_override flag columns
*/
alter table part_instance add column pst_override_flg varchar(1) DEFAULT 'N';
alter table part_instance add column gst_override_flg varchar(1) DEFAULT 'N';
alter table part_instance add column enviro_override_flg varchar(1) DEFAULT 'N';

alter table workorder_expense add column pst_override_flg varchar(1) DEFAULT 'N';
alter table workorder_expense add column gst_override_flg varchar(1) DEFAULT 'N';
-- alter table workorder_expense add column enviro_override_flg varchar(1) DEFAULT 'N';



update system_settings set value = '1.2.8' where code = 'APP_VERSION';

update system_settings set value = '07' where code = 'DB_VERSION';

-- alter table workorder_expense drop column gst_override_flg;
update system_settings set value = '08' where code = 'DB_VERSION';

