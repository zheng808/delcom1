<div id="nav"><ul>

  <li class="first">&nbsp;</li>

  <li<?php if /*(sfConfig::get('app_selected_menu') == 'dashboard')*/(((substr($_SERVER['REQUEST_URI'],1,9)) == 'dashboard') || ($_SERVER['REQUEST_URI'] == "/")) echo ' class="selected"'; ?>>
    <?php echo link_to('Dashboard', '@homepage'); ?>
  </li>

<?php if ($sf_user->hasCredential(array('parts_view','parts_supplier_view','parts_manufacturer_view','orders_view'), false)): ?>
  <li<?php if (sfConfig::get('app_selected_menu') == 'parts') echo ' class="selected"'; ?>>
    <?php echo link_to('Parts', 'part/index'); ?>
    <?php if ($sf_user->hasCredential(array('parts_supplier_view','parts_manufacturer_view','orders_view'), false)): ?>
      <ul>
        <?php if ($sf_user->hasCredential('parts_supplier_view')): ?>
          <li class="first"><?php echo link_to('Suppliers', 'supplier/index'); ?></li>
        <?php endif; ?>
        <?php if ($sf_user->hasCredential('orders_view')): ?>
          <li><?php echo link_to('Supplier Orders', 'supplier_order/index'); ?></li>
        <?php endif; ?>
        <?php if ($sf_user->hasCredential('parts_manufacturer_view')): ?>
          <li class="divider"><?php echo link_to('Manufacturers', 'manufacturer/index'); ?></li>
        <?php endif; ?>
      </ul>
    <?php endif; ?>
  </li>
<?php endif; ?>

<?php if ($sf_user->hasCredential('sales_view')): ?>
  <li<?php if (sfConfig::get('app_selected_menu') == 'sales') echo ' class="selected"'; ?>>
    <?php echo link_to('Sales', 'sale/index'); ?>
  </li>
<?php endif; ?>

<?php if ($sf_user->hasCredential('workorder_view')): ?>
  <li<?php if (sfConfig::get('app_selected_menu') == 'workorders') echo ' class="selected"'; ?>>
    <?php echo link_to('Work Orders', 'work_order/index'); ?>
  </li>
<?php endif; ?>

<?php if ($sf_user->hasCredential('customer_view')): ?>
  <li<?php if (sfConfig::get('app_selected_menu') == 'customers') echo ' class="selected"'; ?>>
    <?php echo link_to('Customers', 'customer/index'); ?>
  </li>
<?php endif; ?>

<?php if ($sf_user->hasCredential('employee_view')): ?>
  <li<?php if (sfConfig::get('app_selected_menu') == 'employees') echo ' class="selected"'; ?>>
    <?php echo link_to('Employees', 'employee/index'); ?>
  </li>
<?php endif; ?>

<?php if ($sf_user->hasCredential('timelogs_view')): ?>
  <li<?php if ((substr($_SERVER['REQUEST_URI'],1,8)) == 'timelogs') echo ' class="selected"'; ?>>
    <?php echo link_to('Timelogs', 'timelogs/index'); ?>
  </li>
<?php endif; ?>

<?php if ($sf_user->hasCredential('reports_view')): ?>
  <li<?php if ((substr($_SERVER['REQUEST_URI'],1,7)) == 'reports') echo ' class="selected"'; ?>>
    <?php echo link_to('Reports', 'reports/index'); ?>
    <ul>
      <li><?php echo link_to('Employee Timesheets', 'reports/timelogs'); ?></li>
      <li><?php echo link_to('Parts Data', 'reports/partsCSV'); ?></li>
      <li><?php echo link_to('Unit Cost Data', 'reports/UnitCostCSV'); ?></li>
      <li class="divider disabled"><a href="#">Workorder Profits</a></li>
      <li class="disabled"><a href="#">Parts Sales</a></li>
      <li class="disabled"><a href="#">Discounts and Warranty</a></li>
      <li class="disabled"><a href="#">Combined Totals</a></li>
      <li class="divider disabled"><a href="#">Data Export</a></li>
    </ul>
  </li>
<?php endif; ?>

<?php /* 
  <li<?php if (sfConfig::get('app_selected_menu') == 'settings') echo ' class="selected"'; ?>>
    <a href="#">Settings</a>
    <ul>
      <li class="first disabled"><a href="#">Labour Types &amp; Rates</a></li>
    </ul>
  </li>
 */ ?>
</ul><div class="clear"></div></div>

