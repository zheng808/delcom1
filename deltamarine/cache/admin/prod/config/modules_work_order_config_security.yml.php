<?php
// auto-generated by sfSecurityConfigHandler
// date: 2014/06/05 00:31:02
$this->security = array (
  'index' => 
  array (
    'credentials' => 'workorder_view',
  ),
  'add' => 
  array (
    'credentials' => 'workorder_estimates',
  ),
  'edit' => 
  array (
    'credentials' => 
    array (
      0 => 'workorder_approve',
      1 => 'workorder_edit',
    ),
  ),
  'view' => 
  array (
    'credentials' => 'workorder_view',
  ),
  'delete' => 
  array (
    'credentials' => 
    array (
      0 => 'workorder_approve',
      1 => 'workorder_edit',
    ),
  ),
  'itemmove' => 
  array (
    'credentials' => 'workorder_edit',
  ),
  'itemload' => 
  array (
    'credentials' => 'workorder_view',
  ),
  'itemedit' => 
  array (
    'credentials' => 'workorder_add',
  ),
  'itemdelete' => 
  array (
    'credentials' => 'workorder_edit',
  ),
  'partadd' => 
  array (
    'credentials' => 'workorder_add',
  ),
  'partedit' => 
  array (
    'credentials' => 'workorder_edit',
  ),
  'partload' => 
  array (
    'credentials' => 'workorder_view',
  ),
  'partdelete' => 
  array (
    'credentials' => 'workorder_edit',
  ),
  'partmove' => 
  array (
    'credentials' => 'workorder_add',
  ),
  'expenseedit' => 
  array (
    'credentials' => 'workorder_add',
  ),
  'expensemove' => 
  array (
    'credentials' => 'workorder_add',
  ),
  'expenseload' => 
  array (
    'credentials' => 'workorder_view',
  ),
  'expensedelete' => 
  array (
    'credentials' => 'workorder_edit',
  ),
  'notesedit' => 
  array (
    'credentials' => 'workorder_edit',
  ),
  'notesload' => 
  array (
    'credentials' => 'workorder_view',
  ),
  'timelogmove' => 
  array (
    'credentials' => 'workorder_add',
  ),
  'all' => 
  array (
    'is_secure' => true,
    'credentials' => 'app_admin',
  ),
);
