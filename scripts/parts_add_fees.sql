alter table part_variant
add column shipping_fees decimal(8,2) DEFAULT 0,
add column broker_fees decimal(8,2) DEFAULT 0;

alter table part_instance
add column shipping_fees decimal(8,2) DEFAULT 0,
add column broker_fees decimal(8,2) DEFAULT 0;

