alter table workorder
add column exemption_file VARCHAR(255) NULL;

alter table workorder
add column canada_entry_num VARCHAR(255) NULL;

alter table workorder
add column canada_entry_date DATETIME NULL;

alter table workorder
add column usa_entry_num VARCHAR(255) NULL;

alter table workorder
add column usa_entry_date DATETIME NULL;

