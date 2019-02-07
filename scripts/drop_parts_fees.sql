
alter table part_variant
drop column shipping_fees,
drop column broker_fees;

alter table part_instance
drop column shipping_fees,
drop column broker_fees;


/*
mysql> select count(*) from part_variant where broker_fees is not null or shipping_fees is not null;
+----------+
| count(*) |
+----------+
|        3 |
+----------+
1 row in set (0.01 sec)

mysql> select count(*) from part_instance where broker_fees is not null or shipping_fees is not null;
+----------+
| count(*) |
+----------+
|       18 |
+----------+
1 row in set (0.07 sec)

mysql> select * from part_instance where broker_fees is not null or shipping_fees is not null;
+--------+-----------------+----------+------------+-----------+-------------+-------------+-------------+-------------+--------------+------------------------+-------------------+----------------------+----------+----------+-----------+-----------+---------------------+---------------+---------------------+-------------------------+------------------------------------------+---------------+----------------------------------------------------------+---------------+-------------+
| id     | part_variant_id | quantity | unit_price | unit_cost | taxable_hst | taxable_pst | taxable_gst | enviro_levy | battery_levy | supplier_order_item_id | workorder_item_id | workorder_invoice_id | added_by | estimate | allocated | delivered | include_in_estimate | serial_number | date_used           | is_inventory_adjustment | custom_name                              | custom_origin | internal_notes                                           | shipping_fees | broker_fees |
+--------+-----------------+----------+------------+-----------+-------------+-------------+-------------+-------------+--------------+------------------------+-------------------+----------------------+----------+----------+-----------+-----------+---------------------+---------------+---------------------+-------------------------+------------------------------------------+---------------+----------------------------------------------------------+---------------+-------------+
| 177180 |            NULL |    2.000 |      13.69 |      6.19 |        0.00 |        0.00 |        0.00 |        0.00 |         0.00 |                   NULL |             45273 |                 NULL |       55 |        0 |         1 |         1 |                   0 |               | 2019-02-05 11:40:37 |                       0 | Chrome Brass Barrel Bolt #2225221        | CDN           | 02/05/2019
INV1337274
Land and Sea
PO#922910
Blake F     |          0.00 |        0.00 |
| 177251 |            NULL |    2.000 |      54.15 |     22.74 |        0.00 |        0.00 |        0.00 |        0.00 |         0.00 |                   NULL |             45467 |                 NULL |       55 |        0 |         1 |         1 |                   0 |               | 2019-02-06 08:11:45 |                       0 | Napa Belt #25-9485                       | CDN           | 02/06/2019
INV808-566759
Beacon Auto
PO#722374
Blake F   |          0.00 |        0.00 |
| 177253 |            NULL |    2.000 |      46.39 |     19.49 |        0.00 |        0.00 |        0.00 |        0.00 |         0.00 |                   NULL |             45467 |                 NULL |       55 |        0 |         1 |         1 |                   0 |               | 2019-02-06 08:21:16 |                       0 | Napa Belt #25-9350                       | CDN           | 02/06/2019
INV808-566759
Beacon Auto
PO#722374
Blake F   |          0.00 |        0.00 |
| 177254 |            NULL |    1.000 |      50.85 |     21.36 |        0.00 |        0.00 |        0.00 |        0.00 |         0.00 |                   NULL |             45467 |                 NULL |       55 |        0 |         1 |         1 |                   0 |               | 2019-02-06 08:23:26 |                       0 | Napa Belts #25-9400                      | CDN           | 022/06/2019
INV808-566759
Beacon Auto 
PO#722374
Blake F |          0.00 |        0.00 |
| 177255 |            NULL |    2.000 |      63.44 |     26.64 |        0.00 |        0.00 |        0.00 |        0.00 |         0.00 |                   NULL |             45467 |                 NULL |       55 |        0 |         1 |         1 |                   0 |               | 2019-02-06 08:26:19 |                       0 | Napa Belt #25-9570                       | CDN           | 02/06/2019
INV808-566759
Beacon Auto
PO#722374
Blake F   |          0.00 |        0.00 |
| 177256 |            NULL |    1.000 |      65.24 |     27.40 |        0.00 |        0.00 |        0.00 |        0.00 |         0.00 |                   NULL |             45467 |                 NULL |       55 |        0 |         1 |         1 |                   0 |               | 2019-02-06 08:34:25 |                       0 | Napa Belt #25-9600                       | CDN           | 02/06/2019
INV808-566759
Beacon Auto
PO#722374
Blake F   |          0.00 |        0.00 |
| 177259 |            NULL |    1.000 |    3221.00 |      0.00 |        0.00 |        0.00 |        0.00 |        0.00 |         0.00 |                   NULL |             45515 |                 NULL |        1 |        1 |         0 |         0 |                   0 |               | 2019-02-06 10:10:07 |                       0 | DRS-4ZXT Radar                           |               |                                                          |          1.00 |        1.00 |
| 177260 |            NULL |    1.000 |    3785.00 |      0.00 |        0.00 |        0.00 |        5.00 |        0.00 |         0.00 |                   NULL |             45515 |                 NULL |        1 |        1 |         0 |         0 |                   0 |               | 2019-02-06 10:11:23 |                       0 | navpilot 711c                            |               |                                                          |          1.00 |        1.00 |
| 177262 |            NULL |    1.000 |    3279.00 |      0.00 |        0.00 |        0.00 |        0.00 |        0.00 |         0.00 |                   NULL |             45515 |                 NULL |        1 |        1 |         0 |         0 |                   0 |               | 2019-02-06 10:13:53 |                       0 | SATELLITE COMPASS NMEA2000  S33          |               |                                                          |          1.00 |        1.00 |
| 177263 |            NULL |    1.000 |    2647.00 |      0.00 |        0.00 |        0.00 |        0.00 |        0.00 |         0.00 |                   NULL |             45516 |                 NULL |        1 |        1 |         0 |         0 |                   0 |               | 2019-02-06 10:15:17 |                       0 | DFF3DMULT-BEAM SONAR F/NAVNET TZTCH      |               |                                                          |          1.00 |        1.00 |
| 177264 |            NULL |    1.000 |    2579.00 |      0.00 |        0.00 |        0.00 |        5.00 |        0.00 |         0.00 |                   NULL |             45516 |                 NULL |        1 |        1 |         0 |         0 |                   0 |               | 2019-02-06 10:17:28 |                       0 | 165T-SS54DFF3D TH SS XDCR W/MOTION SNSR  |               |                                                          |          1.00 |        1.00 |
| 177265 |            NULL |    3.000 |       8.13 |      3.66 |        0.00 |        0.00 |        0.00 |        0.00 |         0.00 |                   NULL |             45447 |                 NULL |       55 |        0 |         1 |         1 |                   0 |               | 2019-02-06 10:23:43 |                       0 | NGK Spark Plug #7839                     | CDN           | 02/06/2019
INV808-566807
Beacon Auto
PO#722376
Blake F   |          0.00 |        0.00 |
| 177285 |            NULL |    2.000 |      99.95 |     83.29 |        0.00 |        0.00 |        0.00 |        0.00 |         0.00 |                   NULL |             43150 |                 NULL |       55 |        0 |         1 |         1 |                   0 |               | 2019-02-06 12:42:47 |                       0 | Waxed Neutral Gelcoat #300723            | CDN           | 02/06/2019
INV799519
IPP
PO#922911
Blake F               |          0.00 |        0.00 |
| 177286 |            NULL |    1.000 |      26.51 |     22.09 |        0.00 |        0.00 |        0.00 |        0.00 |         0.00 |                   NULL |             43150 |                 NULL |       55 |        0 |         1 |         1 |                   0 |               | 2019-02-06 12:43:48 |                       0 | Color Paste White #114216                | CDN           | 02/06/2019
INV799519
IPP
PO#922911
Blake F               |          0.00 |        0.00 |
| 177287 |            NULL |    1.000 |      31.61 |     26.34 |        0.00 |        0.00 |        0.00 |        0.00 |         0.00 |                   NULL |             43150 |                 NULL |       55 |        0 |         1 |         1 |                   0 |               | 2019-02-06 12:49:21 |                       0 | Color Paste Topaz #114220                | CDN           | 02/06/2019
INV799519
IPP
PO#922911
Blake F               |          0.00 |        0.00 |
| 177288 |            NULL |    1.000 |      31.61 |     26.34 |        0.00 |        0.00 |        0.00 |        0.00 |         0.00 |                   NULL |             43150 |                 NULL |       55 |        0 |         1 |         1 |                   0 |               | 2019-02-06 12:51:18 |                       0 | Color Paste Dune Beige #114219           | CDN           | 02/06/2019
INV799519
IPP
PO#922911
Blake F               |          0.00 |        0.00 |
| 177289 |            NULL |    2.000 |      99.95 |     83.29 |        0.00 |        0.00 |        0.00 |        0.00 |         0.00 |                   NULL |             43150 |                 NULL |       55 |        0 |         1 |         1 |                   0 |               | 2019-02-06 12:55:10 |                       0 | Unwaxed Neutral Gelcoat #111886          | CDN           | 02/06/2019
INV799587
IPP
PO#922912
Blake F               |          0.00 |        0.00 |
| 177290 |            NULL |    1.000 |      31.61 |     26.34 |        0.00 |        0.00 |        0.00 |        0.00 |         0.00 |                   NULL |             43150 |                 NULL |       55 |        0 |         1 |         1 |                   0 |               | 2019-02-06 12:57:17 |                       0 | Color Paste Topaz #114220                | CDN           | 02/06/2019
INV799587
IPP
PO#922912
Blake F               |          0.00 |        0.00 |
+--------+-----------------+----------+------------+-----------+-------------+-------------+-------------+-------------+--------------+------------------------+-------------------+----------------------+----------+----------+-----------+-----------+---------------------+---------------+---------------------+-------------------------+------------------------------------------+---------------+----------------------------------------------------------+---------------+-------------+
18 rows in set (0.29 sec)

mysql> select * from part_variant where broker_fees is not null or shipping_fees is not null;
+-------+---------+--------------------+------------------+---------------+-------------------+-------+---------------------+-------------------------+-----------+---------------------+------------+---------------+----------------+-------------+-------------+-------------+-------------+--------------+------------------------+-----------------+----------------+-----------------+----------------+-----------------+-----------------------+-----------------+-----------------+-----------------+-----------------+-----------------+------------------+----------+-----------------------+----------------------+----------------+---------------------+---------------+-------------+
| id    | part_id | is_default_variant | manufacturer_sku | internal_sku  | use_default_units | units | use_default_costing | cost_calculation_method | unit_cost | use_default_pricing | unit_price | markup_amount | markup_percent | taxable_hst | taxable_pst | taxable_gst | enviro_levy | battery_levy | use_default_dimensions | shipping_weight | shipping_width | shipping_height | shipping_depth | shipping_volume | use_default_inventory | track_inventory | minimum_on_hand | maximum_on_hand | current_on_hand | current_on_hold | current_on_order | location | last_inventory_update | standard_package_qty | stocking_notes | created_at          | shipping_fees | broker_fees |
+-------+---------+--------------------+------------------+---------------+-------------------+-------+---------------------+-------------------------+-----------+---------------------+------------+---------------+----------------+-------------+-------------+-------------+-------------+--------------+------------------------+-----------------+----------------+-----------------+----------------+-----------------+-----------------------+-----------------+-----------------+-----------------+-----------------+-----------------+------------------+----------+-----------------------+----------------------+----------------+---------------------+---------------+-------------+
|  3205 |    3160 |                  1 | 026916416001     | CDA1600       |                 0 | NULL  |                   0 | lifo                    |      5.49 |                   0 |      11.59 |          NULL |           NULL |        1.00 |           1 |           1 |        0.25 |         NULL |                      0 |            NULL |           NULL |            NULL |           NULL |            NULL |                     0 |               1 |           1.000 |           3.000 |           7.000 |           0.000 |            0.000 | RE2 502  | 2019-02-05 10:57:16   |                 NULL | NULL           | 2010-02-01 14:46:36 |          5.00 |       10.00 |
| 10906 |   10865 |                  1 |                  | testdelcom    |                 0 | NULL  |                   0 | lifo                    |      1.00 |                   0 |       2.00 |          NULL |           NULL |        0.00 |           1 |           1 |        NULL |         NULL |                      0 |            NULL |           NULL |            NULL |           NULL |            NULL |                     0 |               1 |           0.000 |            NULL |           2.000 |           0.000 |            0.000 | NULL     | 2019-02-05 11:09:27   |                 NULL | NULL           | 2019-02-05 10:49:10 |         10.00 |        5.00 |
| 10907 |   10866 |                  1 |                  | testdlecomtwo |                 0 | NULL  |                   0 | lifo                    |      5.00 |                   0 |       NULL |          NULL |             25 |        0.00 |           1 |           1 |        NULL |         NULL |                      0 |            NULL |           NULL |            NULL |           NULL |            NULL |                     0 |               1 |           0.000 |            NULL |           1.000 |           0.000 |            0.000 | NULL     | 2019-02-05 11:15:51   |                 NULL | NULL           | 2019-02-05 11:15:06 |          5.00 |       10.00 |
+-------+---------+--------------------+------------------+---------------+-------------------+-------+---------------------+-------------------------+-----------+---------------------+------------+---------------+----------------+-------------+-------------+-------------+-------------+--------------+------------------------+-----------------+----------------+-----------------+----------------+-----------------+-----------------------+-----------------+-----------------+-----------------+-----------------+-----------------+------------------+----------+-----------------------+----------------------+----------------+---------------------+---------------+-------------+
3 rows in set (0.02 sec)

*/
