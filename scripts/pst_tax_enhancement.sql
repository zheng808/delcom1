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

alter table part_instance add column enviro_taxable varchar(1) DEFAULT 'Y';

alter table workorder_expense add column sub_contractor_flg varchar(1) DEFAULT 'N';
