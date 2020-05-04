update workorder wo,
       workorder_item wi,
       part_instance pi 
   set pi.taxable_pst = 0
 where wi.workorder_id = wo.id and pi.workorder_item_id = wi.id 
   and (wo.pst_exempt = 1  and pi.taxable_pst > 0 and pi.pst_override_flg = 'N' );
   
update workorder wo,
       workorder_item wi,
       part_instance pi 
   set pi.taxable_gst = 0
 where wi.workorder_id = wo.id and pi.workorder_item_id = wi.id 
   and (wo.gst_exempt = 1  and pi.taxable_gst > 0 and pi.gst_override_flg = 'N' );

update system_settings set value = '10' where code = 'DB_VERSION';

