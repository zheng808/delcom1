<div class="leftside" style="padding-top: 27px;">
  <?php
    echo link_to('Return to Parts List', 'part/index', 
                  array('class' => 'button tabbutton'));
  ?>
</div>

<div class="rightside rightside-narrow">

  <h1 class="headicon headicon-part">Update Part Inventory: Part 3 (Complete)</h1>
  <div class="pagebox">
    <p>The specified parts' inventory levels were updated:</p>
    <ul>
      <li>Number of parts updated: <strong><?php echo $count_updated; ?></strong></li>
      <li>Number of parts with quantity unchanged: <strong><?php echo $count_unchanged; ?></strong></li>
      <?php if ($count_errored > 0): ?>
        <li style="color: #900;">Number of parts with invalid quantities entered: <strong><?php echo $count_errored; ?></strong></li>
      <?php endif; ?>
    </ul>

    <?php if ($count_errored > 0): ?>
      <p style="font-weight: bold">Note about errors: Some items could not be updated due to invalid quantities entered (negative numbers and non-numeric input is not allowed). To update the quantities of these items, click the button below to enter more inventory levels, but DO NOT "CLEAR ALL RECORDS" first. This will let you re-enter the same inventory values-- already updated items will not be changed, and you'll have another chance to update the inventory levels for the items that had invalid input.</p>
    <?php endif; ?>

    <p style="font-size: 16px; color: #c00; font-weight: bold; margin-top: 50px;">
      NOTE: Be sure to scan the "CLEAR ALL RECORDS" barcode on the instruction sheet before
      continuing! If you do not, the same already-scanned barcodes will be transmitted
      again next time!
    </p>
    <div style="text-align: center; margin-top: 50px;">
      <?php
        echo link_to('Update Inventory of More Parts', 'part/bulkInventory', array('class' => 'button'));
        echo link_to('Return to Parts List', 'part/index', array('class' => 'button'));
      ?>
    </div>
  </div>
</div>
