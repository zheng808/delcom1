<?php use_helper('Form','Javascript'); ?>
<?php echo javascript_tag("
  var barcode_default_handler = function(code, symbid){
    var form = document.createElement('form');
    form.setAttribute('method', 'post');
    form.setAttribute('action', '".url_for('parts/search?id='.$workorder->getId().'&item='.$item->getId())."');
    var hiddenfield = document.createElement('input');
    hiddenfield.setAttribute('type','hidden');
    hiddenfield.setAttribute('name','code');
    hiddenfield.setAttribute('value',code);
    form.appendChild(hiddenfield);
    document.body.appendChild(form);
    form.submit();
  }

  barcodeListener.handleroverride = barcode_default_handler;

"); ?>
<div id="filter">
  <h2>Add Part to Workorder #<?php echo $workorder->getId(); ?> Task: "<?php echo $item->getLabel(); ?>"</h2>
</div>

<h2 style="text-align: center; margin-top: 20px;">Scan Barcode Now Using Barcode Scanner<br />OR Search Below:</h2>

<div class="search-area rounded10">
  <h2 class="rounded10-top">Search by Part Name</h2>
  <div style="padding: 10px 30px 0 30px;">
    <?php echo form_tag('parts/search?id='.$workorder->getId().'&item='.$item->getId()); ?>
      <button type="submit" style="float: right;">Search</button>
      <?php echo input_tag('name', $sf_request->getParameter('name'), array('id' => 'name_field', 'style' => 'width: 250px; height: 35px; font-size: 18px;')); ?>
    </form>
  </div>
</div>

<div class="search-area rounded10">
  <h2 class="rounded10-top">Search by Part SKU</h2>
  <div style="padding: 10px 30px 0 30px;">
    <?php echo form_tag('parts/search?id='.$workorder->getId().'&item='.$item->getId()); ?>
      <button type="submit" style="float: right;">Search</button>
      <?php echo input_tag('sku', $sf_request->getParameter('sku'), array('id' => 'sku_field', 'style' => 'width: 250px; height: 35px; font-size: 18px;')); ?>
    </form>
  </div>
</div>

<div class="clear"></div>

<?php if (isset($parts) && count($parts) > 0): ?>
  <h2 style="padding-left: 100px; padding-top: 0;">Please select the desired match: (<?php echo count($parts); ?> found)</h2>
  <?php
    $base_url = 'parts/details?id='.$workorder->getId().'&item='.$item->getId().'&part=';
    $max_rows = 6;
    $max_cols = 2;
    $col = 1;
    $row = 1;

    $offset = 1;
    if ($page > 1)
    {
      $offset += ($page - 1) * ($max_rows * $max_cols);
      $parts = array_slice($parts, $offset);
    }
  ?>
    <div class="partselect-children">
    <?php foreach ($parts AS $idx => $part): ?>
      <?php if ($row == 1): ?>
        <div class="partselect-col">
      <?php endif; ?>
        <?php
          $text = $part->getName().'<br /><span>'.
                  'SKU: '.$part->getDefaultVariant()->getInternalSku().'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.
                  'Price: '.$part->getDefaultVariant()->outputUnitPrice().'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.
                  'Qty Avail: '.$part->getDefaultVariant()->getQuantity('available');
          echo link_to($text, $base_url.$part->getId(), array('class' => 'button partselect'));
        ?>
      <?php if ($row == $max_rows): ?>
        </div>
        <?php if ($col == $max_cols) break; ?>
        <?php $row = 1; $col++; ?>
      <?php else: ?>
        <?php $row ++; ?>
      <?php endif; ?>
    <?php endforeach; ?>
    <?php if (($row != $max_rows) || (($row == $max_rows) && ($col != $max_cols))): ?>
      </div>
    <?php endif; ?>
    <div class="clear"></div>
    </div>

    <div class="pager">
      <?php if ((count($parts) > ($max_rows * $max_cols)) || ($page > 1)): ?>
          <?php 
            $base_url = 'parts/search?id='.$workorder->getId().'&item='.$item->getId().
                        ($sf_request->hasParameter('name') ? '&name='.$sf_request->getParameter('name') : '').
                        ($sf_request->hasParameter('sku')  ? '&sku='.$sf_request->getParameter('sku') : '').
                        '&page='; 
          ?>
          <?php echo link_to('&lt; Previous Parts', $base_url.($page - 1), array('class' => 'button', 'style' => 'width: 150px;'.($page == 1 ? 'visibility: hidden;' : ''))); ?>
          <?php echo link_to('More Parts &gt;', $base_url.($page + 1), array('class' => 'button', 'style' => 'width: 150px;'.((count($parts) <= ($max_rows * $max_cols)) ? 'visibility: hidden;' : ''))); ?>
      <?php endif; ?>
    </div>

<?php else: ?>

  <div class="error-area" style="margin-top: 100px; margin-left: 200px;">
    <?php if ($sf_request->hasParameter('sku_error')): ?>
      <div class="bigwarning error">Empty SKU Specified. Try again.</div>
    <?php elseif ($sf_request->hasParameter('name_error')): ?>
      <div class="bigwarning error">Empty Name Specified. Try again.</div>
    <?php elseif ($sf_request->hasParameter('notfound_error')): ?>
      <div class="bigwarning error">No results found. Try again!</div>
    <?php endif; ?>
  </div>

<?php endif; ?>

<?php echo javascript_tag("
if (window.addEventListener) {
    window.addEventListener('load', function() { f=document.getElementById('".($sf_request->hasParameter('sku') ? 'sku_field' : 'name_field')."'); f.focus(); f.select();}, false);
} else if (window.attachEvent) {
    window.attachEvent('onload', function() { f=document.getElementById('".($sf_request->hasParameter('sku') ? 'sku_field' : 'name_field')."'); f.focus(); f.select();});
}
"); ?>
