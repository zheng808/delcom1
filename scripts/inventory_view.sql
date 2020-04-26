create or replace view inventory_view as 
select id, location_group, part, location,category, 
       internal_sku 'Delta SKU', manufacturer_sku 'Manu. SKU', 
       origin, unit_cost 'Unit Cost', current_on_hand 'Inventory (Current)',
       total_cost 'Total Cost', ' ' as 'New Inventory'
from (
select pv.id,
       case 
         when trim(pv.`location`) = '' then 'UNKNOWN - 2' 
         when isnull(pv.location) = 1 then 'UNKNOWN - 2' 
         else concat(trim(pv.`location`),' - 2')
        end as sort,
        ' ' as location_group,
       concat('          ', p.`name`) Part, 
       case 
         when trim(pv.`location`) = '' then 'UNKNOWN' 
         when isnull(pv.location) = 1 then 'UNKNOWN' 
         else trim(pv.`location`)
        end as Location,
        pc.`name` Category, 
        pv.`internal_sku` , 
        pv.`manufacturer_sku`,
        upper(coalesce(p.`origin`,'')) Origin,
        pv.`unit_cost` ,
        pv.`current_on_hand` ,
        pv.`unit_cost`*pv.`current_on_hand` total_cost
from part p, part_variant pv, part_category pc
where p.id = pv.`part_id` and p.active = 1
and p.`part_category_id` = pc.id
union all
select distinct 
       case 
         when trim(pv.`location`) = '' then 'UNKNOWN' 
         when isnull(pv.location) = 1 then 'UNKNOWN' 
         else concat(trim(pv.`location`) ,' - 1')
        end as id,
        case 
         when trim(pv.`location`) = '' then 'UNKNOWN - 1' 
         when isnull(pv.location) = 1 then 'UNKNOWN - 1' 
         else concat(trim(pv.`location`) ,' - 1')
        end as sort,
        case 
         when trim(pv.`location`) = '' then 'UNKNOWN' 
         when isnull(pv.location) = 1 then 'UNKNOWN' 
         else trim(pv.`location`)
        end as location_group,
        '','','','','','','','',''
  from part p, part_variant pv
  where p.id = pv.`part_id` and p.active = 1
) as inventory
order by sort, part;

update system_settings set value = '09' where code = 'DB_VERSION';

