<?php
// auto-generated by sfSecurityConfigHandler
// date: 2014/06/05 07:23:09
$this->security = array (
  'index' => 
  array (
    'credentials' => 'parts_view',
  ),
  'view' => 
  array (
    'credentials' => 'parts_view',
  ),
  'add' => 
  array (
    'credentials' => 'parts_edit',
  ),
  'edit' => 
  array (
    'credentials' => 'parts_edit',
  ),
  'inventory' => 
  array (
    'credentials' => 'parts_inventory',
  ),
  'bulkinventory' => 
  array (
    'credentials' => 'parts_inventory',
  ),
  'bulkinventoryreview' => 
  array (
    'credentials' => 'parts_inventory',
  ),
  'bulkinventorypost' => 
  array (
    'credentials' => 'parts_inventory',
  ),
  'inventorysheet' => 
  array (
    'credentials' => 'parts_view',
  ),
  'barcodes' => 
  array (
    'credentials' => 'parts_view',
  ),
  'delete' => 
  array (
    'credentials' => 'parts_delete',
  ),
  'supplieredit' => 
  array (
    'credentials' => 'parts_edit',
  ),
  'supplierremove' => 
  array (
    'credentials' => 'parts_edit',
  ),
  'category' => 
  array (
    'credentials' => 'parts_category_view',
  ),
  'categoryedit' => 
  array (
    'credentials' => 'parts_category_edit',
  ),
  'categorydelete' => 
  array (
    'credentials' => 'parts_category_edit',
  ),
  'all' => 
  array (
    'is_secure' => true,
    'credentials' => 'app_admin',
  ),
);
