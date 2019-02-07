<div class="leftside" style="padding-top: 27px;">
  <?php
    echo link_to('Return to Parts List', 'part/index', 
      array('class' => 'button tabbutton'));
  ?>
</div>

<div class="rightside rightside-narrow">

  <h1 class="headicon headicon-part">Update Part Inventory: Part 2 (Review)</h1>
  <div class="pagebox">
    <p>Below is a table showing all of the items that you have scanned. Any barcodes that
       were scanned but were not found in the database are highlighted, as are any for which
       more than one part shares the same barcode.
    </p>
    <p>
      A column showing the current inventory level can be used to compare the newly-entered
      inventory-- if these two numbers are very different it might mean you recorded the wrong
      quantity!
    </p>
    <p>
      Once you have checked the results below for errors, you can click the submit button
      at the bottom of the page to save the changes of the inventory levels for all these
      parts. Note that you can edit the quantity-- if you want to NOT update a particular item,
      just ensure the NEW quantity is the same as the OLD quantity, and nothing will be changed.
    </p>
    <form action="<?php echo url_for('part/bulkInventoryPost'); ?>" method="post">
      <table style="text-align:center;">
        <tr>
          <th>Scanned Code</th>
          <th>SKU in System</th>
          <th>Part Name</th>
          <th>Old Qty</th>
          <th>New Qty</th>
          <th>Update?</th>
        </tr>
        <?php foreach ($final_data AS $scanned => $data): ?>
        <tr>
            <td><?php echo $scanned; ?></td>
            <?php if ($data === true): ?>
              <td colspan="4" style="background-color: #cc0000; color: #ffffff;">
                ERROR: Multiple PartsFound!
              </td>
              <td>
                <input type="checkbox" disabled="disabled" name="garbage[]" value="0" />
              </td>
            <?php elseif (is_array($data)): ?>
              <td><?php echo $data['internal_sku']; ?></td>
              <td class="left"><?php echo link_to($data['name'], 'part/view?id='.$data['part_id']); ?></td>
              <td><?php echo $data['current_on_hand']; ?></td>
              <td><input type="text" size="3" maxlength="5" name="qty[<?php echo $data['part_variant_id']; ?>]" value="<?php echo $data['qty']; ?>" /></td>
              <td><input type="checkbox" name="enabled[<?php echo $data['part_variant_id']; ?>]" value="1" checked="checked" /></td>
            <?php else: ?>
              <td colspan="4" style="background-color: #cc0000; color: #ffffff;">
                ERROR: No Part Found!
              </td>
              <td>
                <input type="checkbox" disabled="disabled" name="garbage[]" value="0" />
              </td>
            <?php endif; ?>
          </tr>
        <?php endforeach; ?>
      </table>

      <div style="float: right; margin-right: 30px;">
        <button type="submit" name="submit">Update Inventory</button>
      </div>
      <div class="clear"></div>
    </form>
  </div>
</div>
